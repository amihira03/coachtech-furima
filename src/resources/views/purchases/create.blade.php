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

                <input type="hidden" name="shipping_postal_code" value="{{ $shipping_postal_code ?? '' }}">
                <input type="hidden" name="shipping_address" value="{{ $shipping_address ?? '' }}">
                <input type="hidden" name="shipping_building" value="{{ $shipping_building ?? '' }}">

                <section class="purchase-left">
                    <div class="purchase-item">
                        <div class="purchase-thumb">
                            @php
                                $image = $item->image_path ?? null;
                                $imageUrl = null;

                                if ($image) {
                                    $imageUrl = str_starts_with($image, 'images/goods/')
                                        ? asset($image) // 初期データ（public）
                                        : \Illuminate\Support\Facades\Storage::url($image); // 出品画像（storage）
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

                        @error('payment_method')
                            <p class="purchase-error">{{ $message }}</p>
                        @else
                            <p class="purchase-error"></p>
                        @enderror
                    </section>

                    <div class="purchase-divider"></div>

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

                    @error('shipping_postal_code')
                        <p class="purchase-error">{{ $message }}</p>
                    @else
                        <p class="purchase-error"></p>
                    @enderror

                    <p class="purchase-back">
                        <a class="purchase-back-link" href="{{ route('items.show', ['item_id' => $item->id]) }}">
                            商品詳細へ戻る
                        </a>
                    </p>
                </section>

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
