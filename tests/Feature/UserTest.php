<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase; // Garante um banco de dados limpo para cada teste

    /** @test */
    public function it_registers_a_new_user()
    {
        $data = [
            'name' => 'Denis Teste',
            'email' => 'denis@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->post(route('register'), $data);

        $this->assertDatabaseHas('users', [
            'email' => 'denis@example.com',
        ]);

        $response->assertRedirect(route('home'));
    }

    /** @test */
    public function it_logs_in_a_user_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'denis@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'denis@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('home'));
    }

    /** @test */
    public function it_fails_to_log_in_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'denis@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'denis@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function it_denies_access_to_unauthenticated_users()
    {
        $response = $this->get(route('home'));

        $response->assertRedirect(route('login'));
    }
}
