<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatKeahlian extends Model
{
    use HasFactory;

    protected $table = 'riwayat_keahlians';

    protected $fillable = [
        'pelamar_id', 'nama_keahlian', 'tingkat', 'sertifikat'
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class);
    }
}