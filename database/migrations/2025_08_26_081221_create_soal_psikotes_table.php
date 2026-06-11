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
        Schema::create('soal_psikotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_psikotes')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->enum('jenis', ['kognitif', 'kepribadian', 'minat'])->default('kognitif');
            $table->json('opsi_jawaban');
            $table->string('kunci_jawaban', 10)->nullable();
            $table->json('bobot_opsi')->nullable();
            $table->integer('bobot_nilai')->default(1);
            $table->integer('tingkat_kesulitan')->default(1)->comment('1-5 (mudah-sulit)');
            $table->integer('waktu_per_soal_detik')->default(60);
            $table->boolean('aktif')->default(true);
            $table->text('penjelasan')->nullable();
            $table->string('kode_soal', 50)->unique();
            $table->string('gambar')->nullable(); // Path gambar jika ada
            $table->timestamps();

            // Index untuk performa
            $table->index(['jenis', 'aktif']);
            $table->index('tingkat_kesulitan');
            $table->index('kategori_id');
            $table->index('kode_soal');
            $table->index('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_psikotes');
    }
};
