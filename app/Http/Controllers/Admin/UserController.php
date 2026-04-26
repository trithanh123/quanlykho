<?php
namespace App\Http\Controllers\Admin;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
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
    public function store(StoreUserRequest $request)
    {
        // 1. Dữ liệu đã được FormRequest lọc sạch sẽ và an toàn
        $data = $request->validated();
        
        // 2. Băm mật khẩu
        $data['password'] = bcrypt($data['password']);

        // 3. Lưu vào Database cực nhanh với lệnh create
        User::create($data);

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
    public function update(UpdateUserRequest $request, string $id)
    {
        $user = User::findOrFail($id);
        
        // 1. Lấy dữ liệu an toàn
        $data = $request->validated();

        // 2. Xử lý mật khẩu (Nếu có nhập pass mới thì băm, không thì bỏ qua để giữ pass cũ)
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']); 
        }

        // 3. Lưu đè thông tin mới
        $user->update($data);

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
