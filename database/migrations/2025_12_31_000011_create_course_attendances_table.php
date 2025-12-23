<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_attendances', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('course_session_id')->constrained('course_sessions')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('status')->default('hadir'); // hadir/telat/izin/absen
            $table->text('reason')->nullable();
            $table->string('proof_url')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_attendances');
    }
};
