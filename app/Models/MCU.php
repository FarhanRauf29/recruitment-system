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
        'catatan_admin',
        'tanggal_verifikasi',
        'dokter_pemeriksa',
        'nama_rs',
        'kondisi_kesehatan',
        'nilai_mcu',
        'tinggi_badan',
        'berat_badan',
        'bmi',
        'tekanan_darah',
        'visus_mata',
        'buta_warna',
        'hasil_narkoba',
        'riwayat_penyakit',
        'surat_mcu_file',
        'dokumen_pendukung_file'
    ];

    protected $casts = [
        'jadwal_mcu' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'nilai_mcu' => 'float',
        'tinggi_badan' => 'float',
        'berat_badan' => 'float',
        'bmi' => 'float',
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

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'dijadwalkan' => 'warning',
            'dikonfirmasi' => 'info',
            'hadir' => 'primary',
            'tidak_hadir' => 'danger',
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
            'verifikasi' => 'Diverifikasi',
            'lolos' => 'Lolos MCU',
            'gagal' => 'Tidak Lolos',
        ];
        return $texts[$this->status] ?? ucfirst($this->status);
    }

    public function hitungSkorMCU()
    {
        $skor = 0;
        $totalBobot = 0;

        $kriteria = [
            ['field' => 'bmi', 'bobot' => 25, 'min' => 18.5, 'max' => 25, 'optimal' => 22],
            ['field' => 'tekanan_darah', 'bobot' => 25],
            ['field' => 'visus_mata', 'bobot' => 15],
            ['field' => 'buta_warna', 'bobot' => 15],
            ['field' => 'hasil_narkoba', 'bobot' => 20],
        ];

        foreach ($kriteria as $k) {
            if ($k['field'] === 'tekanan_darah') {
                $nilai = $this->nilaiTekananDarah($this->tekanan_darah);
                $skor += $nilai * $k['bobot'];
                $totalBobot += $k['bobot'];
            } elseif ($k['field'] === 'visus_mata') {
                $nilai = $this->nilaiVisusMata($this->visus_mata);
                $skor += $nilai * $k['bobot'];
                $totalBobot += $k['bobot'];
            } elseif ($k['field'] === 'buta_warna') {
                $nilai = $this->nilaiButaWarna($this->buta_warna);
                $skor += $nilai * $k['bobot'];
                $totalBobot += $k['bobot'];
            } elseif ($k['field'] === 'hasil_narkoba') {
                $nilai = $this->nilaiNarkoba($this->hasil_narkoba);
                $skor += $nilai * $k['bobot'];
                $totalBobot += $k['bobot'];
            } else {
                $nilai = $this->{$k['field']};
                if ($nilai !== null) {
                    $normalisasi = $this->normalisasiNilai($nilai, $k['min'], $k['max'], $k['optimal']);
                    $skor += $normalisasi * $k['bobot'];
                    $totalBobot += $k['bobot'];
                }
            }
        }

        if ($totalBobot > 0) {
            $skor = ($skor / $totalBobot) * 100;
        }

        return round($skor, 2);
    }

    private function normalisasiNilai($nilai, $min, $max, $optimal)
    {
        if ($nilai >= $min && $nilai <= $max) {
            $jarak = abs($nilai - $optimal);
            $maksJarak = max($optimal - $min, $max - $optimal);
            return max(0, 1 - ($jarak / $maksJarak));
        }
        return 0;
    }

    private function nilaiTekananDarah($tekanan)
    {
        if (empty($tekanan)) return 50;

        preg_match('/(\d+)\s*\/\s*(\d+)/', $tekanan, $matches);
        if (count($matches) < 3) return 50;

        $sistolik = (int)$matches[1];
        $diastolik = (int)$matches[2];

        if ($sistolik < 120 && $diastolik < 80) return 100;
        if ($sistolik < 130 && $diastolik < 85) return 85;
        if ($sistolik < 140 && $diastolik < 90) return 70;
        if ($sistolik < 160 && $diastolik < 100) return 50;
        return 30;
    }

    private function nilaiVisusMata($visus)
    {
        if (empty($visus)) return 50;

        if ($visus === '6/6' || $visus === 'Normal') return 100;
        if ($visus === '6/9' || $visus === '6/12') return 80;
        if ($visus === '6/18' || $visus === '6/24') return 60;
        if ($visus === '6/36' || $visus === '6/60') return 40;
        return 50;
    }

    private function nilaiButaWarna($butaWarna)
    {
        if (empty($butaWarna)) return 70;

        if ($butaWarna === 'Tidak') return 100;
        if ($butaWarna === 'Parsial') return 60;
        if ($butaWarna === 'Total') return 20;
        return 70;
    }

    private function nilaiNarkoba($hasil)
    {
        if (empty($hasil)) return 50;

        if ($hasil === 'Negatif') return 100;
        if ($hasil === 'Positif') return 0;
        return 50;
    }

    public function cekKondisiKritis()
    {
        $rules = [];

        if ($this->hasil_narkoba === 'Positif') {
            $rules[] = 'Tes narkoba positif';
        }

        if ($this->buta_warna === 'Total') {
            $rules[] = 'Buta warna total';
        }

        if (!empty($this->tekanan_darah)) {
            preg_match('/(\d+)\s*\/\s*(\d+)/', $this->tekanan_darah, $matches);
            if (count($matches) >= 3) {
                $sistolik = (int)$matches[1];
                if ($sistolik > 180) {
                    $rules[] = 'Tekanan darah sangat tinggi (>180 mmHg)';
                }
            }
        }

        if (!empty($this->riwayat_penyakit)) {
            $penyakitBerat = ['jantung', 'diabetes', 'hipertensi', 'epilepsi', 'kanker'];
            $riwayat = strtolower($this->riwayat_penyakit);
            foreach ($penyakitBerat as $p) {
                if (strpos($riwayat, $p) !== false) {
                    $rules[] = 'Riwayat penyakit berat: ' . $p;
                    break;
                }
            }
        }

        return $rules;
    }

    public function getStatusKesehatan()
    {
        $kritis = $this->cekKondisiKritis();

        if (!empty($kritis)) {
            return [
                'status' => 'Tidak Sehat',
                'keterangan' => 'Ditemukan kondisi kritis: ' . implode(', ', $kritis),
                'skor' => 0,
                'kritis' => $kritis
            ];
        }

        $skor = $this->hitungSkorMCU();

        if ($skor >= 95) {
            $status = 'Sangat Sehat';
        } elseif ($skor >= 85) {
            $status = 'Sehat';
        } elseif ($skor >= 70) {
            $status = 'Cukup Sehat';
        } elseif ($skor >= 50) {
            $status = 'Kurang Sehat';
        } else {
            $status = 'Tidak Sehat';
        }

        return [
            'status' => $status,
            'skor' => $skor,
            'keterangan' => 'Skor MCU: ' . $skor . ' - ' . $status,
            'kritis' => []
        ];
    }

    public function generateSuratHasilMCU()
    {
        $content = view('admin.mcu.surat_hasil', [
            'mcu' => $this
        ])->render();

        $fileName = 'hasil_mcu_' .
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
        ]);

        $path = 'mcu/hasil_surat/' . $fileName;
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }

    public function generateSuratPanggilan()
    {
        $content = view('admin.mcu.surat_panggilan', [
            'mcu' => $this
        ])->render();

        $fileName = 'surat_panggilan_mcu_' .
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
        ]);

        $path = 'mcu/surat_panggilan/' . $fileName;
        Storage::disk('public')->put($path, $pdf->output());

        return $path;
    }
}
