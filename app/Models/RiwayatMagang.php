<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatMagang extends Model
{
    use HasFactory;

    protected $table = 'riwayat_magangs';

    protected $fillable = [
        'pelamar_id', 'perusahaan', 'posisi', 'lokasi', 'durasi', 'tanggal_mulai', 'tanggal_selesai', 'deskripsi'
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class);
    }
}