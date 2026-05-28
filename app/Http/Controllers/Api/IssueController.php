<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Issue;
use App\Models\IssueDetail;
use App\Models\Product;

class IssueController extends Controller
{   
    // API Lấy danh sách Lịch sử XUẤT kho
    public function index()
    {
        // Lấy phiếu xuất, kèm theo danh sách chi tiết và tên sản phẩm bên trong
        $issues = Issue::with('details.product')->orderBy('id', 'desc')->get();
        return response()->json($issues, 200);
    }
    // API Lập phiếu xuất kho & Tự động trừ tồn kho
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // 1. Tạo Phiếu Xuất
            $issue = Issue::create([
                'note'         => $request->input('note', 'Xuất hàng khỏi kho'),
                'user_id'      => 1,
                //'warehouse_id' => 1,      // Thêm kho
                'tai_xe_id'    => 1,      // Thêm tài xế (nếu có)
                'status'       => 1,      // Trạng thái thành công
                'issue_code'   => 'PX' . time(), // Mã phiếu
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);

            $details = $request->input('details', []);

            // 2. Lưu chi tiết & Trừ kho
            foreach ($details as $item) {
                $product = Product::find($item['product_id']);
                
                if (!$product) {
                    throw new \Exception('Không tìm thấy sản phẩm có ID: ' . $item['product_id']);
                }

                // Kiểm tra xem kho còn đủ hàng để xuất không
                if ($product->quantity < $item['quantity']) {
                    throw new \Exception('Lỗi: Sản phẩm "' . $product->name . '" không đủ số lượng để xuất!');
                }

                IssueDetail::create([
                    'issue_id'   => $issue->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Tự động TRỪ kho
                $product->quantity -= $item['quantity'];
                $product->save();
            }

            DB::commit();
            return response()->json(['message' => 'Lập phiếu XUẤT kho thành công, đã trừ tồn kho!'], 201);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400); // Trả về lỗi nếu kho không đủ
        }
    }
}