<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wawancara', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_tahapan_id')->constrained('pelamar_tahapan')->onDelete('cascade');
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->datetime('jadwal_wawancara');
            $table->enum('jenis_wawancara', ['online', 'offline'])->default('online');
            $table->string('link_wawancara')->nullable();
            $table->string('lokasi_wawancara')->nullable();
            $table->text('alamat_lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['dijadwalkan', 'dikonfirmasi', 'hadir', 'tidak_hadir', 'selesai', 'verifikasi', 'lolos', 'gagal'])->default('dijadwalkan');
            $table->integer('nilai_teknis')->nullable();
            $table->integer('nilai_komunikasi')->nullable();
            $table->integer('nilai_pengalaman')->nullable();
            $table->integer('nilai_attitude')->nullable();
            $table->integer('nilai_total')->nullable();
            $table->text('catatan_hr')->nullable();
            $table->text('hasil_wawancara_file')->nullable();
            $table->string('dokumen_pendukung_file')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->datetime('tanggal_verifikasi')->nullable();
            $table->string('pewawancara')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wawancara');
    }
};
