<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        $shipping_postal_code = !empty($item->shipping_postal_code) ? $item->shipping_postal_code : $user->postal_code;
        $shipping_address     = !empty($item->shipping_address)     ? $item->shipping_address     : $user->address;
        $shipping_building    = !empty($item->shipping_building)    ? $item->shipping_building    : $user->building;

        return view('purchases.edit_address', compact(
            'item_id',
            'shipping_postal_code',
            'shipping_address',
            'shipping_building'
        ));
    }

    public function update(AddressRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        $item->update([
            'shipping_postal_code' => $request->input('shipping_postal_code'),
            'shipping_address' => $request->input('shipping_address'),
            'shipping_building' => $request->input('shipping_building'),
        ]);

        return redirect()->route('purchases.create', ['item_id' => $item->id]);
    }
}
