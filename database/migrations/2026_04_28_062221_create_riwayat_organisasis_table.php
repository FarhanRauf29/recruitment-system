<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_organisasis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pelamar_id');
            $table->string('nama_organisasi');
            $table->string('jabatan');
            $table->string('tahun');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            $table->foreign('pelamar_id')->references('id')->on('pelamar')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_organisasis');
    }
};