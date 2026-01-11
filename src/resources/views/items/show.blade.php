@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/show.css') }}">
@endsection

@section('content')
    <main class="items-show">
        <div class="items-show__inner">

            {{-- 左：画像 --}}
            <section class="items-show__left">
                <div class="items-show__image-wrap {{ $item->purchase ? 'is-sold' : '' }}">
                    @php
                        $image = $item->image_path ?? null;
                        $imageUrl = null;

                        if (!empty($image)) {
                            $imageUrl = \Illuminate\Support\Str::startsWith($image, ['http://', 'https://'])
                                ? $image
                                : asset('storage/' . $image);
                        }
                    @endphp

                    @if (!empty($imageUrl))
                        <img class="items-show__image" src="{{ $imageUrl }}" alt="{{ $item->name }}">
                    @else
                        <div class="items-show__no-image">商品画像</div>
                    @endif

                    {{-- Sold（purchaseがあれば） --}}
                    @if ($item->purchase)
                        <span class="items-show__sold">Sold</span>
                    @endif
                </div>
            </section>

            {{-- 右：情報 --}}
            <section class="items-show__right">
                <h1 class="items-show__name">{{ $item->name }}</h1>

                @if (!empty($item->brand_name))
                    <p class="items-show__brand">{{ $item->brand_name }}</p>
                @endif

                <p class="items-show__price">¥{{ number_format($item->price) }}</p>

                {{-- いいね数 --}}
                <div class="items-show__count">
                    @auth
                        <form method="POST" action="{{ route('items.like', ['item_id' => $item->id]) }}">
                            @csrf
                            <button type="submit" class="items-show__like-button">
                                <img class="items-show__icon"
                                    src="{{ asset($isLiked ? 'images/icons/heart-liked.png' : 'images/icons/heart-default.png') }}"
                                    alt="いいね">
                            </button>
                        </form>
                    @else
                        <a href="/login" class="items-show__like-button">
                            <img class="items-show__icon" src="{{ asset('images/icons/heart-default.png') }}"
                                alt="いいね（ログインが必要です）">
                        </a>
                    @endauth
                    <span class="items-show__count-number">{{ $item->likes_count }}</span>
                </div>

                {{-- コメント数 --}}
                <div class="items-show__count">
                    <img class="items-show__icon" src="{{ asset('images/icons/comment.png') }}" alt="コメント">
                    <span class="items-show__count-number">{{ $item->comments_count }}</span>
                </div>

                {{-- 購入ボタン --}}
                <div class="items-show__actions">
                    @if ($item->purchase)
                        <button class="items-show__purchase-button" type="button" disabled>売り切れました</button>
                    @elseif (auth()->check() && $item->user_id === auth()->id())
                        <button class="items-show__purchase-button" type="button" disabled>自分の商品です</button>
                    @else
                        <a class="items-show__purchase-button" href="/purchase/{{ $item->id }}">購入手続きへ</a>
                    @endif
                </div>

                {{-- 商品説明 --}}
                <section class="items-show__section">
                    <h2 class="items-show__section-title">商品説明</h2>
                    <p class="items-show__description">{{ $item->description }}</p>
                </section>

                {{-- 商品情報 --}}
                <section class="items-show__section">
                    <h2 class="items-show__section-title">商品の情報</h2>

                    <div class="items-show__meta">
                        <p class="items-show__meta-title">カテゴリ</p>
                        <div class="items-show__categories">
                            @foreach ($item->categories as $category)
                                <span class="items-show__category">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div class="items-show__meta">
                        <p class="items-show__meta-title">商品の状態</p>
                        <p class="items-show__condition">{{ $item->condition }}</p>
                    </div>
                </section>

                {{-- コメント --}}
                <section class="items-show__section">
                    <h2 class="items-show__section-title">コメント（{{ $item->comments_count }}）</h2>

                    @auth
                        <form method="POST" action="{{ route('items.comment.store', ['item_id' => $item->id]) }}" novalidate>
                            @csrf

                            <div class="items-show__comment-form">
                                <textarea name="body" rows="4" placeholder="商品へのコメント">{{ old('body') }}</textarea>

                                @error('body')
                                    <p class="items-show__error">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="items-show__comment-submit">
                                コメントを送信する
                            </button>
                        </form>
                    @else
                        <p class="items-show__login-note">
                            ※ コメントを投稿するにはログインが必要です
                        </p>
                    @endauth

                    {{-- コメント一覧 --}}
                    <div class="items-show__comments">
                        @forelse ($item->comments as $comment)
                            <div class="items-show__comment">
                                <p class="items-show__comment-user">{{ $comment->user->name }}</p>
                                <p class="items-show__comment-body">{{ $comment->body }}</p>
                            </div>
                        @empty
                            <p class="items-show__no-comments">コメントはまだありません</p>
                        @endforelse
                    </div>
                </section>

            </section> {{-- items-show__right --}}

        </div> {{-- items-show__inner --}}
    </main>
@endsection
