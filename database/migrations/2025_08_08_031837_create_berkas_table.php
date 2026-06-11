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
        Schema::create('berkas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('pelamar_tahapan_id')->nullable()->constrained('pelamar_tahapan')->onDelete('set null');
            $table->string('cv_file')->nullable();
            $table->string('surat_lamaran_file')->nullable();
            $table->string('ijazah_file')->nullable();
            $table->string('transkrip_nilai_file')->nullable();
            $table->string('ktp_file')->nullable();
            $table->string('pas_foto_file')->nullable();
            $table->string('sertifikat_file')->nullable();
            $table->string('skck_file')->nullable();
            $table->string('surat_keterangan_sehat_file')->nullable();
            $table->string('portofolio_file')->nullable();
            $table->string('kartu_keluarga_file')->nullable();
            $table->string('dokumen_lain_file')->nullable();

            $table->enum('status_verifikasi', ['Pending', 'Diterima', 'Ditolak'])->default('Pending');

            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('berkas', function (Blueprint $table) {
            $table->dropForeign(['pelamar_tahapan_id']);
            $table->dropColumn('pelamar_tahapan_id');
        });
    }
};
