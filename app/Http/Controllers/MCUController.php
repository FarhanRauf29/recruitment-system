<?php

namespace App\Http\Controllers;

use App\Models\MCU;
use App\Models\PelamarTahapan;
use App\Models\Pelamar;
use App\Models\Lowongan;
use App\Models\TahapanRekrutmen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MCUController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->adminIndex($request);
        }

        return $this->pelamarIndex();
    }

    private function adminIndex(Request $request)
    {
        $query = MCU::with(['pelamar', 'pelamarTahapan', 'lowongan']);

        if ($request->search) {
            $query->whereHas('pelamar', function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', "%{$request->search}%");
            })->orWhereHas('lowongan', function ($q) use ($request) {
                $q->where('judul_lowongan', 'like', "%{$request->search}%");
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $mcu = $query->latest()->paginate(10)->withQueryString();
        $statusOptions = MCU::select('status')->distinct()->pluck('status');

        return view('admin.mcu.index', compact('mcu', 'statusOptions'));
    }

    private function pelamarIndex()
    {
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil terlebih dahulu.');
        }

        $mcu = MCU::with(['lowongan', 'pelamarTahapan'])
            ->where('pelamar_id', $pelamar->id)
            ->latest()
            ->get();

        return view('pelamar.mcu.index', compact('mcu'));
    }

    public function create()
    {
        $pelamarTahapans = PelamarTahapan::with(['pelamar', 'lowongan', 'tahapan'])
            ->whereHas('tahapan', function ($q) {
                $q->where('nama_tahapan', 'like', '%medical%')
                    ->orWhere('nama_tahapan', 'like', '%MCU%')
                    ->orWhere('nama_tahapan', 'like', '%check up%');
            })
            ->where('status', 'diproses')
            ->get();

        $existingMCU = MCU::pluck('pelamar_tahapan_id')->toArray();

        $pelamarTahapans = $pelamarTahapans->filter(function ($pt) use ($existingMCU) {
            return !in_array($pt->id, $existingMCU);
        });

        return view('admin.mcu.create', compact('pelamarTahapans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelamar_tahapan_id' => 'required|exists:pelamar_tahapan,id',
            'jadwal_mcu' => 'required|date',
            'lokasi_mcu' => 'required|string',
            'alamat_lokasi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'dokter_pemeriksa' => 'nullable|string',
            'nama_rs' => 'nullable|string',
        ]);

        $existingMCU = MCU::where('pelamar_tahapan_id', $request->pelamar_tahapan_id)->first();
        if ($existingMCU) {
            return redirect()->back()->with('error', 'Pelamar ini sudah memiliki jadwal MCU.')->withInput();
        }

        $pelamarTahapan = PelamarTahapan::with(['pelamar', 'lowongan'])->find($request->pelamar_tahapan_id);

        MCU::create([
            'pelamar_tahapan_id' => $request->pelamar_tahapan_id,
            'pelamar_id' => $pelamarTahapan->pelamar_id,
            'lowongan_id' => $pelamarTahapan->lowongan_id,
            'jadwal_mcu' => $request->jadwal_mcu,
            'lokasi_mcu' => $request->lokasi_mcu,
            'alamat_lokasi' => $request->alamat_lokasi,
            'keterangan' => $request->keterangan,
            'status' => 'dijadwalkan',
            'dokter_pemeriksa' => $request->dokter_pemeriksa,
            'nama_rs' => $request->nama_rs,
        ]);

        return redirect()->route('admin.mcu.index')->with('success', 'Jadwal MCU berhasil ditambahkan.');
    }

    public function show($id)
    {
        $user = Auth::user();
        $mcu = MCU::with(['pelamar', 'pelamarTahapan', 'lowongan'])->findOrFail($id);

        if ($user->isAdmin()) {
            return view('admin.mcu.show', compact('mcu'));
        }

        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar || $mcu->pelamar_id != $pelamar->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('pelamar.mcu.show', compact('mcu'));
    }

    public function pelamarShow($id)
    {
        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil terlebih dahulu.');
        }

        $mcu = MCU::with(['pelamar', 'pelamarTahapan', 'lowongan'])
            ->where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        return view('pelamar.mcu.show', compact('mcu'));
    }

    public function edit($id)
    {
        $mcu = MCU::findOrFail($id);
        return view('admin.mcu.edit', compact('mcu'));
    }

    public function update(Request $request, $id)
    {
        $mcu = MCU::findOrFail($id);

        $request->validate([
            'jadwal_mcu' => 'required|date',
            'lokasi_mcu' => 'required|string',
            'status' => 'required|in:dijadwalkan,dikonfirmasi,hadir,tidak_hadir,verifikasi,lolos,gagal',
        ]);

        $mcu->update([
            'jadwal_mcu' => $request->jadwal_mcu,
            'lokasi_mcu' => $request->lokasi_mcu,
            'alamat_lokasi' => $request->alamat_lokasi,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'dokter_pemeriksa' => $request->dokter_pemeriksa,
            'nama_rs' => $request->nama_rs,
            'catatan_admin' => $request->catatan_admin,
        ]);

        if ($request->status == 'lolos') {
            $this->lanjutkanKeTahapanSelanjutnya($mcu);
        } elseif ($request->status == 'gagal') {
            $pelamarTahapan = PelamarTahapan::find($mcu->pelamar_tahapan_id);
            if ($pelamarTahapan) {
                $pelamarTahapan->status = 'gagal';
                $pelamarTahapan->tanggal_selesai = now();
                $pelamarTahapan->save();
            }
        }

        return redirect()->route('admin.mcu.index')->with('success', 'Data MCU berhasil diperbarui.');
    }

    public function inputHasil($id)
    {
        $mcu = MCU::with(['pelamar', 'lowongan'])->findOrFail($id);
        return view('admin.mcu.input_hasil', compact('mcu'));
    }

    public function storeHasil(Request $request, $id)
    {
        $mcu = MCU::findOrFail($id);

        $request->validate([
            'tinggi_badan' => 'nullable|numeric',
            'berat_badan' => 'nullable|numeric',
            'bmi' => 'nullable|numeric',
            'tekanan_darah' => 'nullable|string',
            'visus_mata' => 'nullable|string',
            'buta_warna' => 'nullable|in:Tidak,Parsial,Total',
            'hasil_narkoba' => 'nullable|in:Negatif,Positif',
            'riwayat_penyakit' => 'nullable|string',
        ]);

        $mcu->update([
            'tinggi_badan' => $request->tinggi_badan,
            'berat_badan' => $request->berat_badan,
            'bmi' => $request->bmi,
            'tekanan_darah' => $request->tekanan_darah,
            'visus_mata' => $request->visus_mata,
            'buta_warna' => $request->buta_warna,
            'hasil_narkoba' => $request->hasil_narkoba,
            'riwayat_penyakit' => $request->riwayat_penyakit,
            'status' => 'hadir',
        ]);

        $hasil = $mcu->getStatusKesehatan();

        $skorAsli = $hasil['skor'];
        if ($skorAsli > 100) {
            $skorFinal = $skorAsli / 100;
        } else {
            $skorFinal = $skorAsli;
        }

        $statusKesehatan = $this->getStatusKesehatanBySkor($skorFinal);

        $mcu->kondisi_kesehatan = $statusKesehatan;
        $mcu->nilai_mcu = $skorFinal;
        $mcu->save();

        $this->autoVerifikasiMCU($mcu);

        $skorFormatted = number_format($mcu->nilai_mcu, 2, ',', '.');

        return redirect()->route('admin.mcu.show', $mcu->id)->with('success', 'Hasil MCU berhasil disimpan. Skor: ' . $skorFormatted . ' - ' . $statusKesehatan);
    }

    private function getStatusKesehatanBySkor($skor)
    {
        if ($skor >= 90) {
            return 'Sangat Sehat';
        } elseif ($skor >= 80) {
            return 'Sehat';
        } elseif ($skor >= 70) {
            return 'Cukup Sehat';
        } elseif ($skor >= 50) {
            return 'Kurang Sehat';
        } else {
            return 'Tidak Sehat';
        }
    }

    private function autoVerifikasiMCU($mcu)
    {
        $skor = $mcu->nilai_mcu;

        DB::beginTransaction();

        try {
            if ($skor >= 25) {
                $mcu->status = 'lolos';
                $mcu->tanggal_verifikasi = now();
                $mcu->save();

                $this->lanjutkanKeTahapanSelanjutnya($mcu);
            } else {
                $mcu->status = 'gagal';
                $mcu->tanggal_verifikasi = now();
                $mcu->save();

                $pelamarTahapan = PelamarTahapan::find($mcu->pelamar_tahapan_id);
                if ($pelamarTahapan) {
                    $pelamarTahapan->status = 'gagal';
                    $pelamarTahapan->tanggal_selesai = now();
                    $pelamarTahapan->save();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function lanjutkanKeTahapanSelanjutnya($mcu)
    {
        $pelamarTahapan = PelamarTahapan::with(['tahapan'])->find($mcu->pelamar_tahapan_id);

        if (!$pelamarTahapan) {
            return;
        }

        $pelamarTahapan->status = 'lolos';
        $pelamarTahapan->tanggal_selesai = now();
        $pelamarTahapan->save();

        $urutanSekarang = $pelamarTahapan->tahapan->urutan;

        $nextTahapans = TahapanRekrutmen::where('urutan', '>', $urutanSekarang)
            ->orderBy('urutan', 'asc')
            ->get();

        foreach ($nextTahapans as $tahapan) {
            $existing = PelamarTahapan::where([
                'pelamar_id' => $mcu->pelamar_id,
                'lowongan_id' => $mcu->lowongan_id,
                'tahapan_id' => $tahapan->id,
            ])->first();

            if (!$existing) {
                PelamarTahapan::create([
                    'pelamar_id' => $mcu->pelamar_id,
                    'lowongan_id' => $mcu->lowongan_id,
                    'tahapan_id' => $tahapan->id,
                    'status' => 'diproses',
                    'tanggal_mulai' => now(),
                ]);
            } else {
                if ($existing->status == 'menunggu' || $existing->status == 'belum') {
                    $existing->status = 'diproses';
                    $existing->tanggal_mulai = now();
                    $existing->save();
                }
            }
        }
    }

    public function verifikasi(Request $request, $id)
    {
        $mcu = MCU::findOrFail($id);

        $request->validate([
            'status' => 'required|in:lolos,gagal',
            'catatan_admin' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $mcu->status = $request->status;
            $mcu->catatan_admin = $request->catatan_admin;
            $mcu->tanggal_verifikasi = now();
            $mcu->save();

            if ($request->status == 'lolos') {
                $this->lanjutkanKeTahapanSelanjutnya($mcu);
            } else {
                $pelamarTahapan = PelamarTahapan::find($mcu->pelamar_tahapan_id);
                if ($pelamarTahapan) {
                    $pelamarTahapan->status = 'gagal';
                    $pelamarTahapan->tanggal_selesai = now();
                    $pelamarTahapan->save();
                }
            }

            DB::commit();

            return redirect()->route('admin.mcu.index')->with('success', 'Verifikasi MCU berhasil.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    public function generateSuratHasil($id)
    {
        try {
            $mcu = MCU::findOrFail($id);

            if (!$mcu->surat_mcu_file || !Storage::disk('public')->exists($mcu->surat_mcu_file)) {
                $path = $mcu->generateSuratHasilMCU();
                $mcu->surat_mcu_file = $path;
                $mcu->save();
            } else {
                $path = $mcu->surat_mcu_file;
            }

            if (!Storage::disk('public')->exists($path)) {
                return redirect()->back()->with('error', 'File surat tidak ditemukan. Silakan coba lagi.');
            }

            return response()->download(storage_path('app/public/' . $path));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh surat: ' . $e->getMessage());
        }
    }

    public function generateSuratPanggilan($id)
    {
        try {
            $mcu = MCU::findOrFail($id);

            if (!$mcu->surat_panggilan_file || !Storage::disk('public')->exists($mcu->surat_panggilan_file)) {
                $path = $mcu->generateSuratPanggilan();
                $mcu->surat_panggilan_file = $path;
                $mcu->save();
            } else {
                $path = $mcu->surat_panggilan_file;
            }

            if (!Storage::disk('public')->exists($path)) {
                return redirect()->back()->with('error', 'File surat panggilan tidak ditemukan. Silakan coba lagi.');
            }

            return response()->download(storage_path('app/public/' . $path));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh surat panggilan: ' . $e->getMessage());
        }
    }

    public function cetakSuratPanggilan($id)
    {
        $mcu = MCU::with(['pelamar', 'lowongan'])->findOrFail($id);
        return view('admin.mcu.surat_panggilan_preview', compact('mcu'));
    }

    public function downloadSurat($id)
    {
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil terlebih dahulu.');
        }

        $mcu = MCU::where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        try {
            if (in_array($mcu->status, ['dijadwalkan', 'dikonfirmasi'])) {
                $path = $mcu->generateSuratPanggilan();
                $mcu->surat_panggilan_file = $path;
                $mcu->save();

                if ($mcu->surat_panggilan_file && Storage::disk('public')->exists($mcu->surat_panggilan_file)) {
                    return response()->download(storage_path('app/public/' . $mcu->surat_panggilan_file));
                }
            } else {
                if (!$mcu->surat_mcu_file || !Storage::disk('public')->exists($mcu->surat_mcu_file)) {
                    $path = $mcu->generateSuratHasilMCU();
                    $mcu->surat_mcu_file = $path;
                    $mcu->save();
                }

                if ($mcu->surat_mcu_file && Storage::disk('public')->exists($mcu->surat_mcu_file)) {
                    return response()->download(storage_path('app/public/' . $mcu->surat_mcu_file));
                }
            }

            return redirect()->back()->with('error', 'File surat tidak ditemukan. Silakan coba lagi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh surat: ' . $e->getMessage());
        }
    }

    public function downloadSuratHasil($id)
    {
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil terlebih dahulu.');
        }

        $mcu = MCU::where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        try {
            if (!$mcu->surat_mcu_file || !Storage::disk('public')->exists($mcu->surat_mcu_file)) {
                $path = $mcu->generateSuratHasilMCU();
                $mcu->surat_mcu_file = $path;
                $mcu->save();
                $mcu->refresh();
            }

            if ($mcu->surat_mcu_file && Storage::disk('public')->exists($mcu->surat_mcu_file)) {
                return response()->download(storage_path('app/public/' . $mcu->surat_mcu_file));
            }

            return redirect()->back()->with('error', 'File surat hasil tidak ditemukan. Silakan coba lagi.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh surat hasil: ' . $e->getMessage());
        }
    }

    public function uploadDokumen(Request $request, $id)
    {
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil terlebih dahulu.');
        }

        $mcu = MCU::where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        $request->validate([
            'dokumen_pendukung_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($mcu->dokumen_pendukung_file && Storage::disk('public')->exists($mcu->dokumen_pendukung_file)) {
            Storage::disk('public')->delete($mcu->dokumen_pendukung_file);
        }

        $file = $request->file('dokumen_pendukung_file');
        $fileName = time() . '_dokumen_pendukung.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('mcu/dokumen', $fileName, 'public');

        $mcu->dokumen_pendukung_file = $path;
        $mcu->save();

        return redirect()->route('pelamar.mcu.show', $id)->with('success', 'Dokumen pendukung berhasil diupload.');
    }

    public function konfirmasi($id)
    {
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil terlebih dahulu.');
        }

        $mcu = MCU::where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        if ($mcu->status == 'dijadwalkan') {
            $mcu->status = 'dikonfirmasi';
            $mcu->save();
            return redirect()->route('pelamar.mcu.show', $id)->with('success', 'Konfirmasi kehadiran MCU berhasil.');
        }

        return redirect()->route('pelamar.mcu.show', $id)->with('error', 'Tidak dapat mengkonfirmasi jadwal ini.');
    }

    public function destroy($id)
    {
        $mcu = MCU::findOrFail($id);

        if ($mcu->surat_mcu_file && Storage::disk('public')->exists($mcu->surat_mcu_file)) {
            Storage::disk('public')->delete($mcu->surat_mcu_file);
        }

        if ($mcu->surat_panggilan_file && Storage::disk('public')->exists($mcu->surat_panggilan_file)) {
            Storage::disk('public')->delete($mcu->surat_panggilan_file);
        }

        if ($mcu->dokumen_pendukung_file && Storage::disk('public')->exists($mcu->dokumen_pendukung_file)) {
            Storage::disk('public')->delete($mcu->dokumen_pendukung_file);
        }

        $mcu->delete();

        return redirect()->route('admin.mcu.index')->with('success', 'Data MCU berhasil dihapus.');
    }

    public function getFormattedSkor($id)
    {
        $mcu = MCU::findOrFail($id);
        $skor = $mcu->nilai_mcu;

        if ($skor !== null) {
            if ($skor > 100) {
                $skor = $skor / 100;
            }
            $skorFormatted = number_format($skor, 2, ',', '.');
        } else {
            $skorFormatted = '-';
        }

        return response()->json([
            'skor' => $skor,
            'skor_formatted' => $skorFormatted,
            'kondisi_kesehatan' => $mcu->kondisi_kesehatan
        ]);
    }
}
