<?php

namespace Tests\Feature;

use App\Models\Especie;
use App\Models\Pet;
use App\Models\Raca;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PetPhotoManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_add_and_delete_a_selected_pet_photo_from_details(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $pet = $this->createPet($user);

        $this->actingAs($user)
            ->get(route('pets.show', $pet))
            ->assertOk()
            ->assertSee('Adicionar fotos');

        $this->post(route('pets.photos.store', $pet), [
            'fotos' => [
                UploadedFile::fake()->createWithContent('pet-1.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/G7sAAAAASUVORK5CYII=')),
                UploadedFile::fake()->createWithContent('pet-2.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/G7sAAAAASUVORK5CYII=')),
            ],
        ])->assertRedirect(route('pets.show', $pet))
            ->assertSessionHas('success');

        $photos = $pet->fotos()->orderBy('id')->get();
        $this->assertCount(2, $photos);

        $photoToDelete = $photos[0];
        $photoToKeep = $photos[1];
        Storage::disk('public')->assertExists($photoToDelete->url_foto);
        Storage::disk('public')->assertExists($photoToKeep->url_foto);

        $this->delete(route('pets.photos.destroy', [$pet, $photoToDelete]))
            ->assertRedirect(route('pets.show', $pet))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('fotos', ['id' => $photoToDelete->id]);
        $this->assertDatabaseHas('fotos', ['id' => $photoToKeep->id]);
        Storage::disk('public')->assertMissing($photoToDelete->url_foto);
        Storage::disk('public')->assertExists($photoToKeep->url_foto);
    }

    public function test_user_cannot_delete_a_photo_from_another_users_pet(): void
    {
        Storage::fake('public');
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $pet = $this->createPet($owner);

        $this->actingAs($otherUser)
            ->delete(route('pets.photos.destroy', [$pet, $pet->fotos()->create([
                'nome_arquivo' => 'pet.jpg',
                'url_foto' => 'fotos/pets/pet.jpg',
            ])]))
            ->assertForbidden();

        $this->assertDatabaseHas('fotos', [
            'id_pet' => $pet->id,
            'nome_arquivo' => 'pet.jpg',
        ]);
    }

    private function createPet(User $owner): Pet
    {
        $especie = Especie::create(['tipo_pet' => 'CACHORRO']);
        $raca = Raca::create([
            'nome' => 'SRD',
            'id_especie' => $especie->id,
        ]);

        return Pet::create([
            'id_user' => $owner->id,
            'nome' => 'Toby',
            'id_especie' => $especie->id,
            'id_raca' => $raca->id,
            'sexo' => 'MACHO',
        ]);
    }
}
