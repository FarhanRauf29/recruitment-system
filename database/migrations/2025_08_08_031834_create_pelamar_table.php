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
            $table->string('nama_depan')->nullable();
            $table->string('nama_belakang')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('email')->unique();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->string('kewarganegaraan', 100)->nullable();
            $table->string('nomor_handphone', 15)->nullable();
            $table->string('gaji_diharapkan')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('kab_kota', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('desa_kelurahan', 100)->nullable();
            $table->string('jurusan')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('nomor_identitas', 20)->nullable()->unique();
            $table->string('nomor_whatsapp', 15)->nullable();
            $table->string('foto_profil')->nullable();
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
