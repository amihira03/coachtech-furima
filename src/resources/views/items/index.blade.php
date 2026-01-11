@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
    <main class="items-index">

        {{-- タブ（おすすめ / マイリスト） --}}
        <div class="items-index__tabs">
            <a href="/" class="items-index__tab {{ request('tab') !== 'mylist' ? 'is-active' : '' }}">
                おすすめ
            </a>

            <a href="/?tab=mylist" class="items-index__tab {{ request('tab') === 'mylist' ? 'is-active' : '' }}">
                マイリスト
            </a>
        </div>

        {{-- 一覧 --}}
        <div class="items-index__grid">
            @forelse($items as $item)
                <a class="items-index__card" href="/item/{{ $item->id }}">
                    <div class="items-index__image-wrap {{ $item->purchase ? 'is-sold' : '' }}">
                        @php
                            $image = $item->image_path ?? null;

                            // image_path が URL ならそのまま、ローカルパスなら storage 配下として扱う
                            $imageUrl = null;
                            if (!empty($image)) {
                                $imageUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
                                    ? $image
                                    : asset('storage/' . $image);
                            }
                        @endphp

                        @if (!empty($imageUrl))
                            <img class="items-index__image" src="{{ $imageUrl }}" alt="{{ $item->name }}">
                        @else
                            <div class="items-index__no-image">商品画像</div>
                        @endif

                        {{-- Sold表示：purchase が存在するなら購入済み --}}
                        @if ($item->purchase)
                            <span class="items-index__sold">Sold</span>
                        @endif
                    </div>

                    <p class="items-index__name">{{ $item->name }}</p>
                </a>
            @empty
                {{-- 未ログインで mylist の場合は「何も表示しない」 --}}
                @if (!(auth()->guest() && request('tab') === 'mylist'))
                    <p class="items-index__empty">表示できる商品がありません</p>
                @endif
            @endforelse
        </div>

    </main>
@endsection
