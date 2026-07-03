<?php

namespace App\Http\Controllers;

use App\Models\Wawancara;
use App\Models\PelamarTahapan;
use App\Models\Pelamar;
use App\Models\Lowongan;
use App\Models\TahapanRekrutmen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class WawancaraController extends Controller
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
        $query = Wawancara::with(['pelamar', 'pelamarTahapan', 'lowongan']);

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

        $wawancara = $query->latest()->paginate(10)->withQueryString();
        $statusOptions = Wawancara::select('status')->distinct()->pluck('status');

        return view('admin.wawancara.index', compact('wawancara', 'statusOptions'));
    }

    private function pelamarIndex()
    {
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil terlebih dahulu.');
        }

        $wawancara = Wawancara::with(['lowongan', 'pelamarTahapan'])
            ->where('pelamar_id', $pelamar->id)
            ->latest()
            ->get();

        return view('pelamar.wawancara.index', compact('wawancara'));
    }

    public function create()
    {
        $pelamarTahapans = PelamarTahapan::with(['pelamar', 'lowongan', 'tahapan'])
            ->whereHas('tahapan', function ($q) {
                $q->where('nama_tahapan', 'like', '%wawancara%')
                    ->orWhere('nama_tahapan', 'like', '%interview%');
            })
            ->where('status', 'diproses')
            ->get();

        $existingWawancara = Wawancara::pluck('pelamar_tahapan_id')->toArray();

        $pelamarTahapans = $pelamarTahapans->filter(function ($pt) use ($existingWawancara) {
            return !in_array($pt->id, $existingWawancara);
        });

        return view('admin.wawancara.create', compact('pelamarTahapans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelamar_tahapan_id' => 'required|exists:pelamar_tahapan,id',
            'jadwal_wawancara' => 'required|date',
            'jenis_wawancara' => 'required|in:online,offline',
            'link_wawancara' => 'required_if:jenis_wawancara,online|nullable|url',
            'lokasi_wawancara' => 'required_if:jenis_wawancara,offline|nullable|string',
            'alamat_lokasi' => 'nullable|string',
            'keterangan' => 'nullable|string',
            'pewawancara' => 'nullable|string',
        ]);

        try {
            $existingWawancara = Wawancara::where('pelamar_tahapan_id', $request->pelamar_tahapan_id)->first();
            if ($existingWawancara) {
                return redirect()->back()->withInput()->with('error', 'Pelamar ini sudah memiliki jadwal wawancara.');
            }

            $pelamarTahapan = PelamarTahapan::with(['pelamar', 'lowongan'])->find($request->pelamar_tahapan_id);

            if (!$pelamarTahapan) {
                return redirect()->back()->withInput()->with('error', 'Data pelamar tahapan tidak ditemukan.');
            }

            Wawancara::create([
                'pelamar_tahapan_id' => $request->pelamar_tahapan_id,
                'pelamar_id' => $pelamarTahapan->pelamar_id,
                'lowongan_id' => $pelamarTahapan->lowongan_id,
                'jadwal_wawancara' => $request->jadwal_wawancara,
                'jenis_wawancara' => $request->jenis_wawancara,
                'link_wawancara' => $request->link_wawancara,
                'lokasi_wawancara' => $request->lokasi_wawancara,
                'alamat_lokasi' => $request->alamat_lokasi,
                'keterangan' => $request->keterangan,
                'status' => 'dijadwalkan',
                'pewawancara' => $request->pewawancara,
            ]);

            return redirect()->route('admin.wawancara.index')->with('success', 'Jadwal wawancara berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error saving wawancara: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan jadwal wawancara: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $wawancara = Wawancara::with(['pelamar', 'pelamarTahapan', 'lowongan'])->findOrFail($id);

        if ($user->isAdmin()) {
            return view('admin.wawancara.show', compact('wawancara'));
        }

        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar || $wawancara->pelamar_id != $pelamar->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('pelamar.wawancara.show', compact('wawancara'));
    }

    public function edit($id)
    {
        $wawancara = Wawancara::findOrFail($id);
        $pelamarTahapans = PelamarTahapan::with(['pelamar', 'lowongan'])->get();
        return view('admin.wawancara.edit', compact('wawancara', 'pelamarTahapans'));
    }

    public function update(Request $request, $id)
    {
        $wawancara = Wawancara::findOrFail($id);

        $request->validate([
            'jadwal_wawancara' => 'required|date',
            'jenis_wawancara' => 'required|in:online,offline',
            'link_wawancara' => 'required_if:jenis_wawancara,online|nullable|url',
            'lokasi_wawancara' => 'required_if:jenis_wawancara,offline|nullable|string',
            'status' => 'required|in:dijadwalkan,dikonfirmasi,hadir,tidak_hadir,selesai,verifikasi,lolos,gagal',
        ]);

        try {
            $wawancara->update([
                'jadwal_wawancara' => $request->jadwal_wawancara,
                'jenis_wawancara' => $request->jenis_wawancara,
                'link_wawancara' => $request->link_wawancara,
                'lokasi_wawancara' => $request->lokasi_wawancara,
                'alamat_lokasi' => $request->alamat_lokasi,
                'keterangan' => $request->keterangan,
                'status' => $request->status,
                'pewawancara' => $request->pewawancara,
                'catatan_admin' => $request->catatan_admin,
            ]);

            if ($request->status == 'lolos') {
                $pelamarTahapan = PelamarTahapan::find($wawancara->pelamar_tahapan_id);
                if ($pelamarTahapan) {
                    $pelamarTahapan->status = 'lolos';
                    $pelamarTahapan->tanggal_selesai = now();
                    $pelamarTahapan->save();

                    $nextTahapans = TahapanRekrutmen::where('urutan', '>', $pelamarTahapan->tahapan->urutan)
                        ->orderBy('urutan', 'asc')
                        ->get();

                    foreach ($nextTahapans as $tahapan) {
                        $existing = PelamarTahapan::where([
                            'pelamar_id' => $wawancara->pelamar_id,
                            'lowongan_id' => $wawancara->lowongan_id,
                            'tahapan_id' => $tahapan->id,
                        ])->first();

                        if (!$existing) {
                            PelamarTahapan::create([
                                'pelamar_id' => $wawancara->pelamar_id,
                                'lowongan_id' => $wawancara->lowongan_id,
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
            } elseif ($request->status == 'gagal') {
                $pelamarTahapan = PelamarTahapan::find($wawancara->pelamar_tahapan_id);
                if ($pelamarTahapan) {
                    $pelamarTahapan->status = 'gagal';
                    $pelamarTahapan->tanggal_selesai = now();
                    $pelamarTahapan->save();
                }
            }

            return redirect()->route('admin.wawancara.index')->with('success', 'Data wawancara berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui jadwal wawancara: ' . $e->getMessage());
        }
    }

    public function nilai(Request $request, $id)
    {
        $wawancara = Wawancara::findOrFail($id);

        $request->validate([
            'nilai_teknis' => 'nullable|integer|min:0|max:100',
            'nilai_komunikasi' => 'nullable|integer|min:0|max:100',
            'nilai_pengalaman' => 'nullable|integer|min:0|max:100',
            'nilai_attitude' => 'nullable|integer|min:0|max:100',
            'catatan_hr' => 'nullable|string',
        ]);

        $wawancara->nilai_teknis = $request->nilai_teknis;
        $wawancara->nilai_komunikasi = $request->nilai_komunikasi;
        $wawancara->nilai_pengalaman = $request->nilai_pengalaman;
        $wawancara->nilai_attitude = $request->nilai_attitude;
        $wawancara->catatan_hr = $request->catatan_hr;
        $wawancara->status = 'selesai';
        $wawancara->hitungNilaiTotal();
        $wawancara->save();

        return redirect()->route('admin.wawancara.show', $id)->with('success', 'Nilai wawancara berhasil disimpan.');
    }

    public function uploadHasil(Request $request, $id)
    {
        $wawancara = Wawancara::findOrFail($id);

        $request->validate([
            'hasil_wawancara_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($wawancara->hasil_wawancara_file && Storage::disk('public')->exists($wawancara->hasil_wawancara_file)) {
            Storage::disk('public')->delete($wawancara->hasil_wawancara_file);
        }

        $file = $request->file('hasil_wawancara_file');
        $fileName = time() . '_hasil_wawancara.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('wawancara/hasil', $fileName, 'public');

        $wawancara->hasil_wawancara_file = $path;
        $wawancara->save();

        return redirect()->route('admin.wawancara.show', $id)->with('success', 'Hasil wawancara berhasil diupload.');
    }

    public function verifikasi(Request $request, $id)
    {
        $wawancara = Wawancara::findOrFail($id);

        $request->validate([
            'status' => 'required|in:lolos,gagal',
            'catatan_admin' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $wawancara->status = $request->status;
            $wawancara->catatan_admin = $request->catatan_admin;
            $wawancara->tanggal_verifikasi = now();
            $wawancara->save();

            $pelamarTahapan = PelamarTahapan::with('tahapan')->find($wawancara->pelamar_tahapan_id);

            if ($pelamarTahapan) {
                if ($request->status == 'lolos') {
                    $pelamarTahapan->status = 'lolos';
                    $pelamarTahapan->tanggal_selesai = now();
                    $pelamarTahapan->save();

                    $nextTahapans = TahapanRekrutmen::where('urutan', '>', $pelamarTahapan->tahapan->urutan)
                        ->orderBy('urutan', 'asc')
                        ->get();

                    foreach ($nextTahapans as $tahapan) {
                        $existing = PelamarTahapan::where([
                            'pelamar_id' => $wawancara->pelamar_id,
                            'lowongan_id' => $wawancara->lowongan_id,
                            'tahapan_id' => $tahapan->id,
                        ])->first();

                        if (!$existing) {
                            PelamarTahapan::create([
                                'pelamar_id' => $wawancara->pelamar_id,
                                'lowongan_id' => $wawancara->lowongan_id,
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
                } else {
                    $pelamarTahapan->status = 'gagal';
                    $pelamarTahapan->tanggal_selesai = now();
                    $pelamarTahapan->save();
                }
            }

            DB::commit();

            return redirect()->route('admin.wawancara.index')->with('success', 'Verifikasi wawancara berhasil.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    public function konfirmasi($id)
    {
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil terlebih dahulu.');
        }

        $wawancara = Wawancara::where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        if ($wawancara->status == 'dijadwalkan') {
            $wawancara->status = 'dikonfirmasi';
            $wawancara->save();
            return redirect()->route('pelamar.wawancara.show', $id)->with('success', 'Konfirmasi kehadiran wawancara berhasil.');
        }

        return redirect()->route('pelamar.wawancara.show', $id)->with('error', 'Tidak dapat mengkonfirmasi jadwal ini.');
    }

    public function uploadDokumen(Request $request, $id)
    {
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil terlebih dahulu.');
        }

        $wawancara = Wawancara::where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        $request->validate([
            'dokumen_pendukung_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($wawancara->dokumen_pendukung_file && Storage::disk('public')->exists($wawancara->dokumen_pendukung_file)) {
            Storage::disk('public')->delete($wawancara->dokumen_pendukung_file);
        }

        $file = $request->file('dokumen_pendukung_file');
        $fileName = time() . '_dokumen_wawancara.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('wawancara/dokumen', $fileName, 'public');

        $wawancara->dokumen_pendukung_file = $path;
        $wawancara->save();

        return redirect()->route('pelamar.wawancara.show', $id)->with('success', 'Dokumen pendukung berhasil diupload.');
    }

    public function viewFile($id, $field)
    {
        $wawancara = Wawancara::findOrFail($id);

        $user = Auth::user();
        if (!$user) {
            abort(403, 'Login diperlukan.');
        }

        $isAdmin = $user->isAdmin();
        $isOwner = $user->pelamar && $wawancara->pelamar_id == $user->pelamar->id;

        if (!$isAdmin && !$isOwner) {
            abort(403, 'Akses ditolak.');
        }

        if ($wawancara->$field && Storage::disk('public')->exists($wawancara->$field)) {
            $path = storage_path('app/public/' . $wawancara->$field);
            return response()->file($path);
        }

        abort(404, 'File tidak ditemukan.');
    }

    public function destroy($id)
    {
        $wawancara = Wawancara::findOrFail($id);

        if ($wawancara->hasil_wawancara_file && Storage::disk('public')->exists($wawancara->hasil_wawancara_file)) {
            Storage::disk('public')->delete($wawancara->hasil_wawancara_file);
        }

        if ($wawancara->dokumen_pendukung_file && Storage::disk('public')->exists($wawancara->dokumen_pendukung_file)) {
            Storage::disk('public')->delete($wawancara->dokumen_pendukung_file);
        }

        $wawancara->delete();

        return redirect()->route('admin.wawancara.index')->with('success', 'Data wawancara berhasil dihapus.');
    }
}
