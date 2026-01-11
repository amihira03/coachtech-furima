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
            // 画像は任意。入れるなら jpg/jpeg/png のみ
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png'],

            // ユーザー名：必須、20文字以内
            'name' => ['required', 'string', 'max:20'],

            // 郵便番号：必須、ハイフンあり 8文字（例：123-4567）
            'postal_code' => ['required', 'string', 'size:8', 'regex:/^\d{3}-\d{4}$/'],

            // 住所：必須
            'address' => ['required', 'string'],

            // 建物名：任意
            'building' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            // 画像
            'profile_image.image' => '画像ファイルを選択してください。',
            'profile_image.mimes' => '画像は「jpeg」または「png」形式でアップロードしてください。',

            // ユーザー名
            'name.required' => 'ユーザー名を入力してください。',
            'name.string' => 'ユーザー名は文字で入力してください。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',

            // 郵便番号
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.string' => '郵便番号は文字で入力してください。',
            'postal_code.size' => '郵便番号はハイフンありの8文字で入力してください。',
            'postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',

            // 住所
            'address.required' => '住所を入力してください。',
            'address.string' => '住所は文字で入力してください。',

            // 建物名（任意だけど、入力された場合の型・長さエラーは出る可能性があるため）
            'building.string' => '建物名は文字で入力してください。',
            'building.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}
