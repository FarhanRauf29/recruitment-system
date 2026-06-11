<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HasilPsikotes extends Model
{
    use HasFactory;

    protected $table = 'hasil_psikotes';

    protected $fillable = [
        'pelamar_id',
        'lowongan_id',
        'pelamar_tahapan_id',
        'sesi_psikotes_id',
        'konfigurasi_id',
        'total_soal_ditampilkan',
        'soal_dijawab',
        'soal_kognitif_ditampilkan',
        'soal_kepribadian_ditampilkan',
        'soal_minat_ditampilkan',
        'soal_kognitif_benar',
        'soal_kognitif_salah',
        'soal_minat_skor',
        'nilai_kognitif',
        'nilai_kepribadian',
        'nilai_minat',
        'nilai_akhir',
        'keterangan',
        'rekomendasi',
        'waktu_selesai',
        'durasi_pengerjaan_detik',
        'lulus',
        'detail_analisis',
        'konfigurasi_penilaian'
    ];

    protected $casts = [
        'nilai_kognitif' => 'decimal:2',
        'nilai_kepribadian' => 'decimal:2',
        'nilai_minat' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
        'lulus' => 'boolean',
        'detail_analisis' => 'array',
        'konfigurasi_penilaian' => 'array',
        'waktu_selesai' => 'datetime',
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

    public function sesi(): BelongsTo
    {
        return $this->belongsTo(SesiPsikotes::class, 'sesi_psikotes_id');
    }

    public function konfigurasi(): BelongsTo
    {
        return $this->belongsTo(KonfigurasiPsikotes::class, 'konfigurasi_id');
    }

    public function getNilaiKognitifFormattedAttribute()
    {
        return $this->nilai_kognitif !== null ? round($this->nilai_kognitif, 2) : 0;
    }

    public function getNilaiKepribadianFormattedAttribute()
    {
        return $this->nilai_kepribadian !== null ? round($this->nilai_kepribadian, 2) : 0;
    }

    public function getNilaiMinatFormattedAttribute()
    {
        return $this->nilai_minat !== null ? round($this->nilai_minat, 2) : 0;
    }

    public function getNilaiAkhirFormattedAttribute()
    {
        return $this->nilai_akhir !== null ? round($this->nilai_akhir, 2) : 0;
    }

    public function hasCompleteData()
    {
        return $this->nilai_kognitif !== null &&
            $this->nilai_kepribadian !== null &&
            $this->nilai_minat !== null;
    }

    protected static function booted()
    {
        static::saved(function ($hasilPsikotes) {
            $hasilPsikotes->updateTahapanStatus();
        });
    }

    public function updateTahapanStatus()
    {
        if (!$this->pelamar_id || !$this->lowongan_id) {
            return;
        }

        $pelamarTahapan = PelamarTahapan::where('pelamar_id', $this->pelamar_id)
            ->where('lowongan_id', $this->lowongan_id)
            ->whereHas('tahapan', function ($q) {
                $q->where('nama_tahapan', 'like', '%psikotes%');
            })
            ->first();

        if ($pelamarTahapan) {
            if ($pelamarTahapan->status === 'diproses') {
                $newStatus = $this->lulus ? 'lolos' : 'gagal';
                $pelamarTahapan->status = $newStatus;
                $pelamarTahapan->tanggal_selesai = now();
                $pelamarTahapan->save();

                if ($newStatus === 'lolos') {
                    $this->bukaTahapanBerikutnya($pelamarTahapan);
                } elseif ($newStatus === 'gagal') {
                    PelamarTahapan::where('pelamar_id', $this->pelamar_id)
                        ->where('lowongan_id', $this->lowongan_id)
                        ->where('status', 'menunggu')
                        ->update(['status' => 'gagal', 'catatan' => 'Proses berhenti karena gagal pada tahap psikotes', 'tanggal_selesai' => now()]);
                }
            }
        }
    }

    private function bukaTahapanBerikutnya($pelamarTahapan)
    {
        $tahapanPsikotes = $pelamarTahapan->tahapan;

        $nextTahapan = TahapanRekrutmen::where('urutan', '>', $tahapanPsikotes->urutan)
            ->orderBy('urutan', 'asc')
            ->first();

        if ($nextTahapan) {
            $nextPelamarTahapan = PelamarTahapan::where('pelamar_id', $pelamarTahapan->pelamar_id)
                ->where('lowongan_id', $pelamarTahapan->lowongan_id)
                ->where('tahapan_id', $nextTahapan->id)
                ->first();

            if ($nextPelamarTahapan) {
                if ($nextPelamarTahapan->status === 'menunggu') {
                    $nextPelamarTahapan->status = 'diproses';
                    $nextPelamarTahapan->tanggal_mulai = now();
                    $nextPelamarTahapan->save();
                }
            } else {
                PelamarTahapan::create([
                    'pelamar_id' => $pelamarTahapan->pelamar_id,
                    'lowongan_id' => $pelamarTahapan->lowongan_id,
                    'tahapan_id' => $nextTahapan->id,
                    'status' => 'diproses',
                    'tanggal_mulai' => now(),
                ]);
            }
        }
    }

    public function getTotalSoalAttribute()
    {
        return $this->total_soal_ditampilkan ?? 0;
    }

    public function getSoalTerjawabAttribute()
    {
        return $this->soal_dijawab ?? 0;
    }

    public function getTotalSkorAttribute()
    {
        return $this->nilai_akhir ?? 0;
    }

    public function getPersentaseAttribute()
    {
        return $this->nilai_akhir ?? 0;
    }
}
