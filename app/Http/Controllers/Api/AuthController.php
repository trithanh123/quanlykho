<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Lấy email và password từ App mobile gửi lên
        $credentials = $request->only('email', 'password');

        // Kiểm tra xem tài khoản có tồn tại và đúng mật khẩu không
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Tạo Token cho App mobile sử dụng
            $token = $user->createToken('WarehouseToken')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Đăng nhập thành công',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        }

        // Trả về báo lỗi nếu sai user/pass
        return response()->json([
            'status' => 'error',
            'message' => 'Tài khoản hoặc mật khẩu không chính xác'
        ], 401);
    }
}
?>