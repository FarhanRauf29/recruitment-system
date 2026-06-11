<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_pendidikans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelamar_id');
            $table->string('tingkat');
            $table->string('nama_instansi');
            $table->string('jurusan')->nullable();
            $table->string('tahun_masuk')->nullable();
            $table->string('tahun_lulus');
            $table->string('nilai')->nullable();
            $table->timestamps();
            $table->foreign('pelamar_id')->references('id')->on('pelamar')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_pendidikans');
    }
};