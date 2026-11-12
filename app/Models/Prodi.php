<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodis';

    protected $primaryKey = 'id_prodi';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_prodi',
        'name',
        'short_name',
        'faculty',
        'faculty_short',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'id_prodi', 'id_prodi');
    }
}
