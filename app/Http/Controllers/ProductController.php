<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
use Cloudinary\Cloudinary; // Đã thêm Cloudinary

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(\App\Http\Requests\StoreProductRequest $request)
    {
        // 1. Kiểm tra dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,webp,jfif|max:5120',
        ], [
            'price.min' => 'Giá bán không được nhỏ hơn 0.', // Tui gộp báo lỗi của ông lên đây cho gọn
            'quantity.min' => 'Số lượng không được nhỏ hơn 0.',
        ]);

        // 2. Xử lý file ảnh ĐẨY LÊN CLOUDINARY
        $imagePath = null;
        if ($request->hasFile('image')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $uploadedImage = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'warehouse_products']
            );
            $imagePath = $uploadedImage['secure_url']; // Lấy link trực tiếp
        }

        // 3. Cất tất cả vào Database
        Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'unit' => $request->unit ?? 'Cái',
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        // 4. Đá người dùng về lại trang Danh sách
        return redirect()->route('products.index');
    }

    public function edit($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $categories = \App\Models\Category::all(); 
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // 1. KHÔNG CẦN $request->validate() Ở ĐÂY NỮA VÌ StoreProductRequest ĐÃ LÀM THAY RỒI!
        // 2. Xử lý file ảnh ĐẨY LÊN CLOUDINARY
        $imagePath = null;
        if ($request->hasFile('image')) {
            $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
            $uploadedImage = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'warehouse_products']
            );
            $imagePath = $uploadedImage['secure_url']; // Lấy link trực tiếp
        }

        // 3. Cất tất cả vào Database
        Product::create([
            'name' => $request->name,
            'sku' => $request->sku,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'unit' => $request->unit ?? 'Cái',
            'image' => $imagePath,
            'description' => $request->description,
        ]);

        // 4. Đá người dùng về lại trang Danh sách
        return redirect()->route('products.index')->with('success', 'Thêm hàng hóa thành công!');
    }

    public function destroy($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Đã xóa sản phẩm thành công rồi ông nhé!');
    }
}