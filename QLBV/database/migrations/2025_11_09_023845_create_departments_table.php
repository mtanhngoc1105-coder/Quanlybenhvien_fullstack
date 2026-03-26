<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *  
     * Tạo bảng departments gồm:
     * - id: khóa chính
     * - name: tên khoa (string, tối đa 100)
     * - code: mã khoa (string, tối đa 10, unique)
     * - parent_id: khoa cha (nullable, khóa ngoại tới chính bảng departments)
     * - description: mô tả (nullable)
     * - timestamps: created_at & updated_at
     *
     * @return void
     */
     
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 10)->unique();
            $table->foreignId("parent_id")->nullable()->constrained('departments')->onDelete('set null');   
            $table->string('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Xóa bảng departments nếu tồn tại.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
