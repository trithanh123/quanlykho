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
       // 1. Kiểm duyệt dữ liệu (Phải validate ở đây để dùng được luật bỏ qua ID hiện tại cho SKU)
        $request->validate([
            'name' => 'required|string|max:255',
            // QUAN TRỌNG NHẤT LÀ DÒNG DƯỚI ĐÂY: Nó báo cho hệ thống biết không check trùng với chính nó
            'sku' => 'required|string|unique:products,sku,' . $id, 
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|mimes:jpg,jpeg,png,gif,webp,jfif|max:5120',
        ], [
            'sku.unique' => '❌ Mã SKU này đã bị sản phẩm khác lấy rồi!',
            'price.min' => 'Giá bán không được nhỏ hơn 0.',
            'quantity.min' => 'Số lượng không được nhỏ hơn 0.',
        ]);

        // 2. TÌM ĐÚNG SẢN PHẨM CẦN SỬA TRONG KHO
        $product = \App\Models\Product::findOrFail($id);

        // 3. Gán từng thông tin mới đè lên thông tin cũ
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->unit = $request->unit ?? 'Cái';
        $product->description = $request->description;

        // 4. XỬ LÝ ẢNH (Chỉ khi nào ông CHỌN ẢNH MỚI thì nó mới đẩy lên Cloudinary)
        if ($request->hasFile('image')) {
            $cloudinary = new \Cloudinary\Cloudinary(env('CLOUDINARY_URL'));
            $uploadedImage = $cloudinary->uploadApi()->upload(
                $request->file('image')->getRealPath(),
                ['folder' => 'warehouse_products']
            );
            $product->image = $uploadedImage['secure_url']; // Cập nhật link ảnh mới
        }

        // 5. LƯU LẠI VÀO DATABASE (Dùng lệnh save() để lưu đè, KHÔNG dùng create)
        $product->save();

        // 6. Đá người dùng về lại trang Danh sách kèm thông báo chuẩn
        return redirect()->route('products.index')->with('success', 'Đã cập nhật sản phẩm thành công!');
    }

    public function destroy($id)
    {
        $product = \App\Models\Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Đã xóa sản phẩm thành công rồi ông nhé!');
    }
}