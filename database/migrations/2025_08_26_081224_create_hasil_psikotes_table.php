<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_psikotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->foreignId('pelamar_tahapan_id')->constrained('pelamar_tahapan')->onDelete('cascade');
            $table->foreignId('sesi_psikotes_id')->constrained('sesi_psikotes')->onDelete('cascade');
            $table->foreignId('konfigurasi_id')->constrained('konfigurasi_psikotes')->onDelete('cascade');
            $table->integer('total_soal_ditampilkan');
            $table->integer('soal_dijawab');
            $table->integer('soal_kognitif_ditampilkan');
            $table->integer('soal_kepribadian_ditampilkan');
            $table->integer('soal_minat_ditampilkan')->default(0);
            $table->integer('soal_kognitif_benar')->nullable();
            $table->integer('soal_kognitif_salah')->nullable();
            $table->integer('soal_minat_skor')->default(0);
            $table->decimal('nilai_kognitif', 8, 2)->nullable();
            $table->decimal('nilai_kepribadian', 8, 2)->nullable();
            $table->decimal('nilai_minat', 8, 2)->nullable();
            $table->decimal('nilai_akhir', 8, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('rekomendasi', [
                'sangat_baik',
                'baik',
                'cukup',
                'kurang',
                'tidak_lulus'
            ])->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('durasi_pengerjaan_detik')->nullable();
            $table->boolean('lulus')->default(false);
            $table->json('detail_analisis')->nullable();
            $table->json('konfigurasi_penilaian')->nullable();
            $table->timestamps();

            $table->index(['pelamar_id', 'lowongan_id']);
            $table->index('lulus');
            $table->index('nilai_akhir');
            $table->index('rekomendasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_psikotes');
    }
};
