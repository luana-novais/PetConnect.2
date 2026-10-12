<?php

namespace Tests\Feature;

use App\Models\Servico;
use Tests\TestCase;

class ServicoDurationTest extends TestCase
{
    public function test_service_duration_is_supported_and_casted_as_integer(): void
    {
        $servico = new Servico([
            'nome' => 'Pet Sitter',
            'valor_servico' => 80.00,
            'duracao_minutos' => 60,
        ]);

        $this->assertContains('duracao_minutos', $servico->getFillable());
        $this->assertSame(60, $servico->duracao_minutos);
    }
}
