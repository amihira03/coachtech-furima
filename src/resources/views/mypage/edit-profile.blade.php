@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/edit-profile.css') }}">
@endsection

@section('content')
    @php
        $user = auth()->user();
        $profileImagePath = $user->profile_image_path ?? null;
    @endphp

    <main class="profile-edit">
        <div class="profile-edit-inner">
            <h1 class="profile-edit-title">プロフィール設定</h1>

            <form class="profile-edit-form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                novalidate>
                @csrf
                @method('PATCH')

                <div class="profile-edit-group">

                    <div class="profile-edit-image-row">
                        <div class="profile-edit-avatar">
                            @if ($profileImagePath)
                                <img class="profile-edit-avatar-image" src="{{ asset('storage/' . $profileImagePath) }}"
                                    alt="プロフィール画像">
                            @endif
                        </div>

                        <div class="profile-edit-file">
                            <label class="profile-edit-file-button" for="profile_image">
                                画像を選択する
                            </label>
                            <input class="profile-edit-file-input" type="file" name="profile_image" id="profile_image"
                                accept=".png,.jpeg">
                        </div>
                    </div>

                    @error('profile_image')
                        <p class="profile-edit-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit-group">
                    <label class="profile-edit-label" for="name">ユーザー名</label>
                    <input class="profile-edit-input" type="text" name="name" id="name"
                        value="{{ old('name', $user->name) }}">

                    @error('name')
                        <p class="profile-edit-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit-group">
                    <label class="profile-edit-label" for="postal_code">郵便番号</label>
                    <input class="profile-edit-input" type="text" name="postal_code" id="postal_code"
                        value="{{ old('postal_code', $user->postal_code) }}" placeholder="例：123-4567">

                    @error('postal_code')
                        <p class="profile-edit-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit-group">
                    <label class="profile-edit-label" for="address">住所</label>
                    <input class="profile-edit-input" type="text" name="address" id="address"
                        value="{{ old('address', $user->address) }}">

                    @error('address')
                        <p class="profile-edit-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit-group">
                    <label class="profile-edit-label" for="building">建物名</label>
                    <input class="profile-edit-input" type="text" name="building" id="building"
                        value="{{ old('building', $user->building) }}">

                    @error('building')
                        <p class="profile-edit-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit-actions">
                    <button class="profile-edit-submit" type="submit">更新する</button>
                </div>
            </form>
        </div>
    </main>
@endsection
