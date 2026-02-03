@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/sells/create.css') }}">
@endsection

@section('content')
    @php
        $oldCategories = old('categories', []);
    @endphp

    <main class="sell-create">
        <div class="sell-create-inner">
            <h1 class="sell-create-title">商品出品</h1>

            <form class="sell-create-form" action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data"
                novalidate>
                @csrf

                <div class="sell-create-group">
                    <label class="sell-create-label" for="image">商品画像</label>
                    <input class="sell-create-file" id="image" type="file" name="image" accept=".png,.jpeg">

                    @error('image')
                        <p class="sell-create-error">{{ $message }}</p>
                    @else
                        <p class="sell-create-error"></p>
                    @enderror
                </div>

                <div class="sell-create-group">
                    <label class="sell-create-label" for="name">商品名</label>
                    <input class="sell-create-input" id="name" type="text" name="name"
                        value="{{ old('name') }}">

                    @error('name')
                        <p class="sell-create-error">{{ $message }}</p>
                    @else
                        <p class="sell-create-error"></p>
                    @enderror
                </div>

                <div class="sell-create-group">
                    <label class="sell-create-label" for="brand_name">ブランド名</label>
                    <input class="sell-create-input" id="brand_name" type="text" name="brand_name"
                        value="{{ old('brand_name') }}">

                    @error('brand_name')
                        <p class="sell-create-error">{{ $message }}</p>
                    @else
                        <p class="sell-create-error"></p>
                    @enderror
                </div>

                <div class="sell-create-group">
                    <label class="sell-create-label" for="description">商品説明</label>
                    <textarea class="sell-create-textarea" id="description" name="description" rows="6">{{ old('description') }}</textarea>

                    @error('description')
                        <p class="sell-create-error">{{ $message }}</p>
                    @else
                        <p class="sell-create-error"></p>
                    @enderror
                </div>

                <div class="sell-create-group">
                    <p class="sell-create-label">カテゴリ</p>

                    <div class="sell-create-checkboxes">
                        @foreach ($categories as $category)
                            <label class="sell-create-checkbox" for="category_{{ $category->id }}">
                                <input class="sell-create-checkbox-input" id="category_{{ $category->id }}" type="checkbox"
                                    name="categories[]" value="{{ $category->id }}"
                                    {{ in_array($category->id, $oldCategories) ? 'checked' : '' }}>
                                <span class="sell-create-checkbox-label">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    @error('categories')
                        <p class="sell-create-error">{{ $message }}</p>
                    @else
                        <p class="sell-create-error"></p>
                    @enderror
                </div>

                <div class="sell-create-group">
                    <label class="sell-create-label" for="condition_id">商品の状態</label>

                    <div class="sell-create-select-wrap">
                        <select class="sell-create-select" id="condition_id" name="condition_id">
                            <option value="">選択してください</option>
                            @foreach ($conditions as $condition)
                                <option value="{{ $condition->id }}"
                                    {{ (string) old('condition_id') === (string) $condition->id ? 'selected' : '' }}>
                                    {{ $condition->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @error('condition_id')
                        <p class="sell-create-error">{{ $message }}</p>
                    @else
                        <p class="sell-create-error"></p>
                    @enderror
                </div>

                <div class="sell-create-group">
                    <label class="sell-create-label" for="price">価格</label>

                    <div class="sell-create-price-wrap">
                        <span class="sell-create-price-prefix">¥</span>
                        <input class="sell-create-input sell-create-input-price" id="price" type="number"
                            min="0" step="1" name="price" value="{{ old('price') }}" inputmode="numeric">
                    </div>

                    @error('price')
                        <p class="sell-create-error">{{ $message }}</p>
                    @else
                        <p class="sell-create-error"></p>
                    @enderror
                </div>

                <button class="sell-create-button" type="submit">出品する</button>
            </form>
        </div>
    </main>
@endsection
