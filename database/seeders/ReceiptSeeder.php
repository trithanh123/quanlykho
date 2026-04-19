<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Receipt;
use App\Models\ReceiptDetail;

class ReceiptSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo phiếu nhập kho do "Thành Thủ Kho" (id = 2) lập
        $receipt = Receipt::create([
            'user_id' => 2,
            'note' => 'Nhập hàng đợt 1 tháng 4/2026 từ nhà cung cấp',
        ]);

        // Thêm chi tiết các món hàng vào phiếu nhập này
        ReceiptDetail::create([
            'receipt_id' => $receipt->id,
            'product_id' => 1, // Nhập Laptop Dell
            'quantity' => 50,
            'price' => 23000000, // Giá nhập
        ]);

        ReceiptDetail::create([
            'receipt_id' => $receipt->id,
            'product_id' => 2, // Nhập Chuột
            'quantity' => 200,
            'price' => 250000, // Giá nhập
        ]);
    }
}