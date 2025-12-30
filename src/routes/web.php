<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    // PG01 商品一覧（トップ）
    return view('items.index');
});

// PG05 商品詳細（公開）
Route::get('/item/{item_id}', function ($item_id) {
    return "商品詳細（仮） item_id={$item_id}";
});

// 認証必須（PG06, PG07, PG08, PG09, PG10, PG11, PG12）
Route::middleware('auth')->group(function () {
    // PG08 商品出品
    Route::get('/sell', function () {
        return view('items.sell');
    });


    // PG06 商品購入
    Route::get('/purchase/{item_id}', function ($item_id) {
        return "商品購入（仮） item_id={$item_id}";
    });

    // PG07 送付先住所変更
    Route::get('/purchase/address/{item_id}', function ($item_id) {
        return "送付先住所変更（仮） item_id={$item_id}";
    });

    // PG09 プロフィール（マイページ） ※ ?page=buy / ?page=sell もここで受ける
    Route::get('/mypage', function () {
        return view('mypage.index');
    });

    // PG10 プロフィール編集
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');
});
