<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('issue_details', function (Blueprint $table) {
        $table->id();
        $table->foreignId('issue_id')->constrained('issues')->onDelete('cascade');
        $table->foreignId('product_id')->constrained('products'); 
        $table->integer('quantity'); // Số lượng xuất
        $table->decimal('price', 15, 2)->nullable(); // Giá xuất (nếu cần ghi nhận)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issue_details');
    }
};
