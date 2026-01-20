<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    // ID5-1 マイリスト一覧取得-1
    // ユーザーにログインする → マイリストページ（/?tab=mylist）を開くといいねをした商品だけが表示される
    public function test_mylist_01_only_liked_items_are_shown(): void
    {
        $me = User::factory()->create();

        $seller = User::factory()->create();

        $likedItem = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'いいねした商品',
        ]);

        $notLikedItem = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'いいねしてない商品',
        ]);

        Like::factory()->create([
            'user_id' => $me->id,
            'item_id' => $likedItem->id,
        ]);

        $response = $this->actingAs($me)->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('いいねした商品');

        $response->assertDontSee('いいねしてない商品');
    }


    // ID5-2 マイリスト一覧取得-2
    // ログイン → マイリストページを開く → 購入済み商品を確認すると購入済み商品に「Sold」のラベルが表示される
    public function test_mylist_02_purchased_item_shows_sold_label(): void
    {
        $me = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'マイリスト購入済み商品',
        ]);

        Like::factory()->create([
            'user_id' => $me->id,
            'item_id' => $item->id,
        ]);

        \App\Models\Purchase::factory()->create([
            'user_id' => $me->id,
            'item_id' => $item->id,
            'payment_method' => 'card',
        ]);

        $response = $this->actingAs($me)->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('マイリスト購入済み商品');
        $response->assertSee('Sold');
    }

    // ID5-3 マイリスト一覧取得-2
    // 未ログインでマイリストページ（/?tab=mylist）を開くと何も表示されない
    public function test_mylist_03_guest_sees_nothing(): void
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'ゲストには見えないはずの商品',
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertDontSee('ゲストには見えないはずの商品');
    }
}
