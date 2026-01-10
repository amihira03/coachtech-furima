@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/verify-notice.css') }}">
@endsection

@section('content')
    <div class="verify-notice">
        <div class="verify-notice__inner">
            <p class="verify-notice__message">
                メール認証が完了していません。<br>
                「認証はこちらから」よりメール認証を完了してください。
            </p>

            <form class="verify-notice__form" method="POST" action="{{ route('verification.start') }}">
                @csrf
                <button type="submit" class="verify-notice__button">
                    認証はこちらから
                </button>
            </form>
        </div>
    </div>
@endsection
