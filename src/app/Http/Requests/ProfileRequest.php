<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png'],

            'name' => ['required', 'string', 'max:20'],

            'postal_code' => ['required', 'string', 'size:8', 'regex:/^\d{3}-\d{4}$/'],

            'address' => ['required', 'string'],

            'building' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'profile_image.image' => '画像ファイルを選択してください。',
            'profile_image.mimes' => '画像は「jpeg」または「png」形式でアップロードしてください。',

            'name.required' => 'ユーザー名を入力してください。',
            'name.string' => 'ユーザー名は文字で入力してください。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',

            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.string' => '郵便番号は文字で入力してください。',
            'postal_code.size' => '郵便番号はハイフンありの8文字で入力してください。',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',

            'address.required' => '住所を入力してください。',
            'address.string' => '住所は文字で入力してください。',

            'building.string' => '建物名は文字で入力してください。',
            'building.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}
