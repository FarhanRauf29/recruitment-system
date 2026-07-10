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
        'dokumen_pendukung_file',
        'surat_panggilan_file'
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
            ['field' => 'bmi', 'bobot' => 10, 'min' => 18.5, 'max' => 25, 'optimal' => 22],
            ['field' => 'tekanan_darah', 'bobot' => 15],
            ['field' => 'visus_mata', 'bobot' => 15],
            ['field' => 'buta_warna', 'bobot' => 15],
            ['field' => 'hasil_narkoba', 'bobot' => 25],
            ['field' => 'riwayat_penyakit', 'bobot' => 20],
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
            } elseif ($k['field'] === 'riwayat_penyakit') {
                $nilai = $this->nilaiRiwayatPenyakit($this->riwayat_penyakit);
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

        if ($sistolik < 120 && $diastolik < 80) {
            return 100;
        } elseif ($sistolik < 130 && $diastolik < 85) {
            return 85;
        } elseif ($sistolik < 140 && $diastolik < 90) {
            return 75;
        } elseif ($sistolik < 160 && $diastolik < 100) {
            return 60;
        } elseif ($sistolik < 180 && $diastolik < 110) {
            return 40;
        } else {
            return 20;
        }
    }

    private function nilaiVisusMata($visus)
    {
        if (empty($visus)) return 50;

        $visus = trim(strtolower($visus));

        if ($visus === '6/6' || $visus === 'normal' || $visus === '1.0' || $visus === '20/20') {
            return 100;
        } elseif ($visus === '6/9' || $visus === '6/12' || $visus === '0.8' || $visus === '0.7') {
            return 80;
        } elseif ($visus === '6/18' || $visus === '6/24' || $visus === '0.5' || $visus === '0.4') {
            return 60;
        } elseif ($visus === '6/36' || $visus === '6/60' || $visus === '0.3' || $visus === '0.2') {
            return 40;
        } elseif (strpos($visus, '6/') !== false) {
            $parts = explode('/', $visus);
            if (count($parts) == 2) {
                $penyebut = (int)$parts[1];
                if ($penyebut > 60) {
                    return 20;
                }
            }
            return 50;
        } elseif ($visus === 'buta' || $visus === 'blind' || $visus === '0' || $visus === 'npl') {
            return 0;
        } elseif (strpos($visus, 'kanan') !== false || strpos($visus, 'kiri') !== false) {
            if (strpos($visus, 'kanan') !== false && strpos($visus, 'kiri') !== false) {
                preg_match_all('/6\/\d+/', $visus, $matches);
                if (!empty($matches[0])) {
                    $best = 0;
                    foreach ($matches[0] as $v) {
                        $score = $this->nilaiVisusMata($v);
                        $best = max($best, $score);
                    }
                    return $best;
                }
            }
            return 50;
        }
        return 50;
    }

    private function nilaiButaWarna($butaWarna)
    {
        if (empty($butaWarna)) return 70;

        $butaWarna = trim(strtolower($butaWarna));

        if ($butaWarna === 'tidak' || $butaWarna === 'normal' || $butaWarna === 'no' || $butaWarna === 'negatif') {
            return 100;
        } elseif ($butaWarna === 'parsial' || $butaWarna === 'partial' || $butaWarna === 'deuteranomali' || $butaWarna === 'protanomali') {
            return 60;
        } elseif ($butaWarna === 'total' || $butaWarna === 'complete' || $butaWarna === 'monochromacy' || $butaWarna === 'akromatopsia') {
            return 20;
        } elseif ($butaWarna === 'merah-hijau' || $butaWarna === 'red-green' || $butaWarna === 'deuteranopia' || $butaWarna === 'protanopia') {
            return 40;
        } elseif ($butaWarna === 'biru-kuning' || $butaWarna === 'blue-yellow' || $butaWarna === 'tritanopia') {
            return 50;
        }
        return 70;
    }

    private function nilaiNarkoba($hasil)
    {
        if (empty($hasil)) return 50;

        $hasil = trim(strtolower($hasil));

        if ($hasil === 'negatif' || $hasil === 'negative' || $hasil === 'tidak terdeteksi' || $hasil === 'non-reactive') {
            return 100;
        } elseif ($hasil === 'positif' || $hasil === 'positive' || $hasil === 'reactive' || $hasil === 'terdeteksi') {
            return 0;
        } elseif ($hasil === 'tidak dilakukan' || $hasil === 'not tested' || $hasil === 'n/a') {
            return 30;
        }
        return 50;
    }

    private function nilaiRiwayatPenyakit($riwayat)
    {
        if (empty($riwayat)) {
            return 100;
        }

        $riwayat = trim(strtolower($riwayat));

        $daftarPenyakit = [
            'jantung koroner' => 15,
            'gagal jantung' => 10,
            'kanker' => 10,
            'stroke' => 15,
            'gagal ginjal' => 10,
            'sirosis' => 10,
            'hiv' => 5,
            'aids' => 5,
            'tuberkulosis' => 15,
            'tbc aktif' => 15,
            'hepatitis b' => 20,
            'hepatitis c' => 20,
            'diabetes melitus' => 35,
            'hipertensi esensial' => 35,
            'asma berat' => 40,
            'epilepsi' => 25,
            'autoimun' => 20,
            'thalasemia' => 25,
            'tumor ganas' => 20,
            'kista' => 45,
            'maag kronis' => 55,
            'gastritis kronis' => 55,
            'kolesterol tinggi' => 55,
            'asam urat' => 55,
            'rematik' => 50,
            'alergi berat' => 65,
            'jantung' => 20,
            'diabetes' => 40,
            'hipertensi' => 40,
            'asma' => 50,
            'hepatitis' => 30,
            'tumor' => 25,
            'maag' => 60,
            'gastritis' => 60,
            'kolesterol' => 60,
            'asam urat' => 60,
            'alergi' => 70,
        ];

        $penyakitDitemukan = [];
        $totalNilai = 0;
        $jumlahPenyakit = 0;

        foreach ($daftarPenyakit as $penyakit => $nilai) {
            if (strpos($riwayat, $penyakit) !== false) {
                $penyakitDitemukan[] = [
                    'nama' => $penyakit,
                    'nilai' => $nilai
                ];
                $totalNilai += $nilai;
                $jumlahPenyakit++;
            }
        }

        if ($jumlahPenyakit === 0) {
            $penyakitRingan = ['pilek', 'flu', 'demam', 'batuk', 'sakit kepala', 'migrain', 'alergi ringan'];
            foreach ($penyakitRingan as $p) {
                if (strpos($riwayat, $p) !== false) {
                    return 85;
                }
            }
            if (strlen($riwayat) > 10) {
                return 70;
            }
            return 100;
        }

        $rataRataNilai = $totalNilai / $jumlahPenyakit;

        $penaltiJumlahPenyakit = 0;
        if ($jumlahPenyakit >= 5) {
            $penaltiJumlahPenyakit = 25;
        } elseif ($jumlahPenyakit >= 4) {
            $penaltiJumlahPenyakit = 20;
        } elseif ($jumlahPenyakit >= 3) {
            $penaltiJumlahPenyakit = 15;
        } elseif ($jumlahPenyakit >= 2) {
            $penaltiJumlahPenyakit = 10;
        } elseif ($jumlahPenyakit >= 1) {
            $penaltiJumlahPenyakit = 5;
        }

        $nilaiAkhir = $rataRataNilai - $penaltiJumlahPenyakit;

        $kombinasiBerbahaya = [
            ['jantung', 'diabetes'],
            ['jantung', 'hipertensi'],
            ['diabetes', 'hipertensi'],
            ['jantung', 'diabetes', 'hipertensi'],
            ['asma', 'jantung'],
            ['diabetes', 'kolesterol'],
            ['hipertensi', 'kolesterol'],
            ['jantung', 'kolesterol'],
            ['diabetes', 'asma'],
            ['hipertensi', 'diabetes', 'kolesterol']
        ];

        foreach ($kombinasiBerbahaya as $kombinasi) {
            $semuaAda = true;
            foreach ($kombinasi as $penyakit) {
                $found = false;
                foreach ($penyakitDitemukan as $pd) {
                    if (strpos($pd['nama'], $penyakit) !== false) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $semuaAda = false;
                    break;
                }
            }
            if ($semuaAda) {
                $nilaiAkhir -= 15;
                break;
            }
        }

        return max(0, min(100, round($nilaiAkhir, 2)));
    }

    public function cekKondisiKritis()
    {
        $rules = [];

        if ($this->hasil_narkoba === 'Positif' || strtolower($this->hasil_narkoba) === 'positif') {
            $rules[] = 'Tes narkoba positif - eliminasi otomatis';
        }

        if ($this->buta_warna === 'Total' || strtolower($this->buta_warna) === 'total') {
            $rules[] = 'Buta warna total - perlu evaluasi untuk posisi';
        }

        if (!empty($this->tekanan_darah)) {
            preg_match('/(\d+)\s*\/\s*(\d+)/', $this->tekanan_darah, $matches);
            if (count($matches) >= 3) {
                $sistolik = (int)$matches[1];
                $diastolik = (int)$matches[2];
                if ($sistolik >= 180 || $diastolik >= 120) {
                    $rules[] = 'Tekanan darah krisis (>180/120 mmHg) - perlu penanganan medis segera';
                } elseif ($sistolik >= 160 || $diastolik >= 100) {
                    $rules[] = 'Hipertensi stage 2 (>160/100 mmHg) - perlu evaluasi lebih lanjut';
                }
            }
        }

        if (!empty($this->visus_mata)) {
            $visus = trim(strtolower($this->visus_mata));
            if ($visus === 'buta' || $visus === 'blind' || $visus === '0' || $visus === 'npl') {
                $rules[] = 'Kebutaan - perlu evaluasi untuk posisi';
            }
            if (strpos($visus, '6/60') !== false || strpos($visus, '6/120') !== false) {
                $rules[] = 'Visus sangat rendah - perlu evaluasi lebih lanjut';
            }
        }

        if (!empty($this->riwayat_penyakit)) {
            $riwayat = strtolower($this->riwayat_penyakit);
            $penyakitBerat = [
                'jantung koroner',
                'gagal jantung',
                'kanker',
                'stroke',
                'gagal ginjal',
                'sirosis',
                'hiv',
                'aids',
                'tuberkulosis',
                'tbc aktif',
                'diabetes melitus',
                'hipertensi esensial',
                'hepatitis b',
                'hepatitis c',
                'thalasemia',
                'tumor ganas'
            ];
            foreach ($penyakitBerat as $p) {
                if (strpos($riwayat, $p) !== false) {
                    $rules[] = 'Riwayat penyakit berat: ' . $p . ' - perlu evaluasi medis lebih lanjut';
                    break;
                }
            }
        }

        if (!empty($this->bmi)) {
            if ($this->bmi < 15) {
                $rules[] = 'BMI sangat rendah (<15) - malnutrisi berat';
            } elseif ($this->bmi > 40) {
                $rules[] = 'BMI sangat tinggi (>40) - obesitas morbid';
            } elseif ($this->bmi < 17) {
                $rules[] = 'BMI rendah (<17) - perlu perhatian';
            } elseif ($this->bmi > 35) {
                $rules[] = 'BMI tinggi (>35) - obesitas berat';
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
            $keterangan = 'Kondisi kesehatan sangat baik, tidak ada keluhan berarti.';
        } elseif ($skor >= 85) {
            $status = 'Sehat';
            $keterangan = 'Kondisi kesehatan baik, ada beberapa temuan minor.';
        } elseif ($skor >= 70) {
            $status = 'Cukup Sehat';
            $keterangan = 'Kondisi kesehatan cukup, ada beberapa temuan yang perlu diperhatikan.';
        } elseif ($skor >= 50) {
            $status = 'Kurang Sehat';
            $keterangan = 'Kondisi kesehatan kurang, terdapat temuan yang perlu tindak lanjut.';
        } else {
            $status = 'Tidak Sehat';
            $keterangan = 'Kondisi kesehatan buruk, perlu penanganan medis segera.';
        }

        return [
            'status' => $status,
            'skor' => $skor,
            'keterangan' => $keterangan,
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
