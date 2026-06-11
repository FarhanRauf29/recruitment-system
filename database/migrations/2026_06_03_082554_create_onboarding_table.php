<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('onboarding', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->foreignId('hiring_id')->constrained('hiring')->onDelete('cascade');
            $table->string('divisi');
            $table->string('atasan_langsung');
            $table->string('lokasi_kerja');
            $table->date('tanggal_masuk');
            $table->string('nip')->unique()->nullable();
            $table->string('email_kantor')->nullable();
            $table->string('no_telepon_kantor')->nullable();
            $table->text('catatan')->nullable();
            $table->json('dokumen_yang_disiapkan')->nullable();
            $table->json('checklist_onboarding')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->datetime('tanggal_selesai')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('onboarding');
    }
};
