@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/edit_address.css') }}">
@endsection

@section('content')
    <main class="edit-address">
        <div class="edit-address__inner">
            <h1 class="edit-address__title">住所の変更</h1>

            <form class="edit-address__form" action="{{ route('purchase.address.update', ['item_id' => $item_id]) }}"
                method="POST" novalidate>
                @csrf
                @method('PATCH')

                {{-- 郵便番号 --}}
                <div class="edit-address__group">
                    <label class="edit-address__label" for="shipping_postal_code">郵便番号</label>
                    <input class="edit-address__input" type="text" name="shipping_postal_code" id="shipping_postal_code"
                        value="{{ old('shipping_postal_code', $shipping_postal_code ?? '') }}">
                    <p class="edit-address__error">
                        @error('shipping_postal_code')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                {{-- 住所 --}}
                <div class="edit-address__group">
                    <label class="edit-address__label" for="shipping_address">住所</label>
                    <input class="edit-address__input" type="text" name="shipping_address" id="shipping_address"
                        value="{{ old('shipping_address', $shipping_address ?? '') }}">
                    <p class="edit-address__error">
                        @error('shipping_address')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                {{-- 建物名 --}}
                <div class="edit-address__group">
                    <label class="edit-address__label" for="shipping_building">建物名</label>
                    <input class="edit-address__input" type="text" name="shipping_building" id="shipping_building"
                        value="{{ old('shipping_building', $shipping_building ?? '') }}">
                    <p class="edit-address__error">
                        @error('shipping_building')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <button class="edit-address__button" type="submit">更新する</button>
            </form>

            <p class="edit-address__back">
                <a class="edit-address__back-link" href="{{ route('purchases.create', ['item_id' => $item_id]) }}">
                    購入画面へ戻る
                </a>
            </p>
        </div>
    </main>
@endsection
