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
        Schema::create('kategori_psikotes', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 100);
            $table->string('kode_kategori', 20)->unique();
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', ['kognitif', 'kepribadian', 'minat'])->default('kognitif');
            $table->boolean('aktif')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();

            // Index untuk performa
            $table->index(['aktif', 'jenis']);
            $table->index('kode_kategori');
            $table->index('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_psikotes');
    }
};
