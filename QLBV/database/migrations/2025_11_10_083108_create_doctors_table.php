<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tạo bảng doctors với các trường:
     * - id: khóa chính
     * - name: tên bác sĩ (string, tối đa 100)
     * - code: mã bác sĩ (string, tối đa 10, unique)
     * - specialty: chuyên môn (string, tối đa 100, nullable)
     * - department_id: khóa ngoại đến bảng departments (xóa khoa thì bác sĩ bị xóa theo - cascade)
     * - phone: số điện thoại (string, nullable)
     * - email: email bác sĩ (string, nullable)
     * - timestamps: created_at & updated_at
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 10)->unique();
            $table->string('specialty', 100)->nullable();
            $table->foreignId('department_id')
                  ->constrained('departments')
                  ->onDelete('cascade');
            $table->string('phone', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * Xóa bảng doctors nếu tồn tại.
     *
     * @return void
     */

    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
