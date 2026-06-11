<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class MCU extends Model
{
    use HasFactory;

    protected $table = 'mcu';

    protected $fillable = [
        'pelamar_tahapan_id',
        'pelamar_id',
        'lowongan_id',
        'jadwal_mcu',
        'lokasi_mcu',
        'alamat_lokasi',
        'keterangan',
        'status',
        'hasil_mcu_file',
        'hasil_mcu_keterangan',
        'surat_mcu_file',
        'dokumen_pendukung_file',
        'catatan_admin',
        'tanggal_verifikasi',
        'dokter_pemeriksa',
        'nama_rs',
        'kondisi_kesehatan',
        'nilai_mcu',
    ];

    protected $casts = [
        'jadwal_mcu' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'nilai_mcu' => 'integer',
    ];

    public static function kondisiOptions()
    {
        return [
            'Sangat Sehat' => 100,
            'Sehat' => 85,
            'Cukup' => 70,
            'Kurang' => 50,
            'Tidak Lulus' => 0,
        ];
    }

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
        static::saved(function ($mcu) {
            $mcu->updateTahapanStatus();
        });
    }

    public function updateTahapanStatus()
    {
        $pelamarTahapan = PelamarTahapan::where('id', $this->pelamar_tahapan_id)->first();

        if ($pelamarTahapan && $pelamarTahapan->status === 'diproses') {
            if ($this->status === 'lolos') {
                $pelamarTahapan->status = 'lolos';
                $pelamarTahapan->catatan = $this->catatan_admin ?: 'Lolos MCU';
                $pelamarTahapan->tanggal_selesai = now();
                $pelamarTahapan->save();

                $this->bukaTahapanBerikutnya($pelamarTahapan);
            } elseif ($this->status === 'gagal') {
                $pelamarTahapan->status = 'gagal';
                $pelamarTahapan->catatan = $this->catatan_admin ?: 'Gagal MCU';
                $pelamarTahapan->tanggal_selesai = now();
                $pelamarTahapan->save();
            }
        }
    }

    private function bukaTahapanBerikutnya($pelamarTahapan)
    {
        $nextTahapan = TahapanRekrutmen::aktif()
            ->where('id', '>', $pelamarTahapan->tahapan_id)
            ->orderBy('id', 'asc')
            ->first();

        if ($nextTahapan) {
            $nextPelamarTahapan = PelamarTahapan::firstOrNew([
                'pelamar_id' => $pelamarTahapan->pelamar_id,
                'lowongan_id' => $pelamarTahapan->lowongan_id,
                'tahapan_id' => $nextTahapan->id,
            ]);

            if ($nextPelamarTahapan->status === 'menunggu') {
                $nextPelamarTahapan->status = 'diproses';
                $nextPelamarTahapan->tanggal_mulai = now();
                $nextPelamarTahapan->save();
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
            'hasil_diupload' => 'secondary',
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
            'hadir' => 'Hadir MCU',
            'tidak_hadir' => 'Tidak Hadir',
            'hasil_diupload' => 'Hasil Diupload',
            'verifikasi' => 'Diverifikasi',
            'lolos' => 'Lolos MCU',
            'gagal' => 'Tidak Lolos',
        ];
        return $texts[$this->status] ?? ucfirst($this->status);
    }

    public function getNilaiFromKondisiAttribute()
    {
        $nilaiMap = self::kondisiOptions();
        return $nilaiMap[$this->kondisi_kesehatan] ?? 0;
    }

    public function generateSuratMCU()
    {
        $content = view('admin.mcu.surat', [
            'mcu' => $this
        ])->render();

        $fileName = 'surat_mcu_' .
            preg_replace('/[^a-zA-Z0-9]/', '_', $this->pelamar->nama_lengkap) .
            '_' .
            now()->format('Ymd_His') .
            '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($content);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'defaultFont' => 'Times New Roman',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'dpi' => 120,
            'defaultMediaType' => 'print',
        ]);

        $path = 'mcu/surat/' . $fileName;
        Storage::disk('public')->put($path, $pdf->output());
        $this->surat_mcu_file = $path;
        $this->save();

        return $path;
    }
}
