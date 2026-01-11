@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login">
        <div class="login__inner">
            <h1 class="login__title">ログイン</h1>

            {{-- 認証失敗（loginキー）: タイトル直下に固定表示 --}}
            <p class="login__error login__error--global">
                @error('login')
                    {{ $message }}
                @enderror
            </p>

            <form class="login__form" method="POST" action="/login" novalidate>
                @csrf

                <div class="login__group">
                    <label class="login__label" for="email">メールアドレス</label>
                    <input class="login__input" id="email" type="email" name="email" value="{{ old('email') }}">

                    <p class="login__error">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="login__group">
                    <label class="login__label" for="password">パスワード</label>
                    <input class="login__input" id="password" type="password" name="password">

                    <p class="login__error">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <button class="login__button" type="submit">ログインする</button>
            </form>

            <p class="login__link">
                <a class="login__link-anchor" href="/register">会員登録はこちら</a>
            </p>
        </div>
    </div>
@endsection
