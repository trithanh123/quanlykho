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
       Schema::create('receipts', function (Blueprint $table) {
        $table->id();
        // Liên kết với bảng users để biết Thủ kho nào lập phiếu này
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
        $table->string('note')->nullable(); // Ghi chú thêm
        $table->timestamps(); // Cái này tự động lưu ngày giờ lập phiếu
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('receipt_details', function (Blueprint $table) {
        $table->id();
        // Nối với phiếu nhập nào
        $table->foreignId('receipt_id')->constrained('receipts')->onDelete('cascade');
        // Nối với hàng hóa nào
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
        
        $table->integer('quantity'); // Số lượng nhập vào
        $table->bigInteger('price'); // Giá nhập (có thể khác giá bán)
        $table->timestamps();
    });
    }
};
