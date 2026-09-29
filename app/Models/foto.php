<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Foto extends Model
{
    protected $table = 'fotos';
    protected $fillable = ['nome_arquivo', 'url_foto'];

    public function getUrlAttribute()
    {
        return Storage::url($this->url_foto);
    }
}