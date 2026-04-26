<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIssueRequest extends FormRequest // SỬA TÊN CLASS Ở ĐÂY
{
    public function authorize(): bool
    {
        return true;
    }

   public function rules(): array
    {
        return [
            'note' => ['nullable', 'string', 'max:500'],
            
            // Khớp với name="products[...][...]" trong HTML
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'products.required' => 'phải thêm ít nhất một dòng sản phẩm!',
            'products.*.id.required' => 'Có dòng chưa chọn sản phẩm kìa.',
            'products.*.id.exists' => 'Sản phẩm chọn không hợp lệ.',
            
            'products.*.quantity.required' => 'Vui lòng nhập số lượng.',
            'products.*.quantity.integer' => 'Số lượng phải là số nguyên.',
            'products.*.quantity.min' => 'Số lượng xuất phải lớn hơn 0.', 
        ];
    }
}