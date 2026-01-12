@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/edit_address.css') }}">
@endsection

@section('content')
    <main class="edit-address">
        <div class="edit-address-inner">
            <h1 class="edit-address-title">住所の変更</h1>

            <form class="edit-address-form" action="{{ route('purchase.address.update', ['item_id' => $item_id]) }}"
                method="POST" novalidate>
                @csrf
                @method('PATCH')

                <div class="edit-address-group">
                    <label class="edit-address-label" for="shipping_postal_code">郵便番号</label>
                    <input class="edit-address-input" type="text" name="shipping_postal_code" id="shipping_postal_code"
                        value="{{ old('shipping_postal_code', $shipping_postal_code ?? '') }}" placeholder="例：123-4567">
                    <p class="edit-address-error">
                        @error('shipping_postal_code')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="edit-address-group">
                    <label class="edit-address-label" for="shipping_address">住所</label>
                    <input class="edit-address-input" type="text" name="shipping_address" id="shipping_address"
                        value="{{ old('shipping_address', $shipping_address ?? '') }}">
                    <p class="edit-address-error">
                        @error('shipping_address')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="edit-address-group">
                    <label class="edit-address-label" for="shipping_building">建物名</label>
                    <input class="edit-address-input" type="text" name="shipping_building" id="shipping_building"
                        value="{{ old('shipping_building', $shipping_building ?? '') }}">
                    <p class="edit-address-error">
                        @error('shipping_building')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <button class="edit-address-button" type="submit">更新する</button>
            </form>

            <p class="edit-address-back">
                <a class="edit-address-back-link" href="{{ route('purchases.create', ['item_id' => $item_id]) }}">
                    購入画面へ戻る
                </a>
            </p>
        </div>
    </main>
@endsection
