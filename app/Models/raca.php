<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raca extends Model
{
    use HasFactory;

    protected $table = 'raca';

    protected $fillable = [
        'nome',
        'id_especie',
    ];

    // Relacionamentos

    public function especie()
    {
        return $this->belongsTo(Especie::class, 'id_especie');
    }

    public function pets()
    {
        return $this->hasMany(Pet::class, 'id_raca');
    }
}