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
        $page = $request->query('page', 'sell');

        $sellItems = collect();
        $buyItems = collect();

        if ($page === 'buy') {
            $itemIds = Purchase::where('user_id', $userId)
                ->latest()
                ->pluck('item_id')
                ->all();

            $itemsById = Item::whereIn('id', $itemIds)->get()->keyBy('id');

            $buyItems = collect($itemIds)
                ->map(fn($id) => $itemsById->get($id))
                ->filter();
        } else {
            $sellItems = Item::where('user_id', $userId)
                ->latest()
                ->get();

            $soldItemIds = Purchase::whereIn('item_id', $sellItems->pluck('id'))
                ->pluck('item_id')
                ->all();

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
