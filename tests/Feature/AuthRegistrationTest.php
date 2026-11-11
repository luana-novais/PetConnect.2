<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_saves_normalized_cpf_for_user(): void
    {
        $response = $this->post(route('auth.register.store'), [
            'name' => 'Cliente de teste',
            'email' => 'cliente@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'CLI',
            'tipo_documento' => 'CPF',
            'documento' => '123.456.789-01',
        ]);

        $response->assertRedirect(route('welcome'));
        $this->assertDatabaseHas('identificacao', [
            'tipo_documento' => 'CPF',
            'documento' => '12345678901',
        ]);
    }
}