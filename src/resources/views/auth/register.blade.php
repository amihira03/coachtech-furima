@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
    <div class="register">
        <div class="register__inner">
            <h1 class="register__title">会員登録</h1>

            <form class="register__form" method="POST" action="/register" novalidate>
                @csrf

                <div class="register__group">
                    <label class="register__label" for="name">ユーザー名</label>
                    <input class="register__input" id="name" type="text" name="name" value="{{ old('name') }}">
                    <p class="register__error">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="register__group">
                    <label class="register__label" for="email">メールアドレス</label>
                    <input class="register__input" id="email" type="email" name="email" value="{{ old('email') }}">
                    <p class="register__error">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="register__group">
                    <label class="register__label" for="password">パスワード</label>
                    <input class="register__input" id="password" type="password" name="password">
                    <p class="register__error">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="register__group">
                    <label class="register__label" for="password_confirmation">確認用パスワード</label>
                    <input class="register__input" id="password_confirmation" type="password" name="password_confirmation">
                    <p class="register__error">
                        @error('password_confirmation')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <button class="register__button" type="submit">登録する</button>
            </form>

            <p class="register__link">
                <a href="/login">ログインはこちら</a>
            </p>
        </div>
    </div>
@endsection
