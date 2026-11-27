<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One run of "Tarik Data dari SISKA": pulling the active-student list and
 * reconciling the voter roll of a period against it.
 *
 * Kept after the fact on purpose. The roll decides who may cast a ballot, so
 * an election has to be able to show later where its roll came from, what the
 * run changed, and which students it could not place.
 */
class VoterSyncRun extends Model
{
    public const STATUS_RUNNING = 'running';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'period_id',
        'started_by',
        'status',
        'current_page',
        'last_page',
        'total_students',
        'created_count',
        'updated_count',
        'enrolled_count',
        'skipped_count',
        'seen_count',
        'removed_count',
        'issues',
        'error',
        'started_at',
        'finished_at',
    ];

    protected function casts(): array
    {
        return [
            'issues' => 'array',
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function startedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'started_by');
    }

    /** Whether another run is already working on this period. */
    public static function isRunning(): bool
    {
        return static::query()
            ->where('period_id', Period::activeId())
            ->where('status', self::STATUS_RUNNING)
            ->exists();
    }

    /**
     * Note something the run could not do, with the student it concerns.
     *
     * Appended one at a time rather than collected in memory: the pages run as
     * separate queued jobs, so there is nothing to collect across them.
     */
    public function addIssue(string $nim, string $reason): void
    {
        $issues = $this->issues ?? [];

        // Capped so one badly broken import cannot grow a single row without
        // bound; the counts still tell the admin the real scale.
        if (count($issues) < 500) {
            $issues[] = ['nim' => $nim, 'reason' => $reason];
            $this->issues = $issues;
        }
    }

    public function markFailed(string $error): void
    {
        $this->forceFill([
            'status' => self::STATUS_FAILED,
            'error' => $error,
            'finished_at' => now(),
        ])->save();
    }
}
