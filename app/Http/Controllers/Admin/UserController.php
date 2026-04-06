<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       // Lấy danh sách tất cả tài khoản, sắp xếp mới nhất lên đầu và phân trang (10 người/trang)
        $users = User::orderBy('id', 'desc')->paginate(10);
        
        // Trả về view và truyền biến $users sang
        return view('admin.users.adminindex', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Gọi file giao diện create.blade.php ra
            return view('admin.users.create');
    }
    public function toggleLock($id)
    {
        $user = User::findOrFail($id);

        // Chống Admin tự tự khóa chính mình
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Bạn không thể tự khóa tài khoản của chính mình!');
        }

        // Đảo ngược trạng thái: Nếu đang 0 (mở) thì thành 1 (khóa), và ngược lại
        $user->is_locked = !$user->is_locked;
        $user->save();

        // Chuẩn bị câu thông báo tương ứng
        $message = $user->is_locked ? 'Đã KHÓA tài khoản thành công!' : 'Đã MỞ KHÓA tài khoản thành công!';
        
        return back()->with('success', $message);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào (Validation)
        // Nếu nhập thiếu hoặc sai, Laravel sẽ tự động đá ngược lại form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,manager,driver',
        ], [
            // Tùy chỉnh câu thông báo lỗi cho thân thiện
            'name.required' => 'Vui lòng nhập tên nhân viên.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
        ]);

        // 2. Lưu thông tin vào database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Lệnh bcrypt giúp băm nhỏ mật khẩu để bảo mật
            'role' => $request->role,
        ]);

        // 3. Chuyển hướng về trang danh sách và gửi kèm một dòng thông báo
        return redirect('admin/users')->with('success', 'Đã thêm nhân viên mới thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Tìm nhân viên theo ID, nếu không thấy sẽ báo lỗi 404
        $user = User::findOrFail($id); 
        
        // Trả về form sửa và truyền biến $user sang
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // 1. Tìm nhân viên đang cần sửa
        $user = User::findOrFail($id);

        // 2. Kiểm tra dữ liệu nhập vào
        $request->validate([
            'name' => 'required|string|max:255',
            // Lệnh unique có thêm biến $id để bỏ qua email của chính nhân viên này (nếu họ không đổi email)
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,manager,driver',
            'password' => 'nullable|string|min:8', // Nullable: Không bắt buộc nhập (cho phép để trống)
        ]);

        // 3. Cập nhật các thông tin cơ bản
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // 4. Nếu người quản lý CÓ gõ mật khẩu mới thì mới mã hóa và lưu đè lên pass cũ
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // 5. Đá văng ra ngoài danh sách và báo màu xanh
        return redirect('admin/users')->with('success', 'Đã cập nhật thông tin nhân viên thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       // 1. Tìm nhân viên cần xóa
        $user = User::findOrFail($id);

        // 2. Chặn lỗi logic: Không cho phép Admin tự xóa chính tài khoản mình đang đăng nhập
        if (auth()->id() == $user->id) {
            return redirect('admin/users')->with('error', 'Bạn không thể tự xóa tài khoản của chính mình!');
        }

        // 3. Tiến hành xóa khỏi database
        $user->delete();

        // 4. Quay về danh sách và báo thành công
        return redirect('admin/users')->with('success', 'Đã xóa tài khoản nhân viên vĩnh viễn!');
    }
}
