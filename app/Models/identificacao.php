<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Identificacao extends Model
{
    use HasFactory;

    protected $table = 'identificacao';

    protected $fillable = [
        'id_user',
        'tipo_documento',
        'documento',
    ];

    protected $casts = [
        'tipo_documento' => 'string',
    ];

    // Relacionamentos

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}