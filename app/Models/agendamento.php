<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Pet;
use App\Models\Servico;
use App\Models\Pagamento;
use App\Models\Avaliacao;
use App\Models\HistoricoServico;

class Agendamento extends Model
{
    use HasFactory;

    protected $table = 'agendamentos';

    protected $fillable = [
        'id_user_cliente',
        'id_pet',
        'id_user_sitter',
        'id_servico',
        'data_inicio',
        'data_fim',
        'status',
        'observacoes',
    ];

    protected $casts = [
        'data_inicio' => 'datetime:Y-m-d H:i',
        'data_fim' => 'datetime:Y-m-d H:i',
        'status' => 'string',
    ];

    const STATUS_PENDENTE = 'pendente';
    const STATUS_CONFIRMADO = 'confirmado';
    const STATUS_CANCELADO = 'cancelado';
    const STATUS_CONCLUIDO = 'concluido';

    // RELACIONAMENTOS

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

    public function fotos()
    {
        return $this->hasMany(FotoPet::class, 'id_agendamento');
    }

    public function historicoServicos()
    {
        return $this->hasMany(HistoricoServico::class, 'id_agendamento');
    }
}