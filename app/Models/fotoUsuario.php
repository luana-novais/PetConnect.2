<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoUsuario extends Model
{
    use HasFactory;

    protected $table = 'fotos';

    protected $fillable = [
        'id_user',
        'id_agendamento',
        'nome_arquivo',
        'url_foto',
    ];
}