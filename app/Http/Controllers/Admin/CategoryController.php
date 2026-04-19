<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
       // Lấy cả 2 loại dữ liệu
        $categories = Category::latest()->get();
        $warehouses = Warehouse::latest()->get();
        
        return view('admin.categories.index', compact('categories', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ], [
            'name.unique' => 'Tên danh mục này đã tồn tại!'
        ]);

        Category::create($request->all());
        return redirect()->back()->with('success', 'Đã thêm danh mục mới!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Kiểm tra xem danh mục có đang chứa sản phẩm nào không
        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'Không thể xóa! Danh mục này đang chứa hàng hóa.');
        }

        $category->delete();
        return redirect()->back()->with('success', 'Đã xóa danh mục!');
    }
}