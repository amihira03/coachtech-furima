@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">
@endsection

@section('content')
    <div class="verify">
        <div class="verify__inner">
            <p class="verify__message">
                登録したメールアドレス宛に認証メールを送信しました。<br>
                メール内のリンクをクリックして認証を完了してください。
            </p>

            @if (session('status') === 'verification-link-sent')
                <p class="verify__status">
                    認証メールを再送しました。
                </p>
            @endif

            <p class="verify__note">
                ※ メールが届かない場合は、下の「認証メールを再送する」を押してください
            </p>

            <form class="verify__resend-form" method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button class="verify__resend-button" type="submit">
                    認証メールを再送する
                </button>
            </form>
        </div>
    </div>
@endsection
