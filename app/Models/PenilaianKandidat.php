<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenilaianKandidat extends Model
{
    use HasFactory;

    protected $table = 'penilaian_kandidat';

    protected $fillable = [
        'pelamar_id',
        'lowongan_id',
        'kriteria_id',
        'nilai',
        'catatan'
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    public function pelamar()
    {
        return $this->belongsTo(Pelamar::class);
    }

    public function lowongan()
    {
        return $this->belongsTo(Lowongan::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
