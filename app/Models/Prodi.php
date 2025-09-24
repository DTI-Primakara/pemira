<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodis';

    protected $primaryKey = 'id_prodi';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id_prodi',
        'name',
        'short_name',
    ];

    public function events()
    {
        return $this->hasMany(Event::class, 'id_prodi', 'id_prodi');
    }
}
