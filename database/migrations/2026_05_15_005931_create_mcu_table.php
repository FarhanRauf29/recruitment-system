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
            $table->string('lokasi_mcu', 150)->default('Klinik Utama RSUP Dr. M. Djamil Padang');
            $table->text('alamat_lokasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['dijadwalkan', 'dikonfirmasi', 'hadir', 'tidak_hadir', 'verifikasi', 'lolos', 'gagal'])->default('dijadwalkan');
            $table->text('catatan_admin')->nullable();
            $table->datetime('tanggal_verifikasi')->nullable();
            $table->string('dokter_pemeriksa', 100)->nullable();
            $table->string('nama_rs', 150)->nullable();
            $table->enum('kondisi_kesehatan', ['Sangat Sehat', 'Sehat', 'Cukup Sehat', 'Kurang Sehat', 'Tidak Sehat'])->nullable();
            $table->float('nilai_mcu')->nullable();
            $table->float('tinggi_badan')->nullable();
            $table->float('berat_badan')->nullable();
            $table->float('bmi')->nullable();
            $table->string('tekanan_darah', 20)->nullable();
            $table->string('visus_mata', 20)->nullable();
            $table->enum('buta_warna', ['Tidak', 'Parsial', 'Total'])->nullable();
            $table->enum('hasil_narkoba', ['Negatif', 'Positif'])->nullable();
            $table->string('riwayat_penyakit', 100)->nullable();
            $table->string('surat_mcu_file', 255)->nullable();
            $table->string('surat_panggilan_file', 255)->nullable();
            $table->string('dokumen_pendukung_file', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mcu');
    }
};
