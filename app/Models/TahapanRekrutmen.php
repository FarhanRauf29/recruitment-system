<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahapanRekrutmen extends Model
{
    use HasFactory;

    protected $table = 'tahapan_rekrutmen';

    protected $fillable = [
        'nama_tahapan',
        'deskripsi',
        'urutan',
        'status',
    ];

    protected $casts = [
        'urutan' => 'integer',
    ];

    public static function statusOptions()
    {
        return [
            'aktif' => 'Aktif',
            'tidak_aktif' => 'Tidak Aktif',
        ];
    }

    public static function tahapanStatusOptions()
    {
        return [
            'menunggu' => 'Menunggu',
            'diproses' => 'Sedang Diproses',
            'lolos' => 'Lolos',
            'gagal' => 'Tidak Lolos',
            'belum_dimulai' => 'Belum Dimulai',
        ];
    }

    public function getStatusTextAttribute()
    {
        return self::tahapanStatusOptions()[$this->status] ?? ucfirst($this->status);
    }

    public function pelamarTahapans(): HasMany
    {
        return $this->hasMany(PelamarTahapan::class, 'tahapan_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif')->orderBy('urutan');
    }
}
