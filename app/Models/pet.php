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
        'id_foto',
        'observacoes',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'peso'            => 'decimal:2',
        'sexo'            => 'string',
    ];

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

    // foto é um belongsTo: id_foto fica na tabela pets
    public function foto()
    {
        return $this->belongsTo(FotoPet::class, 'id_foto');
    }

    public function agendamentos()
    {
        return $this->hasMany(Agendamento::class, 'id_pet');
    }
}