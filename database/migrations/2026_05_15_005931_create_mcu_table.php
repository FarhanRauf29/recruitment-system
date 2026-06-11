<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mcu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_tahapan_id')->constrained('pelamar_tahapan')->onDelete('cascade');
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->datetime('jadwal_mcu');
            $table->string('lokasi_mcu')->default('Klinik Utama RSUP Dr. M. Djamil Padang');
            $table->text('alamat_lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['dijadwalkan', 'dikonfirmasi', 'hadir', 'tidak_hadir', 'hasil_diupload', 'verifikasi', 'lolos', 'gagal'])->default('dijadwalkan');
            $table->string('hasil_mcu_file')->nullable();
            $table->text('hasil_mcu_keterangan')->nullable();
            $table->string('surat_mcu_file')->nullable();
            $table->string('dokumen_pendukung_file')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->datetime('tanggal_verifikasi')->nullable();
            $table->string('dokter_pemeriksa')->nullable();
            $table->string('nama_rs')->nullable();
            $table->enum('kondisi_kesehatan', ['Sangat Sehat', 'Sehat', 'Cukup', 'Kurang', 'Tidak Lulus'])->nullable();
            $table->integer('nilai_mcu')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mcu');
    }
};
