<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    // ID10-1 商品購入機能-1
    // 「購入する」ボタンを押すと購入が完了する（Purchaseが作成される）
    public function test_purchase_01_user_can_purchase_item(): void
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($buyer);

        $response = $this->post("/purchase/{$item->id}", [
            'payment_method' => 'card',
            'shipping_postal_code' => '1234567',
            'shipping_address' => '東京都テスト1-2-3',
            'shipping_building' => 'テストビル101',
        ]);

        $response->assertStatus(302);

        $response->assertRedirect();
        $this->assertStringContainsString('checkout.stripe.com', $response->headers->get('location'));
    }

    // ID10-2 商品購入機能-2
    // 購入した商品は商品一覧画面で「Sold」と表示される
    public function test_item_index_02_purchased_item_shows_sold_label(): void
    {
        $buyer  = \App\Models\User::factory()->create();
        $seller = \App\Models\User::factory()->create();

        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        \App\Models\Purchase::factory()->create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
        ]);

        $this->actingAs($buyer);
        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSee('Sold');
    }

    // ID10-3 商品購入機能-3
    // 購入した商品がマイページに表示される
    public function test_purchase_03_purchased_item_is_shown_on_mypage(): void
    {
        $seller = \App\Models\User::factory()->create();
        $buyer  = \App\Models\User::factory()->create();

        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '購入テスト商品',
        ]);

        \App\Models\Purchase::factory()->create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
        ]);

        $this->actingAs($buyer);
        $response = $this->get('/mypage?page=buy');

        $response->assertStatus(200);

        $response->assertSee('購入テスト商品');
    }

    // ID11 支払い方法選択機能
    // 支払い方法の選択がリクエストとして送信され、old入力として保持される
    public function test_purchase_11_payment_method_is_kept_as_old_input(): void
    {
        $seller = \App\Models\User::factory()->create();
        $buyer  = \App\Models\User::factory()->create();

        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($buyer);

        $response = $this->from("/purchase/{$item->id}")
            ->post(route('purchases.store', ['item_id' => $item->id]), [
                'payment_method' => 'card',
                'shipping_postal_code' => '',
                'shipping_address' => '',
                'shipping_building' => '',
            ]);

        $response->assertStatus(302);

        $response->assertSessionHasInput('payment_method', 'card');

        $this->assertDatabaseCount('purchases', 0);
    }
}
