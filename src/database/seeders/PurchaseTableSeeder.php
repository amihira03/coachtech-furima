<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseTableSeeder extends Seeder
{
    public function run()
    {
        $now = now();

        DB::table('purchases')->insert([
            'user_id' => 1,
            'item_id' => 4,
            'payment_method' => 'card',
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
