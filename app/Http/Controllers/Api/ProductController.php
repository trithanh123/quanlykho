<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index() {
        $products = \App\Models\Product::all(); 
        return response()->json($products);
    }

    public function show($id) {
        $product = \App\Models\Product::find($id); 
        if (!$product) {
            return response()->json(['message' => 'Lỗi không tìm thấy'], 404);
        }
        return response()->json($product); 
    }

    // ==========================================
    // HÀM THÊM MỚI (ĐÃ FIX LỖI 500 VÀ NHẬN HÌNH ẢNH)
    // ==========================================
    public function store(Request $request) {
        $data = $request->all();
        
        // Tự động điền danh mục 1 nếu điện thoại quên gửi
        if (!isset($data['category_id'])) {
            $data['category_id'] = 1; 
        }

        // Hứng hình ảnh và lưu thẳng ra thư mục public/images
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $data['image'] = 'images/' . $filename;
        }

        $product = \App\Models\Product::create($data);
        return response()->json(['message' => 'Thêm thành công', 'data' => $product], 201);
    }

    // ==========================================
    // HÀM CẬP NHẬT (ĐÃ FIX ĐỂ NHẬN HÌNH ẢNH)
    // ==========================================
    public function update(Request $request, $id) {
        $product = \App\Models\Product::find($id);
        if (!$product) return response()->json(['message' => 'Không tìm thấy'], 404);
        
        $data = $request->all();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            $data['image'] = 'images/' . $filename;
        }

        $product->update($data);
        return response()->json(['message' => 'Cập nhật thành công']);
    }

    // Hàm xóa
    public function destroy($id) {
        $product = \App\Models\Product::find($id);
        if (!$product) return response()->json(['message' => 'Không tìm thấy'], 404);
        
        $product->delete();
        return response()->json(['message' => 'Đã xóa thành công']);
    }
}