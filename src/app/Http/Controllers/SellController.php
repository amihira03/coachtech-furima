<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class SellController extends Controller
{
    /**
     * 出品フォーム表示
     */
    public function create()
    {
        $categories = Category::orderBy('id')->get();
        return view('sells.create', compact('categories'));
    }

    /**
     * 出品登録
     */
    public function store(ExhibitionRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated) {
            // ① 画像保存（storage/app/public/items/...）
            $storedPath = $request->file('image')->store('items', 'public');

            // ② items 作成
            $item = Item::create([
                'user_id' => $request->user()->id,
                'name' => $validated['name'],
                'brand_name' => $validated['brand_name'] ?? null,
                'description' => $validated['description'],
                'price' => $validated['price'],
                'condition' => $validated['condition'],

                // DBには相対パスを保存
                'image_path' => $storedPath,

                // 購入時に使うので出品時は null のままでOK
                'shipping_postal_code' => null,
                'shipping_address' => null,
                'shipping_building' => null,
            ]);

            // ③ カテゴリ紐付け（category_items に反映）
            $item->categories()->sync($validated['categories']);
        });

        // とりあえずトップへ（要件で別指定があれば後で変更できます）
        return redirect('/')->with('message', '商品を出品しました。');
    }
}
