<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreReceiptRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Models\Receipt;            
use App\Models\ReceiptDetail;      
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    public function create()
    {
        // Lấy tất cả sản phẩm để hiển thị trong dropdown chọn hàng
        $products = Product::all();   

        // Lấy 5 phiếu nhập kho mới nhất để hiện bảng bên dưới form
        $recentReceipts = Receipt::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        // Truyền cả 2 biến ra View
        return view('receipts.create', compact('recentReceipts', 'products'));
    }

    public function store(StoreReceiptRequest $request )
    {
        // 1. Kiểm tra dữ liệu (Validation)
        $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|numeric|min:1',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
        ]);

        try {
            // 2. Dùng TRANSACTION để đảm bảo an toàn (Lỗi là hủy hết, không bị lệch kho)
            DB::beginTransaction();

            // 3. Tạo Phiếu Nhập
            $receipt = new Receipt();
            $receipt->user_id = Auth::id(); 
            $receipt->note = $request->note;
            $receipt->save(); 

            // 4. Vòng lặp: Bóc tách từng món hàng để lưu chi tiết và cộng kho
            $soLuongMonHang = count($request->product_id);
            
            for ($i = 0; $i < $soLuongMonHang; $i++) {
                // Lưu vào bảng chi tiết phiếu nhập (ReceiptDetail)
                $detail = new ReceiptDetail();
                $detail->receipt_id = $receipt->id;
                $detail->product_id = $request->product_id[$i];
                $detail->quantity = $request->quantity[$i];
                $detail->price = $request->price[$i];
                $detail->save();

                // CỘNG DỒN VÀO KHO (Dùng increment cho chuẩn Laravel)
                $product = Product::find($request->product_id[$i]);
                $product->increment('quantity', $request->quantity[$i]);
            }

            // 5. Nếu mọi thứ chạy tốt thì mới lưu thật sự vào Database
            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Lập phiếu nhập thành công! Hàng đã được cộng vào kho.');

        } catch (\Exception $e) {
            // 6. Nếu có bất kỳ lỗi nào, Rollback (xóa ngược lại) hết những gì vừa chạy
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        // Tìm phiếu nhập theo ID, lấy kèm thông tin người tạo và chi tiết sản phẩm
        $receipt = \App\Models\Receipt::with('user', 'details.product')->findOrFail($id);
        
        return view('receipts.show', compact('receipt'));
    }
    public function index()
    {
        // Lấy danh sách phiếu nhập kho, sắp xếp mới nhất lên đầu
        $receipts = \App\Models\Receipt::with('user', 'warehouse')->latest()->get();

        // Trả về file giao diện hiển thị danh sách
        return view('receipts.index', compact('receipts'));
    }
}