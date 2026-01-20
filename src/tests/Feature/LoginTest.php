<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    // ID2-1 ログイン機能-1
    // メールアドレスを未入力のままログインすると「メールアドレスを入力してください」と表示される
    public function test_login_01_email_required(): void
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }
    // ID2-2 ログイン機能-2
    // パスワードを未入力のままログインすると「パスワードを入力してください」と表示される
    public function test_login_02_password_required(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    // ID2-3 ログイン機能-3
    // 登録されていない情報でログインすると「ログイン情報が登録されていません」と表示される
    public function test_login_03_invalid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'not-exist_' . uniqid() . '@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302);

        $response->assertSessionHasErrors([
            'login' => 'ログイン情報が登録されていません',
        ]);
    }

    // ID2-4 ログイン機能-4
    // 認証済みユーザーで正しい情報を入力してログインするとログイン処理が実行され、トップ（/）へ遷移する
    public function test_login_04_success_verified_redirect_to_top(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'login_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    // ID2-5 ログイン機能-5
    // 未認証ユーザーで正しい情報を入力してログインすると、/email/verify-notice に遷移する
    public function test_login_05_success_unverified_redirect_to_verify_notice(): void
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'login_' . uniqid() . '@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/email/verify-notice');
        $this->assertAuthenticatedAs($user);
    }
}
