<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\StripeCheckoutService;

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

        $user = auth()->user();

        $shipping = session()->get("purchase.shipping.{$item_id}", []);

        if (!empty($shipping['shipping_postal_code'])) {
            $shipping_postal_code = $shipping['shipping_postal_code'];
        } else {
            $shipping_postal_code = $user->postal_code;
        }

        if (!empty($shipping['shipping_address'])) {
            $shipping_address = $shipping['shipping_address'];
        } else {
            $shipping_address = $user->address;
        }

        if (array_key_exists('shipping_building', $shipping)) {
            $shipping_building = $shipping['shipping_building'];
        } else {
            $shipping_building = $user->building;
        }

        return view('purchases.create', compact(
            'item',
            'shipping_postal_code',
            'shipping_address',
            'shipping_building'
        ));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::with('purchase')->findOrFail($item_id);

        if ($item->purchase) {
            return redirect()->route('items.show', ['item_id' => $item->id]);
        }

        if ($item->user_id === auth()->id()) {
            return redirect()->route('items.show', ['item_id' => $item->id]);
        }

        $paymentMethod = $request->input('payment_method');

        session()->put("purchase.shipping.{$item_id}", [
            'shipping_postal_code' => $request->input('shipping_postal_code'),
            'shipping_address'     => $request->input('shipping_address'),
            'shipping_building'    => $request->input('shipping_building'),
        ]);

        if ($paymentMethod === 'card') {
            $paymentMethodTypes = ['card'];
        } elseif ($paymentMethod === 'convenience_store') {
            $paymentMethodTypes = ['konbini'];
        } else {
            $paymentMethodTypes = ['card'];
        }

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
            'success_url' => route('purchases.success', ['item_id' => $item->id], true) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('purchases.cancel',  ['item_id' => $item->id], true),
            'metadata' => [
                'item_id' => (string) $item->id,
                'user_id' => (string) auth()->id(),
                'payment_method' => (string) $paymentMethod,
            ],
        ]);

        session([
            'purchase_item_id' => $item->id,
            'purchase_payment_method' => $paymentMethod,
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request, $item_id)
    {
        $item = Item::with('purchase')->findOrFail($item_id);

        if ($item->purchase) {
            return redirect('/');
        }

        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('purchases.create', ['item_id' => $item->id]);
        }

        $checkout = app(StripeCheckoutService::class)->retrieveCheckoutSession($sessionId);

        $metaItemId = $checkout->metadata->item_id ?? null;
        $metaUserId = $checkout->metadata->user_id ?? null;

        if ($metaItemId !== (string) $item->id || $metaUserId !== (string) auth()->id()) {
            return redirect()->route('purchases.create', ['item_id' => $item->id]);
        }

        if (($checkout->payment_status ?? null) !== 'paid') {
            return redirect()->route('purchases.create', ['item_id' => $item->id]);
        }

        $paymentMethod = session('purchase_payment_method')
            ?? ($checkout->metadata->payment_method ?? null);

        $user = auth()->user();
        $shipping = session()->get("purchase.shipping.{$item_id}", []);

        if (!empty($shipping['shipping_postal_code'])) {
            $shipping_postal_code = $shipping['shipping_postal_code'];
        } else {
            $shipping_postal_code = $user->postal_code;
        }

        if (!empty($shipping['shipping_address'])) {
            $shipping_address = $shipping['shipping_address'];
        } else {
            $shipping_address = $user->address;
        }

        if (!empty($shipping['shipping_building'])) {
            $shipping_building = $shipping['shipping_building'];
        } else {
            $shipping_building = $user->building;
        }

        DB::transaction(function () use (
            $item,
            $shipping_postal_code,
            $shipping_address,
            $shipping_building,
            $paymentMethod
        ) {
            $item->update([
                'shipping_postal_code' => $shipping_postal_code,
                'shipping_address'     => $shipping_address,
                'shipping_building'    => $shipping_building,
            ]);

            Purchase::firstOrCreate(
                ['item_id' => $item->id],
                [
                    'user_id' => auth()->id(),
                    'payment_method' => $paymentMethod,
                ]
            );
        });

        session()->forget([
            'purchase_item_id',
            'purchase_payment_method',
            "purchase.shipping.{$item_id}",
        ]);

        return redirect('/');
    }

    public function cancel($item_id)
    {
        return redirect()->route('purchases.create', ['item_id' => $item_id])
            ->withInput(['payment_method' => session('purchase_payment_method')]);
    }
}
