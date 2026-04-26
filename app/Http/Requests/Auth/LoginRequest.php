<?php

namespace App\Http\Requests\Auth;

use App\Models\User; // Thêm thư viện gọi Model User
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Thêm thư viện kiểm tra mật khẩu
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Dịch thông báo lỗi khi để trống hoặc nhập sai định dạng
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Bạn phải nhập đầy đủ tài khoản.',
            'email.email' => 'Tài khoản phải đúng định dạng email.',
            'password.required' => 'Bạn phải nhập đầy đủ mật khẩu.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // Bước 1: Tìm xem tài khoản (email) này có tồn tại không
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            // Nếu không tìm thấy user -> Báo lỗi sai tài khoản
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Bạn đã nhập sai tài khoản.',
            ]);
        }

        // Bước 2: Nếu tài khoản đúng, kiểm tra tiếp mật khẩu
        if (!Hash::check($this->password, $user->password)) {
            // Nếu mật khẩu không khớp -> Báo lỗi sai mật khẩu
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'password' => 'Bạn đã nhập sai mật khẩu.',
            ]);
        }

        // Bước 3: Nếu cả 2 đều đúng, tiến hành đăng nhập
        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}