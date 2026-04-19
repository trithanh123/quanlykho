<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\Issue;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Khai báo thêm thư viện này để tính toán ngày tháng

class InventoryController extends Controller
{
    public function index()
    {
        // 1. KIỂM SOÁT TỒN KHO: Lọc hàng dưới mức tối thiểu
        $lowStockProducts = Product::whereRaw('quantity <= min_stock')->get();
        $allProducts = Product::with('category')->get();

        // 2. THỐNG KÊ BIỂU ĐỒ 7 NGÀY GẦN NHẤT
        $chartLabels = [];
        $chartReceiptData = [];
        $chartIssueData = [];

        for ($i = 6; $i >= 0; $i--) {
            // Lùi về $i ngày so với hôm nay
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d/m'); // Lấy định dạng Ngày/Tháng

            // Đếm số lượng phiếu Nhập và Xuất trong ngày đó
            $chartReceiptData[] = Receipt::whereDate('created_at', $date)->count();
            $chartIssueData[] = Issue::whereDate('issue_date', $date)->count();
        }

        // Truyền các mảng biểu đồ ra ngoài View
        return view('manager.inventory.index', compact('lowStockProducts', 'allProducts', 'chartLabels', 'chartReceiptData', 'chartIssueData'));
    }

    // 3. XUẤT FILE EXCEL: Xuất CSV đơn giản cho Thủ kho đi kiểm kho
    public function exportCsv()
    {
        $fileName = 'kiem_kho_' . date('d-m-Y') . '.csv';
        $products = Product::all();

        $headers = array(
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Ma SP', 'Ten San Pham', 'Ton Kho Thuc Te', 'Ghi chu (Kiem thuc)');

        $callback = function() use($products, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // Fix lỗi font tiếng Việt Excel
            fputcsv($file, $columns);

            foreach ($products as $p) {
                fputcsv($file, array($p->id, $p->name, $p->quantity, ''));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}