<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanPsikotes extends Model
{
    use HasFactory;

    protected $table = 'jawaban_psikotes';

    protected $fillable = [
        'pelamar_id',
        'soal_id',
        'sesi_psikotes_id',
        'pelamar_tahapan_id',
        'jawaban',
        'benar',
        'skor',
        'skor_kepribadian',
        'skor_minat_bakat',
        'waktu_menjawab_detik',
        'waktu_jawab'
    ];

    protected $casts = [
        'benar' => 'boolean',
        'skor' => 'integer',
        'skor_kepribadian' => 'integer',
        'skor_minat_bakat' => 'integer',
        'waktu_jawab' => 'datetime'
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }

    public function soalPsikotes(): BelongsTo
    {
        return $this->belongsTo(SoalPsikotes::class, 'soal_id');
    }

    public function sesiPsikotes(): BelongsTo
    {
        return $this->belongsTo(SesiPsikotes::class, 'sesi_psikotes_id');
    }

    public function pelamarTahapan(): BelongsTo
    {
        return $this->belongsTo(PelamarTahapan::class, 'pelamar_tahapan_id');
    }
}
