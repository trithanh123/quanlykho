<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Issue;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // 1. TỔNG GIÁ TRỊ HÀNG TỒN (Lấy Số lượng * Giá bán của tất cả sản phẩm)
        $totalInventoryValue = Product::sum(DB::raw('quantity * price'));

        // 2. MẶT HÀNG XUẤT ĐI NHIỀU NHẤT (Top 5)
        // Kết nối bảng chi tiết xuất (issue_details) với sản phẩm để đếm tổng số lượng xuất
        $topExported = DB::table('issue_details')
            ->join('products', 'issue_details.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(issue_details.quantity) as total_exported'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_exported')
            ->take(5)
            ->get();

        // 3. MẶT HÀNG TỒN QUÁ LÂU (Top 5)
        // Những hàng còn số lượng > 0 nhưng ngày cập nhật (nhập/xuất) cuối cùng là lâu nhất (cũ nhất)
        $oldestStock = Product::where('quantity', '>', 0)
            ->orderBy('updated_at', 'asc') 
            ->take(5)
            ->get();

        // 4. HIỆU SUẤT NHÂN SỰ: TÀI XẾ GIAO NHIỀU NHẤT (Top 5)
        // Đếm số lượng phiếu xuất có trạng thái 'hoan_thanh' theo từng tài xế
        $topDrivers = Issue::where('status', 'hoan_thanh')
            ->whereNotNull('tai_xe_id')
            ->join('users', 'issues.tai_xe_id', '=', 'users.id')
            ->select('users.name', DB::raw('COUNT(issues.id) as total_delivered'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_delivered')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact('totalInventoryValue', 'topExported', 'oldestStock', 'topDrivers'));
    }
}