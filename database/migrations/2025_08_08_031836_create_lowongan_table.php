<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lowongan', function (Blueprint $table) {
            $table->id();
            $table->string('judul_lowongan', 150);
            $table->text('deskripsi');
            $table->text('persyaratan');
            $table->date('tanggal_buka');
            $table->date('tanggal_tutup');
            $table->enum('status', ['buka', 'tutup'])->default('buka');
            $table->integer('slot')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lowongan');
    }
};
