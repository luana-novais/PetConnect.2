<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserPasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_wrong_current_password_is_reported_in_portuguese_without_updating_the_profile(): void
    {
        $user = User::factory()->create(['name' => 'Nome original']);

        $this->actingAs($user)
            ->put(route('users.update', $user), $this->profileData([
                'name' => 'Nome alterado',
                'current_password' => 'senha-errada',
                'password' => 'novaSenha123',
                'password_confirmation' => 'novaSenha123',
            ]))
            ->assertSessionHasErrors([
                'current_password' => 'A senha atual está incorreta.',
            ]);

        $this->assertSame('Nome original', $user->fresh()->name);
        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_password_confirmation_error_is_reported_in_portuguese(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->put(route('users.update', $user), $this->profileData([
                'current_password' => 'password',
                'password' => 'novaSenha123',
                'password_confirmation' => 'senha-diferente',
            ]))
            ->assertSessionHasErrors([
                'password' => 'A confirmação da nova senha não confere.',
            ]);
    }

    private function profileData(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Nome de teste',
            'email' => 'teste@example.com',
            'numero' => '11999999999',
            'endereco' => 'Rua de teste, 10',
            'complemento' => '',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'cep' => '01000-000',
        ], $overrides);
    }
}
