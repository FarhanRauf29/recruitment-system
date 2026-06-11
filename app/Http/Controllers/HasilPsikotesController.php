<?php

namespace App\Http\Controllers;

use App\Models\HasilPsikotes;
use App\Models\SesiPsikotes;
use App\Models\JawabanPsikotes;
use App\Models\PelamarTahapan;
use App\Models\SoalPsikotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HasilPsikotesController extends Controller
{
    public function index(Request $request)
    {
        $query = HasilPsikotes::with(['pelamar', 'lowongan', 'sesi']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pelamar', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('lulus', $request->status);
        }

        $hasils = $query->latest()->paginate(10);
        return view('admin.hasilpsikotes.index', compact('hasils'));
    }

    public function show($id)
    {
        $hasil = HasilPsikotes::with([
            'pelamar',
            'lowongan',
            'sesi',
            'konfigurasi',
            'sesi.jawabanPsikotes.soalPsikotes'
        ])->findOrFail($id);

        return view('admin.hasilpsikotes.show', compact('hasil'));
    }

    public function destroy($id)
    {
        HasilPsikotes::findOrFail($id)->delete();
        return redirect()->route('admin.hasilpsikotes.index')
            ->with('success', 'Hasil psikotes berhasil dihapus.');
    }

    public function showForPelamar()
    {
        $user = Auth::user();
        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')
                ->with('error', 'Data pelamar tidak ditemukan');
        }

        $hasils = HasilPsikotes::where('pelamar_id', $pelamar->id)
            ->with(['lowongan', 'sesi'])
            ->latest()
            ->paginate(10);

        return view('pelamar.hasilpsikotes.index', compact('hasils'));
    }

    public function showHasilPelamar($token)
    {
        $user = Auth::user();
        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')
                ->with('error', 'Data pelamar tidak ditemukan.');
        }

        $sesi = SesiPsikotes::where('token_sesi', $token)
            ->where('pelamar_id', $pelamar->id)
            ->with('lowongan', 'konfigurasi')
            ->first();

        if (!$sesi) {
            return redirect()->route('pelamar.psikotes.index')
                ->with('error', 'Sesi psikotes tidak ditemukan.');
        }

        if (!$sesi->selesai) {
            return redirect()->route('pelamar.psikotes.soal', $token)
                ->with('warning', 'Tes belum selesai. Silakan selesaikan tes terlebih dahulu.');
        }

        $hasil = HasilPsikotes::where('sesi_psikotes_id', $sesi->id)->first();

        if (!$hasil) {
            $this->hitungHasilPsikotes($sesi);
            $hasil = HasilPsikotes::where('sesi_psikotes_id', $sesi->id)->first();
        }

        if ($hasil && ($hasil->nilai_kognitif === null || $hasil->nilai_kepribadian === null || $hasil->nilai_minat === null)) {
            $this->recalculateAllCategories($sesi);
            $hasil = HasilPsikotes::where('sesi_psikotes_id', $sesi->id)->first();
        }

        if (!$hasil) {
            return redirect()->route('pelamar.psikotes.index')
                ->with('error', 'Hasil tes belum tersedia. Silakan coba lagi nanti.');
        }

        $jawaban = JawabanPsikotes::with('soalPsikotes')
            ->where('sesi_psikotes_id', $sesi->id)
            ->get()
            ->filter(fn($j) => $j->soalPsikotes !== null);

        return view('pelamar.psikotes.hasil', compact('sesi', 'hasil', 'jawaban'));
    }

    public function recalculateAllCategories($sesi)
    {
        $jawaban = JawabanPsikotes::where('sesi_psikotes_id', $sesi->id)
            ->with('soalPsikotes')
            ->get();

        $jawabanKognitif = $jawaban->filter(fn($j) => $j->soalPsikotes && $j->soalPsikotes->jenis == 'kognitif');
        $jawabanKepribadian = $jawaban->filter(fn($j) => $j->soalPsikotes && $j->soalPsikotes->jenis == 'kepribadian');
        $jawabanMinat = $jawaban->filter(fn($j) => $j->soalPsikotes && $j->soalPsikotes->jenis == 'minat');

        $totalBobotKognitif = 0;
        $totalBobotBenar = 0;
        $jumlahBenar = 0;
        $jumlahSalah = 0;

        foreach ($jawabanKognitif as $jwb) {
            $soal = $jwb->soalPsikotes;
            $bobot = $soal->bobot_nilai ?? 1;
            $totalBobotKognitif += $bobot;

            $isBenar = strtoupper(trim($jwb->jawaban)) === strtoupper(trim($soal->kunci_jawaban));

            $jwb->update([
                'benar' => $isBenar,
                'skor' => $isBenar ? $bobot : 0
            ]);

            if ($isBenar) {
                $jumlahBenar++;
                $totalBobotBenar += $bobot;
            } else {
                $jumlahSalah++;
            }
        }

        $nilaiKognitif = $totalBobotKognitif > 0
            ? round(($totalBobotBenar / $totalBobotKognitif) * 100, 2)
            : 0;

        $totalSkorKepribadian = 0;
        $maxSkorKepribadian = 0;

        foreach ($jawabanKepribadian as $jwb) {
            $soal = $jwb->soalPsikotes;
            $bobotOpsiArray = $soal->getBobotOpsiAttribute();
            $skor = $bobotOpsiArray[$jwb->jawaban] ?? 0;

            $jwb->update([
                'skor_kepribadian' => $skor
            ]);

            $totalSkorKepribadian += $skor;
            $maxSkorKepribadian += 5;
        }

        $nilaiKepribadian = $maxSkorKepribadian > 0
            ? round(($totalSkorKepribadian / $maxSkorKepribadian) * 100, 2)
            : 0;

        $totalSkorMinat = 0;
        $maxSkorMinat = 0;

        foreach ($jawabanMinat as $jwb) {
            $soal = $jwb->soalPsikotes;
            $bobotOpsiArray = $soal->getBobotOpsiAttribute();
            $skor = $bobotOpsiArray[$jwb->jawaban] ?? 0;

            $jwb->update([
                'skor_minat_bakat' => $skor
            ]);

            $totalSkorMinat += $skor;
            $maxSkorMinat += 5;
        }

        $nilaiMinat = $maxSkorMinat > 0
            ? round(($totalSkorMinat / $maxSkorMinat) * 100, 2)
            : 0;

        $nilaiAkhir = 0;
        $jumlahJenis = 0;

        if ($jawabanKognitif->count() > 0) {
            $nilaiAkhir += $nilaiKognitif;
            $jumlahJenis++;
        }
        if ($jawabanKepribadian->count() > 0) {
            $nilaiAkhir += $nilaiKepribadian;
            $jumlahJenis++;
        }
        if ($jawabanMinat->count() > 0) {
            $nilaiAkhir += $nilaiMinat;
            $jumlahJenis++;
        }

        $nilaiAkhir = $jumlahJenis > 0 ? round($nilaiAkhir / $jumlahJenis, 2) : 0;

        $konfigurasi = $sesi->konfigurasi;
        $batasKelulusan = $konfigurasi ? ($konfigurasi->batas_nilai_kelulusan ?? 60) : 60;
        $lulus = $nilaiAkhir >= $batasKelulusan;
        $rekomendasi = $this->tentukanRekomendasi($nilaiAkhir);

        $existingHasil = HasilPsikotes::where('sesi_psikotes_id', $sesi->id)->first();

        if ($existingHasil) {
            $existingHasil->update([
                'soal_minat_ditampilkan' => $jawabanMinat->count(),
                'soal_minat_skor' => $totalSkorMinat,
                'nilai_minat' => $nilaiMinat,
                'nilai_kognitif' => $nilaiKognitif,
                'nilai_kepribadian' => $nilaiKepribadian,
                'nilai_akhir' => $nilaiAkhir,
                'lulus' => $lulus,
                'rekomendasi' => $rekomendasi,
                'keterangan' => "Nilai Kognitif: {$nilaiKognitif}, Kepribadian: {$nilaiKepribadian}, Minat: {$nilaiMinat}, Total: {$nilaiAkhir}"
            ]);
            $hasil = $existingHasil;
        } else {
            $hasil = HasilPsikotes::create([
                'sesi_psikotes_id' => $sesi->id,
                'pelamar_id' => $sesi->pelamar_id,
                'lowongan_id' => $sesi->lowongan_id,
                'pelamar_tahapan_id' => $sesi->pelamar_tahapan_id,
                'konfigurasi_id' => $sesi->konfigurasi_id,
                'total_soal_ditampilkan' => $sesi->jumlah_soal_ditampilkan ?? 0,
                'soal_dijawab' => $jawaban->count(),
                'soal_kognitif_ditampilkan' => $jawabanKognitif->count(),
                'soal_kepribadian_ditampilkan' => $jawabanKepribadian->count(),
                'soal_minat_ditampilkan' => $jawabanMinat->count(),
                'soal_kognitif_benar' => $jumlahBenar,
                'soal_kognitif_salah' => $jumlahSalah,
                'soal_minat_skor' => $totalSkorMinat,
                'nilai_kognitif' => $nilaiKognitif,
                'nilai_kepribadian' => $nilaiKepribadian,
                'nilai_minat' => $nilaiMinat,
                'nilai_akhir' => $nilaiAkhir,
                'lulus' => $lulus,
                'rekomendasi' => $rekomendasi,
                'waktu_selesai' => now(),
                'durasi_pengerjaan_detik' => $sesi->durasi_terpakai_detik ?? 0,
                'keterangan' => "Nilai Kognitif: {$nilaiKognitif}, Kepribadian: {$nilaiKepribadian}, Minat: {$nilaiMinat}, Total: {$nilaiAkhir}"
            ]);
        }

        $this->updateTahapanPelamar($sesi);

        return $hasil;
    }

    public function hitungHasilPsikotes($sesi)
    {
        return $this->recalculateAllCategories($sesi);
    }

    private function tentukanRekomendasi($nilai)
    {
        if ($nilai >= 90) return 'sangat_baik';
        if ($nilai >= 80) return 'baik';
        if ($nilai >= 70) return 'cukup';
        if ($nilai >= 60) return 'kurang';
        return 'tidak_lulus';
    }

    private function updateTahapanPelamar($sesi)
    {
        $hasil = HasilPsikotes::where('sesi_psikotes_id', $sesi->id)->first();
        $tahapan = PelamarTahapan::find($sesi->pelamar_tahapan_id);

        if ($hasil && $tahapan) {
            if ($hasil->lulus) {
                $tahapan->update([
                    'status' => 'lolos',
                    'tanggal_selesai' => now(),
                    'catatan' => 'Lulus psikotes dengan nilai: ' . ($hasil->nilai_akhir ?? 0)
                ]);
            } else {
                $tahapan->update([
                    'status' => 'gagal',
                    'tanggal_selesai' => now(),
                    'catatan' => 'Tidak lulus psikotes dengan nilai: ' . ($hasil->nilai_akhir ?? 0)
                ]);
            }
        }
    }
}
