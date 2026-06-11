<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatPengalaman extends Model
{
    use HasFactory;

    protected $table = 'riwayat_pengalamans';

    protected $fillable = [
        'pelamar_id', 'perusahaan', 'jabatan', 'lokasi', 'tahun_masuk', 'tahun_keluar', 'is_current', 'deskripsi'
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class);
    }
}