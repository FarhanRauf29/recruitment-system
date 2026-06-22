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

    /**
     * ===================================================================
     * METODE SAW (Simple Additive Weighting)
     * ===================================================================
     * Langkah-langkah:
     * 1. Membuat matriks keputusan (nilai setiap alternatif untuk setiap kriteria)
     * 2. Menentukan nilai maksimum (benefit) dan minimum (cost) setiap kriteria
     * 3. Normalisasi matriks keputusan ke skala 0-1
     * 4. Menghitung nilai preferensi dengan mengalikan normalisasi dengan bobot
     * 5. Menjumlahkan semua nilai terbobot untuk setiap alternatif
     * 6. Mengurutkan hasil (ranking) dari nilai tertinggi ke terendah
     * ===================================================================
     */
    public function hitungSAW($lowonganId)
    {
        try {
            DB::beginTransaction();

            // ===========================================================
            // LANGKAH 0: Persiapan Data
            // ===========================================================
            // Ambil semua pelamar yang terdaftar di lowongan ini
            $pelamars = Pelamar::whereHas('pelamarTahapan', function ($q) use ($lowonganId) {
                $q->where('lowongan_id', $lowonganId);
            })->get();

            if ($pelamars->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Tidak ada kandidat untuk diproses'
                ];
            }

            // Total bobot = penjumlahan semua bobot kriteria
            $totalBobot = $this->kriterias->sum('bobot');

            // ===========================================================
            // LANGKAH 1: Membangun Matriks Keputusan (X)
            // ===========================================================
            // Matriks keputusan berisi nilai setiap alternatif pada setiap kriteria
            // Rumus: X = [[x11, x12, ..., x1n], [x21, x22, ..., x2n], ...]
            // Dimana:
            // - xij = nilai alternatif ke-i pada kriteria ke-j
            // - i = 1,2,...,m (jumlah alternatif)
            // - j = 1,2,...,n (jumlah kriteria)
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

            // ===========================================================
            // LANGKAH 2: Menentukan Nilai Max (Benefit) dan Min (Cost)
            // ===========================================================
            // Untuk normalisasi, kita perlu mengetahui:
            // - Max: nilai tertinggi pada kolom kriteria (untuk benefit)
            // - Min: nilai terendah pada kolom kriteria (untuk cost)
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

            // ===========================================================
            // LANGKAH 3: Normalisasi Matriks Keputusan (R)
            // ===========================================================
            // Normalisasi mengubah nilai ke skala 0-1 agar dapat dibandingkan
            // Rumus normalisasi:
            // - Untuk kriteria BENEFIT: Rij = Xij / Max(Xj)
            //   (nilai lebih tinggi = lebih baik, dibagi nilai max)
            // - Untuk kriteria COST: Rij = Min(Xj) / Xij
            //   (nilai lebih rendah = lebih baik, min dibagi nilai)
            // Dimana:
            // - Xij = nilai alternatif ke-i pada kriteria ke-j
            // - Max(Xj) = nilai maksimum pada kolom kriteria ke-j
            // - Min(Xj) = nilai minimum pada kolom kriteria ke-j
            $hasilPerhitungan = [];

            foreach ($pelamars as $pelamar) {
                $nilaiTotal = 0;
                $detailPerhitungan = [];

                foreach ($this->kriterias as $kriteria) {
                    $nilai = $matrix[$pelamar->id][$kriteria->id];

                    // =====================================================
                    // Normalisasi berdasarkan jenis atribut
                    // =====================================================
                    if ($kriteria->atribut == 'benefit') {
                        // BENEFIT: semakin tinggi nilai, semakin baik
                        // Rumus: Rij = Xij / Max(Xj)
                        $normalisasi = $maxValues[$kriteria->id] > 0
                            ? $nilai / $maxValues[$kriteria->id]
                            : 0;
                    } else {
                        // COST: semakin rendah nilai, semakin baik
                        // Rumus: Rij = Min(Xj) / Xij
                        $normalisasi = $nilai > 0
                            ? $minValues[$kriteria->id] / $nilai
                            : 0;
                    }

                    // =====================================================
                    // LANGKAH 4: Perhitungan Bobot Normalisasi
                    // =====================================================
                    // Bobot normalisasi = bobot kriteria / total bobot
                    // Digunakan untuk memberi bobot pada setiap kriteria
                    $bobotNormalisasi = $kriteria->bobot / $totalBobot;

                    // =====================================================
                    // LANGKAH 5: Perhitungan Nilai Terbobot (Wij)
                    // =====================================================
                    // Nilai terbobot = normalisasi × bobot normalisasi
                    // Rumus: Wij = Rij × Wj
                    // Dimana Wj adalah bobot kriteria ke-j
                    $nilaiTerbobot = $normalisasi * $bobotNormalisasi;

                    // =====================================================
                    // LANGKAH 6: Penjumlahan Nilai Preferensi (Vi)
                    // =====================================================
                    // Nilai preferensi = jumlah semua nilai terbobot
                    // Rumus: Vi = Σ(Rij × Wj)
                    // Semakin tinggi nilai Vi, semakin baik alternatif tersebut
                    $nilaiTotal += $nilaiTerbobot;

                    // Simpan detail perhitungan untuk setiap kriteria
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

                // Simpan hasil perhitungan untuk setiap pelamar
                $hasilPerhitungan[] = [
                    'pelamar_id' => $pelamar->id,
                    'nilai_saw' => round($nilaiTotal, 4),
                    'detail' => $detailPerhitungan
                ];
            }

            // ===========================================================
            // LANGKAH 7: Ranking (Pengurutan)
            // ===========================================================
            // Urutkan dari nilai preferensi tertinggi ke terendah
            // Semakin tinggi nilai, semakin baik alternatif tersebut
            usort($hasilPerhitungan, function ($a, $b) {
                return $b['nilai_saw'] <=> $a['nilai_saw'];
            });

            // Simpan hasil ranking ke database
            foreach ($hasilPerhitungan as $index => $hasil) {
                $ranking = $index + 1;

                // 3 teratas mendapatkan status "rekomendasi"
                $statusRekomendasi = $ranking <= 2
                    ? 'rekomendasi'
                    : 'tidak_rekomendasi';

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

    /**
     * ===================================================================
     * METODE TOPSIS (Technique for Order Preference by Similarity to Ideal Solution)
     * ===================================================================
     * Langkah-langkah:
     * 1. Membangun matriks keputusan
     * 2. Normalisasi matriks (vektor Euclidean)
     * 3. Menghitung matriks terbobot
     * 4. Menentukan solusi ideal positif dan negatif
     * 5. Menghitung jarak setiap alternatif ke solusi ideal
     * 6. Menghitung nilai preferensi (kedekatan relatif)
     * 7. Mengurutkan hasil (ranking)
     * ===================================================================
     */
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

            $jumlahAlternatif = $pelamars->count();
            $jumlahKriteria = $this->kriterias->count();

            if ($jumlahAlternatif == 0 || $jumlahKriteria == 0) {
                return ['success' => false, 'message' => 'Data alternatif atau kriteria tidak tersedia'];
            }

            // ===========================================================
            // LANGKAH 1: Membangun Matriks Keputusan (X)
            // ===========================================================
            // Matriks keputusan berisi nilai setiap alternatif pada setiap kriteria
            // X = [[x11, x12, ..., x1n], [x21, x22, ..., x2n], ...]
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

            // ===========================================================
            // LANGKAH 2: Normalisasi Matriks (Metode Euclidean)
            // ===========================================================
            // Normalisasi menggunakan rumus vektor Euclidean
            // Rumus: Rij = Xij / √(Σ Xij²)
            // Dimana:
            // - Xij = nilai alternatif ke-i pada kriteria ke-j
            // - Σ Xij² = jumlah kuadrat dari semua nilai pada kolom kriteria ke-j
            // Tujuan: membuat nilai dalam skala yang sama
            $this->normalisasiMatrixTOPSIS($jumlahAlternatif, $jumlahKriteria);

            // ===========================================================
            // LANGKAH 3: Menghitung Matriks Terbobot (V)
            // ===========================================================
            // Matriks terbobot = normalisasi × bobot normalisasi
            // Rumus: Vij = Rij × Wj
            // Dimana Wj = bobot kriteria / total bobot
            $this->hitungMatrixTerbobot($jumlahAlternatif, $jumlahKriteria);

            // ===========================================================
            // LANGKAH 4: Menentukan Solusi Ideal Positif dan Negatif
            // ===========================================================
            // Solusi ideal positif (A+): nilai terbaik untuk setiap kriteria
            // - Untuk BENEFIT: nilai maksimum (semakin tinggi semakin baik)
            // - Untuk COST: nilai minimum (semakin rendah semakin baik)
            // Solusi ideal negatif (A-): nilai terburuk untuk setiap kriteria
            // - Untuk BENEFIT: nilai minimum
            // - Untuk COST: nilai maksimum
            $solusiIdeal = $this->hitungSolusiIdeal($jumlahKriteria);

            // ===========================================================
            // LANGKAH 5: Menghitung Jarak ke Solusi Ideal
            // ===========================================================
            // Jarak menggunakan rumus Euclidean
            // Jarak ke solusi positif (D+): √(Σ(Vij - Vj+)²)
            // Jarak ke solusi negatif (D-): √(Σ(Vij - Vj-)²)
            // Dimana:
            // - Vij = nilai terbobot alternatif ke-i pada kriteria ke-j
            // - Vj+ = solusi ideal positif kriteria ke-j
            // - Vj- = solusi ideal negatif kriteria ke-j
            $jarak = $this->hitungJarakSolusi($jumlahAlternatif, $jumlahKriteria, $solusiIdeal);

            // ===========================================================
            // LANGKAH 6: Menghitung Nilai Preferensi (Kedekatan Relatif)
            // ===========================================================
            // Nilai preferensi mengukur seberapa dekat alternatif ke solusi ideal
            // Rumus: Ci = D- / (D+ + D-)
            // Dimana:
            // - D+ = jarak ke solusi ideal positif
            // - D- = jarak ke solusi ideal negatif
            // Semakin tinggi Ci, semakin baik alternatif tersebut (mendekati 1)
            $nilaiPreferensi = $this->hitungNilaiPreferensi($jumlahAlternatif, $jarak);

            $hasilPerhitungan = [];

            // Simpan solusi ideal untuk setiap alternatif
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

            // ===========================================================
            // LANGKAH 7: Ranking (Pengurutan)
            // ===========================================================
            // Urutkan dari nilai preferensi tertinggi ke terendah
            // Semakin tinggi Ci, semakin baik alternatif tersebut
            usort($hasilPerhitungan, function ($a, $b) {
                return $b['nilai_topsis'] <=> $a['nilai_topsis'];
            });

            // Simpan hasil ranking ke database
            foreach ($hasilPerhitungan as $index => $hasil) {
                $ranking = $index + 1;
                $statusRekomendasi = $ranking <= 2 ? 'rekomendasi' : 'tidak_rekomendasi';

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

    /**
     * ===========================================================
     * FUNGSI NORMALISASI MATRIKS TOPSIS (Euclidean)
     * ===========================================================
     * Rumus: Rij = Xij / √(Σ Xij²)
     *
     * Penjelasan:
     * - Setiap nilai pada kolom kriteria dibagi dengan akar dari
     *   jumlah kuadrat semua nilai pada kolom tersebut
     * - Tujuan: menghasilkan nilai 0-1 yang proporsional
     * - Digunakan untuk menghilangkan satuan antar kriteria
     */
    private function normalisasiMatrixTOPSIS($jumlahAlternatif, $jumlahKriteria)
    {
        $this->matrixNormalisasi = array_fill(0, $jumlahAlternatif, array_fill(0, $jumlahKriteria, 0));

        for ($j = 0; $j < $jumlahKriteria; $j++) {
            // Hitung √(Σ Xij²) untuk setiap kriteria
            $jumlahKuadrat = 0;
            for ($i = 0; $i < $jumlahAlternatif; $i++) {
                $jumlahKuadrat += pow($this->matrixNilai[$i][$j], 2);
            }
            $akarJumlahKuadrat = sqrt($jumlahKuadrat);

            // Normalisasi: Xij / √(Σ Xij²)
            for ($i = 0; $i < $jumlahAlternatif; $i++) {
                if ($akarJumlahKuadrat != 0) {
                    $this->matrixNormalisasi[$i][$j] = $this->matrixNilai[$i][$j] / $akarJumlahKuadrat;
                } else {
                    $this->matrixNormalisasi[$i][$j] = 0;
                }
            }
        }
    }

    /**
     * ===========================================================
     * FUNGSI HITUNG MATRIKS TERBOBOT
     * ===========================================================
     * Rumus: Vij = Rij × Wj
     *
     * Penjelasan:
     * - Rij = nilai normalisasi
     * - Wj = bobot kriteria / total bobot (bobot normalisasi)
     * - Tujuan: memberi pengaruh bobot pada setiap kriteria
     * - Kriteria dengan bobot lebih tinggi akan lebih berpengaruh
     */
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

    /**
     * ===========================================================
     * FUNGSI HITUNG SOLUSI IDEAL
     * ===========================================================
     * Rumus:
     * - A+ (positif): nilai terbaik untuk setiap kriteria
     *   - Benefit: max(Vij)
     *   - Cost: min(Vij)
     * - A- (negatif): nilai terburuk untuk setiap kriteria
     *   - Benefit: min(Vij)
     *   - Cost: max(Vij)
     *
     * Penjelasan:
     * - A+ adalah alternatif ideal (terbaik secara teoritis)
     * - A- adalah alternatif anti-ideal (terburuk secara teoritis)
     * - Semakin dekat ke A+, semakin baik alternatif
     */
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
                // Benefit: lebih tinggi = lebih baik
                $solusiIdeal['positif'][$j] = max($nilaiTerbobot);
                $solusiIdeal['negatif'][$j] = min($nilaiTerbobot);
            } else {
                // Cost: lebih rendah = lebih baik
                $solusiIdeal['positif'][$j] = min($nilaiTerbobot);
                $solusiIdeal['negatif'][$j] = max($nilaiTerbobot);
            }
        }

        return $solusiIdeal;
    }

    /**
     * ===========================================================
     * FUNGSI HITUNG JARAK KE SOLUSI IDEAL (Euclidean)
     * ===========================================================
     * Rumus:
     * - D+ = √(Σ(Vij - Vj+)²) (jarak ke solusi positif)
     * - D- = √(Σ(Vij - Vj-)²) (jarak ke solusi negatif)
     *
     * Penjelasan:
     * - Menggunakan rumus jarak Euclidean (akar kuadrat)
     * - D+ = semakin kecil = semakin baik (dekat ke ideal)
     * - D- = semakin besar = semakin baik (jauh dari anti-ideal)
     * - Jarak dihitung per alternatif ke setiap solusi ideal
     */
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
                // Jarak ke solusi positif: (Vij - Vj+)²
                $jarakPositif += pow($this->matrixTerbobot[$i][$j] - $solusiIdeal['positif'][$j], 2);
                // Jarak ke solusi negatif: (Vij - Vj-)²
                $jarakNegatif += pow($this->matrixTerbobot[$i][$j] - $solusiIdeal['negatif'][$j], 2);
            }

            $jarak['positif'][$i] = sqrt($jarakPositif);
            $jarak['negatif'][$i] = sqrt($jarakNegatif);
        }

        return $jarak;
    }

    /**
     * ===========================================================
     * FUNGSI HITUNG NILAI PREFERENSI (KEDEKATAN RELATIF)
     * ===========================================================
     * Rumus: Ci = D- / (D+ + D-)
     *
     * Penjelasan:
     * - Ci = nilai preferensi (0 ≤ Ci ≤ 1)
     * - D+ = jarak ke solusi ideal positif
     * - D- = jarak ke solusi ideal negatif
     * - Jika Ci mendekati 1 → alternatif semakin baik
     * - Jika Ci mendekati 0 → alternatif semakin buruk
     * - Alternatif terbaik adalah yang memiliki Ci tertinggi
     */
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

    /**
     * ===========================================================
     * FUNGSI GET RANKING SAW
     * ===========================================================
     * Mengambil hasil ranking SAW dari database yang sudah diurutkan
     */
    public function getRankingSAW($lowonganId)
    {
        return HasilSAW::with(['pelamar'])
            ->where('lowongan_id', $lowonganId)
            ->orderBy('ranking', 'asc')
            ->get();
    }

    /**
     * ===========================================================
     * FUNGSI GET RANKING TOPSIS
     * ===========================================================
     * Mengambil hasil ranking TOPSIS dari database yang sudah diurutkan
     */
    public function getRankingTOPSIS($lowonganId)
    {
        return HasilTOPSIS::with(['pelamar'])
            ->where('lowongan_id', $lowonganId)
            ->orderBy('ranking', 'asc')
            ->get();
    }

    /**
     * ===========================================================
     * FUNGSI APPROVE KANDIDAT
     * ===========================================================
     * Menyetujui kandidat untuk lanjut ke tahap offering
     * Memilih metode terbaik antara SAW atau TOPSIS
     */
    public function approveKandidat($pelamarId, $lowonganId, $metode, $catatan, $userId)
    {
        try {
            DB::beginTransaction();

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
            }

            DB::commit();

            return ['success' => true, 'message' => 'Kandidat berhasil di-approve ke tahap offering'];
        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false, 'message' => 'Gagal approve kandidat: ' . $e->getMessage()];
        }
    }
}
