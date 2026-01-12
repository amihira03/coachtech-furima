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
        $user = User::where('email', 'seller_a@example.com')->first();

        if (!$user) {
            $this->command->warn('出品者Aが見つかりません。LikeTableSeederをスキップします。');
            return;
        }

        $items = Item::whereIn('name', [
            '革靴',
            'ノートPC',
        ])->get();

        if ($items->isEmpty()) {
            $this->command->warn('対象の商品が見つかりません。LikeTableSeederをスキップします。');
            return;
        }

        foreach ($items as $item) {
            Like::firstOrCreate([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }
    }
}
