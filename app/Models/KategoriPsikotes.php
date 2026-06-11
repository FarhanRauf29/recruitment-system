<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriPsikotes extends Model
{
    use HasFactory;

    protected $table = 'kategori_psikotes';

    protected $fillable = [
        'nama_kategori',
        'kode_kategori',
        'deskripsi',
        'jenis',
        'aktif',
        'urutan'
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relasi
    public function soalPsikotes(): HasMany
    {
        return $this->hasMany(SoalPsikotes::class, 'kategori_id');
    }

    // Scope
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis', $jenis);
    }
}
