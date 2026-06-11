<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wawancara extends Model
{
    use HasFactory;

    protected $table = 'wawancara';

    protected $fillable = [
        'pelamar_tahapan_id',
        'pelamar_id',
        'lowongan_id',
        'jadwal_wawancara',
        'jenis_wawancara',
        'link_wawancara',
        'lokasi_wawancara',
        'alamat_lokasi',
        'keterangan',
        'status',
        'nilai_teknis',
        'nilai_komunikasi',
        'nilai_pengalaman',
        'nilai_attitude',
        'nilai_total',
        'catatan_hr',
        'hasil_wawancara_file',
        'dokumen_pendukung_file',
        'catatan_admin',
        'tanggal_verifikasi',
        'pewawancara',
    ];

    protected $casts = [
        'jadwal_wawancara' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
    ];

    public function pelamarTahapan(): BelongsTo
    {
        return $this->belongsTo(PelamarTahapan::class, 'pelamar_tahapan_id');
    }

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class);
    }

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class);
    }

    protected static function booted()
    {
        static::saved(function ($wawancara) {
            $wawancara->updateTahapanStatus();
        });
    }

    public function updateTahapanStatus()
    {
        $pelamarTahapan = PelamarTahapan::where('id', $this->pelamar_tahapan_id)->first();

        if ($pelamarTahapan && $pelamarTahapan->status === 'diproses') {
            if ($this->status === 'lolos') {
                $pelamarTahapan->status = 'lolos';
                $pelamarTahapan->catatan = $this->catatan_admin ?: 'Lolos wawancara';
                $pelamarTahapan->tanggal_selesai = now();
                $pelamarTahapan->save();
            } elseif ($this->status === 'gagal') {
                $pelamarTahapan->status = 'gagal';
                $pelamarTahapan->catatan = $this->catatan_admin ?: 'Gagal wawancara';
                $pelamarTahapan->tanggal_selesai = now();
                $pelamarTahapan->save();
            }
        }
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'dijadwalkan' => 'warning',
            'dikonfirmasi' => 'info',
            'hadir' => 'primary',
            'tidak_hadir' => 'danger',
            'selesai' => 'secondary',
            'verifikasi' => 'dark',
            'lolos' => 'success',
            'gagal' => 'danger',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusTextAttribute()
    {
        $texts = [
            'dijadwalkan' => 'Dijadwalkan',
            'dikonfirmasi' => 'Dikonfirmasi',
            'hadir' => 'Hadir',
            'tidak_hadir' => 'Tidak Hadir',
            'selesai' => 'Selesai',
            'verifikasi' => 'Diverifikasi',
            'lolos' => 'Lolos',
            'gagal' => 'Tidak Lolos',
        ];
        return $texts[$this->status] ?? ucfirst($this->status);
    }

    public function hitungNilaiTotal()
    {
        $nilai = [
            $this->nilai_teknis,
            $this->nilai_komunikasi,
            $this->nilai_pengalaman,
            $this->nilai_attitude
        ];
        $nilaiValid = array_filter($nilai, fn($n) => !is_null($n));
        if (count($nilaiValid) > 0) {
            $this->nilai_total = round(array_sum($nilaiValid) / count($nilaiValid));
            $this->save();
        }
        return $this->nilai_total;
    }
}
