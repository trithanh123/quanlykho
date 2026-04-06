<?php
namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Product;
class ProductController extends Controller
{
   public function index()
    {
        // Lấy danh sách hàng hóa kèm theo Danh mục, sắp xếp mới nhất lên đầu, phân trang 10 dòng
        $products = Product::with('category')->latest()->paginate(10);
        
        return view('products.index', compact('products'));
    }
    public function create()
    {
        // Lấy toàn bộ danh mục để đổ vào thẻ <select>
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }
    public function store(Request $request)
    {
        // 1. Kiểm tra xem người dùng có nhập sót hay nhập bậy không
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku', // SKU không được trùng
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Chỉ nhận file ảnh, tối đa 2MB
        ]);

        // 2. Xử lý file ảnh (nếu có tải lên VÀ file không bị lỗi)
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Tạo một cái tên mới không bị trùng (VD: 1712345678_hinh-giay.jpg)
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Ép di chuyển thẳng file ra ngoài thư mục public/uploads/products
            $file->move(public_path('uploads/products'), $filename);
            
            // Lưu đường dẫn này vào Database để lát nữa hiển thị ra web
            $imagePath = 'uploads/products/' . $filename;
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
        $request->validate([
    'price' => 'required|numeric|min:0', // Bắt buộc là số và phải lớn hơn hoặc bằng 0
], [
    'price.min' => 'Giá bán không được nhỏ hơn 0.',
]); 
        // 4. Đá người dùng về lại trang Danh sách
        return redirect()->route('products.index');
    }
// 1. Hàm này có nhiệm vụ lôi dữ liệu cũ lên Form
    public function edit($id)
    {
        // Tìm sản phẩm theo ID, không thấy thì báo lỗi 404
        $product = \App\Models\Product::findOrFail($id);
        
        // Lấy danh sách danh mục để show ra cái dropdown <select>
        $categories = \App\Models\Category::all(); 
        
        // Trả về view edit kèm theo data
        return view('products.edit', compact('product', 'categories'));
    }

    // 2. Hàm này có nhiệm vụ nhận dữ liệu mới từ Form và Lưu đè vào DB
    public function update(Request $request, $id)
    {
        // Nhớ validate dữ liệu y chang như lúc Thêm mới nha ông (chặn số âm các kiểu)
        $request->validate([
            'name' => 'required',
            'sku' => 'required',
            'category_id' => 'required',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
        ]);

        $product = \App\Models\Product::findOrFail($id);
        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->unit = $request->unit;
        $product->description = $request->description;

        // Chỗ này nếu có làm upload file ảnh thì ông xử lý thêm nha, không thì tạm thời để vậy
        // if ($request->hasFile('image')) { ... }
        
        $product->save();

        return redirect()->route('products.index')->with('success', 'Cập nhật hàng hóa thành công!');
    }
    public function destroy($id)
{
    // Tìm sản phẩm, nếu không thấy thì báo lỗi 404 luôn cho chắc
    $product = \App\Models\Product::findOrFail($id);

    // Thực hiện lệnh xóa
    $product->delete();

    // Xóa xong thì quay về trang danh sách và hiện thông báo thành công
    return redirect()->route('products.index')->with('success', 'Đã xóa sản phẩm thành công rồi ông nhé!');
}
}
