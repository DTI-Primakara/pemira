<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vote extends Model
{
    protected $table = 'votes';
    protected $fillable = [
        'event_id',
        'candidate_id',
        'voted_at',
    ];

    public function candidate() : BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
