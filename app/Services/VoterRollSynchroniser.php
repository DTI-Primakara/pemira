<?php

namespace App\Services;

use App\Models\Period;
use App\Models\User;
use App\Models\UserVote;
use App\Models\VoterSyncRun;
use Illuminate\Support\Facades\DB;

/**
 * Reconciles a period's voter roll against SISKA's list of active students.
 *
 * After a completed run the roll *is* that list, plus anyone who had already
 * cast a ballot. That single rule is what removes the two failures this was
 * built for: a student missing from the roll who therefore cannot vote, and
 * the same person holding two accounts because two systems disagreed on their
 * e-mail address.
 */
class VoterRollSynchroniser
{
    /** SISKA's lifecycle status code for a student who is currently enrolled. */
    private const STATUS_AKTIF = 'A';

    /**
     * Take one student from SISKA and make the local data agree with it.
     *
     * Counts and issues are accumulated on the run; the caller saves it once
     * per page rather than once per student.
     *
     * @param  array{nim: string, name: string, email: ?string, prodi_code: ?string, status_code: ?string}  $student
     */
    public function absorb(array $student, Period $period, VoterSyncRun $run): void
    {
        $nim = $student['nim'];
        $email = $student['email'];

        if ($nim === '') {
            $run->skipped_count++;
            $run->addIssue('(tanpa NIM)', 'Baris SISKA tanpa NIM, tidak ada yang bisa dicocokkan.');

            return;
        }

        /*
         * The request already filtered on the active status, but the id it
         * filters by comes from configuration. Checking the code that comes
         * back means a wrong id cannot quietly put alumni on the roll — it
         * empties the run instead, which the guard in finish() then refuses to
         * act on.
         */
        if ($student['status_code'] === null) {
            $run->addIssue($nim, 'SISKA tidak menyertakan status mahasiswa; didaftarkan, tapi periksa sendiri.');
        } elseif ($student['status_code'] !== self::STATUS_AKTIF) {
            $run->skipped_count++;
            $run->addIssue($nim, 'Status di SISKA "'.$student['status_code'].'", bukan Aktif — tidak didaftarkan.');

            return;
        }

        if ($email === null) {
            // Without an e-mail there is no SSO account, so there is nobody who
            // could ever sign in to use this ballot.
            $run->skipped_count++;
            $run->addIssue($nim, 'Belum punya email di SISKA, jadi belum punya akun SSO untuk login.');

            return;
        }

        $user = $this->matchAccount($nim, $email);

        if (! $user) {
            $user = User::create([
                'name' => $student['name'] !== '' ? $student['name'] : $nim,
                'nim' => $nim,
                'email' => $email,
                'roles' => User::ROLE_PARTICIPANT,
            ]);

            $run->created_count++;
        } elseif ($this->refresh($user, $student, $email)) {
            $run->updated_count++;
        }

        $this->checkProdi($student, $user, $run);

        $alreadyOnRoll = DB::table('period_user')
            ->where('period_id', $period->id)
            ->where('user_id', $user->id)
            ->exists();

        // The stamp is what marks this student as seen by this run; the
        // clean-up phase removes roll entries that carry no fresh stamp.
        $period->voters()->syncWithoutDetaching([$user->id => ['synced_at' => now()]]);

        if (! $alreadyOnRoll) {
            $run->enrolled_count++;
        }

        $run->seen_count++;
    }

    /**
     * Close the run: drop everyone the pull did not see, then mark it done.
     */
    public function finish(Period $period, VoterSyncRun $run): void
    {
        $rollSize = DB::table('period_user')->where('period_id', $period->id)->count();

        /*
         * A guard against a half-finished pull emptying the roll. If SISKA
         * returned far fewer active students than the roll already holds,
         * something is wrong with the data, not with the roll — so nothing is
         * removed and the run is left visibly failed.
         */
        if ($rollSize > 0 && $run->seen_count < (int) ceil($rollSize * 0.5)) {
            $run->markFailed(
                'Dihentikan sebelum menghapus siapa pun: hanya '.$run->seen_count.
                ' mahasiswa aktif terbaca dari SISKA, sementara daftar pemilih berisi '.$rollSize.
                '. Data yang sudah masuk tetap tersimpan. Periksa API SISKA lalu tarik ulang.'
            );

            return;
        }

        $this->removeUnseen($period, $run);

        $run->forceFill([
            'status' => VoterSyncRun::STATUS_COMPLETED,
            'finished_at' => now(),
        ])->save();
    }

    /**
     * Which local account belongs to this student.
     *
     * Student number first, e-mail second — the opposite order to SSO sign-in,
     * and deliberately so. SISKA is where student numbers are decided, while
     * an account created by an SSO login may carry whichever address the SSO
     * holds. Checking the number first is what keeps those two from becoming
     * two accounts for one person.
     */
    private function matchAccount(string $nim, string $email): ?User
    {
        return User::query()->where('nim', $nim)->first()
            ?: User::query()->where('email', $email)->first();
    }

    /**
     * Bring an existing account in line with SISKA. Returns whether anything
     * actually changed.
     *
     * @param  array{nim: string, name: string, email: ?string, prodi_code: ?string, status_code: ?string}  $student
     */
    private function refresh(User $user, array $student, string $email): bool
    {
        $changes = [];

        if ($student['name'] !== '' && $user->name !== $student['name']) {
            $changes['name'] = $student['name'];
        }

        // Both columns are unique. A value another account already holds is
        // left alone: one person's corrected e-mail must not make a second
        // student's login impossible.
        if ($user->nim !== $student['nim'] && ! $this->heldByAnother('nim', $student['nim'], $user)) {
            $changes['nim'] = $student['nim'];
        }

        if ($user->email !== $email && ! $this->heldByAnother('email', $email, $user)) {
            $changes['email'] = $email;
        }

        // Roles and class are never written here: an admin or korti
        // appointment must survive every sync.
        if ($changes === []) {
            return false;
        }

        $user->forceFill($changes)->save();

        return true;
    }

    private function heldByAnother(string $column, string $value, User $user): bool
    {
        return User::query()->where($column, $value)->whereKeyNot($user->getKey())->exists();
    }

    /**
     * PEMIRA reads a student's programme from their student number, which is
     * what decides the HIMA election they are offered. When SISKA disagrees
     * with that, say so: the student can still vote, but their HIMA ballot may
     * be the wrong one, and that is not something to discover on voting day.
     *
     * @param  array{nim: string, name: string, email: ?string, prodi_code: ?string, status_code: ?string}  $student
     */
    private function checkProdi(array $student, User $user, VoterSyncRun $run): void
    {
        if ($student['prodi_code'] === null) {
            $run->addIssue($student['nim'], 'Program studi di SISKA belum dikenali PEMIRA, pemilihan HIMA bisa tidak muncul.');

            return;
        }

        $fromNim = $user->prodiCode();

        if ($fromNim !== $student['prodi_code']) {
            $run->addIssue(
                $student['nim'],
                'Kode prodi pada NIM ('.($fromNim ?? 'tidak terbaca').') tidak cocok dengan prodi di SISKA ('.
                $student['prodi_code'].'), pemilihan HIMA bisa salah.'
            );
        }
    }

    /**
     * Take off the roll everyone this run did not see — students who are no
     * longer active in SISKA, and anyone added by hand who is not on that
     * list.
     *
     * Ballots are never touched. Someone who already voted keeps their place
     * regardless: their vote is cast and counted, and dropping them would make
     * their own dashboard claim they were never entitled to it.
     */
    private function removeUnseen(Period $period, VoterSyncRun $run): void
    {
        $alreadyVoted = UserVote::query()
            ->where('period_id', $period->id)
            ->where('status', '!=', UserVote::STATUS_NOT_VOTED)
            ->pluck('user_id');

        $unseen = DB::table('period_user')
            ->where('period_id', $period->id)
            ->where(fn ($query) => $query->whereNull('synced_at')->orWhere('synced_at', '<', $run->started_at))
            ->whereNotIn('user_id', $alreadyVoted)
            ->pluck('user_id');

        if ($unseen->isEmpty()) {
            return;
        }

        foreach (User::query()->whereKey($unseen)->get(['id', 'nim', 'name']) as $dropped) {
            $run->addIssue(
                (string) ($dropped->nim ?? $dropped->id),
                'Dikeluarkan dari daftar pemilih: '.$dropped->name.' tidak ada di daftar mahasiswa aktif SISKA.'
            );
        }

        $period->voters()->detach($unseen->all());

        $run->removed_count += $unseen->count();
    }
}
