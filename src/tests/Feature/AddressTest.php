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
        $seller = \App\Models\User::factory()->create();
        $buyer  = \App\Models\User::factory()->create();

        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'shipping_postal_code' => null,
            'shipping_address' => null,
            'shipping_building' => null,
        ]);

        $this->actingAs($buyer);

        $this->patch("/purchase/address/{$item->id}", [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区テスト1-2-3',
            'shipping_building' => 'テストビル101',
        ])->assertStatus(302);

        $this->post(route('purchases.store', ['item_id' => $item->id]), [
            'payment_method' => 'card',
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区テスト1-2-3',
            'shipping_building' => 'テストビル101',
        ])->assertStatus(302);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都新宿区テスト1-2-3',
            'shipping_building' => 'テストビル101',
        ]);
    }
}
