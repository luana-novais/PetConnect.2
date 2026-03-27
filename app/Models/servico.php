<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    use HasFactory;

    protected $table = 'servicos';

    protected $fillable = [
        'nome',
        'valor_servico',
    ];

    protected $casts = [
        'valor_servico' => 'decimal:2',
    ];

    // Relacionamentos

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_servico');
    }
}