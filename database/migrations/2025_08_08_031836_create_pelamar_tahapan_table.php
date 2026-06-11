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
        Schema::create('pelamar_tahapan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar');
            $table->foreignId('lowongan_id')->constrained('lowongan');
            $table->foreignId('tahapan_id')->constrained('tahapan_rekrutmen');
            $table->enum('status', [
                'belum_dimulai',
                'menunggu',
                'diproses',
                'lolos',
                'gagal'
            ])->default('belum_dimulai');
            $table->text('catatan')->nullable();
            $table->dateTime('tanggal_mulai')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->unique(['pelamar_id', 'lowongan_id', 'tahapan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelamar_tahapan');
    }
};
