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
        Schema::create('konfigurasi_psikotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->integer('jumlah_soal_total')->default(27);
            $table->integer('jumlah_soal_kognitif')->default(7);
            $table->integer('jumlah_soal_kepribadian')->default(20);
            $table->integer('jumlah_soal_minat')->default(0);
            $table->integer('durasi_menit')->default(60);
            $table->boolean('acak_soal')->default(true);
            $table->boolean('acak_opsi')->default(true);
            $table->integer('batas_nilai_kelulusan')->default(70);
            $table->json('kategori_kognitif')->nullable(); // Array ID kategori
            $table->json('kategori_kepribadian')->nullable(); // Array ID kategori
            $table->json('kategori_minat')->nullable(); // Array ID kategori
            $table->boolean('aktif')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Index untuk performa
            $table->index('lowongan_id');
            $table->index('aktif');
            $table->unique('lowongan_id'); // Satu konfigurasi per lowongan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konfigurasi_psikotes');
    }
};
