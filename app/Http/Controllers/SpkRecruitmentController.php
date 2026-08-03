<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\PenilaianKandidat;
use App\Models\HasilSAW;
use App\Models\HasilTOPSIS;
use App\Models\Pelamar;
use App\Models\Lowongan;
use App\Models\PelamarTahapan;
use App\Models\HasilPsikotes;
use App\Models\MCU;
use App\Models\Wawancara;
use App\Models\RiwayatPendidikan;
use App\Models\RiwayatPengalaman;
use App\Models\RiwayatMagang;
use App\Models\RiwayatOrganisasi;
use App\Models\RiwayatBahasa;
use App\Models\RiwayatKeahlian;
use App\Services\SpkCalculationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpkRecruitmentController extends Controller
{
    protected $spkService;

    public function __construct(SpkCalculationService $spkService)
    {
        $this->spkService = $spkService;
    }

    public function index()
    {
        $lowongans = Lowongan::where('status', 'buka')->get();
        return view('admin.spk.index', compact('lowongans'));
    }

    public function kriteria()
    {
        $kriterias = Kriteria::orderBy('kode_kriteria')->get();
        return view('admin.spk.kriteria', compact('kriterias'));
    }

    public function storeKriteria(Request $request)
    {
        $request->validate([
            'kode_kriteria' => 'required|unique:kriteria',
            'nama_kriteria' => 'required',
            'bobot' => 'required|numeric|min:0|max:100',
            'atribut' => 'required|in:benefit,cost',
        ]);

        Kriteria::create($request->all());
        return redirect()->route('admin.spk.kriteria')->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function updateKriteria(Request $request, $id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $request->validate([
            'nama_kriteria' => 'required',
            'bobot' => 'required|numeric|min:0|max:100',
            'atribut' => 'required|in:benefit,cost',
        ]);
        $kriteria->update($request->all());
        return redirect()->route('admin.spk.kriteria')->with('success', 'Kriteria berhasil diperbarui');
    }

    public function deleteKriteria($id)
    {
        $kriteria = Kriteria::findOrFail($id);
        $kriteria->delete();
        return redirect()->route('admin.spk.kriteria')->with('success', 'Kriteria berhasil dihapus');
    }

    public function sinkronisasiNilai($lowonganId)
    {
        $pelamars = Pelamar::whereHas('pelamarTahapan', function ($q) use ($lowonganId) {
            $q->where('lowongan_id', $lowonganId);
        })->get();

        $kriteriaAdministrasi = Kriteria::where('nama_kriteria', 'like', '%administrasi%')
            ->orWhere('nama_kriteria', 'like', '%berkas%')
            ->orWhere('kode_kriteria', 'C1')
            ->first();

        $kriteriaPsikotes = Kriteria::where('nama_kriteria', 'like', '%psikotes%')
            ->orWhere('kode_kriteria', 'C2')
            ->first();

        $kriteriaMCU = Kriteria::where('nama_kriteria', 'like', '%MCU%')
            ->orWhere('nama_kriteria', 'like', '%medical%')
            ->orWhere('kode_kriteria', 'C3')
            ->first();

        $kriteriaWawancara = Kriteria::where('nama_kriteria', 'like', '%wawancara%')
            ->orWhere('kode_kriteria', 'C4')
            ->first();

        foreach ($pelamars as $pelamar) {
            if ($kriteriaAdministrasi) {
                $this->sinkronNilaiAdministrasi($pelamar->id, $lowonganId, $kriteriaAdministrasi->id);
            }
            if ($kriteriaPsikotes) {
                $this->sinkronNilaiPsikotes($pelamar->id, $lowonganId, $kriteriaPsikotes->id);
            }
            if ($kriteriaMCU) {
                $this->sinkronNilaiMCU($pelamar->id, $lowonganId, $kriteriaMCU->id);
            }
            if ($kriteriaWawancara) {
                $this->sinkronNilaiWawancara($pelamar->id, $lowonganId, $kriteriaWawancara->id);
            }
        }

        return redirect()->route('admin.spk.penilaian', $lowonganId)
            ->with('success', 'Nilai penilaian berhasil disinkronisasi dari data tahapan pelamar');
    }

    private function hitungNilaiDariProfil($pelamarId)
    {
        $pendidikan = RiwayatPendidikan::where('pelamar_id', $pelamarId)->get();
        $pengalaman = RiwayatPengalaman::where('pelamar_id', $pelamarId)->get();
        $magang = RiwayatMagang::where('pelamar_id', $pelamarId)->get();
        $organisasi = RiwayatOrganisasi::where('pelamar_id', $pelamarId)->get();
        $bahasa = RiwayatBahasa::where('pelamar_id', $pelamarId)->get();
        $keahlian = RiwayatKeahlian::where('pelamar_id', $pelamarId)->get();

        $nilaiPendidikan = $this->nilaiPendidikan($pendidikan);
        $nilaiPengalaman = $this->nilaiPengalaman($pengalaman);
        $nilaiMagang = $this->nilaiMagang($magang);
        $nilaiOrganisasi = $this->nilaiOrganisasi($organisasi);
        $nilaiBahasa = $this->nilaiBahasa($bahasa);
        $nilaiKeahlian = $this->nilaiKeahlian($keahlian);

        $totalNilai = ($nilaiPendidikan * 0.30) + ($nilaiPengalaman * 0.25) + ($nilaiMagang * 0.10) + ($nilaiOrganisasi * 0.15) + ($nilaiBahasa * 0.10) + ($nilaiKeahlian * 0.10);
        return round($totalNilai, 2);
    }

    private function nilaiPendidikan($pendidikan)
    {
        $tingkatNilai = ['SD' => 40, 'SMP' => 50, 'SMA/SMK' => 60, 'D3' => 75, 'D4' => 80, 'S1' => 85, 'S2' => 100];
        if ($pendidikan->isEmpty()) return 0;
        $tertinggi = $pendidikan->sortByDesc(function ($item) use ($tingkatNilai) {
            return $tingkatNilai[$item->tingkat] ?? 0;
        })->first();
        return $tingkatNilai[$tertinggi->tingkat] ?? 60;
    }

    private function nilaiPengalaman($pengalaman)
    {
        if ($pengalaman->isEmpty()) return 40;
        $totalBulan = 0;
        foreach ($pengalaman as $exp) {
            $tahunMasuk = (int)$exp->tahun_masuk;
            $tahunKeluar = $exp->is_current ? date('Y') : (int)$exp->tahun_keluar;
            if ($tahunKeluar && $tahunMasuk) $totalBulan += ($tahunKeluar - $tahunMasuk) * 12;
        }
        if ($totalBulan >= 60) return 100;
        if ($totalBulan >= 36) return 90;
        if ($totalBulan >= 24) return 80;
        if ($totalBulan >= 12) return 70;
        if ($totalBulan > 0) return 60;
        return 40;
    }

    private function nilaiMagang($magang)
    {
        $jumlah = $magang->count();
        if ($jumlah > 1) return 100;
        if ($jumlah == 1) return 70;
        return 30;
    }

    private function nilaiOrganisasi($organisasi)
    {
        if ($organisasi->isEmpty()) return 30;
        $posisiNilai = ['Ketua' => 100, 'Wakil Ketua' => 95, 'Sekretaris' => 90, 'Bendahara' => 90, 'Koordinator' => 85, 'Pengurus' => 85, 'Anggota' => 70];
        $nilaiTertinggi = 0;
        foreach ($organisasi as $org) {
            foreach ($posisiNilai as $key => $nilai) {
                if (stripos($org->jabatan, $key) !== false) {
                    $nilaiTertinggi = max($nilaiTertinggi, $nilai);
                    break;
                }
            }
        }
        return $nilaiTertinggi > 0 ? $nilaiTertinggi : 70;
    }

    private function nilaiBahasa($bahasa)
    {
        $jumlah = $bahasa->count();
        if ($jumlah > 2) return 100;
        if ($jumlah == 2) return 75;
        if ($jumlah == 1) return 50;
        return 30;
    }

    private function nilaiKeahlian($keahlian)
    {
        $jumlah = $keahlian->count();
        if ($jumlah > 5) return 100;
        if ($jumlah >= 3) return 80;
        if ($jumlah >= 1) return 60;
        return 40;
    }

    private function sinkronNilaiAdministrasi($pelamarId, $lowonganId, $kriteriaId)
    {
        $nilaiDariProfil = $this->hitungNilaiDariProfil($pelamarId);
        $pelamarTahapan = PelamarTahapan::where('pelamar_id', $pelamarId)
            ->where('lowongan_id', $lowonganId)
            ->whereHas('tahapan', function ($q) {
                $q->where('nama_tahapan', 'like', '%screening%')
                    ->orWhere('nama_tahapan', 'like', '%berkas%');
            })->first();

        $nilai = $nilaiDariProfil;
        $catatan = 'Nilai dihitung dari profil pelamar: Pendidikan, Pengalaman, Organisasi, dll';

        if ($pelamarTahapan) {
            if ($pelamarTahapan->status == 'lolos') {
                $nilai = $nilaiDariProfil;
                $catatan = 'Lolos seleksi administrasi. ' . $catatan;
            } elseif ($pelamarTahapan->status == 'gagal') {
                $nilai = 0;
                $catatan = 'Gagal seleksi administrasi';
            } elseif ($pelamarTahapan->status == 'diproses') {
                $nilai = $nilaiDariProfil * 0.5;
                $catatan = 'Sedang dalam proses seleksi administrasi. ' . $catatan;
            } else {
                $nilai = $nilaiDariProfil * 0.3;
                $catatan = 'Menunggu verifikasi administrasi. ' . $catatan;
            }
        }

        PenilaianKandidat::updateOrCreate(
            ['pelamar_id' => $pelamarId, 'lowongan_id' => $lowonganId, 'kriteria_id' => $kriteriaId],
            ['nilai' => $nilai, 'catatan' => $catatan]
        );
    }

    private function sinkronNilaiPsikotes($pelamarId, $lowonganId, $kriteriaId)
    {
        $hasilPsikotes = HasilPsikotes::where('pelamar_id', $pelamarId)->where('lowongan_id', $lowonganId)->latest()->first();
        $nilai = 0;
        $catatan = 'Belum mengikuti psikotes';
        if ($hasilPsikotes) {
            $nilai = $hasilPsikotes->nilai_akhir ?? 0;
            $catatan = 'Nilai psikotes: ' . $nilai . ' - ' . ($hasilPsikotes->lulus ? 'Lulus' : 'Tidak Lulus');
        }
        PenilaianKandidat::updateOrCreate(
            ['pelamar_id' => $pelamarId, 'lowongan_id' => $lowonganId, 'kriteria_id' => $kriteriaId],
            ['nilai' => $nilai, 'catatan' => $catatan]
        );
    }

    private function sinkronNilaiMCU($pelamarId, $lowonganId, $kriteriaId)
    {
        $mcu = MCU::where('pelamar_id', $pelamarId)->where('lowongan_id', $lowonganId)->latest()->first();
        $nilai = 0;
        $catatan = 'Belum melakukan MCU';
        if ($mcu) {
            if ($mcu->nilai_mcu) {
                $nilai = $mcu->nilai_mcu;
                $catatan = 'Hasil MCU: ' . ($mcu->kondisi_kesehatan ?? 'Tidak diketahui') . ' - Nilai: ' . $nilai;
            } elseif ($mcu->status == 'lolos') {
                $nilai = 85;
                $catatan = 'Lolos MCU';
            } elseif ($mcu->status == 'gagal') {
                $nilai = 0;
                $catatan = 'Gagal MCU';
            } elseif ($mcu->status == 'hadir') {
                $nilai = 50;
                $catatan = 'Hadir MCU, menunggu hasil';
            } else {
                $nilai = 25;
                $catatan = 'Dijadwalkan MCU';
            }
        }
        PenilaianKandidat::updateOrCreate(
            ['pelamar_id' => $pelamarId, 'lowongan_id' => $lowonganId, 'kriteria_id' => $kriteriaId],
            ['nilai' => $nilai, 'catatan' => $catatan]
        );
    }

    private function sinkronNilaiWawancara($pelamarId, $lowonganId, $kriteriaId)
    {
        $wawancara = Wawancara::where('pelamar_id', $pelamarId)->where('lowongan_id', $lowonganId)->latest()->first();
        $nilai = 0;
        $catatan = 'Belum melakukan wawancara';
        if ($wawancara) {
            if ($wawancara->nilai_total) {
                $nilai = $wawancara->nilai_total;
                $catatan = 'Nilai wawancara: ' . $nilai;
            } elseif ($wawancara->status == 'lolos') {
                $nilai = 85;
                $catatan = 'Lolos wawancara';
            } elseif ($wawancara->status == 'gagal') {
                $nilai = 0;
                $catatan = 'Gagal wawancara';
            } elseif ($wawancara->status == 'hadir') {
                $nilai = 50;
                $catatan = 'Hadir wawancara, menunggu penilaian';
            } elseif ($wawancara->status == 'dikonfirmasi') {
                $nilai = 30;
                $catatan = 'Konfirmasi kehadiran wawancara';
            } elseif ($wawancara->status == 'dijadwalkan') {
                $nilai = 20;
                $catatan = 'Dijadwalkan wawancara';
            }
        }
        PenilaianKandidat::updateOrCreate(
            ['pelamar_id' => $pelamarId, 'lowongan_id' => $lowonganId, 'kriteria_id' => $kriteriaId],
            ['nilai' => $nilai, 'catatan' => $catatan]
        );
    }

    public function penilaian($lowonganId)
    {
        $this->sinkronisasiNilai($lowonganId);
        $lowongan = Lowongan::findOrFail($lowonganId);
        $kriterias = Kriteria::where('is_active', true)->get();
        $pelamars = Pelamar::whereHas('pelamarTahapan', function ($q) use ($lowonganId) {
            $q->where('lowongan_id', $lowonganId);
        })->get();

        $penilaian = [];
        foreach ($pelamars as $pelamar) {
            foreach ($kriterias as $kriteria) {
                $nilai = PenilaianKandidat::where('pelamar_id', $pelamar->id)
                    ->where('lowongan_id', $lowonganId)
                    ->where('kriteria_id', $kriteria->id)
                    ->first();
                $penilaian[$pelamar->id][$kriteria->id] = $nilai ? $nilai->nilai : null;
            }
        }
        return view('admin.spk.penilaian', compact('lowongan', 'pelamars', 'kriterias', 'penilaian'));
    }

    public function storePenilaian(Request $request, $lowonganId)
    {
        $request->validate(['nilai' => 'required|array', 'nilai.*.*' => 'nullable|numeric|min:0|max:100']);
        DB::beginTransaction();
        try {
            foreach ($request->nilai as $pelamarId => $kriterias) {
                foreach ($kriterias as $kriteriaId => $nilai) {
                    if ($nilai !== null && $nilai !== '') {
                        PenilaianKandidat::updateOrCreate(
                            ['pelamar_id' => $pelamarId, 'lowongan_id' => $lowonganId, 'kriteria_id' => $kriteriaId],
                            ['nilai' => $nilai, 'catatan' => $request->catatan[$pelamarId][$kriteriaId] ?? null]
                        );
                    }
                }
            }
            DB::commit();
            return redirect()->route('admin.spk.penilaian', $lowonganId)->with('success', 'Penilaian berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan penilaian: ' . $e->getMessage());
        }
    }

    public function hitungSAW($lowonganId)
    {
        $result = $this->spkService->hitungSAW($lowonganId);
        if ($result['success']) {
            return redirect()->route('admin.spk.hasil', $lowonganId)->with('success', $result['message']);
        }
        return redirect()->back()->with('error', $result['message']);
    }

    public function hitungTOPSIS($lowonganId)
    {
        $result = $this->spkService->hitungTOPSIS($lowonganId);
        if ($result['success']) {
            return redirect()->route('admin.spk.hasil', $lowonganId)->with('success', $result['message']);
        }
        return redirect()->back()->with('error', $result['message']);
    }

    public function hasil($lowonganId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);
        $hasilSAW = HasilSAW::with(['pelamar'])->where('lowongan_id', $lowonganId)->orderBy('ranking', 'asc')->get();
        $hasilTOPSIS = HasilTOPSIS::with(['pelamar'])->where('lowongan_id', $lowonganId)->orderBy('ranking', 'asc')->get();
        return view('admin.spk.hasil', compact('lowongan', 'hasilSAW', 'hasilTOPSIS'));
    }

    public function detailSAW($lowonganId, $pelamarId)
    {
        $hasil = HasilSAW::with(['pelamar'])->where('lowongan_id', $lowonganId)->where('pelamar_id', $pelamarId)->firstOrFail();
        return view('admin.spk.detail-saw', compact('hasil'));
    }

    public function detailTOPSIS($lowonganId, $pelamarId)
    {
        $hasil = HasilTOPSIS::with(['pelamar'])->where('lowongan_id', $lowonganId)->where('pelamar_id', $pelamarId)->firstOrFail();
        return view('admin.spk.detail-topsis', compact('hasil'));
    }

    public function approve(Request $request, $lowonganId, $pelamarId)
    {
        $request->validate([
            'metode' => 'required|in:saw,topsis',
            'catatan' => 'nullable|string',
        ]);

        $lowongan = Lowongan::find($lowonganId);

        if (!$lowongan->isSlotAvailable()) {
            return redirect()->back()->with('error', 'Slot lowongan sudah penuh. Tidak dapat approve kandidat baru.');
        }

        DB::beginTransaction();

        try {
            $pelamarTahapan = PelamarTahapan::where('pelamar_id', $pelamarId)
                ->where('lowongan_id', $lowonganId)
                ->first();

            if (!$pelamarTahapan) {
                return redirect()->back()->with('error', 'Data pelamar tidak ditemukan');
            }

            $nilaiAkhir = 0;
            if ($request->metode == 'saw') {
                $hasil = HasilSAW::where('pelamar_id', $pelamarId)->where('lowongan_id', $lowonganId)->first();
                if ($hasil) {
                    $nilaiAkhir = $hasil->nilai_saw;
                }
            } else {
                $hasil = HasilTOPSIS::where('pelamar_id', $pelamarId)->where('lowongan_id', $lowonganId)->first();
                if ($hasil) {
                    $nilaiAkhir = $hasil->nilai_topsis;
                }
            }

            $pelamarTahapan->spk_status = 'approved';
            $pelamarTahapan->metode_terbaik = $request->metode;
            $pelamarTahapan->nilai_akhir_spk = $nilaiAkhir;
            $pelamarTahapan->catatan_spk = $request->catatan;
            $pelamarTahapan->tanggal_approval = now();
            $pelamarTahapan->approved_by = Auth::id();
            $pelamarTahapan->save();

            $lowongan->updateStatusIfNeeded();

            DB::commit();

            Log::info('Approve berhasil', [
                'pelamar_id' => $pelamarId,
                'lowongan_id' => $lowonganId,
                'status' => $pelamarTahapan->spk_status,
                'nilai' => $nilaiAkhir,
                'slot_terisi' => $lowongan->getJumlahTerisiAttribute()
            ]);

            return redirect()->route('admin.offering.index', $lowonganId)
                ->with('success', 'Kandidat berhasil di-approve. Slot terisi: ' . $lowongan->getJumlahTerisiAttribute() . ' / ' . $lowongan->slot);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Approve error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal approve kandidat: ' . $e->getMessage());
        }
    }

    public function offering($lowonganId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);
        $kandidatOffering = PelamarTahapan::with(['pelamar'])
            ->where('lowongan_id', $lowonganId)
            ->where('spk_status', 'approved')
            ->get();
        return view('admin.spk.offering', compact('lowongan', 'kandidatOffering'));
    }

    public function pelamarHasil()
    {
        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')->with('error', 'Data pelamar tidak ditemukan');
        }
        $hasilSAW = HasilSAW::with(['lowongan'])->where('pelamar_id', $pelamar->id)->orderBy('created_at', 'desc')->first();
        $hasilTOPSIS = HasilTOPSIS::with(['lowongan'])->where('pelamar_id', $pelamar->id)->orderBy('created_at', 'desc')->first();
        $statusSPK = PelamarTahapan::where('pelamar_id', $pelamar->id)->where('spk_status', '!=', 'pending')->first();
        return view('pelamar.spk.hasil', compact('hasilSAW', 'hasilTOPSIS', 'statusSPK'));
    }

    public function exportExcel($lowonganId, $metode)
    {
        try {
            $lowongan = Lowongan::findOrFail($lowonganId);
            $slot = $lowongan->slot;

            if ($metode == 'saw') {
                $data = HasilSAW::with(['pelamar'])->where('lowongan_id', $lowonganId)->orderBy('ranking', 'asc')->get();
                $judulMetode = 'SAW (Simple Additive Weighting)';
                $kolomNilai = 'Nilai SAW';
            } else {
                $data = HasilTOPSIS::with(['pelamar'])->where('lowongan_id', $lowonganId)->orderBy('ranking', 'asc')->get();
                $judulMetode = 'TOPSIS (Technique for Order Preference by Similarity to Ideal Solution)';
                $kolomNilai = 'Nilai TOPSIS';
            }

            $filename = "Hasil_Ranking_" . strtoupper($metode) . "_" . str_replace(' ', '_', $lowongan->judul_lowongan) . "_" . date('Ymd_His') . ".xlsx";

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Hasil Ranking ' . strtoupper($metode));

            $sheet->getStyle('A1:G1')->getFont()->setBold(true);
            $sheet->getStyle('A1:G1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('FF0D6EFD');
            $sheet->getStyle('A1:G1')->getFont()->getColor()->setARGB('FFFFFFFF');

            $sheet->mergeCells('A1:G1');
            $sheet->setCellValue('A1', 'LAPORAN HASIL RANKING KANDIDAT');
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A1')->getFont()->setSize(14);
            $sheet->getStyle('A1')->getFont()->setBold(true);

            $row = 2;
            $sheet->setCellValue('A' . $row, 'Lowongan');
            $sheet->setCellValue('B' . $row, ': ' . $lowongan->judul_lowongan);
            $row++;

            $sheet->setCellValue('A' . $row, 'Slot Lowongan');
            $sheet->setCellValue('B' . $row, ': ' . $slot . ' orang');
            $row++;

            $sheet->setCellValue('A' . $row, 'Metode Perhitungan');
            $sheet->setCellValue('B' . $row, ': ' . $judulMetode);
            $row++;

            $sheet->setCellValue('A' . $row, 'Tanggal Cetak');
            $sheet->setCellValue('B' . $row, ': ' . now()->translatedFormat('l, d F Y  H:i'));
            $row++;

            $sheet->setCellValue('A' . $row, 'Total Kandidat');
            $sheet->setCellValue('B' . $row, ': ' . $data->count() . ' orang');
            $row++;

            $sheet->getStyle('A2:B' . ($row - 1))->getFont()->setSize(11);
            $sheet->getStyle('A2:B' . ($row - 1))->getFont()->getColor()->setARGB('FF333333');
            $sheet->getStyle('A2:B' . ($row - 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $row = $row + 1;

            $sheet->setCellValue('A' . $row, 'No');
            $sheet->setCellValue('B' . $row, 'Ranking');
            $sheet->setCellValue('C' . $row, 'Nama Lengkap');
            $sheet->setCellValue('D' . $row, 'Email');
            $sheet->setCellValue('E' . $row, $kolomNilai);
            $sheet->setCellValue('F' . $row, 'Status Rekomendasi');
            $sheet->setCellValue('G' . $row, 'Keterangan');

            $headerRange = 'A' . $row . ':G' . $row;
            $sheet->getStyle($headerRange)->getFont()->setBold(true);
            $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
            $sheet->getStyle($headerRange)->getFill()->getStartColor()->setARGB('FFE9ECEF');
            $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($headerRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            $startRow = $row;
            $rowNum = $row + 1;

            foreach ($data as $index => $item) {
                $nilai = $metode == 'saw' ? $item->nilai_saw : $item->nilai_topsis;

                if ($item->status_rekomendasi == 'rekomendasi') {
                    $status = 'REKOMENDASI';
                    $statusColor = 'FF10B981';
                    $keterangan = 'Direkomendasikan utama untuk tahap selanjutnya';
                } elseif ($item->status_rekomendasi == 'cadangan') {
                    $status = 'CADANGAN';
                    $statusColor = 'FFF59E0B';
                    $keterangan = 'Kandidat cadangan tahap selanjutnya';
                } else {
                    $status = 'TIDAK REKOMENDASI';
                    $statusColor = 'FFEF4444';
                    $keterangan = 'Tidak direkomendasikan untuk tahap selanjutnya';
                }

                $sheet->setCellValue('A' . $rowNum, $index + 1);
                $sheet->setCellValue('B' . $rowNum, $item->ranking);
                $sheet->setCellValue('C' . $rowNum, $item->pelamar->nama_lengkap);
                $sheet->setCellValue('D' . $rowNum, $item->pelamar->email);
                $sheet->setCellValue('E' . $rowNum, $nilai);
                $sheet->setCellValue('F' . $rowNum, $status);
                $sheet->setCellValue('G' . $rowNum, $keterangan);

                $sheet->getStyle('E' . $rowNum)->getNumberFormat()->setFormatCode('0.0000');
                $sheet->getStyle('A' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('B' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('E' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                if ($item->ranking <= $slot) {
                    $sheet->getStyle('B' . $rowNum)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                    $sheet->getStyle('B' . $rowNum)->getFill()->getStartColor()->setARGB('FF10B981');
                    $sheet->getStyle('B' . $rowNum)->getFont()->getColor()->setARGB('FFFFFFFF');
                    $sheet->getStyle('B' . $rowNum)->getFont()->setBold(true);
                }

                $sheet->getStyle('F' . $rowNum)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle('F' . $rowNum)->getFill()->getStartColor()->setARGB($statusColor);
                $sheet->getStyle('F' . $rowNum)->getFont()->getColor()->setARGB('FFFFFFFF');
                $sheet->getStyle('F' . $rowNum)->getFont()->setBold(true);
                $sheet->getStyle('F' . $rowNum)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $rowNum++;
            }

            foreach (range('A', 'G') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $borderRange = 'A' . $startRow . ':G' . ($rowNum - 1);
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ];
            $sheet->getStyle($borderRange)->applyFromArray($styleArray);

            $footerRow = $rowNum + 2;
            $sheet->setCellValue('A' . $footerRow, 'Dicetak oleh: ' . (Auth::user()->name ?? 'Administrator'));
            $sheet->mergeCells('A' . $footerRow . ':C' . $footerRow);
            $sheet->getStyle('A' . $footerRow)->getFont()->setItalic(true);

            $sheet->setCellValue('E' . $footerRow, 'Tanda Tangan: ___________________');
            $sheet->mergeCells('E' . $footerRow . ':G' . $footerRow);
            $sheet->getStyle('E' . $footerRow)->getFont()->setItalic(true);

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            header('Expires: 0');
            header('Pragma: public');

            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            Log::error('Export Excel Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }

    public function exportPenilaian($lowonganId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);
        $kriterias = Kriteria::where('is_active', true)->get();
        $pelamars = Pelamar::whereHas('pelamarTahapan', function ($q) use ($lowonganId) {
            $q->where('lowongan_id', $lowonganId);
        })->get();

        $penilaian = [];
        foreach ($pelamars as $pelamar) {
            foreach ($kriterias as $kriteria) {
                $nilai = PenilaianKandidat::where('pelamar_id', $pelamar->id)
                    ->where('lowongan_id', $lowonganId)
                    ->where('kriteria_id', $kriteria->id)
                    ->first();
                $penilaian[$pelamar->id][$kriteria->id] = $nilai ? $nilai->nilai : null;
            }
        }

        $filename = "penilaian_kandidat_{$lowongan->judul_lowongan}_" . date('Ymd_His') . ".xlsx";

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Penilaian Kandidat');

        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle('A1:H1')->getFill()->getStartColor()->setARGB('FF0D6EFD');
        $sheet->getStyle('A1:H1')->getFont()->getColor()->setARGB('FFFFFFFF');

        $sheet->setCellValue('A1', 'LAPORAN PENILAIAN KANDIDAT SPK');
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getFont()->setSize(14);

        $sheet->setCellValue('A2', 'Lowongan');
        $sheet->setCellValue('B2', ': ' . $lowongan->judul_lowongan);
        $sheet->mergeCells('A2:B2');
        $sheet->setCellValue('A3', 'Slot Lowongan');
        $sheet->setCellValue('B3', ': ' . $lowongan->slot . ' orang');
        $sheet->mergeCells('A3:B3');
        $sheet->setCellValue('A4', 'Tanggal Cetak');
        $sheet->setCellValue('B4', ': ' . now()->translatedFormat('d F Y H:i:s'));
        $sheet->mergeCells('A4:B4');

        $row = 6;
        $col = 'A';
        $sheet->setCellValue($col++ . $row, 'No');
        $sheet->setCellValue($col++ . $row, 'Nama Pelamar');
        $sheet->setCellValue($col++ . $row, 'Email');
        $sheet->setCellValue($col++ . $row, 'No Telepon');

        foreach ($kriterias as $kriteria) {
            $sheet->setCellValue($col++ . $row, $kriteria->nama_kriteria);
            $sheet->setCellValue($col++ . $row, 'Catatan ' . $kriteria->nama_kriteria);
        }

        $sheet->setCellValue($col++ . $row, 'Total Nilai');
        $sheet->setCellValue($col++ . $row, 'Keterangan');

        $headerRange = 'A6:' . $sheet->getHighestColumn() . '6';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
        $sheet->getStyle($headerRange)->getFill()->getStartColor()->setARGB('FFE9ECEF');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle($headerRange)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $rowNum = 7;
        foreach ($pelamars as $index => $pelamar) {
            $col = 'A';
            $sheet->setCellValue($col++ . $rowNum, $index + 1);
            $sheet->setCellValue($col++ . $rowNum, $pelamar->nama_lengkap);
            $sheet->setCellValue($col++ . $rowNum, $pelamar->email);
            $sheet->setCellValue($col++ . $rowNum, $pelamar->nomor_handphone ?? '-');

            $totalNilai = 0;
            foreach ($kriterias as $kriteria) {
                $nilai = $penilaian[$pelamar->id][$kriteria->id] ?? 0;
                $totalNilai += floatval($nilai);
                $sheet->setCellValue($col++ . $rowNum, $nilai ?: '-');
                $sheet->setCellValue($col++ . $rowNum, '');
            }

            $rataRata = $kriterias->count() > 0 ? $totalNilai / $kriterias->count() : 0;
            $sheet->setCellValue($col++ . $rowNum, round($rataRata, 2));
            $sheet->setCellValue($col++ . $rowNum, $rataRata >= 70 ? 'Lolos Seleksi' : 'Perlu Evaluasi');

            $rowNum++;
        }

        foreach (range('A', $sheet->getHighestColumn()) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $borderRange = 'A6:' . $sheet->getHighestColumn() . ($rowNum - 1);
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        $sheet->getStyle($borderRange)->applyFromArray($styleArray);

        $sheet->setCellValue('A' . ($rowNum + 2), 'Dicetak oleh: ' . (Auth::user()->name ?? 'Administrator'));
        $sheet->mergeCells('A' . ($rowNum + 2) . ':C' . ($rowNum + 2));
        $sheet->getStyle('A' . ($rowNum + 2))->getFont()->setItalic(true);

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}
