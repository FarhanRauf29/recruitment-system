<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatBahasa extends Model
{
    use HasFactory;

    protected $table = 'riwayat_bahasas';

    protected $fillable = [
        'pelamar_id', 'nama_bahasa', 'tingkat', 'sertifikat'
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class);
    }
}