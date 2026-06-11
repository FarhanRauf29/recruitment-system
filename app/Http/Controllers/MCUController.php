<?php

namespace App\Http\Controllers;

use App\Models\MCU;
use App\Models\PelamarTahapan;
use App\Models\Pelamar;
use App\Models\Lowongan;
use App\Models\TahapanRekrutmen;
use App\Models\PenilaianKandidat;
use App\Models\Kriteria;
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

    public function edit($id)
    {
        $mcu = MCU::findOrFail($id);
        $pelamarTahapans = PelamarTahapan::with(['pelamar', 'lowongan'])->get();
        return view('admin.mcu.edit', compact('mcu', 'pelamarTahapans'));
    }

    public function update(Request $request, $id)
    {
        $mcu = MCU::findOrFail($id);

        $request->validate([
            'jadwal_mcu' => 'required|date',
            'lokasi_mcu' => 'required|string',
            'status' => 'required|in:dijadwalkan,dikonfirmasi,hadir,tidak_hadir,hasil_diupload,verifikasi,lolos,gagal',
            'kondisi_kesehatan' => 'nullable|in:Sangat Sehat,Sehat,Cukup,Kurang,Tidak Lulus',
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
            'kondisi_kesehatan' => $request->kondisi_kesehatan,
            'nilai_mcu' => $this->getNilaiFromKondisi($request->kondisi_kesehatan),
        ]);

        if ($request->status == 'lolos') {
            $pelamarTahapan = PelamarTahapan::find($mcu->pelamar_tahapan_id);
            $pelamarTahapan->status = 'lolos';
            $pelamarTahapan->tanggal_selesai = now();
            $pelamarTahapan->save();

            $this->saveNilaiToPenilaianKandidat($mcu->pelamar_id, $mcu->lowongan_id, $mcu->nilai_mcu);

            $nextTahapans = TahapanRekrutmen::where('urutan', '>', $pelamarTahapan->tahapan->urutan)->get();
            foreach ($nextTahapans as $tahapan) {
                PelamarTahapan::firstOrCreate([
                    'pelamar_id' => $mcu->pelamar_id,
                    'lowongan_id' => $mcu->lowongan_id,
                    'tahapan_id' => $tahapan->id,
                ], [
                    'status' => 'diproses',
                    'tanggal_mulai' => now(),
                ]);
            }
        } elseif ($request->status == 'gagal') {
            $pelamarTahapan = PelamarTahapan::find($mcu->pelamar_tahapan_id);
            $pelamarTahapan->status = 'gagal';
            $pelamarTahapan->tanggal_selesai = now();
            $pelamarTahapan->save();
        }

        return redirect()->route('admin.mcu.index')->with('success', 'Data MCU berhasil diperbarui.');
    }

    private function getNilaiFromKondisi($kondisi)
    {
        $nilaiMap = [
            'Sangat Sehat' => 100,
            'Sehat' => 85,
            'Cukup' => 70,
            'Kurang' => 50,
            'Tidak Lulus' => 0,
        ];
        return $nilaiMap[$kondisi] ?? 0;
    }

    private function saveNilaiToPenilaianKandidat($pelamarId, $lowonganId, $nilai)
    {
        $kriteriaMCU = Kriteria::where('nama_kriteria', 'like', '%MCU%')
            ->orWhere('nama_kriteria', 'like', '%Medical%')
            ->orWhere('kode_kriteria', 'C3')
            ->first();

        if ($kriteriaMCU) {
            PenilaianKandidat::updateOrCreate(
                [
                    'pelamar_id' => $pelamarId,
                    'lowongan_id' => $lowonganId,
                    'kriteria_id' => $kriteriaMCU->id,
                ],
                [
                    'nilai' => $nilai,
                    'catatan' => 'Nilai MCU otomatis dari kondisi kesehatan: ' . ($this->getKondisiFromNilai($nilai) ?? '-'),
                ]
            );
        }
    }

    private function getKondisiFromNilai($nilai)
    {
        if ($nilai >= 90) return 'Sangat Sehat';
        if ($nilai >= 75) return 'Sehat';
        if ($nilai >= 60) return 'Cukup';
        if ($nilai >= 30) return 'Kurang';
        return 'Tidak Lulus';
    }

    public function uploadHasil(Request $request, $id)
    {
        $mcu = MCU::findOrFail($id);

        $request->validate([
            'hasil_mcu_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'hasil_mcu_keterangan' => 'nullable|string',
            'kondisi_kesehatan' => 'nullable|in:Sangat Sehat,Sehat,Cukup,Kurang,Tidak Lulus',
        ]);

        if ($mcu->hasil_mcu_file && Storage::disk('public')->exists($mcu->hasil_mcu_file)) {
            Storage::disk('public')->delete($mcu->hasil_mcu_file);
        }

        $file = $request->file('hasil_mcu_file');
        $fileName = time() . '_hasil_mcu.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('mcu/hasil', $fileName, 'public');

        $mcu->hasil_mcu_file = $path;
        $mcu->hasil_mcu_keterangan = $request->hasil_mcu_keterangan;
        $mcu->status = 'hasil_diupload';
        $mcu->kondisi_kesehatan = $request->kondisi_kesehatan;
        $mcu->nilai_mcu = $this->getNilaiFromKondisi($request->kondisi_kesehatan);
        $mcu->save();

        return redirect()->route('admin.mcu.show', $id)->with('success', 'Hasil MCU berhasil diupload.');
    }

    public function verifikasi(Request $request, $id)
    {
        $mcu = MCU::findOrFail($id);

        $request->validate([
            'status' => 'required|in:lolos,gagal',
            'catatan_admin' => 'nullable|string',
            'kondisi_kesehatan' => 'nullable|in:Sangat Sehat,Sehat,Cukup,Kurang,Tidak Lulus',
        ]);

        DB::beginTransaction();

        try {
            $mcu->status = $request->status;
            $mcu->catatan_admin = $request->catatan_admin;
            $mcu->tanggal_verifikasi = now();
            if ($request->kondisi_kesehatan) {
                $mcu->kondisi_kesehatan = $request->kondisi_kesehatan;
                $mcu->nilai_mcu = $this->getNilaiFromKondisi($request->kondisi_kesehatan);
            }
            $mcu->save();

            if ($mcu->nilai_mcu) {
                $this->saveNilaiToPenilaianKandidat($mcu->pelamar_id, $mcu->lowongan_id, $mcu->nilai_mcu);
            }

            $pelamarTahapan = PelamarTahapan::find($mcu->pelamar_tahapan_id);

            if ($request->status == 'lolos') {
                $pelamarTahapan->status = 'lolos';
                $pelamarTahapan->tanggal_selesai = now();
                $pelamarTahapan->save();
            } else {
                $pelamarTahapan->status = 'gagal';
                $pelamarTahapan->tanggal_selesai = now();
                $pelamarTahapan->save();
            }

            DB::commit();

            return redirect()->route('admin.mcu.index')->with('success', 'Verifikasi MCU berhasil.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    public function cetakSurat($id)
    {
        $mcu = MCU::with(['pelamar', 'lowongan'])->findOrFail($id);
        $content = view('admin.mcu.surat', compact('mcu'))->render();
        return response($content)->header('Content-Type', 'text/html');
    }

    public function generateSurat($id)
    {
        $mcu = MCU::findOrFail($id);

        if (!$mcu->surat_mcu_file || !Storage::disk('public')->exists($mcu->surat_mcu_file)) {
            $path = $mcu->generateSuratMCU();
        } else {
            $path = $mcu->surat_mcu_file;
        }

        return response()->download(storage_path('app/public/' . $path));
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
            if (!$mcu->surat_mcu_file || !Storage::disk('public')->exists($mcu->surat_mcu_file)) {
                $mcu->generateSuratMCU();
                $mcu->refresh();
            }

            if ($mcu->surat_mcu_file && Storage::disk('public')->exists($mcu->surat_mcu_file)) {
                return response()->download(storage_path('app/public/' . $mcu->surat_mcu_file));
            } else {
                return redirect()->back()->with('error', 'File surat tidak ditemukan. Silakan coba lagi.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunduh surat: ' . $e->getMessage());
        }
    }

    public function viewFile($id, $field)
    {
        $mcu = MCU::findOrFail($id);

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Login diperlukan.');
        }

        $isAdmin = $user->isAdmin();
        $isOwner = $user->pelamar && $mcu->pelamar_id == $user->pelamar->id;

        if (!$isAdmin && !$isOwner) {
            abort(403, 'Akses ditolak.');
        }

        if ($mcu->$field && Storage::disk('public')->exists($mcu->$field)) {
            $path = storage_path('app/public/' . $mcu->$field);
            return response()->file($path);
        }

        abort(404, 'File tidak ditemukan.');
    }

    public function destroy($id)
    {
        $mcu = MCU::findOrFail($id);

        if ($mcu->hasil_mcu_file && Storage::disk('public')->exists($mcu->hasil_mcu_file)) {
            Storage::disk('public')->delete($mcu->hasil_mcu_file);
        }

        if ($mcu->surat_mcu_file && Storage::disk('public')->exists($mcu->surat_mcu_file)) {
            Storage::disk('public')->delete($mcu->surat_mcu_file);
        }

        if ($mcu->dokumen_pendukung_file && Storage::disk('public')->exists($mcu->dokumen_pendukung_file)) {
            Storage::disk('public')->delete($mcu->dokumen_pendukung_file);
        }

        $mcu->delete();

        return redirect()->route('admin.mcu.index')->with('success', 'Data MCU berhasil dihapus.');
    }
}
