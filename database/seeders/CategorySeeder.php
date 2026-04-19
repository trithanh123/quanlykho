<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Bo mạch chủ (Mainboard)', 'description' => 'Bảng mạch chính kết nối các linh kiện hệ thống'],
            ['name' => 'Vi xử lý (CPU)', 'description' => 'Các dòng chip xử lý trung tâm (VD: Intel Core i5-12400F...)'],
            ['name' => 'Bộ nhớ trong (RAM)', 'description' => 'Bộ nhớ lưu trữ tạm thời tốc độ cao'],
            ['name' => 'Card màn hình (VGA)', 'description' => 'Thiết bị chuyên xử lý các tác vụ đồ họa'],
            ['name' => 'Ổ cứng (SSD/HDD)', 'description' => 'Thiết bị lưu trữ dữ liệu lâu dài'],
            ['name' => 'Nguồn máy tính (PSU)', 'description' => 'Bộ phận cung cấp điện năng cho toàn bộ máy'],
            ['name' => 'Vỏ máy tính (Case)', 'description' => 'Thùng chứa và bảo vệ các linh kiện bên trong'],
            ['name' => 'Tản nhiệt (Cooling)', 'description' => 'Hệ thống tản nhiệt và quạt hút khí mát vào trong'],
            ['name' => 'Màn hình (Monitor)', 'description' => 'Thiết bị đầu ra hiển thị hình ảnh'],
            ['name' => 'Phụ kiện (Phím, Chuột, Cáp...)', 'description' => 'Các thiết bị ngoại vi và dây kết nối'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}