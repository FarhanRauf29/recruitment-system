<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('offering_penawaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->string('posisi', 100);
            $table->decimal('gaji', 15, 2);
            $table->decimal('gaji_counter', 15, 2)->nullable();
            $table->enum('tipe_kontrak', ['tetap', 'kontrak', 'probation'])->default('kontrak');
            $table->string('durasi_kontrak', 50)->nullable();
            $table->integer('masa_probation')->default(3);
            $table->date('tanggal_mulai_kerja');
            $table->text('catatan_hr')->nullable();
            $table->date('batas_respon')->nullable();
            $table->enum('status', ['draft', 'pending_approval', 'sent', 'counter', 'accepted', 'rejected', 'expired'])->default('draft');
            $table->text('alasan_penolakan')->nullable();
            $table->text('alasan_counter')->nullable();
            $table->datetime('tanggal_dikirim')->nullable();
            $table->datetime('tanggal_diterima')->nullable();
            $table->datetime('tanggal_ditolak')->nullable();
            $table->datetime('tanggal_counter')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->datetime('tanggal_approval')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offering_penawaran');
    }
};
