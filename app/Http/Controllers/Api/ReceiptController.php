<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Receipt;
use App\Models\ReceiptDetail;
use App\Models\Product;

class ReceiptController extends Controller
{   
    // API Lấy danh sách Lịch sử NHẬP kho
    public function index()
    {
        // Lấy phiếu nhập, kèm theo danh sách chi tiết và tên sản phẩm bên trong
        $receipts = Receipt::with('details.product')->orderBy('id', 'desc')->get();
        return response()->json($receipts, 200);
    }
    // API Lập phiếu nhập kho & Tự động cộng tồn kho
    public function store(Request $request)
    {
        DB::beginTransaction(); // Khóa an toàn: Lỗi là tự động hoàn tác
        try {
            // 1. Tạo Phiếu Nhập
            $receipt = Receipt::create([
                'note'         => $request->input('note', 'Nhập hàng vào kho'),
                'user_id'      => 1,      // Gán đại user số 1
                //'warehouse_id' => 1,      // Gán đại kho số 1
                
                'created_at'   => now(),  // Lấy giờ hiện tại
                'updated_at'   => now(),  // Lấy giờ hiện tại
            ]);

            $details = $request->input('details', []);

            // 2. Lưu chi tiết & Cộng kho
            foreach ($details as $item) {
                ReceiptDetail::create([
                    'receipt_id' => $receipt->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => 0,
                    'created_at' => now(), // Bơm thêm giờ
                    'updated_at' => now(), // Bơm thêm giờ
                    // 'price'   => $item['price'] ?? 0, // Mở comment nếu DB có cột price
                ]);

                // Tự động CỘNG kho
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->quantity += $item['quantity'];
                    $product->save();
                }
            }

            DB::commit(); // Lưu thành công
            return response()->json(['message' => 'Lập phiếu NHẬP kho thành công, đã cộng tồn kho!'], 201);
            
        } catch (\Exception $e) {
            DB::rollBack(); // Lỗi thì hủy
            return response()->json(['message' => 'Lỗi hệ thống: ' . $e->getMessage()], 500);
        }
    }
}