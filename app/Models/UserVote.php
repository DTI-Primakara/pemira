<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserVote extends Model
{
    protected $table = 'user_vote';

    public const STATUS_NOT_VOTED = 'not voted';

    public const STATUS_VOTED = 'voted';

    public const STATUS_PASSED = 'passed';

    protected $fillable = [
        'user_id',
        'period_id',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (UserVote $ballot) {
            $ballot->period_id ??= Period::activeId();
        });
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
