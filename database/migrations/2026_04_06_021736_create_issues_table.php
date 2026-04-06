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
    Schema::create('issues', function (Blueprint $table) {
        $table->id();
        $table->string('issue_code')->unique(); // Mã phiếu xuất, vd: #IS-00001
        $table->foreignId('user_id')->constrained('users'); // Người lập (Thủ kho)
        $table->dateTime('issue_date')->useCurrent(); // Ngày lập phiếu
        $table->text('note')->nullable(); // Ghi chú
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
