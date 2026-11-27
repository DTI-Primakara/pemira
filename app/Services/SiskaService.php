<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * SISKA, the academic information system, is where "who is an active student"
 * is actually decided. PEMIRA reads that list rather than keeping its own copy
 * typed in from a spreadsheet.
 *
 * Read-only: this talks to the public student API (GET /api/v1/mahasiswa),
 * which is guarded by its own X-API-KEY and emits no heavy PII. The
 * service-to-service endpoint was deliberately not used — its token is the SSO
 * credential, and revoking it would break account sync for every system.
 */
class SiskaService
{
    /**
     * SISKA programme code => the four-digit code PEMIRA elections are keyed
     * on. Used only to check the code encoded in a student number, never to
     * decide eligibility, so an unknown programme is reported rather than
     * silently given a ballot in the wrong HIMA election.
     */
    public const PRODI_CODES = [
        'S1-SI' => '0101',
        'S1-TI' => '0102',
        'S1-SIA' => '0103',
        'S1-DKV' => '0104',
        'S1-MAN' => '0201',
        'S1-AKT' => '0202',
        'S1-BD' => '0203',
    ];

    private string $host;

    private string $apiKey;

    public function __construct()
    {
        $this->host = rtrim((string) config('services.siska.host'), '/');
        $this->apiKey = (string) config('services.siska.api_key');
    }

    public function isConfigured(): bool
    {
        return $this->host !== '' && $this->apiKey !== '';
    }

    /**
     * One page of active students, already reduced to what PEMIRA stores.
     *
     * Throws rather than returning an empty page on failure: a queued job must
     * retry a hiccup, and — more importantly — a silently empty page would look
     * to the clean-up phase like every student had left the university.
     *
     * @return array{students: list<array{nim: string, name: string, email: ?string, prodi_code: ?string, status_code: ?string}>, last_page: int, total: int}
     */
    public function fetchActiveStudents(int $page): array
    {
        $response = $this->http()->get($this->host.'/api/v1/mahasiswa', [
            'id_ref_status_mahasiswa' => config('services.siska.status_aktif_id'),
            'per_page' => $this->perPage(),
            'page' => $page,
            // A stable order is what makes paging safe: without it two pages
            // can show the same student and skip another.
            'sort_by' => 'nim',
            'sort_order' => 'asc',
        ]);

        if ($response->failed()) {
            throw new RuntimeException('SISKA menolak permintaan daftar mahasiswa (HTTP '.$response->status().').');
        }

        $body = $response->json();

        if (! is_array($body) || ! is_array($body['data'] ?? null)) {
            throw new RuntimeException('Respons SISKA tidak berbentuk daftar mahasiswa.');
        }

        return [
            'students' => array_values(array_map($this->mapStudent(...), $body['data'])),
            'last_page' => max(1, (int) ($body['meta']['last_page'] ?? 1)),
            'total' => (int) ($body['meta']['total'] ?? 0),
        ];
    }

    public function perPage(): int
    {
        return min(100, max(1, (int) config('services.siska.per_page', 100)));
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array{nim: string, name: string, email: ?string, prodi_code: ?string, status_code: ?string}
     */
    private function mapStudent(array $row): array
    {
        return [
            'nim' => trim((string) ($row['nim'] ?? '')),
            'name' => trim((string) ($row['nama_mahasiswa'] ?? '')),
            'email' => $this->loginEmail($row),
            'prodi_code' => self::PRODI_CODES[$row['prodi']['kode_prodi'] ?? ''] ?? null,
            'status_code' => $row['status_mahasiswa']['kode'] ?? null,
        ];
    }

    /**
     * The address the student's SSO account was created under.
     *
     * This mirrors SISKA's own Mahasiswa::ssoEmail() exactly, and the exactness
     * is the whole point: pick a different address here and a student ends up
     * with one PEMIRA account from this sync and a second one the first time
     * they sign in through the SSO.
     *
     * Note the asymmetry it inherits — an empty `email_pribadi` means "no
     * e-mail" and does NOT fall back to `email_kampus`, because the rule is
     * `??` on null, not a coalesce over blanks.
     *
     * @param  array<string, mixed>  $row
     */
    private function loginEmail(array $row): ?string
    {
        $email = $row['email_pribadi'] ?? $row['email_kampus'] ?? null;

        if ($email === null || trim((string) $email) === '') {
            return null;
        }

        return trim((string) $email);
    }

    private function http(): PendingRequest
    {
        return Http::withHeaders(['X-API-KEY' => $this->apiKey])
            ->acceptJson()
            ->connectTimeout(5)
            ->timeout((int) config('services.siska.timeout', 30));
    }
}
