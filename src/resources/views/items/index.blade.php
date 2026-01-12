@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/index.css') }}">
@endsection

@section('content')
    <main class="items-index">

        <div class="items-index__tabs">
            <a href="/{{ request('keyword') ? '?keyword=' . urlencode(request('keyword')) : '' }}"
                class="items-index__tab {{ request('tab') !== 'mylist' ? 'is-active' : '' }}">
                おすすめ
            </a>

            <a href="/?tab=mylist{{ request('keyword') ? '&keyword=' . urlencode(request('keyword')) : '' }}"
                class="items-index__tab {{ request('tab') === 'mylist' ? 'is-active' : '' }}">
                マイリスト
            </a>
        </div>

        <div class="items-index__grid">
            @forelse($items as $item)
                <a class="items-index__card" href="/item/{{ $item->id }}">
                    <div class="items-index__image-wrap {{ $item->purchase ? 'is-sold' : '' }}">

                        @php
                            $image = $item->image_path ?? null;
                            $imageUrl = null;

                            if ($image) {
                                $imageUrl = str_starts_with($image, 'images/goods/')
                                    ? asset($image)
                                    : \Illuminate\Support\Facades\Storage::url($image);
                            }
                        @endphp

                        @if ($imageUrl)
                            <img class="items-index__image" src="{{ $imageUrl }}" alt="{{ $item->name }}">
                        @else
                            <div class="items-index__no-image">No Image</div>
                        @endif

                        @if ($item->purchase)
                            <span class="items-index__sold">Sold</span>
                        @endif
                    </div>

                    <p class="items-index__name">{{ $item->name }}</p>
                </a>
            @empty
                @if (!(auth()->guest() && request('tab') === 'mylist'))
                    <p class="items-index__empty">表示できる商品がありません</p>
                @endif
            @endforelse
        </div>

    </main>
@endsection
