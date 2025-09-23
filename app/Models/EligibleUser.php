<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EligibleUser extends Model
{
    protected $table = 'eligible_user';

    protected $fillable = [
        'nim',
        'status',
    ];
}
