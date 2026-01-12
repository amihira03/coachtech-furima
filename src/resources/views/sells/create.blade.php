@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sells/create.css') }}">
@endsection

@section('content')
    @php
        $conditions = ['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い'];
        $oldCategories = old('categories', []);
    @endphp

    <main class="sell-create">
        <div class="sell-create__inner">
            <h1 class="sell-create__title">商品出品</h1>

            <form class="sell-create__form" action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data"
                novalidate>
                @csrf

                <div class="sell-create__group">
                    <label class="sell-create__label" for="image">商品画像</label>
                    <input class="sell-create__file" id="image" type="file" name="image" accept=".png,.jpeg">

                    @error('image')
                        <p class="sell-create__error">{{ $message }}</p>
                    @else
                        <p class="sell-create__error"></p>
                    @enderror
                </div>

                <div class="sell-create__group">
                    <label class="sell-create__label" for="name">商品名</label>
                    <input class="sell-create__input" id="name" type="text" name="name"
                        value="{{ old('name') }}">

                    @error('name')
                        <p class="sell-create__error">{{ $message }}</p>
                    @else
                        <p class="sell-create__error"></p>
                    @enderror
                </div>

                <div class="sell-create__group">
                    <label class="sell-create__label" for="brand_name">ブランド名</label>
                    <input class="sell-create__input" id="brand_name" type="text" name="brand_name"
                        value="{{ old('brand_name') }}">

                    @error('brand_name')
                        <p class="sell-create__error">{{ $message }}</p>
                    @else
                        <p class="sell-create__error"></p>
                    @enderror
                </div>

                <div class="sell-create__group">
                    <label class="sell-create__label" for="description">商品説明</label>
                    <textarea class="sell-create__textarea" id="description" name="description" rows="6">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="sell-create__error">{{ $message }}</p>
                    @else
                        <p class="sell-create__error"></p>
                    @enderror
                </div>

                <div class="sell-create__group">
                    <p class="sell-create__label">カテゴリ</p>

                    <div class="sell-create__checkboxes">
                        @foreach ($categories as $category)
                            <label class="sell-create__checkbox" for="category_{{ $category->id }}">
                                <input class="sell-create__checkbox-input" id="category_{{ $category->id }}"
                                    type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    {{ in_array($category->id, $oldCategories, true) ? 'checked' : '' }}>
                                <span class="sell-create__checkbox-label">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    @error('categories')
                        <p class="sell-create__error">{{ $message }}</p>
                    @else
                        <p class="sell-create__error"></p>
                    @enderror
                </div>

                <div class="sell-create__group">
                    <label class="sell-create__label" for="condition">商品の状態</label>

                    <div class="sell-create__select-wrap">
                        <select class="sell-create__select" id="condition" name="condition">
                            <option value="">選択してください</option>
                            @foreach ($conditions as $condition)
                                <option value="{{ $condition }}"
                                    {{ old('condition') === $condition ? 'selected' : '' }}>
                                    {{ $condition }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @error('condition')
                        <p class="sell-create__error">{{ $message }}</p>
                    @else
                        <p class="sell-create__error"></p>
                    @enderror
                </div>

                <div class="sell-create__group">
                    <label class="sell-create__label" for="price">価格</label>

                    <div class="sell-create__price-wrap">
                        <span class="sell-create__price-prefix">¥</span>
                        <input class="sell-create__input sell-create__input--price" id="price" type="number"
                            min="0" step="1" name="price" value="{{ old('price') }}" inputmode="numeric">
                    </div>

                    @error('price')
                        <p class="sell-create__error">{{ $message }}</p>
                    @else
                        <p class="sell-create__error"></p>
                    @enderror
                </div>

                <button class="sell-create__button" type="submit">出品する</button>
            </form>
        </div>
    </main>
@endsection
