<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Update data yang bernilai 'pending' menjadi 'cadangan' terlebih dahulu agar tidak terpotong (truncate) saat ENUM diubah
        DB::table('hasil_saw')->where('status_rekomendasi', 'pending')->update(['status_rekomendasi' => 'cadangan']);
        DB::table('hasil_topsis')->where('status_rekomendasi', 'pending')->update(['status_rekomendasi' => 'cadangan']);

        // 2. Ubah tipe ENUM pada tabel hasil_saw
        DB::statement("ALTER TABLE hasil_saw MODIFY COLUMN status_rekomendasi ENUM('rekomendasi', 'cadangan', 'tidak_rekomendasi') NOT NULL DEFAULT 'cadangan'");

        // 3. Ubah tipe ENUM pada tabel hasil_topsis
        DB::statement("ALTER TABLE hasil_topsis MODIFY COLUMN status_rekomendasi ENUM('rekomendasi', 'cadangan', 'tidak_rekomendasi') NOT NULL DEFAULT 'cadangan'");
    }

    public function down()
    {
        // Kembalikan data 'cadangan' ke 'pending' jika migration di-rollback
        DB::table('hasil_saw')->where('status_rekomendasi', 'cadangan')->update(['status_rekomendasi' => 'pending']);
        DB::table('hasil_topsis')->where('status_rekomendasi', 'cadangan')->update(['status_rekomendasi' => 'pending']);

        // Kembalikan tipe ENUM semula
        DB::statement("ALTER TABLE hasil_saw MODIFY COLUMN status_rekomendasi ENUM('rekomendasi', 'tidak_rekomendasi', 'pending') NOT NULL DEFAULT 'pending'");
        DB::statement("ALTER TABLE hasil_topsis MODIFY COLUMN status_rekomendasi ENUM('rekomendasi', 'tidak_rekomendasi', 'pending') NOT NULL DEFAULT 'pending'");
    }
};
