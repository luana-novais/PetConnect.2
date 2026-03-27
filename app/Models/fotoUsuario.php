<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoUsuario extends Model
{
    use HasFactory;

    protected $table = 'fotos_usuarios';

    protected $fillable = [
        'id_user',
        'nome_arquivo',
        'url_foto',
    ];

    // Relacionamentos

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}