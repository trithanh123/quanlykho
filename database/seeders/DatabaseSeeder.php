<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,      // Phải có user trước
            CategorySeeder::class,  // Phải có danh mục trước
            ProductSeeder::class,   // Có danh mục mới tạo được sản phẩm
            ReceiptSeeder::class,   // Có user và sản phẩm mới tạo Phiếu Nhập
            IssueSeeder::class,     // Tương tự cho Phiếu Xuất
        ]);
    }

}
