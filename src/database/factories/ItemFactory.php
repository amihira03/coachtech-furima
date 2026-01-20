<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->realText(80),
            'price' => 1000,
            'condition' => '良好',
            'image_path' => 'items/dummy.jpg',

            'brand_name' => $this->faker->company(),
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都テスト区1-2-3',
            'shipping_building' => 'テストビル101',
        ];
    }
}
