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
        Schema::create('aktivitas_psikotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('sesi_psikotes_id')->constrained('sesi_psikotes')->onDelete('cascade');
            $table->foreignId('pelamar_tahapan_id')->constrained('pelamar_tahapan')->onDelete('cascade');
            $table->enum('tipe_aktivitas', [
                'mulai_tes',
                'jawab_soal',
                'pause_tes',
                'lanjut_tes',
                'selesai_tes',
                'lihat_hasil'
            ]);
            $table->foreignId('soal_id')->nullable()->constrained('soal_psikotes')->onDelete('cascade');
            $table->string('jawaban', 10)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable(); // Data tambahan
            $table->timestamps();

            // Index untuk performa
            $table->index(['pelamar_id', 'sesi_psikotes_id']);
            $table->index('tipe_aktivitas');
            $table->index('soal_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_psikotes');
    }
};
