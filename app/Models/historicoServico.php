<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoServico extends Model
{
    use HasFactory;

    protected $table = 'historico_servicos';

    protected $fillable = [
        'id_agendamento',
        'descricao',
        'data_evento',
    ];

    protected $casts = [
        'data_evento' => 'datetime',
    ];

    // Relacionamentos

    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class, 'id_agendamento');
    }
}