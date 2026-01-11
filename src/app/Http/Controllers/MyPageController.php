<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPageController extends Controller
{
    public function show(Request $request)
    {
        $userId = Auth::id();
        $page = $request->query('page', 'sell'); // デフォルトは出品一覧

        $sellItems = collect();
        $buyItems  = collect();

        if ($page === 'buy') {
            // 購入一覧：自分の購入（Purchase）を購入順で取得
            $purchases = Purchase::where('user_id', $userId)
                ->latest() // purchases.created_at の新しい順
                ->get();

            // item_id を購入順のまま取り出す
            $itemIds = $purchases->pluck('item_id')->all();

            // items をまとめて取得（順番は whereIn だと崩れることがあるので後で並べ替える）
            $items = Item::whereIn('id', $itemIds)->get()->keyBy('id');

            // 購入順に items を並べ直す
            $buyItems = collect($itemIds)
                ->map(fn($id) => $items->get($id))
                ->filter();
        } else {
            // 出品一覧：自分が出品した商品
            $sellItems = Item::where('user_id', $userId)
                ->latest()
                ->get();

            // SOLD 判定：購入済み item_id 一覧を取得（1商品1購入の前提）
            $soldItemIds = Purchase::pluck('item_id')->all();

            // 各商品に is_sold フラグを付与（view で SOLD 表示に利用）
            $sellItems = $sellItems->map(function ($item) use ($soldItemIds) {
                $item->is_sold = in_array($item->id, $soldItemIds, true);
                return $item;
            });
        }

        return view('mypage.index', [
            'page' => $page,
            'sellItems' => $sellItems,
            'buyItems' => $buyItems,
        ]);
    }
}
