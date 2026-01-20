<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    // ID16-1 メール認証機能
    // 会員登録後、認証メール（VerifyEmail通知）が送信される
    public function test_email_verification_01_verification_mail_is_sent_after_register(): void
    {
        Notification::fake();

        $email = 'verify_' . uniqid() . '@example.com';

        $response = $this->post('/register', [
            'name' => '認証テストユーザー',
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/email/verify-notice');

        $user = User::where('email', $email)->firstOrFail();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    // ID16-2 メール認証機能
    // メール認証誘導画面で「認証はこちらから」ボタンを押下すると、メール認証サイト（MailHog）へ遷移できる
    public function test_email_verification_02_verify_notice_has_link_to_mailhog(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        $response = $this->get('/email/verify-notice');

        $response->assertStatus(200);

        $response->assertSee('http://localhost:8025', false);
    }

    // ID16-3 メール認証機能
    // 認証リンクへアクセスすると、ユーザーが認証済みになり /mypage/profile へ遷移する
    public function test_email_verification_03_user_can_verify_email_and_redirect_to_profile(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );

        $response = $this->get($verificationUrl);

        $response->assertStatus(302);
        $response->assertRedirect('/mypage/profile');

        $this->assertNotNull($user->fresh()->email_verified_at);
    }
}
