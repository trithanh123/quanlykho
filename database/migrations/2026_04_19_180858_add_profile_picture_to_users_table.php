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
        // Thêm cột profile_picture
        $table->string('profile_picture')->nullable();
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        // Xóa cột nếu lùi (rollback)
        $table->dropColumn('profile_picture');
    });
}
};
