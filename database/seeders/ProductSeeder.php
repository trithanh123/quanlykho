<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Laptop Dell XPS 15',
                'sku' => 'LAP-DELL-001', // Thêm mã SKU bắt buộc
                'category_id' => 1,      // Nối với Linh kiện Điện tử
                'price' => 25000000,
                'quantity' => 100,
                'unit' => 'Cái',         // Đơn vị tính
                'description' => 'Laptop văn phòng cao cấp',
                'image' => null,         // Ảnh tạm thời để trống
            ],
            [
                'name' => 'Chuột không dây Logitech',
                'sku' => 'PK-LOGI-001',  // Thêm mã SKU bắt buộc
                'category_id' => 3,      // Nối với Phụ kiện
                'price' => 350000,
                'quantity' => 500,
                'unit' => 'Cái',
                'description' => 'Chuột bluetooth không dây',
                'image' => null,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}