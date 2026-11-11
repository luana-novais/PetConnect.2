<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAccountDeletionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_their_own_account_and_is_logged_out(): void
    {
        $user = User::factory()->create(['role' => 'CLI']);

        $this->actingAs($user)
            ->delete(route('users.destroy', $user))
            ->assertRedirect(route('home'))
            ->assertSessionHas('success');

        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_user_cannot_delete_another_users_account(): void
    {
        $user = User::factory()->create(['role' => 'CLI']);
        $otherUser = User::factory()->create(['role' => 'CLI']);

        $this->actingAs($user)
            ->delete(route('users.destroy', $otherUser))
            ->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $otherUser->id]);
    }

    public function test_administrator_can_still_delete_another_account(): void
    {
        $admin = User::factory()->create(['role' => 'ADM']);
        $user = User::factory()->create(['role' => 'CLI']);

        $this->actingAs($admin)
            ->delete(route('users.destroy', $user))
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
