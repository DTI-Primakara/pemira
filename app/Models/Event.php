<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'period_id',
        'title',
        'type',
        'id_prodi',
    ];

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            $event->period_id ??= Period::activeId();
        });
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    /** Only the elections of the period being run now. */
    public function scopeForActivePeriod(Builder $query): Builder
    {
        return $query->where('period_id', Period::activeId());
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id_prodi');
    }

    /**
     * University-wide elections have no study programme attached.
     */
    public function isUniversityWide(): bool
    {
        return $this->id_prodi === null;
    }
}
