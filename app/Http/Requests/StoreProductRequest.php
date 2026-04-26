<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'unique:products,sku', 'max:50'],
            'category_id' => ['required', 'exists:categories,id'],
            
            // 1. KIỂM TRA SỐ THẬP PHÂN (Decimal), BẮT BUỘC >= 0
            'price' => ['required', 'numeric', 'min:0'], 
            
            // 2. KIỂM TRA SỐ NGUYÊN (Integer), BẮT BUỘC >= 0
            'quantity' => ['required', 'integer', 'min:0'],
            'min_stock' => ['nullable', 'integer', 'min:0'],
            
            'unit' => ['required', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,jfif,gif', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên sản phẩm không được để trống.',
            'sku.required' => 'Mã SKU không được để trống.',
            'sku.unique' => 'Mã SKU này đã tồn tại.',
            'category_id.exists' => 'Danh mục đã chọn không hợp lệ.',
            
            // Thông báo lỗi cho phần số và tiền
            'price.numeric' => 'Giá sản phẩm phải là một con số.',
            'price.min' => 'Giá sản phẩm không được nhỏ hơn 0.',
            'quantity.integer' => 'Số lượng tồn kho phải là số nguyên (không chứa dấu phẩy).',
            'quantity.min' => 'Số lượng tồn kho không được nhỏ hơn 0.',
            'min_stock.integer' => 'Mức tồn kho tối thiểu phải là số nguyên.',
            'min_stock.min' => 'Mức tồn kho tối thiểu không được nhỏ hơn 0.',
            'sku.required' => 'Mã SKU không được để trống!',
            'sku.unique'   => '❌ Mã Sản Phẩm này đã tồn tại trong hệ thống rồi !',
            'image.image' => 'File tải lên phải là định dạng hình ảnh.',
            'image.mimes' => '❌ Hình ảnh phải là định dạng jpg, jpeg, png, webp hoặc jfif !',
            'image.max' => '❌ File ảnh nặng quá 5MB rồi,  nén lại bớt để giảm!',
        ];
    }
}