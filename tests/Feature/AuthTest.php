<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_accessible(): void
    {
        $this->get(route('login'))->assertOk();
    }

    public function test_register_page_is_accessible(): void
    {
        $this->get(route('register'))->assertOk();
    }

    public function test_user_can_register(): void
    {
        $response = $this->post(route('register'), [
            'name'                  => 'Иван Иванов',
            'email'                 => 'ivan@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('home.index'));
        $this->assertDatabaseHas('users', ['email' => 'ivan@example.com']);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secret123')]);

        $response = $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('home.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_cannot_login_with_wrong_password(): void
    {
        $user = User::factory()->create(['password' => bcrypt('correct')]);

        $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'wrong',
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post(route('logout'))
             ->assertRedirect('/');

        $this->assertGuest();
    }
}
