<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeTableSeeder extends Seeder
{
    public function run()
    {
        // ① いいねをするユーザー（出品者A）
        $user = User::where('email', 'seller_a@example.com')->first();

        if (!$user) {
            $this->command->warn('出品者Aが見つかりません。LikeTableSeederをスキップします。');
            return;
        }

        // ② いいね対象の商品（出品者Bの商品）
        $items = Item::whereIn('name', [
            '革靴',
            'ノートPC',
        ])->get();

        if ($items->isEmpty()) {
            $this->command->warn('対象の商品が見つかりません。LikeTableSeederをスキップします。');
            return;
        }

        // ③ likes データ作成
        foreach ($items as $item) {
            Like::firstOrCreate([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }
    }
}
