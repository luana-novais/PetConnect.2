<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    use HasFactory;

    protected $table = 'especie';

    protected $fillable = [
        'tipo_pet',
    ];

    protected $casts = [
        'tipo_pet' => 'string',
    ];

    // Relacionamentos

    public function racas()
    {
        return $this->hasMany(Raca::class, 'id_especie');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'id_especie');
    }
}