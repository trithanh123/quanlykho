<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Nhớ phải có dòng này để gọi Model nha ông

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Danh sách các linh kiện PC cơ bản
        $pcComponents = [
            ['name' => 'Bo mạch chủ (Mainboard)'],
            ['name' => 'Vi xử lý (CPU)'],
            ['name' => 'Bộ nhớ trong (RAM)'],
            ['name' => 'Card màn hình (VGA)'],
            ['name' => 'Ổ cứng (SSD/HDD)'],
            ['name' => 'Nguồn máy tính (PSU)'],
            ['name' => 'Vỏ máy tính (Case)'],
            ['name' => 'Tản nhiệt (Cooling)'],
            ['name' => 'Màn hình (Monitor)'],
            ['name' => 'Phụ kiện (Phím, Chuột, Cáp...)'],
        ];

        // Chạy vòng lặp để tạo tự động vào Database
        foreach ($pcComponents as $item) {
            Category::create($item);
        }
    }
}