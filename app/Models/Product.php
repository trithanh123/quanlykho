<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
   // Đây chính là "Chìa khóa" mở cổng cho lệnh create() nè:
    protected $fillable = [
        'name', 'sku', 'category_id', 'price', 'quantity', 'unit', 'description', 'image'
    ];

    // Thiết lập quan hệ với bảng Danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
