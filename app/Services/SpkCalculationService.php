<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\PenilaianKandidat;
use App\Models\HasilSAW;
use App\Models\HasilTOPSIS;
use App\Models\Pelamar;
use App\Models\PelamarTahapan;
use App\Models\Lowongan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpkCalculationService
{
    protected $kriterias;
    protected $matrixNilai;
    protected $matrixNormalisasi;
    protected $matrixTerbobot;

    public function __construct()
    {
        $this->kriterias = Kriteria::where('is_active', true)->orderBy('id')->get();
    }

    public function hitungSAW($lowonganId)
    {
        try {
            DB::beginTransaction();

            $pelamars = Pelamar::whereHas('pelamarTahapan', function ($q) use ($lowonganId) {
                $q->where('lowongan_id', $lowonganId);
            })->get();

            if ($pelamars->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada kandidat untuk diproses'
                ];
            }

            $lowongan = Lowongan::find($lowonganId);
            $slot = $lowongan ? $lowongan->slot : 1;

            $totalBobot = $this->kriterias->sum('bobot');

            $matrix = [];

            foreach ($pelamars as $pelamar) {
                foreach ($this->kriterias as $kriteria) {
                    $penilaian = PenilaianKandidat::where(
                        'pelamar_id',
                        $pelamar->id
                    )
                        ->where('lowongan_id', $lowonganId)
                        ->where('kriteria_id', $kriteria->id)
                        ->first();

                    $matrix[$pelamar->id][$kriteria->id] =
                        $penilaian
                        ? floatval($penilaian->nilai)
                        : 0;
                }
            }

            $maxValues = [];
            $minValues = [];

            foreach ($this->kriterias as $kriteria) {
                $nilaiKriteria = [];

                foreach ($pelamars as $pelamar) {
                    $nilaiKriteria[] = $matrix[$pelamar->id][$kriteria->id];
                }

                $maxValues[$kriteria->id] = max($nilaiKriteria);
                $minValues[$kriteria->id] = min($nilaiKriteria);
            }

            $hasilPerhitungan = [];

            foreach ($pelamars as $pelamar) {
                $nilaiTotal = 0;
                $detailPerhitungan = [];

                foreach ($this->kriterias as $kriteria) {
                    $nilai = $matrix[$pelamar->id][$kriteria->id];

                    if ($kriteria->atribut == 'benefit') {
                        $normalisasi = $maxValues[$kriteria->id] > 0
                            ? $nilai / $maxValues[$kriteria->id]
                            : 0;
                    } else {
                        $normalisasi = $nilai > 0
                            ? $minValues[$kriteria->id] / $nilai
                            : 0;
                    }

                    $bobotNormalisasi = $kriteria->bobot / $totalBobot;
                    $nilaiTerbobot = $normalisasi * $bobotNormalisasi;
                    $nilaiTotal += $nilaiTerbobot;

                    $detailPerhitungan[$kriteria->kode_kriteria] = [
                        'nama_kriteria' => $kriteria->nama_kriteria,
                        'atribut' => $kriteria->atribut,
                        'nilai_mentah' => $nilai,
                        'nilai_normalisasi' => round($normalisasi, 4),
                        'bobot' => $kriteria->bobot,
                        'bobot_normalisasi' => round($bobotNormalisasi, 4),
                        'nilai_terbobot' => round($nilaiTerbobot, 4),
                    ];
                }

                $hasilPerhitungan[] = [
                    'pelamar_id' => $pelamar->id,
                    'nilai_saw' => round($nilaiTotal, 4),
                    'detail' => $detailPerhitungan
                ];
            }

            usort($hasilPerhitungan, function ($a, $b) {
                return $b['nilai_saw'] <=> $a['nilai_saw'];
            });

            foreach ($hasilPerhitungan as $index => $hasil) {
                $ranking = $index + 1;

                if ($ranking <= $slot) {
                    $statusRekomendasi = 'rekomendasi';
                } elseif ($ranking <= ($slot * 2)) {
                    $statusRekomendasi = 'cadangan';
                } else {
                    $statusRekomendasi = 'tidak_rekomendasi';
                }

                HasilSAW::updateOrCreate(
                    [
                        'pelamar_id' => $hasil['pelamar_id'],
                        'lowongan_id' => $lowonganId,
                    ],
                    [
                        'nilai_saw' => $hasil['nilai_saw'],
                        'ranking' => $ranking,
                        'status_rekomendasi' => $statusRekomendasi,
                        'detail_perhitungan' => $hasil['detail']
                    ]
                );
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Perhitungan SAW berhasil dilakukan',
                'data' => $hasilPerhitungan
            ];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error SAW : ' . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function hitungTOPSIS($lowonganId)
    {
        try {
            DB::beginTransaction();

            $pelamars = Pelamar::whereHas('pelamarTahapan', function ($q) use ($lowonganId) {
                $q->where('lowongan_id', $lowonganId);
            })->get();

            if ($pelamars->isEmpty()) {
                return ['success' => false, 'message' => 'Tidak ada kandidat untuk diproses'];
            }

            $lowongan = Lowongan::find($lowonganId);
            $slot = $lowongan ? $lowongan->slot : 1;

            $jumlahAlternatif = $pelamars->count();
            $jumlahKriteria = $this->kriterias->count();

            if ($jumlahAlternatif == 0 || $jumlahKriteria == 0) {
                return ['success' => false, 'message' => 'Data alternatif atau kriteria tidak tersedia'];
            }

            $this->matrixNilai = array_fill(0, $jumlahAlternatif, array_fill(0, $jumlahKriteria, 0));

            foreach ($pelamars as $i => $pelamar) {
                foreach ($this->kriterias as $j => $kriteria) {
                    $penilaian = PenilaianKandidat::where('pelamar_id', $pelamar->id)
                        ->where('lowongan_id', $lowonganId)
                        ->where('kriteria_id', $kriteria->id)
                        ->first();

                    $this->matrixNilai[$i][$j] = $penilaian ? floatval($penilaian->nilai) : 0;
                }
            }

            $this->normalisasiMatrixTOPSIS($jumlahAlternatif, $jumlahKriteria);
            $this->hitungMatrixTerbobot($jumlahAlternatif, $jumlahKriteria);

            $solusiIdeal = $this->hitungSolusiIdeal($jumlahKriteria);
            $jarak = $this->hitungJarakSolusi($jumlahAlternatif, $jumlahKriteria, $solusiIdeal);
            $nilaiPreferensi = $this->hitungNilaiPreferensi($jumlahAlternatif, $jarak);

            $hasilPerhitungan = [];

            $solusiPositifArray = [];
            $solusiNegatifArray = [];

            foreach ($this->kriterias as $j => $kriteria) {
                $solusiPositifArray[$kriteria->kode_kriteria] = $solusiIdeal['positif'][$j];
                $solusiNegatifArray[$kriteria->kode_kriteria] = $solusiIdeal['negatif'][$j];
            }

            foreach ($pelamars as $i => $pelamar) {
                $detailKriteria = [];
                foreach ($this->kriterias as $j => $kriteria) {
                    $detailKriteria[$kriteria->kode_kriteria] = [
                        'nama_kriteria' => $kriteria->nama_kriteria,
                        'atribut' => $kriteria->atribut,
                        'bobot' => $kriteria->bobot,
                        'nilai_mentah' => $this->matrixNilai[$i][$j],
                        'nilai_normalisasi' => round($this->matrixNormalisasi[$i][$j], 4),
                        'nilai_terbobot' => round($this->matrixTerbobot[$i][$j], 4),
                    ];
                }

                $hasilPerhitungan[] = [
                    'pelamar_id' => $pelamar->id,
                    'nilai_topsis' => round($nilaiPreferensi[$i], 4),
                    'jarak_positif' => round($jarak['positif'][$i], 4),
                    'jarak_negatif' => round($jarak['negatif'][$i], 4),
                    'detail_kriteria' => $detailKriteria,
                    'solusi_ideal_positif' => $solusiPositifArray,
                    'solusi_ideal_negatif' => $solusiNegatifArray,
                ];
            }

            usort($hasilPerhitungan, function ($a, $b) {
                return $b['nilai_topsis'] <=> $a['nilai_topsis'];
            });

            foreach ($hasilPerhitungan as $index => $hasil) {
                $ranking = $index + 1;

                if ($ranking <= $slot) {
                    $statusRekomendasi = 'rekomendasi';
                } elseif ($ranking <= ($slot * 2)) {
                    $statusRekomendasi = 'cadangan';
                } else {
                    $statusRekomendasi = 'tidak_rekomendasi';
                }

                HasilTOPSIS::updateOrCreate(
                    [
                        'pelamar_id' => $hasil['pelamar_id'],
                        'lowongan_id' => $lowonganId,
                    ],
                    [
                        'nilai_topsis' => $hasil['nilai_topsis'],
                        'ranking' => $ranking,
                        'status_rekomendasi' => $statusRekomendasi,
                        'detail_perhitungan' => [
                            'jarak_positif' => $hasil['jarak_positif'],
                            'jarak_negatif' => $hasil['jarak_negatif'],
                            'detail_kriteria' => $hasil['detail_kriteria'],
                            'solusi_ideal_positif' => $hasil['solusi_ideal_positif'],
                            'solusi_ideal_negatif' => $hasil['solusi_ideal_negatif'],
                        ]
                    ]
                );
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Perhitungan TOPSIS berhasil dilakukan',
                'data' => $hasilPerhitungan
            ];
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error TOPSIS: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Gagal menghitung TOPSIS: ' . $e->getMessage()];
        }
    }

    private function normalisasiMatrixTOPSIS($jumlahAlternatif, $jumlahKriteria)
    {
        $this->matrixNormalisasi = array_fill(0, $jumlahAlternatif, array_fill(0, $jumlahKriteria, 0));

        for ($j = 0; $j < $jumlahKriteria; $j++) {
            $jumlahKuadrat = 0;
            for ($i = 0; $i < $jumlahAlternatif; $i++) {
                $jumlahKuadrat += pow($this->matrixNilai[$i][$j], 2);
            }
            $akarJumlahKuadrat = sqrt($jumlahKuadrat);

            for ($i = 0; $i < $jumlahAlternatif; $i++) {
                if ($akarJumlahKuadrat != 0) {
                    $this->matrixNormalisasi[$i][$j] = $this->matrixNilai[$i][$j] / $akarJumlahKuadrat;
                } else {
                    $this->matrixNormalisasi[$i][$j] = 0;
                }
            }
        }
    }

    private function hitungMatrixTerbobot($jumlahAlternatif, $jumlahKriteria)
    {
        $totalBobot = $this->kriterias->sum('bobot');
        $this->matrixTerbobot = array_fill(0, $jumlahAlternatif, array_fill(0, $jumlahKriteria, 0));

        for ($i = 0; $i < $jumlahAlternatif; $i++) {
            for ($j = 0; $j < $jumlahKriteria; $j++) {
                $bobotNormalisasi = $this->kriterias[$j]->bobot / $totalBobot;
                $this->matrixTerbobot[$i][$j] = $this->matrixNormalisasi[$i][$j] * $bobotNormalisasi;
            }
        }
    }

    private function hitungSolusiIdeal($jumlahKriteria)
    {
        $solusiIdeal = [
            'positif' => array_fill(0, $jumlahKriteria, 0),
            'negatif' => array_fill(0, $jumlahKriteria, 0)
        ];
        $jumlahAlternatif = count($this->matrixTerbobot);

        for ($j = 0; $j < $jumlahKriteria; $j++) {
            $nilaiTerbobot = [];
            for ($i = 0; $i < $jumlahAlternatif; $i++) {
                $nilaiTerbobot[] = $this->matrixTerbobot[$i][$j];
            }

            if ($this->kriterias[$j]->atribut == 'benefit') {
                $solusiIdeal['positif'][$j] = max($nilaiTerbobot);
                $solusiIdeal['negatif'][$j] = min($nilaiTerbobot);
            } else {
                $solusiIdeal['positif'][$j] = min($nilaiTerbobot);
                $solusiIdeal['negatif'][$j] = max($nilaiTerbobot);
            }
        }

        return $solusiIdeal;
    }

    private function hitungJarakSolusi($jumlahAlternatif, $jumlahKriteria, $solusiIdeal)
    {
        $jarak = [
            'positif' => array_fill(0, $jumlahAlternatif, 0),
            'negatif' => array_fill(0, $jumlahAlternatif, 0)
        ];

        for ($i = 0; $i < $jumlahAlternatif; $i++) {
            $jarakPositif = 0;
            $jarakNegatif = 0;

            for ($j = 0; $j < $jumlahKriteria; $j++) {
                $jarakPositif += pow($this->matrixTerbobot[$i][$j] - $solusiIdeal['positif'][$j], 2);
                $jarakNegatif += pow($this->matrixTerbobot[$i][$j] - $solusiIdeal['negatif'][$j], 2);
            }

            $jarak['positif'][$i] = sqrt($jarakPositif);
            $jarak['negatif'][$i] = sqrt($jarakNegatif);
        }

        return $jarak;
    }

    private function hitungNilaiPreferensi($jumlahAlternatif, $jarak)
    {
        $nilaiPreferensi = array_fill(0, $jumlahAlternatif, 0);

        for ($i = 0; $i < $jumlahAlternatif; $i++) {
            $totalJarak = $jarak['positif'][$i] + $jarak['negatif'][$i];
            if ($totalJarak > 0) {
                $nilaiPreferensi[$i] = $jarak['negatif'][$i] / $totalJarak;
            } else {
                $nilaiPreferensi[$i] = 0;
            }
        }

        return $nilaiPreferensi;
    }

    public function getRankingSAW($lowonganId)
    {
        return HasilSAW::with(['pelamar'])
            ->where('lowongan_id', $lowonganId)
            ->orderBy('ranking', 'asc')
            ->get();
    }

    public function getRankingTOPSIS($lowonganId)
    {
        return HasilTOPSIS::with(['pelamar'])
            ->where('lowongan_id', $lowonganId)
            ->orderBy('ranking', 'asc')
            ->get();
    }

    public function approveKandidat($pelamarId, $lowonganId, $metode, $catatan, $userId)
    {
        try {
            DB::beginTransaction();

            $lowongan = Lowongan::find($lowonganId);

            if (!$lowongan->isSlotAvailable()) {
                return ['success' => false, 'message' => 'Slot lowongan sudah penuh'];
            }

            $hasilSAW = HasilSAW::where('pelamar_id', $pelamarId)
                ->where('lowongan_id', $lowonganId)
                ->first();

            $hasilTOPSIS = HasilTOPSIS::where('pelamar_id', $pelamarId)
                ->where('lowongan_id', $lowonganId)
                ->first();

            $pelamarTahapan = PelamarTahapan::where('pelamar_id', $pelamarId)
                ->where('lowongan_id', $lowonganId)
                ->whereHas('tahapan', function ($q) {
                    $q->where('nama_tahapan', 'like', '%wawancara%');
                })
                ->first();

            if ($pelamarTahapan) {
                $pelamarTahapan->spk_status = 'approved';
                $pelamarTahapan->nilai_akhir_spk = $metode == 'saw' ? $hasilSAW->nilai_saw : $hasilTOPSIS->nilai_topsis;
                $pelamarTahapan->metode_terbaik = $metode;
                $pelamarTahapan->catatan_spk = $catatan;
                $pelamarTahapan->tanggal_approval = now();
                $pelamarTahapan->approved_by = $userId;
                $pelamarTahapan->save();

                $lowongan->updateStatusIfNeeded();
            }

            DB::commit();

            return ['success' => true, 'message' => 'Kandidat berhasil di-approve ke tahap offering'];
        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false, 'message' => 'Gagal approve kandidat: ' . $e->getMessage()];
        }
    }
}
