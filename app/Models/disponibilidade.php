<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilidade extends Model
{
    use HasFactory;

    protected $table = 'disponibilidade';

    public $timestamps = false;

    protected $fillable = [
        'id_user',
        'dia_semana',
        'hora_inicio',
        'hora_fim',
    ];

    protected $casts = [
        'dia_semana' => 'string',
    ];

    // Relacionamentos

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}