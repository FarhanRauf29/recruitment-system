<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatOrganisasi extends Model
{
    use HasFactory;

    protected $table = 'riwayat_organisasis';

    protected $fillable = [
        'pelamar_id', 'nama_organisasi', 'jabatan', 'tahun', 'deskripsi'
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class);
    }
}