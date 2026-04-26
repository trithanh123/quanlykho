<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceiptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note' => ['nullable', 'string', 'max:500'],
            // Kiểm tra mảng sản phẩm
            'product_id' => ['required', 'array', 'min:1'],
            'product_id.*' => ['required', 'exists:products,id'],
            // Kiểm tra mảng số lượng: phải là số nguyên và > 0
            'quantity' => ['required', 'array'],
            'quantity.*' => ['required', 'integer', 'min:1'],
            // Kiểm tra mảng giá: phải là số và >= 0
            'price' => ['required', 'array'],
            'price.*' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => '❌ Chưa chọn sản phẩm nào để nhập kho hết!',
            'product_id.*.exists' => '❌ Sản phẩm chọn không tồn tại.',
            'quantity.*.integer' => '❌ Số lượng phải là số nguyên.',
            'quantity.*.min' => '❌ Số lượng nhập phải lớn hơn 0.',
            'price.*.numeric' => '❌ Giá nhập phải là con số.',
            'price.*.min' => '❌ Giá nhập không được là số âm.',
            'price.*.required' => '❌ Giá nhập không được để trống!',
            'price.*.numeric'  => '❌ Giá nhập phải là số nguyên cơ!',
        ];
    }
}