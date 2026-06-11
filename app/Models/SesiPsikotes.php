<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class SesiPsikotes extends Model
{
    use HasFactory;

    protected $table = 'sesi_psikotes';

    protected $fillable = [
        'pelamar_id',
        'lowongan_id',
        'pelamar_tahapan_id',
        'konfigurasi_id',
        'waktu_mulai',
        'waktu_selesai',
        'waktu_pause',
        'durasi_menit',
        'durasi_terpakai_detik',
        'status',
        'selesai',
        'soal_terpilih',
        'jawaban_terisi',
        'token_sesi',
        'ip_address',
        'user_agent',
        'jumlah_soal_ditampilkan',
        'nama_sesi',
        'current_soal_index',
        'waktu_mulai_pengerjaan',
        'waktu_terakhir_aktivitas'
    ];

    protected $casts = [
        'soal_terpilih' => 'array',
        'jawaban_terisi' => 'array',
        'selesai' => 'boolean',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'waktu_pause' => 'datetime',
        'durasi_menit' => 'integer',
        'durasi_terpakai_detik' => 'integer',
        'jumlah_soal_ditampilkan' => 'integer',
        'current_soal_index' => 'integer',
        'waktu_mulai_pengerjaan' => 'datetime',
        'waktu_terakhir_aktivitas' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class, 'pelamar_id');
    }

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class, 'lowongan_id');
    }

    public function pelamarTahapan(): BelongsTo
    {
        return $this->belongsTo(PelamarTahapan::class, 'pelamar_tahapan_id');
    }

    public function konfigurasi(): BelongsTo
    {
        return $this->belongsTo(KonfigurasiPsikotes::class, 'konfigurasi_id');
    }

    public function jawabanPsikotes(): HasMany
    {
        return $this->hasMany(JawabanPsikotes::class, 'sesi_psikotes_id');
    }

    public function hasilPsikotes(): HasMany
    {
        return $this->hasMany(HasilPsikotes::class, 'sesi_psikotes_id');
    }

    public function aktivitasPsikotes(): HasMany
    {
        return $this->hasMany(AktivitasPsikotes::class, 'sesi_psikotes_id');
    }

    public function getWaktuMulaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Asia/Jakarta') : null;
    }

    public function getWaktuSelesaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Asia/Jakarta') : null;
    }

    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Asia/Jakarta') : null;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->timezone('Asia/Jakarta') : null;
    }

    public function setWaktuMulaiAttribute($value)
    {
        $this->attributes['waktu_mulai'] = $value ? Carbon::parse($value)->setTimezone('UTC') : null;
    }

    public function setWaktuSelesaiAttribute($value)
    {
        $this->attributes['waktu_selesai'] = $value ? Carbon::parse($value)->setTimezone('UTC') : null;
    }

    public function scopeByPelamar($query, $pelamarId)
    {
        return $query->where('pelamar_id', $pelamarId);
    }

    public function scopeByLowongan($query, $lowonganId)
    {
        return $query->where('lowongan_id', $lowonganId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeSelesai($query)
    {
        return $query->where('selesai', true);
    }

    public function scopeAktif($query)
    {
        return $query->where('waktu_selesai', '>', now())
            ->where('selesai', false);
    }

    public function isExpired()
    {
        return now() > $this->waktu_selesai;
    }

    public function getSisaWaktuAktualAttribute()
    {
        if ($this->selesai) {
            return 0;
        }

        $now = now();

        if ($this->status === 'sedang_berjalan' && $this->waktu_mulai_pengerjaan) {
            $waktuBerlalu = $now->diffInSeconds($this->waktu_mulai_pengerjaan);
            $totalTerpakai = $this->durasi_terpakai_detik + $waktuBerlalu;
            $sisa = $this->durasi_menit * 60 - $totalTerpakai;
        } else {
            $totalTerpakai = $this->durasi_terpakai_detik;
            $sisa = $this->durasi_menit * 60 - $totalTerpakai;
        }

        return max(0, $sisa);
    }
    public function getWaktuTerpakaiDetikAttribute()
    {
        if ($this->selesai) {
            return $this->durasi_menit * 60;
        }

        $now = now();

        if ($this->status === 'sedang_berjalan' && $this->waktu_mulai_pengerjaan) {
            $waktuBerlalu = $now->diffInSeconds($this->waktu_mulai_pengerjaan);
            return $this->durasi_terpakai_detik + $waktuBerlalu;
        }

        return $this->durasi_terpakai_detik;
    }
    public function isActive()
    {
        return !$this->selesai &&
            now() >= $this->waktu_mulai &&
            now() <= $this->waktu_selesai &&
            ($this->status == 'sedang_berjalan' || $this->status == 'dijeda');
    }

    public function getSisaWaktuFormattedAttribute()
    {
        $sisaDetik = $this->sisa_waktu;

        if ($sisaDetik <= 0) {
            return 'Waktu Habis';
        }

        $jam = floor($sisaDetik / 3600);
        $menit = floor(($sisaDetik % 3600) / 60);
        $detik = $sisaDetik % 60;

        if ($jam > 0) {
            return sprintf('%02d:%02d:%02d', $jam, $menit, $detik);
        } else {
            return sprintf('%02d:%02d', $menit, $detik);
        }
    }

    public function getProgressAttribute()
    {
        $totalSoal = count($this->soal_terpilih ?? []);
        if ($totalSoal === 0) return 0;

        $soalTerjawab = $this->jawabanPsikotes()->count();
        return round(($soalTerjawab / $totalSoal) * 100, 2);
    }


    public function getJawabanTerisiArrayAttribute()
    {
        return $this->jawabanPsikotes()->pluck('jawaban', 'soal_id')->toArray();
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->waktu_selesai && now() > $model->waktu_selesai && !$model->selesai) {
                $model->selesai = true;
                $model->status = 'selesai';
            }
        });
    }
}
