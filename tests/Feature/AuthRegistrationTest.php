<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_logs_user_in_without_email_verification(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'New Customer',
            'email' => 'new-customer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'new-customer@example.com')->firstOrFail();

        $response->assertRedirect(route('welcome'))
            ->assertSessionHas('success');
        $this->assertAuthenticatedAs($user);
        $this->assertSame(1, User::where('email', $user->email)->count());
    }

    public function test_unverified_user_can_login(): void
    {
        $user = User::factory()->unverified()->create(['password' => bcrypt('password')]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('welcome'));

        $this->assertAuthenticatedAs($user);
    }
}
