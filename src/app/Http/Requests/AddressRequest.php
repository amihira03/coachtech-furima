<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_postal_code' => ['required', 'string', 'size:8', 'regex:/^\d{3}-\d{4}$/'],
            'shipping_address' => ['required', 'string'],
            'shipping_building' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'shipping_postal_code.required' => '郵便番号を入力してください。',
            'shipping_postal_code.size' => '郵便番号はハイフンありの8文字で入力してください。',
            'shipping_postal_code.regex' => '郵便番号は「123-4567」の形式で入力してください。',

            'shipping_address.required' => '住所を入力してください。',

            'shipping_building.string' => '建物名は文字で入力してください。',
            'shipping_building.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}
