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
    Schema::table('users', function (Blueprint $table) {
        // Dùng lệnh kiểm tra: Nếu CHƯA CÓ cột thì mới tạo, CÓ RỒI thì bỏ qua!
        
        // 1. Định danh
        if (!Schema::hasColumn('users', 'dob')) { $table->date('dob')->nullable(); }
        if (!Schema::hasColumn('users', 'gender')) { $table->string('gender')->nullable(); }
        if (!Schema::hasColumn('users', 'id_card')) { $table->string('id_card')->nullable(); }
        if (!Schema::hasColumn('users', 'id_issue_date')) { $table->date('id_issue_date')->nullable(); }
        if (!Schema::hasColumn('users', 'id_issue_place')) { $table->string('id_issue_place')->nullable(); }
        
        // 2. Liên lạc
        if (!Schema::hasColumn('users', 'phone')) { $table->string('phone')->nullable(); }
        if (!Schema::hasColumn('users', 'address_permanent')) { $table->string('address_permanent')->nullable(); }
        if (!Schema::hasColumn('users', 'address_current')) { $table->string('address_current')->nullable(); }
        if (!Schema::hasColumn('users', 'emergency_contact')) { $table->string('emergency_contact')->nullable(); }
        
        // 3. Công việc
        if (!Schema::hasColumn('users', 'employee_id')) { $table->string('employee_id')->unique()->nullable(); }
        if (!Schema::hasColumn('users', 'position')) { $table->string('position')->nullable(); }
        if (!Schema::hasColumn('users', 'join_date')) { $table->date('join_date')->nullable(); }
        
        // 4. Chuyên môn
        if (!Schema::hasColumn('users', 'education')) { $table->text('education')->nullable(); }
        if (!Schema::hasColumn('users', 'skills')) { $table->text('skills')->nullable(); }
    });
}

    /**     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
