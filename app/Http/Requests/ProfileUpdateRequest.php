<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Cho phép ai đăng nhập cũng được dùng
    }

    public function rules(): array
    {
        // Lấy ID của chính user đang đăng nhập hiện tại
        $userId = $this->user()->id;

        return [
            // 1. KIỂM TRA CHUỖI & REGEX (Tên, Email)
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s]+$/u'],
            'email' => [
                'required', 'string', 'email', 'ends_with:@phongvu.com', 'max:255', 
                Rule::unique('users')->ignore($userId) // Bỏ qua email của chính mình
            ],
            
            // 2. KIỂM TRA CHUỖI SỐ (Regex Format Check)
            'phone' => ['nullable', 'string', 'regex:/^[0-9]{10}$/'],
            'id_card' => ['nullable', 'string', 'regex:/^[0-9]{12}$/', Rule::unique('users')->ignore($userId)],

            // 3. KIỂM TRA NGÀY THÁNG & 4. KIỂM TRA TUỔI (Date, Min Age)
            'dob' => ['nullable', 'date', 'before:-18 years'], // Phải đủ 18 tuổi
            'id_issue_date' => ['nullable', 'date', 'before_or_equal:today'], // Không cấp CCCD ở tương lai
            
            // CÁC THÔNG TIN KHÁC
            'gender' => ['nullable', 'in:Nam,Nữ,Khác'],
            'address_current' => ['nullable', 'string', 'max:500'],
            'address_permanent' => ['nullable', 'string', 'max:500'],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            
            // KIỂM TRA HÌNH ẢNH (Avatar)
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'], // Tối đa 5MB
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Họ và tên không được để trống.',
            'name.regex' => 'Họ tên chỉ được chứa chữ cái và khoảng trắng.',
            'email.required' => 'Email không được để trống.',
            'email.ends_with' => 'Tài khoản bắt buộc phải sử dụng đuôi @phongvu.com.',
            'email.unique' => 'Email này đã được sử dụng.',
            'phone.regex' => 'Số điện thoại phải bao gồm đúng 10 chữ số.',
            'id_card.regex' => 'Căn cước công dân phải bao gồm đúng 12 chữ số.',
            'id_card.unique' => 'Số CCCD này đã tồn tại trong hệ thống.',
            'dob.before' => 'Bạn phải đủ 18 tuổi.',
            'id_issue_date.before_or_equal' => 'Ngày cấp CCCD không thể là ngày trong tương lai.',
            'profile_picture.image' => 'File tải lên phải là hình ảnh.',
            'profile_picture.max' => 'Kích thước ảnh không được vượt quá 5MB.',
        ];
    }
}   