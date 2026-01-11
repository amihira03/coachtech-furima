<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\SellController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', [ItemController::class, 'index']);

// PG05 商品詳細（公開）
Route::get('/item/{item_id}', [ItemController::class, 'show'])
    ->name('items.show');

// 認証必須
Route::middleware('auth')->group(function () {

    // PG08 商品出品
    Route::get('/sell', [SellController::class, 'create'])->name('sell.create');
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

    // PG06 商品購入（購入画面）
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])
        ->name('purchases.create');

    // PG06 商品購入（購入処理）
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])
        ->name('purchases.store');

    // ===== FN023 Stripe決済 戻り先（追加） =====
    Route::get('/purchase/{item_id}/success', [PurchaseController::class, 'success'])
        ->name('purchases.success');

    Route::get('/purchase/{item_id}/cancel', [PurchaseController::class, 'cancel'])
        ->name('purchases.cancel');
    // =========================================

    // PG07 送付先住所変更（表示）
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])
        ->name('purchase.address.edit');

    // PG07 送付先住所変更（更新）
    Route::patch('/purchase/address/{item_id}', [AddressController::class, 'update'])
        ->name('purchase.address.update');

    // PG09（＋ page=buy/sell で PG11/PG12 を出し分ける想定）
    Route::get('/mypage', [MyPageController::class, 'show'])
        ->name('mypage');

    // PG10 プロフィール編集
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/mypage/profile', [ProfileController::class, 'update'])
        ->name('profile.update');


    // いいね（ログイン必須）
    Route::post('/item/{item_id}/like', [ItemController::class, 'like'])
        ->name('items.like');

    // コメント送信（商品詳細）
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])
        ->name('items.comment.store');
});


// ===== メール認証（応用要件） =====

// 誘導画面（1ページに統一）
Route::get('/email/verify-notice', function () {
    $user = auth()->user();

    if ($user && $user->hasVerifiedEmail()) {
        return redirect('/');
    }

    return view('auth.verify-notice');
})->middleware('auth')->name('verification.notice');

// 認証メール再送（誘導画面の「再送」用）
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware('auth')->name('verification.send');

// 認証リンク（メール本文のURLの受け口）
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/mypage/profile'); // 認証完了後はプロフィール設定へ
})->middleware(['auth', 'signed'])->name('verification.verify');
