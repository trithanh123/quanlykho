<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // BỔ SUNG DÒNG NÀY VÀO ĐỂ CẤP PHÉP LƯU DỮ LIỆU NHA ÔNG
    protected $fillable = [
        'name',
        'description',
    ];

    // Hàm này (nếu ông đã tạo) là để liên kết 1 Danh mục có nhiều Sản phẩm
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}