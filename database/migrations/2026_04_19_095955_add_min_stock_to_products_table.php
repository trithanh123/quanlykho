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
        Schema::table('products', function (Blueprint $table) {
            // XÂY THÊM CỘT: Nằm ngay dưới cột 'quantity' cho dễ nhìn
          $table->integer('min_stock')->default(10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // ĐẬP BỎ CỘT (Nếu lỡ có chạy lệnh rollback)
            $table->dropColumn('min_stock');
        });
    }
};