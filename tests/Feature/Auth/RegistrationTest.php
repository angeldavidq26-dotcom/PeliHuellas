<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get(route('register'));

        $response->assertOk();
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post(route('register.store'), [
            'nombres' => 'John',
            'apellidos' => 'Doe',
            'email' => 'test@example.com',
            'telefono' => '3105550000',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => true,
        ]);

        $user = User::where('email', 'test@example.com')->first();

        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('dashboard', absolute: false));

        $this->assertAuthenticated();
    }
}
