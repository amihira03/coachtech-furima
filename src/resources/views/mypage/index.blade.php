@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
@endsection

@section('content')
    @php
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $activePage = request('page', 'sell');
        $isSell = $activePage !== 'buy';

        $profileImagePath = $user->profile_image_path ?? null;
        $profileImageUrl = $profileImagePath ? \Illuminate\Support\Facades\Storage::url($profileImagePath) : null;
    @endphp

    <main class="mypage">
        <div class="mypage__inner">

            <section class="mypage__profile">
                <div class="mypage__profile-image">
                    @if ($profileImageUrl)
                        <img class="mypage__profile-image-src" src="{{ $profileImageUrl }}" alt="プロフィール画像">
                    @endif
                </div>

                <p class="mypage__profile-name">{{ $user->name }}</p>

                <a class="mypage__profile-edit" href="{{ route('profile.edit') }}">
                    プロフィールを編集
                </a>
            </section>

            <nav class="mypage__tabs">
                <a href="{{ url('/mypage?page=sell') }}" class="mypage__tab {{ $isSell ? 'is-active' : '' }}">
                    出品した商品
                </a>

                <a href="{{ url('/mypage?page=buy') }}" class="mypage__tab {{ !$isSell ? 'is-active' : '' }}">
                    購入した商品
                </a>
            </nav>

            <section class="mypage__list">
                @if (!$isSell)
                    {{-- buy items --}}
                    <div class="mypage__grid">
                        @forelse ($buyItems as $item)
                            @php
                                $image = $item->image_path ?? null;
                                $imageUrl = null;

                                if ($image) {
                                    $imageUrl = str_starts_with($image, 'images/goods/')
                                        ? asset($image)
                                        : \Illuminate\Support\Facades\Storage::url($image);
                                }
                            @endphp

                            <a class="mypage__card" href="{{ url('/item/' . $item->id) }}">
                                <div class="mypage__card-image-wrap">
                                    @if ($imageUrl)
                                        <img class="mypage__card-image" src="{{ $imageUrl }}"
                                            alt="{{ $item->name }}">
                                    @else
                                        <div class="mypage__card-no-image">商品画像</div>
                                    @endif
                                </div>

                                <p class="mypage__card-name">{{ $item->name }}</p>
                            </a>
                        @empty
                            <p class="mypage__empty">購入した商品はありません。</p>
                        @endforelse
                    </div>
                @else
                    {{-- sell items --}}
                    <div class="mypage__grid">
                        @forelse ($sellItems as $item)
                            @php
                                $image = $item->image_path ?? null;
                                $imageUrl = null;

                                if ($image) {
                                    $imageUrl = str_starts_with($image, 'images/goods/')
                                        ? asset($image)
                                        : \Illuminate\Support\Facades\Storage::url($image);
                                }

                                $isSold = !empty($item->purchase);
                            @endphp

                            <a class="mypage__card" href="{{ url('/item/' . $item->id) }}">
                                <div class="mypage__card-image-wrap {{ $isSold ? 'is-sold' : '' }}">
                                    @if ($imageUrl)
                                        <img class="mypage__card-image" src="{{ $imageUrl }}"
                                            alt="{{ $item->name }}">
                                    @else
                                        <div class="mypage__card-no-image">商品画像</div>
                                    @endif

                                    @if ($isSold)
                                        <span class="mypage__sold">Sold</span>
                                    @endif
                                </div>

                                <p class="mypage__card-name">{{ $item->name }}</p>
                            </a>
                        @empty
                            <p class="mypage__empty">出品した商品はありません。</p>
                        @endforelse
                    </div>
                @endif
            </section>

        </div>
    </main>
@endsection
