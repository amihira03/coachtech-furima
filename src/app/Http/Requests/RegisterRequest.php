<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 基本設計書：入力必須、20文字以内
            'name' => ['required', 'string', 'max:20'],

            // 基本設計書：入力必須、メール形式
            'email' => ['required', 'string', 'email'],

            // 基本設計書：入力必須、8文字以上
            'password' => ['required', 'string', 'min:8'],

            // 基本設計書：入力必須、8文字以上、「パスワード」と一致
            'password_confirmation' => ['required', 'string', 'min:8', 'same:password'],
        ];
    }

    public function messages(): array
    {
        return [
            // 未入力
            'name.required' => 'お名前を入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'password.required' => 'パスワードを入力してください',

            // メール形式
            'email.email' => 'メールアドレスはメール形式で入力してください',

            // パスワード 8文字
            'password.min' => 'パスワードは 8文字以上で入力してください',

            // 確認用パスワード（不一致/規則違反系はこの文言に寄せる）
            'password_confirmation.required' => 'パスワードと一致しません',
            'password_confirmation.min' => 'パスワードと一致しません',
            'password_confirmation.same' => 'パスワードと一致しません',

            // 20文字超などの「想定外メッセージ」を出さないための保険
            'name.max' => 'お名前を入力してください',
        ];
    }
}
