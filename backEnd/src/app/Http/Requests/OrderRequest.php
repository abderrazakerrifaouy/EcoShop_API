<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order' => 'required|array',
            'order.user_id' => 'required|integer|exists:users,id',
            'order.total_price' => 'required|numeric',
            'order.status' => 'required|string',
            'order.shipping_address' => 'required|string',
            'ItemsOrder' => 'required|array',
            'ItemsOrder.*.productId' => 'required|integer|exists:products,id',
            'ItemsOrder.*.quantity' => 'required|integer|min:1',
            'ItemsOrder.*.price' => 'required|numeric|min:0',
        ];
    }
}

