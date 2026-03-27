<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agendamento extends Model
{
    use HasFactory;

    protected $table = 'agendamentos';

    protected $fillable = [
        'id_user_cliente',
        'id_pet',
        'id_user_sitter',
        'id_servico',
        'data_agendamento',
        'hora_inicio',
        'hora_fim',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'data_agendamento' => 'date',
        'status' => 'string',
    ];

    // Relacionamentos

    public function cliente()
    {
        return $this->belongsTo(User::class, 'id_user_cliente');
    }

    public function sitter()
    {
        return $this->belongsTo(User::class, 'id_user_sitter');
    }

    public function pet()
    {
        return $this->belongsTo(Pet::class, 'id_pet');
    }

    public function servico()
    {
        return $this->belongsTo(Servico::class, 'id_servico');
    }

    public function pagamento()
    {
        return $this->hasOne(Pagamento::class, 'id_agendamento');
    }

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'id_agendamento');
    }

    public function historicoServicos()
    {
        return $this->hasMany(HistoricoServico::class, 'id_agendamento');
    }
}