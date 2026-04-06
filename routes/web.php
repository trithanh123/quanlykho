<?php
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ReceiptController;
use App\Models\Receipt;
use App\Http\Controllers\IssueController;
use App\Models\Issue;
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Đường dẫn xử lý nút Khóa/Mở khóa
Route::put('admin/users/{id}/lock', [UserController::class, 'toggleLock']);

// Dòng cũ của bạn giữ nguyên
Route::resource('admin/users', UserController::class);
Route::resource('products', ProductController::class);    

Route::resource('receipts', ReceiptController::class);

//Phiếu kho
Route::get('/dashboard', function () {
    // Lấy 5 phiếu nhập mới nhất, kèm theo thông tin người lập (user)
    $recentReceipts = Receipt::with('user')
                        ->latest()
                        ->take(5)
                        ->get();

    // Trả về view dashboard kèm theo biến dữ liệu
    return view('dashboard', compact('recentReceipts'));
})->middleware(['auth', 'verified'])->name('dashboard');
});
Route::get('/dashboard', function () {
    // 1. Đoạn code cũ lấy phiếu nhập kho của bạn (cứ giữ nguyên, tui ví dụ thôi nha)
    $recentReceipts = \App\Models\Receipt::with('user')->orderBy('created_at', 'desc')->take(5)->get(); 

    // 2. Thêm dòng lấy phiếu xuất kho vào NGAY DƯỚI ĐÓ
    $recentIssues = Issue::with('user')->orderBy('created_at', 'desc')->take(5)->get();

    // 3. Truyền CẢ 2 biến ra ngoài view bằng cách gom chung vào hàm compact()
    return view('dashboard', compact('recentReceipts', 'recentIssues'));

})->middleware(['auth', 'verified'])->name('dashboard');
// Gom nhóm các route yêu cầu đăng nhập
Route::middleware(['auth'])->group(function () {
    // Các route khác của bạn...

    // Routes cho Phiếu Xuất
    Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');
    Route::get('/issues/create', [IssueController::class, 'create'])->name('issues.create');
    Route::post('/issues', [IssueController::class, 'store'])->name('issues.store');
    Route::get('/issues/{id}', [IssueController::class, 'show'])->name('issues.show');
});
require __DIR__.'/auth.php';
