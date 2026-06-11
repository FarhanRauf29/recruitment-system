<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KonfigurasiPsikotes extends Model
{
    use HasFactory;

    protected $table = 'konfigurasi_psikotes';

    protected $fillable = [
        'lowongan_id',
        'jumlah_soal_total',
        'jumlah_soal_kognitif',
        'jumlah_soal_kepribadian',
        'jumlah_soal_minat',
        'durasi_menit',
        'acak_soal',
        'acak_opsi',
        'batas_nilai_kelulusan',
        'kategori_kognitif',
        'kategori_kepribadian',
        'kategori_minat',
        'aktif',
        'keterangan'
    ];

    protected $casts = [
        'kategori_kognitif' => 'array',
        'kategori_kepribadian' => 'array',
        'kategori_minat' => 'array',
        'acak_soal' => 'boolean',
        'acak_opsi' => 'boolean',
        'aktif' => 'boolean',
        'jumlah_soal_total' => 'integer',
        'jumlah_soal_kognitif' => 'integer',
        'jumlah_soal_kepribadian' => 'integer',
        'jumlah_soal_minat' => 'integer',
        'durasi_menit' => 'integer',
        'batas_nilai_kelulusan' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi
    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

    public function sesiPsikotes(): HasMany
    {
        return $this->hasMany(SesiPsikotes::class, 'konfigurasi_id');
    }

    public function hasilPsikotes(): HasMany
    {
        return $this->hasMany(HasilPsikotes::class, 'konfigurasi_id');
    }

    // Scope
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeByLowongan($query, $lowonganId)
    {
        return $query->where('lowongan_id', $lowonganId);
    }
}
