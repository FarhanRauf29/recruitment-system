<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PelamarTahapan extends Model
{
    use HasFactory;

    protected $table = 'pelamar_tahapan';

    protected $fillable = [
        'pelamar_id',
        'lowongan_id',
        'tahapan_id',
        'status',
        'catatan',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public static function statusOptions()
    {
        return [
            'menunggu' => 'Menunggu',
            'diproses' => 'Diproses',
            'lolos' => 'Lolos',
            'gagal' => 'Gagal',
        ];
    }

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class);
    }

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class);
    }

    public function tahapan(): BelongsTo
    {
        return $this->belongsTo(TahapanRekrutmen::class, 'tahapan_id');
    }

    public function berkas(): HasMany
    {
        return $this->hasMany(Berkas::class, 'pelamar_tahapan_id');
    }

    public function isLolos()
    {
        return $this->status === 'lolos';
    }

    public function isGagal()
    {
        return $this->status === 'gagal';
    }

    public function isDiproses()
    {
        return $this->status === 'diproses';
    }

    public function isMenunggu()
    {
        return $this->status === 'menunggu';
    }

    public function isAccessible()
    {
        return $this->status === 'diproses';
    }

    public function scopeOrdered($query)
    {
        return $query->join('tahapan_rekrutmen', 'pelamar_tahapan.tahapan_id', '=', 'tahapan_rekrutmen.id')
            ->orderBy('tahapan_rekrutmen.urutan')
            ->select('pelamar_tahapan.*');
    }

    public function getStatusTextAttribute()
    {
        $statuses = self::statusOptions();
        return $statuses[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusText()
    {
        $statuses = self::statusOptions();
        return $statuses[$this->status] ?? ucfirst($this->status);
    }
}
