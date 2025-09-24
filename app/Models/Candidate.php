<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $table = 'candidates';

    protected $fillable = [
        'event_id',
        'name_candidates',
        'number',
        'vision',
        'mission',
        'image'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
