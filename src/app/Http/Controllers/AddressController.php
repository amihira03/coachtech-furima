<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function edit($item_id)
    {
        Item::findOrFail($item_id);

        $user = Auth::user();
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

        return view('purchases.edit-address', compact(
            'item_id',
            'shipping_postal_code',
            'shipping_address',
            'shipping_building'
        ));
    }

    public function update(AddressRequest $request, $item_id)
    {
        session()->put("purchase.shipping.{$item_id}", [
            'shipping_postal_code' => $request->input('shipping_postal_code'),
            'shipping_address'     => $request->input('shipping_address'),
            'shipping_building'    => $request->input('shipping_building'),
        ]);

        return redirect()->route('purchases.create', ['item_id' => $item_id]);
    }
}
