<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Pelamar extends Model
{
    use HasFactory;

    protected $table = 'pelamar';

    protected $fillable = [
        'user_id',
        'nama_depan',
        'nama_belakang',
        'nama_lengkap',
        'email',
        'jenis_kelamin',
        'tempat_lahir',
        'kewarganegaraan',
        'nomor_handphone',
        'gaji_diharapkan',
        'alamat_domisili',
        'provinsi',
        'pendidikan_terakhir',
        'kab_kota',
        'kecamatan',
        'desa_kelurahan',
        'jurusan',
        'tanggal_lahir',
        'nomor_identitas',
        'nomor_whatsapp',
        'foto_profil',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public static function pendidikanOptions()
    {
        return [
            'SD' => 'SD',
            'SMP' => 'SMP',
            'SMA/SMK' => 'SMA/SMK',
            'Diploma (D3)' => 'Diploma (D3)',
            'Diploma (D4)' => 'Diploma (D4)',
            'Sarjana (S1)' => 'Sarjana (S1)',
            'Magister (S2)' => 'Magister (S2)',
        ];
    }

    public static function jenisKelaminOptions()
    {
        return [
            'Laki-laki' => 'Laki-laki',
            'Perempuan' => 'Perempuan',
        ];
    }

    public static function kewarganegaraanOptions()
    {
        return [
            'WNI' => 'WNI',
            'WNA' => 'WNA',
        ];
    }

    protected static function booted()
    {
        static::creating(function ($pelamar) {
            if ($pelamar->nama_depan && $pelamar->nama_belakang) {
                $pelamar->nama_lengkap = $pelamar->nama_depan . ' ' . $pelamar->nama_belakang;
            } elseif ($pelamar->nama_depan) {
                $pelamar->nama_lengkap = $pelamar->nama_depan;
            } elseif ($pelamar->nama_belakang) {
                $pelamar->nama_lengkap = $pelamar->nama_belakang;
            }
        });

        static::updating(function ($pelamar) {
            if ($pelamar->nama_depan && $pelamar->nama_belakang) {
                $pelamar->nama_lengkap = $pelamar->nama_depan . ' ' . $pelamar->nama_belakang;
            } elseif ($pelamar->nama_depan) {
                $pelamar->nama_lengkap = $pelamar->nama_depan;
            } elseif ($pelamar->nama_belakang) {
                $pelamar->nama_lengkap = $pelamar->nama_belakang;
            }
        });

        static::deleted(function ($pelamar) {
            if ($pelamar->foto_profil) {
                Storage::disk('public')->delete($pelamar->foto_profil);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lowongans(): BelongsToMany
    {
        return $this->belongsToMany(Lowongan::class, 'lowongan_pelamar')
            ->withPivot(['status', 'created_at', 'updated_at'])
            ->withTimestamps();
    }

    public function pelamarTahapan(): HasMany
    {
        return $this->hasMany(PelamarTahapan::class);
    }

    public function berkas(): HasMany
    {
        return $this->hasMany(Berkas::class);
    }

    public function hasilPsikotes(): HasMany
    {
        return $this->hasMany(HasilPsikotes::class);
    }

    public function jawabanPsikotes(): HasMany
    {
        return $this->hasMany(JawabanPsikotes::class);
    }

    public function riwayatPendidikan(): HasMany
    {
        return $this->hasMany(RiwayatPendidikan::class);
    }

    public function riwayatOrganisasi(): HasMany
    {
        return $this->hasMany(RiwayatOrganisasi::class);
    }

    public function riwayatMagang(): HasMany
    {
        return $this->hasMany(RiwayatMagang::class);
    }

    public function riwayatPengalaman(): HasMany
    {
        return $this->hasMany(RiwayatPengalaman::class);
    }

    public function riwayatBahasa(): HasMany
    {
        return $this->hasMany(RiwayatBahasa::class);
    }

    public function riwayatKeahlian(): HasMany
    {
        return $this->hasMany(RiwayatKeahlian::class);
    }

    public function isRegisteredForLowongan($lowonganId)
    {
        return $this->lowongans()->where('lowongan_id', $lowonganId)->exists();
    }
}
