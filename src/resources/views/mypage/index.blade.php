@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 40px auto; padding: 20px;">
    <h1>マイページ（仮）</h1>

    <ul>
        <li><a href="/mypage?page=buy">購入一覧（仮）</a></li>
        <li><a href="/mypage?page=sell">出品一覧（仮）</a></li>
        <li><a href="/mypage/profile">プロフィール編集</a></li>
    </ul>
</div>
@endsection