<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    // ID12-1
    // 配送先住所変更で登録した住所が購入画面に反映される
    public function test_address_01_updated_address_is_reflected_on_purchase_page(): void
    {
        $seller = User::factory()->create();
        $buyer  = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($buyer);

        $response = $this->patch("/purchase/address/{$item->id}", [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区テスト1-2-3',
            'shipping_building' => 'テストビル101',
        ]);

        $response->assertStatus(302);

        $page = $this->get("/purchase/{$item->id}");
        $page->assertStatus(200);

        $page->assertSee('123-4567');
        $page->assertSee('東京都新宿区テスト1-2-3');
        $page->assertSee('テストビル101');
    }

    // ID12-2
    // 購入時に入力した配送先が、購入した商品（items）に紐づいて保存される
    public function test_address_02_shipping_address_is_saved_on_item_when_purchasing(): void
    {
        $seller = User::factory()->create();
        $buyer  = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'shipping_postal_code' => null,
            'shipping_address' => null,
            'shipping_building' => null,
        ]);

        $this->actingAs($buyer);

        // ① 住所変更（sessionへ保存）
        $this->patch("/purchase/address/{$item->id}", [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区テスト1-2-3',
            'shipping_building' => 'テストビル101',
        ])->assertStatus(302);

        // ② store（Stripe Checkoutへ飛ぶだけ。ここは任意だがフローとして通す）
        $this->post(route('purchases.store', ['item_id' => $item->id]), [
            'payment_method' => 'card',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区テスト1-2-3',
            'shipping_building' => 'テストビル101',
        ])->assertStatus(302);

        // ③ StripeCheckoutService をモック（外部通信なしでsuccessを通す）
        $checkout = (object) [
            'metadata' => (object) [
                'item_id' => (string) $item->id,
                'user_id' => (string) $buyer->id,
                'payment_method' => 'card',
            ],
            'payment_status' => 'paid',
        ];

        $this->mock(\App\Services\StripeCheckoutService::class, function ($mock) use ($checkout) {
            $mock->shouldReceive('retrieveCheckoutSession')
                ->once()
                ->andReturn($checkout);
        });

        // ④ success を叩く（ここで items に保存される）
        $this->get(route('purchases.success', ['item_id' => $item->id]) . '?session_id=dummy')
            ->assertStatus(302);

        // ⑤ items に保存されていることを確認
        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区テスト1-2-3',
            'shipping_building' => 'テストビル101',
        ]);

        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $buyer->id,
            'payment_method' => 'card',
        ]);
    }
}
