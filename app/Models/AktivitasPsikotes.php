<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AktivitasPsikotes extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_psikotes';

    protected $fillable = [
        'pelamar_id',
        'sesi_psikotes_id',
        'pelamar_tahapan_id',
        'tipe_aktivitas',
        'soal_id',
        'jawaban',
        'ip_address',
        'user_agent',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi
    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }

    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiPsikotes::class, 'sesi_psikotes_id');
    }

    public function pelamarTahapan(): BelongsTo
    {
        return $this->belongsTo(PelamarTahapan::class, 'pelamar_tahapan_id');
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(SoalPsikotes::class, 'soal_id');
    }
}
