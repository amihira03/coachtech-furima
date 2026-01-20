<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Item;
use App\Models\Like;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;

    // ID7-1 商品詳細情報取得
    // すべての情報が商品詳細ページに表示されている
    public function test_item_show_01_displays_required_information(): void
    {
        $seller = User::factory()->create();
        $viewer = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '詳細テスト商品',
            'brand_name' => 'テストブランド',
            'description' => 'これは詳細ページの説明文です。',
            'price' => 12345,
            'condition' => '良好',
            'image_path' => 'items/test.jpg',
        ]);

        $cat1 = Category::factory()->create(['name' => 'ファッション']);
        $cat2 = Category::factory()->create(['name' => 'メンズ']);
        $item->categories()->attach([$cat1->id, $cat2->id]);

        Like::factory()->create(['user_id' => $viewer->id, 'item_id' => $item->id]);
        Like::factory()->create(['user_id' => User::factory()->create()->id, 'item_id' => $item->id]);

        $commenter = User::factory()->create(['name' => 'コメント太郎']);
        Comment::factory()->create([
            'user_id' => $commenter->id,
            'item_id' => $item->id,
            'body' => 'テストコメント本文',
        ]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);

        $response->assertSee('詳細テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('これは詳細ページの説明文です。');

        $response->assertSee('12,345');

        $response->assertSee('items/test.jpg');

        $response->assertSee('ファッション');
        $response->assertSee('メンズ');

        $response->assertSee('良好');

        $response->assertSee('コメント');
        $response->assertSee('（1）');

        $response->assertSee('コメント太郎');
        $response->assertSee('テストコメント本文');
    }

    // ID7-2 商品詳細情報取得(カテゴリー表示)
    // 複数選択されたカテゴリが商品詳細ページに表示されている
    public function test_item_show_02_displays_multiple_categories(): void
    {
        $seller = \App\Models\User::factory()->create();

        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'カテゴリ確認商品',
        ]);

        $category1 = \App\Models\Category::factory()->create(['name' => 'ファッション']);
        $category2 = \App\Models\Category::factory()->create(['name' => 'メンズ']);

        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200);

        $response->assertSee('ファッション');
        $response->assertSee('メンズ');
    }
}
