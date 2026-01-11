@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/create.css') }}">
@endsection

@section('content')
    <main class="purchase">
        <div class="purchase-inner">
            <form class="purchase-form" action="{{ route('purchases.store', ['item_id' => $item->id]) }}" method="POST"
                novalidate>
                @csrf

                {{-- 左：商品情報 / 支払い方法 / 配送先 --}}
                <section class="purchase-left">
                    {{-- 商品情報 --}}
                    <div class="purchase-item">
                        <div class="purchase-thumb">
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
                                <img class="purchase-thumb-image" src="{{ $imageUrl }}" alt="{{ $item->name }}">
                            @else
                                <p class="purchase-thumb-noimage">商品画像</p>
                            @endif
                        </div>

                        <div class="purchase-item-meta">
                            <p class="purchase-item-name">{{ $item->name }}</p>
                            <p class="purchase-item-price">¥{{ number_format($item->price) }}</p>
                        </div>
                    </div>

                    <div class="purchase-divider"></div>

                    {{-- 支払い方法 --}}
                    <section class="purchase-section">
                        <h2 class="purchase-section-title">支払い方法</h2>

                        <div class="purchase-select-wrap">
                            <select class="purchase-select" name="payment_method" id="payment_method">
                                <option value="">選択してください</option>
                                <option value="convenience_store"
                                    {{ old('payment_method') === 'convenience_store' ? 'selected' : '' }}>
                                    コンビニ支払い
                                </option>
                                <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>
                                    カード支払い
                                </option>
                            </select>
                        </div>

                        {{-- エラー表示（高さ確保してズレ防止） --}}
                        @error('payment_method')
                            <p class="purchase-error">{{ $message }}</p>
                        @else
                            <p class="purchase-error"></p>
                        @enderror
                    </section>

                    <div class="purchase-divider"></div>

                    {{-- 配送先 --}}
                    <section class="purchase-section">
                        <div class="purchase-section-head">
                            <h2 class="purchase-section-title">配送先</h2>
                            <a class="purchase-change-link"
                                href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}">
                                変更する
                            </a>
                        </div>

                        <div class="purchase-address">
                            <p class="purchase-address-line">〒 {{ $shipping_postal_code ?? '' }}</p>
                            <p class="purchase-address-line">{{ $shipping_address ?? '' }}</p>
                            @if (!empty($shipping_building))
                                <p class="purchase-address-line">{{ $shipping_building }}</p>
                            @endif
                        </div>
                    </section>

                    <p class="purchase-back">
                        <a class="purchase-back-link" href="{{ route('items.show', ['item_id' => $item->id]) }}">
                            商品詳細へ戻る
                        </a>
                    </p>
                </section>

                {{-- 右：サマリー（商品代金 / 支払い方法） + 購入ボタン --}}
                <aside class="purchase-right">
                    <div class="purchase-summary">
                        <div class="purchase-summary-row">
                            <p class="purchase-summary-label">商品代金</p>
                            <p class="purchase-summary-value">¥{{ number_format($item->price) }}</p>
                        </div>

                        <div class="purchase-summary-row purchase-summary-row-border">
                            <p class="purchase-summary-label">支払い方法</p>
                            <p class="purchase-summary-value">
                                <span id="summary-payment-method">未選択</span>
                            </p>
                        </div>
                    </div>

                    {{-- ボタンはここに1個だけ（スマホでも「商品代金/支払い方法」の直下に来る） --}}
                    <button class="purchase-button" type="submit">購入する</button>
                </aside>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('payment_method');
            const summary = document.getElementById('summary-payment-method');

            if (!select || !summary) return;

            const labels = {
                '': '未選択',
                'convenience_store': 'コンビニ支払い',
                'card': 'カード支払い',
            };

            const render = () => {
                summary.textContent = labels[select.value] ?? '未選択';
            };

            render();
            select.addEventListener('change', render);
        });
    </script>
@endsection
