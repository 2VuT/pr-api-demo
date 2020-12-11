<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        Notification::fake();
    }

    public function test_password_can_be_reset()
    {
        $user = User::factory()->create();

        $response = $this->json('POST', '/api/forgot-password', [
            'email' => $user->email,
            'app_url' => 'https://pr-app.vercel.app',
        ]);

        $response->assertOk();

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $this->assertEquals(
                'https://pr-app.vercel.app/reset-password/'.$notification->token,
                $notification->createUrl()
            );

            $response = $this->json('POST', '/api/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertOk();

            $this->assertTrue(Hash::check('password', $user->fresh()->password));

            return true;
        });
    }

    public function test_it_cannot_send_password_reset_email_to_invalid_email()
    {
        $response = $this->withExceptionHandling()
            ->json('POST', '/api/forgot-password', [
                'email' => 'foo@example.com',
                'app_url' => 'https://pr-app.vercel.app/',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'email' => [trans(Password::INVALID_USER)],
            ],
        ]);

        Notification::assertNothingSent();
    }

    public function test_password_cannot_be_reset_with_invalid_token()
    {
        $user = User::factory()->create();

        $response = $this->withExceptionHandling()
            ->json('POST', '/api/reset-password', [
                'token' => 'invalid-token',
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'errors' => [
                'email' => [trans(Password::INVALID_TOKEN)],
            ],
        ]);
    }
}
