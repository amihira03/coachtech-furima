@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
    <div class="register">
        <div class="register-inner">
            <h1 class="register-title">会員登録</h1>

            <form class="register-form" method="POST" action="/register" novalidate>
                @csrf

                <div class="register-group">
                    <label class="register-label" for="name">ユーザー名</label>
                    <input class="register-input" id="name" type="text" name="name" value="{{ old('name') }}">
                    <p class="register-error">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="register-group">
                    <label class="register-label" for="email">メールアドレス</label>
                    <input class="register-input" id="email" type="email" name="email" value="{{ old('email') }}">
                    <p class="register-error">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="register-group">
                    <label class="register-label" for="password">パスワード</label>
                    <input class="register-input" id="password" type="password" name="password">
                    <p class="register-error">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="register-group">
                    <label class="register-label" for="password_confirmation">確認用パスワード</label>
                    <input class="register-input" id="password_confirmation" type="password" name="password_confirmation">
                    <p class="register-error">
                        @error('password_confirmation')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <button class="register-button" type="submit">登録する</button>
            </form>

            <p class="register-link">
                <a href="/login">ログインはこちら</a>
            </p>
        </div>
    </div>
@endsection
