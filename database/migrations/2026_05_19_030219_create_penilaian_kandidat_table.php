<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penilaian_kandidat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria')->onDelete('cascade');
            $table->decimal('nilai', 10, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['pelamar_id', 'lowongan_id', 'kriteria_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('penilaian_kandidat');
    }
};
