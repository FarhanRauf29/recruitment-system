<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hiring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelamar_id')->constrained('pelamar')->onDelete('cascade');
            $table->foreignId('lowongan_id')->constrained('lowongan')->onDelete('cascade');
            $table->foreignId('offering_id')->constrained('offering_penawaran')->onDelete('cascade');
            $table->enum('status', ['pending', 'hired', 'cancelled', 'probation'])->default('pending');
            $table->datetime('tanggal_hired')->nullable();
            $table->datetime('tanggal_probation_start')->nullable();
            $table->datetime('tanggal_probation_end')->nullable();
            $table->enum('probation_result', ['pending', 'passed', 'failed', 'extended'])->default('pending');
            $table->text('catatan')->nullable();
            $table->foreignId('hired_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hiring');
    }
};
