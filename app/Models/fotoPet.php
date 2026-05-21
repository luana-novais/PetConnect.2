<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoPet extends Model
{
    use HasFactory;

    protected $table = 'fotos';

    protected $fillable = [
        'nome_arquivo',
        'url_foto',
    ];
}