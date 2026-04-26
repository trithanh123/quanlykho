<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Lấy ID của user đang được sửa từ đường dẫn (route)
        $userId = $this->route('user'); 

        return [
            // Chỉ kiểm tra đúng 4 trường có trên giao diện Admin
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'email' => [
                'required', 'string', 'email', 'ends_with:@phongvu.com', 'max:255', 
                Rule::unique('users')->ignore($userId)
            ],            
            'password' => ['nullable', 'string', 'min:8'], // Bỏ trống thì không đổi pass
            'role' => ['required', 'in:admin,manager,driver'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Họ và tên không được để trống.',
            'name.regex' => 'Tên nhân viên chỉ được chứa chữ cái và khoảng trắng, không bao gồm số hay ký tự đặc biệt.',
            
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không đúng định dạng.',
            'email.ends_with' => 'Tài khoản bắt buộc phải sử dụng đuôi @phongvu.com.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
        ];
    }
}