<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Like;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        // /?tab=mylist の "mylist" を受け取る（無ければ null）
        $tab = $request->query('tab');

        // /?keyword=○○ の検索キーワード（無ければ null）
        $keyword = $request->query('keyword');

        // 未ログインで mylist を見ようとしたら「何も表示しない」
        if ($tab === 'mylist' && !auth()->check()) {
            return view('items.index', [
                'items' => collect(), // 空の一覧
                'tab' => $tab,
                'keyword' => $keyword,
            ]);
        }

        // 商品一覧のベース：購入情報も一緒に取る（Sold判定用）
        $query = Item::query()
            ->with('purchase');

        // ログイン中なら「自分の出品商品は除外」
        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }

        // 検索：商品名の部分一致
        if (!empty($keyword)) {
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        // マイリスト：自分がいいねした商品だけ
        if ($tab === 'mylist') {
            $query->whereHas('likes', function ($likeQuery) {
                $likeQuery->where('user_id', auth()->id());
            });
        }

        // 取得（新しい順）
        $items = $query->latest()->get();

        // 画面へ渡す
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
        // ログインしている時だけ判定する
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
            // いいね解除
            $like->delete();
        } else {
            // いいね登録
            Like::create([
                'user_id' => $userId,
                'item_id' => $item_id,
            ]);
        }

        return redirect()->route('items.show', ['item_id' => $item_id]);
    }
}
