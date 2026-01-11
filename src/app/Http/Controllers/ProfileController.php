<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('mypage.edit_profile');
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        // 画像がアップロードされている場合のみ保存
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');

            // 既存画像があれば削除
            if (!empty($user->profile_image_path)) {
                Storage::disk('public')->delete($user->profile_image_path);
            }

            $user->profile_image_path = $path;
        }

        // 住所・名前などの更新
        $user->name = $request->input('name');
        $user->postal_code = $request->input('postal_code');
        $user->address = $request->input('address');
        $user->building = $request->input('building');

        $user->save();

        return redirect('/');
    }
}
