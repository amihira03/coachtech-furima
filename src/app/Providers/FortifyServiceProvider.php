<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\LoginRequest;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\RegisterResponse;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequestBase;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // 要件：formrequest を使用（ログイン）
        $this->app->bind(FortifyLoginRequestBase::class, LoginRequest::class);

        // 要件：初回会員登録直後、プロフィール設定へ遷移（FN006）
        $this->app->singleton(RegisterResponseContract::class, RegisterResponse::class);

        // 応用要件：未認証ユーザーがログインした場合、メール認証誘導画面へ
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
    }

    public function boot(): void
    {
        // 会員登録の実処理
        Fortify::createUsersUsing(CreateNewUser::class);

        // 画面（Blade）
        Fortify::registerView(fn() => view('auth.register'));
        Fortify::loginView(fn() => view('auth.login'));

        // 要件：入力情報が誤っている場合の文言（FN010）
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }

            throw ValidationException::withMessages([
                'login' => ['ログイン情報が登録されていません'],
            ]);
        });

        $this->configureRateLimiting();
    }

    private function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');
            return Limit::perMinute(10)->by($email . '|' . $request->ip());
        });
    }
}
