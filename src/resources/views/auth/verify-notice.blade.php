@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/verify-notice.css') }}">
@endsection

@section('content')
    <div class="verify-notice">
        <div class="verify-notice__inner">
            <p class="verify-notice__message">
                登録していただいたメールアドレスに認証メールを送付しました。<br>
                メール認証を完了してください。
            </p>

            <div class="verify-notice__actions">
                <a class="verify-notice__button" href="http://localhost:8025" target="_blank" rel="noopener">
                    認証はこちらから
                </a>
            </div>

            @if (session('status') === 'verification-link-sent')
                <p class="verify-notice__status">
                    認証メールを再送しました。
                </p>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="verify-notice__resend-form">
                @csrf
                <button type="submit" class="verify-notice__resend-button">
                    認証メールを再送する
                </button>
            </form>
        </div>
    </div>
@endsection
