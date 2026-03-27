<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoPet extends Model
{
    use HasFactory;

    protected $table = 'fotos_pets';

    protected $fillable = [
        'id_pet',
        'nome_arquivo',
        'url_foto',
    ];

    // Relacionamentos

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'id_pet');
    }
}