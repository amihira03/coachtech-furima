<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'in:convenience_store,card'],
            'shipping_postal_code' => ['required'],
            'shipping_address' => ['required'],
            'shipping_building' => ['nullable', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method.required' => '支払い方法を選択してください。',
            'payment_method.in' => '支払い方法を正しく選択してください。',
            'shipping_postal_code.required' => '配送先を設定してください。',
            'shipping_address.required' => '配送先を設定してください。',
        ];
    }
}
