<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class AddressController extends Controller
{
    public function edit($item_id)
    {
        return view('purchases.edit_address', ['item_id' => $item_id]);
    }

    public function update(Request $request, $item_id)
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
