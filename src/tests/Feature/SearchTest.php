<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    // ID6-1 商品検索機能-1
    // 全検索キーワードを入力して検索すると商品名が部分一致する商品が表示される
    public function test_search_01_partial_match_by_name(): void
    {
        $seller = User::factory()->create();

        Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'コーヒーミル',
        ]);

        Item::factory()->create([
            'user_id' => $seller->id,
            'name' => 'ノートPC',
        ]);

        $response = $this->get('/?keyword=コーヒー');

        $response->assertStatus(200);

        $response->assertSee('コーヒーミル');
        $response->assertDontSee('ノートPC');
    }

    // ID6-2 商品検索機能-2
    // 検索を実行し、購入済み商品を含む結果を表示すると購入済み商品に「Sold」が表示される
    public function test_search_02_purchased_item_shows_sold_label(): void
    {
        $seller = \App\Models\User::factory()->create();

        $item = \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '検索用テスト商品',
        ]);

        \App\Models\Purchase::factory()->create([
            'item_id' => $item->id,
            'payment_method' => 'card',
        ]);

        $response = $this->get('/?keyword=検索');

        $response->assertStatus(200);

        $response->assertSee('検索用テスト商品');
        $response->assertSee('Sold');
    }

    // ID6-3 商品検索機能-3
    // 異なる検索キーワードで検索するとキーワードに応じて表示される商品が切り替わる
    public function test_search_03_results_change_when_keyword_changes(): void
    {
        $seller = \App\Models\User::factory()->create();

        \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '赤いバッグ',
        ]);

        \App\Models\Item::factory()->create([
            'user_id' => $seller->id,
            'name' => '青いシャツ',
        ]);

        $responseBag = $this->get('/?keyword=バッグ');
        $responseBag->assertStatus(200);
        $responseBag->assertSee('赤いバッグ');
        $responseBag->assertDontSee('青いシャツ');

        $responseShirt = $this->get('/?keyword=シャツ');
        $responseShirt->assertStatus(200);
        $responseShirt->assertSee('青いシャツ');
        $responseShirt->assertDontSee('赤いバッグ');
    }
}
