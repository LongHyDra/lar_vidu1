<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use RuntimeException;
use Tests\TestCase;

class AuthRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_logs_user_in_redirects_to_verification_and_sends_one_notification(): void
    {
        Notification::fake();

        $response = $this->post(route('register'), [
            'name' => 'New Customer',
            'email' => 'new-customer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'new-customer@example.com')->firstOrFail();

        $response->assertRedirect(route('verification.notice'))
            ->assertSessionHas('success');
        $this->assertAuthenticatedAs($user);
        $this->assertSame(1, User::where('email', $user->email)->count());
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_mail_failure_keeps_successful_registration_state_and_logs_user_in(): void
    {
        Notification::shouldReceive('send')->once()->andThrow(new RuntimeException('Resend unavailable'));

        $response = $this->post(route('register'), [
            'name' => 'Mail Failure Customer',
            'email' => 'mail-failure@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'mail-failure@example.com')->firstOrFail();

        $response->assertRedirect(route('verification.notice'))
            ->assertSessionHas('warning');
        $this->assertAuthenticatedAs($user);
        $this->assertSame(1, User::where('email', $user->email)->count());
    }

    public function test_verification_link_updates_email_verified_at(): void
    {
        $user = User::factory()->unverified()->create();
        $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(10), [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]);

        $this->actingAs($user)->get($url)
            ->assertRedirect(route('welcome'));

        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_unverified_user_can_login_but_checkout_requires_verification(): void
    {
        $user = User::factory()->unverified()->create(['password' => bcrypt('password')]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('welcome'));

        $this->assertAuthenticatedAs($user);
        $this->get(route('checkout.index'))
            ->assertRedirect(route('verification.notice'));
    }
}
