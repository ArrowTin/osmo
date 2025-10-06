<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        // ==============================
        // Tabel percobaan ujian (attempts)
        // ==============================
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('score')->nullable(); // nilai akhir (0–100)
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            // index gabungan untuk mempermudah query
            $table->index(['user_id', 'exam_id']);
        });

        // ==============================
        // Tabel jawaban per attempt
        // ==============================
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('exam_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->string('answer')->nullable();      // jawaban user (opsi_a / teks)
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

            $table->index(['attempt_id', 'question_id']);
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_answers');
        Schema::dropIfExists('exam_attempts');
    }
};
