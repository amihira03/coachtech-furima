<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Like;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');

        if ($tab === 'mylist' && !auth()->check()) {
            return view('items.index', [
                'items' => collect(),
                'tab' => $tab,
                'keyword' => $keyword,
            ]);
        }
        $query = Item::query()
            ->with('purchase');

        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }
        if ($tab === 'mylist') {
            $query->whereHas('likes', function ($likeQuery) {
                $likeQuery->where('user_id', auth()->id());
            });
        }

        $items = $query->latest()->get();

        return view('items.index', [
            'items' => $items,
            'tab' => $tab,
            'keyword' => $keyword,
        ]);
    }

    public function show($item_id)
    {
        $item = Item::with(['categories', 'purchase', 'comments.user'])
            ->withCount(['likes', 'comments'])
            ->findOrFail($item_id);
        $isLiked = false;

        if (auth()->check()) {
            $isLiked = $item->likes()
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('items.show', compact('item', 'isLiked'));
    }

    public function like($item_id)
    {
        $userId = auth()->id();
        $like = Like::where('user_id', $userId)
            ->where('item_id', $item_id)
            ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $userId,
                'item_id' => $item_id,
            ]);
        }

        return redirect()->route('items.show', ['item_id' => $item_id]);
    }
}
