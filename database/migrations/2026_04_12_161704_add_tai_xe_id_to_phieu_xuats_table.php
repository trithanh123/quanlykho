<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Đã sửa tên bảng thành 'issues' và thêm lệnh tạo cột 'tai_xe_id'
        Schema::table('issues', function (Blueprint $table) {
            $table->unsignedBigInteger('tai_xe_id')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Lệnh để xóa cột nếu ông lỡ chạy rollback
        Schema::table('issues', function (Blueprint $table) {
            $table->dropColumn('tai_xe_id');
        });
    }
};