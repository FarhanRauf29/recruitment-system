<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pelamar_tahapan', function (Blueprint $table) {
            $table->enum('spk_status', ['pending', 'proses', 'approved', 'rejected', 'offering', 'hired', 'hired_completed', 'onboarding_completed'])->default('pending');
            $table->decimal('nilai_akhir_spk', 10, 4)->nullable();
            $table->string('metode_terbaik', 50)->nullable();
            $table->text('catatan_spk')->nullable();
            $table->datetime('tanggal_approval')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('pelamar_tahapan', function (Blueprint $table) {
            $table->dropColumn(['spk_status', 'nilai_akhir_spk', 'metode_terbaik', 'catatan_spk', 'tanggal_approval', 'approved_by']);
        });
    }
};
