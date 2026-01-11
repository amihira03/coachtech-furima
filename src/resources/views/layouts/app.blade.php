<!doctype html>
<html lang="ja">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>COACHTECH furima</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/layouts/app.css') }}" />
    @yield('css')
</head>

<body>
    <header class="site-header">
        <div class="site-header__inner">
            <a class="site-header__logo-link" href="/">
                <img class="site-header__logo" src="{{ asset('images/logo.png') }}" alt="COACHTECH">
            </a>

            {{-- 検索バー：ログイン/会員登録以外は表示 --}}
            @unless (request()->is('login') || request()->is('register'))
                <form action="/" method="GET" class="site-header__search">
                    @if (request('tab') === 'mylist')
                        <input type="hidden" name="tab" value="mylist">
                    @endif

                    <input class="site-header__search-input" type="text" name="keyword" value="{{ request('keyword') }}"
                        placeholder="なにをお探しですか？">
                </form>

                <nav class="site-header__nav">
                    {{-- 未ログイン時：ログインを表示 --}}
                    @guest
                        <a href="/login" class="site-header__link">ログイン</a>
                    @endguest

                    {{-- ログイン時：ログアウトを表示 --}}
                    @auth
                        <form method="POST" action="{{ route('logout') }}" class="site-header__logout">
                            @csrf
                            <button type="submit" class="site-header__logout-button">ログアウト</button>
                        </form>
                    @endauth

                    {{-- マイページ：ログイン状態に関わらず表示（押したらauthで/loginへ） --}}
                    <a href="/mypage" class="site-header__link">マイページ</a>

                    {{-- 出品：ログイン状態に関わらず表示（押したらauthで/loginへ） --}}
                    <a href="/sell" class="site-header__sell-button">出品</a>
                </nav>
            @endunless

        </div>
    </header>

    @yield('content')
</body>

</html>
