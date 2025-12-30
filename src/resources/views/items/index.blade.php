@extends('layouts.app')

@section('content')
<h1>商品一覧（仮）</h1>

<ul>
    <li><a href="/?tab=mylist">マイリスト（クエリ表示テスト）</a></li>
    <li><a href="/item/1">商品詳細（/item/1）</a></li>
    <li><a href="/sell">出品（未ログインなら/loginへ）</a></li>
    <li><a href="/purchase/1">購入（未ログインなら/loginへ）</a></li>
    <li><a href="/mypage">マイページ（未ログインなら/loginへ）</a></li>
</ul>
@endsection