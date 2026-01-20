<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LikeActionTest extends TestCase
{
    use RefreshDatabase;

    // ID8-1 いいね機能
    // いいねした商品として登録され、いいね合計値が増加表示される
    public function test_like_01_user_can_like_item(): void
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($user);

        $response = $this->post('/item/' . $item->id . '/like');

        $response->assertStatus(302);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    // ID8-2 いいね機能
    // 未いいね時は heart-default.png・いいね後は heart-liked.png
    public function test_like_02_icon_image_is_switched_on_item_show(): void
    {
        $user = \App\Models\User::factory()->create();
        $seller = \App\Models\User::factory()->create();

        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($user);

        $before = $this->get('/item/' . $item->id);
        $before->assertStatus(200);
        $before->assertSee('images/icons/heart-default.png');

        $this->post('/item/' . $item->id . '/like')->assertStatus(302);

        $after = $this->get('/item/' . $item->id);
        $after->assertStatus(200);
        $after->assertSee('images/icons/heart-liked.png');
    }

    // ID8-3 いいね機能
    // もう一度いいねを押すとlikesテーブルのレコードが削除される
    public function test_like_03_user_can_unlike_item_by_toggling(): void
    {
        $user = \App\Models\User::factory()->create();
        $seller = \App\Models\User::factory()->create();

        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $this->actingAs($user);

        $this->post('/item/' . $item->id . '/like')->assertStatus(302);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->post('/item/' . $item->id . '/like')->assertStatus(302);

        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
