<?php

namespace App\Jobs;

use App\Models\VoterSyncRun;
use App\Services\SiskaService;
use App\Services\VoterRollSynchroniser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * One page of SISKA's active-student list.
 *
 * The pages run one after another, each dispatching the next, rather than all
 * at once: SISKA throttles the API at 60 requests per minute, and a fan-out
 * would burn that budget in a second and start failing. The last page closes
 * the run, which is also what triggers the clean-up phase — so a pull that
 * dies half way through never removes anybody.
 */
class ImportSiskaStudentPage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 3;

    /** @var array<int, int> */
    public array $backoff = [30, 120, 300];

    public function __construct(
        public int $runId,
        public int $page = 1,
    ) {}

    public function handle(SiskaService $siska, VoterRollSynchroniser $synchroniser): void
    {
        $run = VoterSyncRun::find($this->runId);

        // Gone, or already finished or failed: nothing left for this page to do.
        if (! $run || $run->status !== VoterSyncRun::STATUS_RUNNING) {
            return;
        }

        $period = $run->period;

        if (! $period) {
            $run->markFailed('Periode pemilihan sudah tidak ada, tarikan dibatalkan.');

            return;
        }

        $page = $siska->fetchActiveStudents($this->page);

        foreach ($page['students'] as $student) {
            $synchroniser->absorb($student, $period, $run);
        }

        $run->forceFill([
            'current_page' => $this->page,
            'last_page' => $page['last_page'],
            'total_students' => $page['total'],
        ])->save();

        if ($this->page < $page['last_page']) {
            // Spaced out to stay well inside SISKA's rate limit even when the
            // pages themselves process quickly.
            self::dispatch($this->runId, $this->page + 1)->delay(now()->addSeconds(2));

            return;
        }

        $synchroniser->finish($period, $run);
    }

    /**
     * Reached only after the last retry. The run is left visibly failed with
     * the reason, and because the clean-up phase never ran, the roll still
     * holds everyone it held before.
     */
    public function failed(?Throwable $exception): void
    {
        Log::error('Tarik data SISKA gagal.', [
            'run_id' => $this->runId,
            'page' => $this->page,
            'message' => $exception?->getMessage(),
        ]);

        VoterSyncRun::find($this->runId)?->markFailed(
            'Gagal pada halaman '.$this->page.': '.($exception?->getMessage() ?? 'penyebab tidak diketahui').
            ' Data yang sudah masuk tetap tersimpan dan tidak ada pemilih yang dikeluarkan.'
        );
    }
}
