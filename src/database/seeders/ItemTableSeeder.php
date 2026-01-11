<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class ItemTableSeeder extends Seeder
{
    public function run()
    {
        $users = User::orderBy('id')->take(2)->get();

        if ($users->count() < 2) {
            return;
        }

        $sellerA = $users[0];
        $sellerB = $users[1];

        $now = now();

        $items = [
            [
                'user_id' => $sellerA->id,
                'name' => '腕時計',
                'brand_name' => 'Rolax',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'condition' => '良好',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'shipping_postal_code' => null,
                'shipping_address' => null,
                'shipping_building' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $sellerA->id,
                'name' => 'HDD',
                'brand_name' => '西芝',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'condition' => '目立った傷や汚れなし',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'shipping_postal_code' => null,
                'shipping_address' => null,
                'shipping_building' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $sellerA->id,
                'name' => '玉ねぎ3束',
                'brand_name' => null,
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'condition' => 'やや傷や汚れあり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'shipping_postal_code' => null,
                'shipping_address' => null,
                'shipping_building' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $sellerB->id,
                'name' => '革靴',
                'brand_name' => null,
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'condition' => '状態が悪い',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'shipping_postal_code' => null,
                'shipping_address' => null,
                'shipping_building' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $sellerB->id,
                'name' => 'ノートPC',
                'brand_name' => null,
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'condition' => '良好',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'shipping_postal_code' => null,
                'shipping_address' => null,
                'shipping_building' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $sellerB->id,
                'name' => 'マイク',
                'brand_name' => null,
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'condition' => '目立った傷や汚れなし',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'shipping_postal_code' => null,
                'shipping_address' => null,
                'shipping_building' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $sellerB->id,
                'name' => 'ショルダーバッグ',
                'brand_name' => null,
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'condition' => 'やや傷や汚れあり',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'shipping_postal_code' => null,
                'shipping_address' => null,
                'shipping_building' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $sellerB->id,
                'name' => 'タンブラー',
                'brand_name' => null,
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'condition' => '状態が悪い',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'shipping_postal_code' => null,
                'shipping_address' => null,
                'shipping_building' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $sellerA->id,
                'name' => 'コーヒーミル',
                'brand_name' => 'Starbacks',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'condition' => '良好',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'shipping_postal_code' => null,
                'shipping_address' => null,
                'shipping_building' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id' => $sellerA->id,
                'name' => 'メイクセット',
                'brand_name' => null,
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'condition' => '目立った傷や汚れなし',
                'image_path' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'shipping_postal_code' => null,
                'shipping_address' => null,
                'shipping_building' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('items')->insert($items);
    }
}
