<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    // ID3-1 ログアウト機能-1
    // ユーザーでログインし、ログアウトボタン（/logout）を押すとログアウト処理が実行される（未認証状態になる）
    public function test_logout_01_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertStatus(302);

        $this->assertGuest();
    }
}
