<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berkas extends Model
{
    use HasFactory;

    protected $table = 'berkas';

    protected $fillable = [
        'pelamar_id',
        'pelamar_tahapan_id',
        'cv_file',
        'surat_lamaran_file',
        'ijazah_file',
        'transkrip_nilai_file',
        'ktp_file',
        'pas_foto_file',
        'sertifikat_file',
        'skck_file',
        'surat_keterangan_sehat_file',
        'portofolio_file',
        'kartu_keluarga_file',
        'dokumen_lain_file',
        'status_verifikasi',
        'catatan_admin',
        'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_verifikasi' => 'datetime',
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }

    public function pelamarTahapan(): BelongsTo
    {
        return $this->belongsTo(PelamarTahapan::class, 'pelamar_tahapan_id');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Pending' => 'warning',
            'Diterima' => 'success',
            'Ditolak' => 'danger',
        ];
        return $badges[$this->status_verifikasi] ?? 'secondary';
    }

    public function isVerifikasi()
    {
        return $this->status_verifikasi !== 'Pending';
    }
}
