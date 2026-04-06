<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use App\Models\IssueDetail;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class IssueController extends Controller
{
    // Hiển thị danh sách phiếu xuất
    public function index()
    {
        $issues = Issue::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('issues.index', compact('issues')); 
        // Lát nữa bạn sẽ tạo thư mục views/issues/index.blade.php
    }

    // Hiển thị form lập phiếu xuất mới
    public function create()
    {
        // Lấy danh sách sản phẩm đang có số lượng > 0 để hiển thị lên form chọn
        $products = Product::where('quantity', '>', 0)->get(); 
        return view('issues.create', compact('products'));
    }

    // Xử lý lưu phiếu xuất và trừ tồn kho
    public function store(Request $request)
    {
        // 1. Validate dữ liệu gửi lên từ form
        $request->validate([
            'note' => 'nullable|string',
            'products' => 'required|array', // Mảng các sản phẩm được chọn
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            // 2. Bắt đầu Transaction
            DB::beginTransaction();

            // 3. Tạo phiếu xuất (Issue)
            $issue = Issue::create([
                'issue_code' => '#IS-' . strtoupper(uniqid()), // Tạo mã ngẫu nhiên, VD: #IS-64B1A2C
                'user_id' => Auth::id(), // Lấy ID của người (Thủ kho) đang đăng nhập
                'issue_date' => now(),
                'note' => $request->note,
            ]);

            // 4. Lưu chi tiết phiếu xuất và Cập nhật tồn kho
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['id']);

                // Kiểm tra xem số lượng xuất có vượt quá tồn kho không
                if ($item['quantity'] > $product->quantity) {
                    // Ném lỗi để rollback lại toàn bộ
                    throw new \Exception("Số lượng xuất của sản phẩm {$product->name} vượt quá số lượng tồn!");
                }

                // Lưu vào bảng issue_details
                IssueDetail::create([
                    'issue_id' => $issue->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price, // Tùy chọn: Lưu lại giá tại thời điểm xuất
                ]);

                // Trừ số lượng tồn trong bảng products
                $product->decrement('quantity', $item['quantity']);
            }

            // 5. Nếu mọi thứ thành công, Commit lưu vào Database
            DB::commit();

            return redirect()->route('issues.index')->with('success', 'Lập phiếu xuất kho thành công!');

        } catch (\Exception $e) {
            // 6. Nếu có bất kỳ lỗi nào xảy ra (VD: xuất lố tồn kho), Rollback toàn bộ
            DB::rollBack();
            return back()->with('error', 'Lỗi: ' . $e->getMessage())->withInput();
        }
    }

    // Xem chi tiết một phiếu xuất
    public function show($id)
    {
        // Lấy phiếu xuất cùng với chi tiết và thông tin sản phẩm
        $issue = Issue::with(['user', 'details.product'])->findOrFail($id);
        return view('issues.show', compact('issue'));
    }
}