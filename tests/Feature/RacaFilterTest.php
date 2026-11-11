<?php

namespace Tests\Feature;

use App\Models\Especie;
use App\Models\Raca;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RacaFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_filter_breeds_by_species(): void
    {
        $admin = User::factory()->create(['role' => 'ADM']);
        $dogSpecies = Especie::create(['tipo_pet' => 'CACHORRO']);
        $catSpecies = Especie::create(['tipo_pet' => 'GATO']);

        Raca::create(['nome' => 'Labrador', 'id_especie' => $dogSpecies->id]);
        Raca::create(['nome' => 'Persa', 'id_especie' => $catSpecies->id]);

        $this->actingAs($admin)
            ->get(route('racas.index', ['id_especie' => $dogSpecies->id]))
            ->assertOk()
            ->assertSee('Labrador')
            ->assertDontSee('Persa')
            ->assertSee('CACHORRO');
    }

    public function test_admin_can_clear_the_breed_species_filter(): void
    {
        $admin = User::factory()->create(['role' => 'ADM']);
        $species = Especie::create(['tipo_pet' => 'CACHORRO']);
        Raca::create(['nome' => 'Labrador', 'id_especie' => $species->id]);
        $otherSpecies = Especie::create(['tipo_pet' => 'GATO']);
        Raca::create(['nome' => 'Persa', 'id_especie' => $otherSpecies->id]);

        $this->actingAs($admin)
            ->get(route('racas.index', ['id_especie' => $species->id]))
            ->assertOk()
            ->assertSee('Limpar filtro');

        $this->get(route('racas.index'))
            ->assertOk()
            ->assertSee('Labrador')
            ->assertSee('Persa');
    }
}
