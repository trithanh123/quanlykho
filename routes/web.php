<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\IssueController;
use App\Models\Receipt;
use App\Models\Issue;
use Carbon\Carbon;

// 1. Chuyển hướng trang chủ
Route::get('/', function () {
    return redirect('/login');
});

// 2. TRANG DASHBOARD (Đã gộp chung và phân quyền)
Route::get('/dashboard', function () {
    $user = auth()->user();

    // 1. Dành cho Tài xế
    if ($user->role == 'driver') {
        $pendingDeliveries = \App\Models\Issue::where('tai_xe_id', $user->id)
            ->whereIn('status', ['dang_giao', 'tam_hoan'])->latest()->take(5)->get();
        $completedDeliveries = \App\Models\Issue::where('tai_xe_id', $user->id)
            ->where('status', 'hoan_thanh')->latest()->take(5)->get();

        return view('dashboard', compact('pendingDeliveries', 'completedDeliveries'));
    } 
    
    // 2. Dành cho Thủ kho (Manager) & Admin
    else {
        $recentReceipts = \App\Models\Receipt::with('user')->latest()->take(5)->get();
        $recentIssues = \App\Models\Issue::with('user')->latest()->take(5)->get();
        
        // Lấy dữ liệu Hàng hóa và Tồn kho sắp hết
        $recentProducts = \App\Models\Product::latest()->take(5)->get();
        $lowStockProducts = \App\Models\Product::whereColumn('quantity', '<=', 'min_stock')->latest()->take(5)->get();
        
        // ==========================================
        // XỬ LÝ DỮ LIỆU BIỂU ĐỒ 6 THÁNG GẦN NHẤT
        // ==========================================
       $chartLabels = [];
        $chartReceiptData = [];
        $chartIssueData = [];

        for ($i = 6; $i >= 0; $i--) {
            // Lùi về $i ngày so với hôm nay
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d/m'); // Sẽ in ra ngày/tháng (VD: 14/04, 15/04, 16/04...)

            // Đếm số phiếu Nhập trong ngày đó
            $chartReceiptData[] = Receipt::whereDate('created_at', $date)->count();

            // Đếm số phiếu Xuất trong ngày đó
            $chartIssueData[] = Issue::whereDate('issue_date', $date)->count();
        }

        return view('dashboard', compact(
            'recentReceipts', 'recentIssues', 'recentProducts', 'lowStockProducts',
            'chartLabels', 'chartReceiptData', 'chartIssueData' // Truyền dữ liệu biểu đồ ra View
        ));
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// ========================================================
// NHÓM CÁC ROUTE YÊU CẦU ĐĂNG NHẬP (AUTH)
// ========================================================
Route::middleware('auth')->group(function () {
    
    // --- QUẢN LÝ TÀI KHOẢN CÁ NHÂN ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- KHU VỰC ADMIN ---
    Route::resource('admin/users', UserController::class);
    Route::put('admin/users/{id}/lock', [UserController::class, 'toggleLock']);
    
    Route::get('admin/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('admin.transactions.index');
    Route::delete('admin/transactions/receipt/{id}', [\App\Http\Controllers\Admin\TransactionController::class, 'destroyReceipt'])->name('admin.transactions.destroyReceipt');
    Route::delete('admin/transactions/issue/{id}', [\App\Http\Controllers\Admin\TransactionController::class, 'destroyIssue'])->name('admin.transactions.destroyIssue');
    Route::put('admin/transactions/receipt/{id}/unlock', [\App\Http\Controllers\Admin\TransactionController::class, 'unlockReceipt'])->name('admin.transactions.unlockReceipt');
    Route::put('admin/transactions/issue/{id}/unlock', [\App\Http\Controllers\Admin\TransactionController::class, 'unlockIssue'])->name('admin.transactions.unlockIssue');
    Route::get('admin/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');

    // --- KHU VỰC QUẢN LÝ CHUNG ---
    Route::resource('products', ProductController::class);    
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class)->except(['create', 'show', 'edit', 'update']);
    Route::resource('warehouses', \App\Http\Controllers\Admin\WarehouseController::class)->only(['store', 'destroy']);
    Route::resource('receipts', ReceiptController::class);
    
    Route::get('/issues', [IssueController::class, 'index'])->name('issues.index');
    Route::get('/issues/create', [IssueController::class, 'create'])->name('issues.create');
    Route::post('/issues', [IssueController::class, 'store'])->name('issues.store');
    Route::get('/issues/{id}', [IssueController::class, 'show'])->name('issues.show');
    Route::put('/issues/{issue}/assign', [App\Http\Controllers\IssueController::class, 'assignDriver'])->name('issues.assign');

    // --- KHU VỰC CỦA TÀI XẾ (DRIVER) ---
    Route::get('/driver/assignments', [\App\Http\Controllers\DriverController::class, 'index'])->name('driver.assignments');
    Route::post('/driver/confirm/{id}', [\App\Http\Controllers\DriverController::class, 'confirm'])->name('driver.confirm');
    Route::get('/driver/history', [\App\Http\Controllers\DriverController::class, 'history'])->name('driver.history');
    Route::post('/driver/postpone/{id}', [\App\Http\Controllers\DriverController::class, 'postpone'])->name('driver.postpone');

    // --- KHU VỰC CỦA THỦ KHO (MANAGER) ---
    Route::get('/manager/inventory', [\App\Http\Controllers\Manager\InventoryController::class, 'index'])->name('manager.inventory.index');
    Route::get('/manager/inventory/export', [\App\Http\Controllers\Manager\InventoryController::class, 'exportCsv'])->name('manager.inventory.export');
    
});

require __DIR__.'/auth.php';