<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'pets';

    protected $fillable = [
        'id_user',
        'nome',
        'data_nascimento',
        'id_especie',
        'id_raca',
        'peso',
        'sexo',
        'observacoes',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'peso' => 'decimal:2',
        'sexo' => 'string',
    ];

    // Relacionamentos

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function especie()
    {
        return $this->belongsTo(Especie::class, 'id_especie');
    }

    public function raca()
    {
        return $this->belongsTo(Raca::class, 'id_raca');
    }

    public function fotos()
    {
        return $this->hasMany(FotoPet::class, 'id_pet');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_pet');
    }
}