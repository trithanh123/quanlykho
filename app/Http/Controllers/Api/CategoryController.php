<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category; // Nhớ có dòng này nha
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        // Lấy toàn bộ danh mục trả về cho điện thoại
        $categories = Category::all();
        return response()->json($categories);
    }
}