<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Timeline extends Model
{
    use HasFactory;

    protected $table = 'timelines';

    protected $fillable = [
        'period_id',
        'title',
        'starts_on',
        'ends_on',
        'position',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Timeline $timeline) {
            $timeline->period_id ??= Period::activeId();
        });
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    /** Only the agenda of the period being run now. */
    public function scopeForActivePeriod(Builder $query): Builder
    {
        return $query->where('period_id', Period::activeId());
    }

    /** Agenda order as the committee arranged it, earliest first on a tie. */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position')->orderBy('starts_on');
    }
}
