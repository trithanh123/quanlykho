<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Issue;
use App\Models\IssueDetail;
use Carbon\Carbon;

class IssueSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo phiếu xuất kho
        $issue = Issue::create([
            'issue_code' => '#IS-00001',
            'user_id' => 2, // Thủ kho lập
            'issue_date' => Carbon::now(),
            'note' => 'Xuất hàng giao cho đại lý chi nhánh Tân Bình',
        ]);

        // Thêm chi tiết xuất kho
        IssueDetail::create([
            'issue_id' => $issue->id,
            'product_id' => 1, // Xuất Laptop
            'quantity' => 5,
            'price' => 25000000, // Giá xuất
        ]);
    }
}