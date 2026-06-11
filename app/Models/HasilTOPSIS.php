<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilTOPSIS extends Model
{
    use HasFactory;

    protected $table = 'hasil_topsis';

    protected $fillable = [
        'pelamar_id',
        'lowongan_id',
        'nilai_topsis',
        'ranking',
        'status_rekomendasi',
        'detail_perhitungan'
    ];

    protected $casts = [
        'nilai_topsis' => 'decimal:4',
        'detail_perhitungan' => 'array',
    ];

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class);
    }

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class);
    }
}
