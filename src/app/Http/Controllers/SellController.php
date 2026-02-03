<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Item;
use App\Models\Condition;
use Illuminate\Support\Facades\DB;

class SellController extends Controller
{
    public function create()
    {
        $categories = Category::orderBy('id')->get();
        $conditions = Condition::orderBy('id')->get();

        return view('sells.create', compact('categories', 'conditions'));
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($request, $validated) {
            $storedPath = $request->file('image')->store('items', 'public');

            $item = Item::create([
                'user_id' => $request->user()->id,
                'name' => $validated['name'],
                'brand_name' => $validated['brand_name'] ?? null,
                'description' => $validated['description'],
                'price' => $validated['price'],
                'condition_id' => $validated['condition_id'],
                'image_path' => $storedPath,
            ]);

            $item->categories()->sync($validated['categories']);
        });

        return redirect('/');
    }
}
