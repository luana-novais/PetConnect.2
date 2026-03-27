<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    use HasFactory;

    protected $table = 'avaliacoes';

    protected $fillable = [
        'id_user',
        'id_agendamento',
        'nota',
        'comentarios',
    ];

    protected $casts = [
        'nota' => 'decimal:2',
    ];

    // Relacionamentos

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class, 'id_agendamento');
    }
}