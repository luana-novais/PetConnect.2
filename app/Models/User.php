<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function identificacao()
    {
        return $this->hasOne(Identificacao::class, 'id_user');
    }
 
    public function pets()
    {
        return $this->hasMany(Pet::class, 'id_user');
    }
 
    public function enderecos()
    {
        return $this->hasMany(Endereco::class, 'id_user');
    }
 
    public function telefones()
    {
        return $this->hasMany(Telefone::class, 'id_user');
    }
 
    public function disponibilidades()
    {
        return $this->hasMany(Disponibilidade::class, 'id_user');
    }
 
    public function fotosUsuarios()
    {
        return $this->hasMany(FotoUsuario::class, 'id_user');
    }
 
    public function agendamentosComoCliente()
    {
        return $this->hasMany(Agendamento::class, 'id_user_cliente');
    }
 
    public function agendamentosComoSitter()
    {
        return $this->hasMany(Agendamento::class, 'id_user_sitter');
    }
 
    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class, 'id_user');
    }
}
