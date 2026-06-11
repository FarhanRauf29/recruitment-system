<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_psikotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('soal_id')->constrained('soal_psikotes')->onDelete('cascade');
            $table->foreignId('sesi_psikotes_id')->constrained('sesi_psikotes')->onDelete('cascade');
            $table->foreignId('pelamar_tahapan_id')->constrained('pelamar_tahapan')->onDelete('cascade');
            $table->string('jawaban', 10);
            $table->boolean('benar')->nullable();
            $table->integer('skor')->nullable()->comment('Skor untuk soal kognitif');
            $table->integer('skor_kepribadian')->nullable();
            $table->integer('skor_minat_bakat')->nullable();
            $table->integer('waktu_menjawab_detik')->nullable();
            $table->timestamp('waktu_jawab')->useCurrent();
            $table->timestamps();

            $table->unique(['pelamar_id', 'soal_id', 'sesi_psikotes_id'], 'unique_jawaban_per_sesi');
            $table->index(['sesi_psikotes_id', 'soal_id']);
            $table->index(['pelamar_id', 'sesi_psikotes_id']);
            $table->index(['soal_id', 'benar']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_psikotes');
    }
};
