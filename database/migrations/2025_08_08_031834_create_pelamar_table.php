<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelamar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nama_depan', 50)->nullable();
            $table->string('nama_belakang', 50)->nullable();
            $table->string('nama_lengkap', 100)->nullable();
            $table->string('email', 100)->unique();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('tempat_lahir', 50)->nullable();
            $table->string('kewarganegaraan', 30)->nullable()->default('WNI');
            $table->string('nomor_handphone', 15)->nullable();
            $table->string('gaji_diharapkan', 20)->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('provinsi', 50)->nullable();
            $table->string('pendidikan_terakhir', 50)->nullable();
            $table->string('kab_kota', 50)->nullable();
            $table->string('kecamatan', 50)->nullable();
            $table->string('desa_kelurahan', 50)->nullable();
            $table->string('instansi', 100)->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->integer('umur')->nullable();
            $table->string('nomor_identitas', 20)->nullable()->unique();
            $table->string('nomor_whatsapp', 15)->nullable();
            $table->string('foto_profil', 255)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('nomor_identitas');
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelamar');
    }
};
