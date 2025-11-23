<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    protected $table = 'candidates';

    protected $fillable = [
        'event_id',
        'name_candidates',
        'number',
        'vision',
        'mission',
        'image',
        'position'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'candidate_id', 'id');
    }
}
