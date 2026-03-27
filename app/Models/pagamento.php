<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pagamento extends Model
{
    use HasFactory;

    protected $table = 'pagamentos';

    protected $fillable = [
        'id_agendamento',
        'total_servico',
        'metodo_pagamento',
        'status_pagamento',
        'data_pagamento',
    ];

    protected $casts = [
        'total_servico' => 'decimal:2',
        'data_pagamento' => 'datetime',
        'metodo_pagamento' => 'string',
        'status_pagamento' => 'string',
    ];

    // Relacionamentos

    public function agendamento()
    {
        return $this->belongsTo(Agendamento::class, 'id_agendamento');
    }
}