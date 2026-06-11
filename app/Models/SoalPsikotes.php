<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SoalPsikotes extends Model
{
    use HasFactory;

    protected $table = 'soal_psikotes';

    protected $fillable = [
        'kategori_id',
        'pertanyaan',
        'jenis',
        'opsi_jawaban',
        'kunci_jawaban',
        'bobot_opsi',
        'bobot_nilai',
        'tingkat_kesulitan',
        'waktu_per_soal_detik',
        'aktif',
        'penjelasan',
        'kode_soal',
        'gambar'
    ];

    protected $casts = [
        'opsi_jawaban' => 'array',
        'bobot_opsi' => 'array',
        'aktif' => 'boolean',
        'bobot_nilai' => 'integer',
        'tingkat_kesulitan' => 'integer',
        'waktu_per_soal_detik' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriPsikotes::class, 'kategori_id');
    }

    public function jawabanPsikotes(): HasMany
    {
        return $this->hasMany(JawabanPsikotes::class, 'soal_id');
    }

    public function aktivitasPsikotes(): HasMany
    {
        return $this->hasMany(AktivitasPsikotes::class, 'soal_id');
    }

    public function getBobotOpsiAttribute(): array
    {
        $defaultBobot = [
            'A' => 5,
            'B' => 4,
            'C' => 3,
            'D' => 2,
            'E' => 1
        ];

        $bobotOpsi = $this->attributes['bobot_opsi'] ?? null;

        if ($bobotOpsi && is_array($bobotOpsi)) {
            return array_merge($defaultBobot, $bobotOpsi);
        }

        if ($bobotOpsi && is_string($bobotOpsi)) {
            $decoded = json_decode($bobotOpsi, true);
            if (is_array($decoded)) {
                $this->attributes['bobot_opsi'] = $decoded;
                return array_merge($defaultBobot, $decoded);
            }
        }

        return $defaultBobot;
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }
}
