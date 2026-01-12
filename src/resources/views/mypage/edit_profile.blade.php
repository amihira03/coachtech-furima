@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/edit_profile.css') }}">
@endsection

@section('content')
    @php
        $user = auth()->user();
        $profileImagePath = $user->profile_image_path ?? null;
    @endphp

    <main class="profile-edit">
        <div class="profile-edit__inner">
            <h1 class="profile-edit__title">プロフィール設定</h1>

            <form class="profile-edit__form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                novalidate>
                @csrf
                @method('PATCH')

                <div class="profile-edit__group">

                    <div class="profile-edit__image-row">
                        <div class="profile-edit__avatar">
                            @if ($profileImagePath)
                                <img class="profile-edit__avatar-image" src="{{ asset('storage/' . $profileImagePath) }}"
                                    alt="プロフィール画像">
                            @endif
                        </div>

                        <div class="profile-edit__file">
                            <label class="profile-edit__file-button" for="profile_image">
                                画像を選択する
                            </label>
                            <input class="profile-edit__file-input" type="file" name="profile_image" id="profile_image"
                                accept=".png,.jpeg">
                        </div>
                    </div>

                    @error('profile_image')
                        <p class="profile-edit__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit__group">
                    <label class="profile-edit__label" for="name">ユーザー名</label>
                    <input class="profile-edit__input" type="text" name="name" id="name"
                        value="{{ old('name', $user->name) }}">

                    @error('name')
                        <p class="profile-edit__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit__group">
                    <label class="profile-edit__label" for="postal_code">郵便番号</label>
                    <input class="profile-edit__input" type="text" name="postal_code" id="postal_code"
                        value="{{ old('postal_code', $user->postal_code) }}" placeholder="例：123-4567">

                    @error('postal_code')
                        <p class="profile-edit__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit__group">
                    <label class="profile-edit__label" for="address">住所</label>
                    <input class="profile-edit__input" type="text" name="address" id="address"
                        value="{{ old('address', $user->address) }}">

                    @error('address')
                        <p class="profile-edit__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit__group">
                    <label class="profile-edit__label" for="building">建物名</label>
                    <input class="profile-edit__input" type="text" name="building" id="building"
                        value="{{ old('building', $user->building) }}">

                    @error('building')
                        <p class="profile-edit__error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-edit__actions">
                    <button class="profile-edit__submit" type="submit">更新する</button>
                </div>
            </form>
        </div>
    </main>
@endsection
