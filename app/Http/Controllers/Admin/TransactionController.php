<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Receipt;
use App\Models\Issue;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $receipts = Receipt::with('user')->latest()->get();
        $issues = Issue::with('user')->latest()->get();
        return view('admin.transactions.index', compact('receipts', 'issues'));
    }

    // --- CÁC HÀM XỬ LÝ PHIẾU NHẬP ---
    public function destroyReceipt($id)
    {
        Receipt::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Admin đã XÓA vĩnh viễn Phiếu Nhập!');
    }

    public function unlockReceipt($id)
    {
        // Gợi ý: Nếu database ông có cột 'status' thì cập nhật trạng thái ở đây
        // $receipt = Receipt::findOrFail($id);
        // $receipt->status = 'mo_khoa'; $receipt->save();
        return redirect()->back()->with('success', 'Đã cấp quyền MỞ KHÓA cho Phiếu Nhập này!');
    }

    // --- CÁC HÀM XỬ LÝ PHIẾU XUẤT ---
    public function destroyIssue($id)
    {
        Issue::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Admin đã XÓA vĩnh viễn Phiếu Xuất!');
    }

    public function unlockIssue($id)
    {
        // $issue = Issue::findOrFail($id);
        // $issue->status = 'mo_khoa'; $issue->save();
        return redirect()->back()->with('success', 'Đã cấp quyền MỞ KHÓA cho Phiếu Xuất này!');
    }
}