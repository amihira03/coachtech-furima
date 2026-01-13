@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login">
        <div class="login-inner">
            <h1 class="login-title">ログイン</h1>

            <p class="login-error login-error-global">
                @error('login')
                    {{ $message }}
                @enderror
            </p>

            <form class="login-form" method="POST" action="/login" novalidate>
                @csrf

                <div class="login-group">
                    <label class="login-label" for="email">メールアドレス</label>
                    <input class="login-input" id="email" type="email" name="email" value="{{ old('email') }}">

                    <p class="login-error">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="login-group">
                    <label class="login-label" for="password">パスワード</label>
                    <input class="login-input" id="password" type="password" name="password">

                    <p class="login-error">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <button class="login-button" type="submit">ログインする</button>
            </form>

            <p class="register-link">
                <a class="register-link-anchor" href="/register">会員登録はこちら</a>
            </p>
        </div>
    </div>
@endsection
