<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
           'name' => 'required|string|max:255',
           'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
        'email' => ['required', 'string', 'email', 'ends_with:@phongvu.com', 'max:255', 'unique:users,email'],
        'password' => 'required|min:8',
        ];
    }
    public function messages(): array
    {
        return [
            // ... các câu khác
            
            // CHÍNH LÀ DÒNG NÀY NÈ ÔNG:
            'name.regex' => 'Tên nhân viên chỉ được chứa chữ cái và khoảng trắng, không bao gồm số hay ký tự đặc biệt.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            // THÊM DÒNG NÀY VÀO NÈ:
            'email.ends_with' => 'Tài khoản bắt buộc phải sử dụng đuôi @phongvu.com.',
            'password' => 'Mật khẩu phải có độ dài từ 8 ký tự trở lên'
        ];
    }
}
