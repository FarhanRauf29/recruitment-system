<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sesi_psikotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->foreignId('pelamar_tahapan_id')->constrained('pelamar_tahapan')->onDelete('cascade');
            $table->foreignId('konfigurasi_id')->constrained('konfigurasi_psikotes')->onDelete('cascade');
            $table->string('nama_sesi', 150)->nullable();
            $table->integer('current_soal_index')->default(0);
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamp('waktu_pause')->nullable();
            $table->timestamp('waktu_mulai_pengerjaan')->nullable();
            $table->timestamp('waktu_terakhir_aktivitas')->nullable();
            $table->integer('durasi_menit')->default(60);
            $table->integer('durasi_terpakai_detik')->default(0);
            $table->enum('status', [
                'belum_dimulai',
                'sedang_berjalan',
                'dijeda',
                'selesai',
                'gagal'
            ])->default('belum_dimulai');
            $table->boolean('selesai')->default(false);
            $table->json('soal_terpilih')->nullable();
            $table->json('jawaban_terisi')->nullable();
            $table->string('token_sesi', 100)->unique();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->integer('jumlah_soal_ditampilkan')->default(0);
            $table->timestamps();
            $table->index(['pelamar_id', 'lowongan_id']);
            $table->index('status');
            $table->index('selesai');
            $table->index('token_sesi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sesi_psikotes');
    }
};
