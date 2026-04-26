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

    public function store(Request $request)
    {
        // 1. Kiểm tra dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
        $request->validate([
            'name' => 'required',
            'sku' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $product = \App\Models\Product::findOrFail($id);
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->unit = $request->unit;
        $product->description = $request->description;

        // XỬ LÝ UPLOAD ẢNH MỚI LÊN CLOUDINARY
        if ($request->hasFile('image')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            $uploadedImage = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'warehouse_products']
            );
            
            // Cập nhật link ảnh mới vào DB (Cloudinary tự quản lý ảnh cũ nên không cần unlink)
            $product->image = $uploadedImage['secure_url'];
        }
        
        $product->save();

        return redirect()->route('products.index')->with('success', 'Cập nhật hàng hóa thành công!');
    }

    public function destroy($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Đã xóa sản phẩm thành công rồi ông nhé!');
    }
}