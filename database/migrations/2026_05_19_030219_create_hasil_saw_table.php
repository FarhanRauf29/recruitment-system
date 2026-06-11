<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hasil_saw', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->decimal('nilai_saw', 10, 4);
            $table->integer('ranking');
            $table->enum('status_rekomendasi', ['rekomendasi', 'tidak_rekomendasi', 'pending'])->default('pending');
            $table->json('detail_perhitungan')->nullable();
            $table->timestamps();

            $table->unique(['pelamar_id', 'lowongan_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hasil_saw');
    }
};
