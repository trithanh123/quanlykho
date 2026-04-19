<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverController extends Controller
{
    // 1. Xem danh sách CẦN GIAO
    public function index()
    {
        $driverId = Auth::id();

        // Chỉ lấy những đơn CHƯA hoàn thành (status khác 'hoan_thanh' hoặc rỗng)
        $assignedIssues = Issue::with('details.product')
            ->where('tai_xe_id', $driverId)
            ->where(function($query) {
                $query->where('status', '!=', 'hoan_thanh')->orWhereNull('status');
            })
            ->orderBy('issue_date', 'desc')
            ->get();

        return view('driver.index', compact('assignedIssues'));
    }

    // 2. Nút XÁC NHẬN GIAO HÀNG
    public function confirm($id)
    {
        $issue = Issue::where('tai_xe_id', Auth::id())->findOrFail($id);
        
        // Đánh dấu trạng thái là hoàn thành
        $issue->status = 'hoan_thanh';
        $issue->save();

        return redirect()->back()->with('success', 'Đã xác nhận giao hàng thành công!');
    }

    // 3. Xem LỊCH SỬ ĐÃ GIAO
    public function history()
    {
        $driverId = Auth::id();

        // Chỉ lấy những đơn ĐÃ HOÀN THÀNH
        $completedIssues = Issue::with('details.product')
            ->where('tai_xe_id', $driverId)
            ->where('status', 'hoan_thanh')
            ->orderBy('updated_at', 'desc') // Sắp xếp theo ngày giao xong
            ->get();

        return view('driver.history', compact('completedIssues'));
    }
    // 4. BÁO DỜI NGÀY GIAO SANG HÔM SAU
    public function postpone(Request $request, $id)
    {
        $issue = Issue::where('tai_xe_id', Auth::id())->findOrFail($id);
        
        // Đổi trạng thái thành tạm hoãn
        $issue->status = 'tam_hoan';
        
        // Lấy lý do tài xế nhập, nếu không nhập thì để mặc định
        $ly_do = $request->input('ly_do', 'Tài xế báo dời sang ngày mai');
        
        // Nối thêm lý do vào ghi chú hiện tại để Admin xem được
        $issue->note = $issue->note ? $issue->note . ' | 🕒 BÁO DỜI: ' . $ly_do : '🕒 BÁO DỜI: ' . $ly_do;
        
        $issue->save();

        return redirect()->back()->with('warning', 'Đã báo dời chuyến hàng này sang ngày mai!');
    }
}   