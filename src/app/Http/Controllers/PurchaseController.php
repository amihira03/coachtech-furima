<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function create($item_id)
    {
        $item = Item::with('purchase')->findOrFail($item_id);

        if ($item->purchase) {
            return redirect()->route('items.show', ['item_id' => $item->id]);
        }

        if ($item->user_id === auth()->id()) {
            return redirect()->route('items.show', ['item_id' => $item->id]);
        }

        // 配送先表示（itemsを優先、未設定ならusers）
        $user = auth()->user();

        $shipping_postal_code = !empty($item->shipping_postal_code) ? $item->shipping_postal_code : $user->postal_code;
        $shipping_address     = !empty($item->shipping_address)     ? $item->shipping_address     : $user->address;
        $shipping_building    = !empty($item->shipping_building)    ? $item->shipping_building    : $user->building;

        return view('purchases.create', compact(
            'item',
            'shipping_postal_code',
            'shipping_address',
            'shipping_building'
        ));
    }

    /**
     * 購入ボタン押下 → Stripe Checkout へ遷移
     * ※ ここでは Purchase を作成しない（決済成功時に確定する）
     */
    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::with('purchase')->findOrFail($item_id);

        // Sold は購入不可（二重ガード）
        if ($item->purchase) {
            return redirect()->route('items.show', ['item_id' => $item->id]);
        }

        // 自分の商品は購入不可（二重ガード）
        if ($item->user_id === auth()->id()) {
            return redirect()->route('items.show', ['item_id' => $item->id]);
        }

        $paymentMethod = $request->input('payment_method');

        // 支払い方法に応じて Stripe Checkout の支払い手段を切替
        // card -> card / convenience_store -> konbini
        $paymentMethodTypes = match ($paymentMethod) {
            'card' => ['card'],
            'convenience_store' => ['konbini'],
            default => ['card'],
        };

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $session = \Stripe\Checkout\Session::create([
            'mode' => 'payment',
            'payment_method_types' => $paymentMethodTypes,

            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => (int) $item->price,
                    'product_data' => [
                        'name' => $item->name,
                    ],
                ],
            ]],

            // 成功/キャンセルの戻り先
            'success_url' => route('purchases.success', ['item_id' => $item->id], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('purchases.cancel',  ['item_id' => $item->id], true),

            // 後で参照しやすいように付与（任意）
            'metadata' => [
                'item_id' => (string) $item->id,
                'user_id' => (string) auth()->id(),
                'payment_method' => (string) $paymentMethod,
            ],
        ]);

        // success/cancel で使えるよう、最低限保持（キー自体じゃないので安全）
        session([
            'purchase_item_id' => $item->id,
            'purchase_payment_method' => $paymentMethod,
        ]);

        // Stripe決済画面へ
        return redirect()->away($session->url);
    }

    /**
     * Stripe 決済成功後：ここで Purchase を確定する
     */
    public function success(Request $request, $item_id)
    {
        $item = Item::with('purchase')->findOrFail($item_id);

        // 念のため（二重作成防止）
        if ($item->purchase) {
            return redirect('/');
        }

        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('purchases.create', ['item_id' => $item->id]);
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $checkout = \Stripe\Checkout\Session::retrieve($sessionId);

        // 支払いが完了している場合だけ確定
        if (($checkout->payment_status ?? null) !== 'paid') {
            return redirect()->route('purchases.create', ['item_id' => $item->id]);
        }

        $paymentMethod = session('purchase_payment_method')
            ?? ($checkout->metadata->payment_method ?? null);

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_method' => $paymentMethod,
        ]);

        session()->forget(['purchase_item_id', 'purchase_payment_method']);

        // 要件：購入後は商品一覧へ
        return redirect('/');
    }

    /**
     * Stripe キャンセル時：購入画面へ戻す
     */
    public function cancel($item_id)
    {
        // 選択状態を残したい場合は withInput を使う
        return redirect()->route('purchases.create', ['item_id' => $item_id])
            ->withInput(['payment_method' => session('purchase_payment_method')]);
    }
}
