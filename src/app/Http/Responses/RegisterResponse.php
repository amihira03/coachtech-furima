<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        // 応用要件：会員登録直後はプロフィール設定ではなく、メール認証誘導画面へ
        return redirect()->route('verification.notice');
    }
}
