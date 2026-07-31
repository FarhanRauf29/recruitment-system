<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\Pelamar;
use App\Models\Berkas;
use App\Models\PelamarTahapan;
use App\Models\TahapanRekrutmen;
use App\Models\HasilPsikotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        $query = Lowongan::latest();

        if ($search = $request->get('search')) {
            $query->where('judul_lowongan', 'like', "%{$search}%");
        }

        $lowongans = $query->paginate(10)->withQueryString();
        return view('admin.lowongan.index', compact('lowongans'));
    }

    public function create()
    {
        $statusOptions = Lowongan::statusOptions();
        return view('admin.lowongan.create', compact('statusOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|string',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'status' => 'required|in:buka,tutup',
            'slot' => 'required|integer|min:0',
        ]);

        Lowongan::create($request->all());

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function show(Lowongan $lowongan)
    {
        return view('admin.lowongan.show', compact('lowongan'));
    }

    public function edit(Lowongan $lowongan)
    {
        $statusOptions = Lowongan::statusOptions();
        return view('admin.lowongan.edit', compact('lowongan', 'statusOptions'));
    }

    public function update(Request $request, Lowongan $lowongan)
    {
        $request->validate([
            'judul_lowongan' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|string',
            'tanggal_buka' => 'required|date',
            'tanggal_tutup' => 'required|date|after_or_equal:tanggal_buka',
            'status' => 'required|in:buka,tutup',
            'slot' => 'required|integer|min:0',
        ]);

        $lowongan->update($request->all());

        $lowongan->updateStatusIfNeeded();

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(Lowongan $lowongan)
    {
        $lowongan->pelamarTahapans()->delete();
        $lowongan->delete();

        return redirect()->route('admin.lowongan.index')->with('success', 'Lowongan berhasil dihapus.');
    }

    public function listLowongan()
    {
        $lowongans = Lowongan::where('status', 'buka')
            ->whereDate('tanggal_tutup', '>=', now())
            ->paginate(10);

        return view('pelamar.lowongan.index', compact('lowongans'));
    }

    public function applyLowongan(Lowongan $lowongan)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return redirect()->back()->with('error', 'Data pelamar tidak ditemukan. Silakan lengkapi profil Anda.');
        }

        if ($pelamar->isRegisteredForLowongan($lowongan->id)) {
            return redirect()->back()->with('error', 'Anda sudah mendaftar di lowongan ini.');
        }

        if ($lowongan->status !== 'buka' || $lowongan->tanggal_tutup < now()) {
            return redirect()->back()->with('error', 'Lowongan tidak tersedia untuk pendaftaran.');
        }

        try {
            DB::beginTransaction();

            $pelamar->lowongans()->attach($lowongan->id, [
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info("✅ Pelamar {$pelamar->id} berhasil mendaftar lowongan {$lowongan->id}");

            try {
                $lowongan->createTahapanForPelamar($pelamar);
                Log::info("✅ Tahapan berhasil dibuat");
            } catch (\Exception $e) {
                Log::error("❌ Gagal createTahapanForPelamar: " . $e->getMessage());
            }

            DB::commit();

            return redirect()->route('pelamar.lowongan.index')
                ->with('success', 'Berhasil mendaftar di lowongan ini. Silakan cek tahapan rekrutmen Anda.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('❌ Fatal error apply: ' . $e->getMessage());
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                return redirect()->route('pelamar.lowongan.index')
                    ->with('error', 'Anda sudah mendaftar di lowongan ini.');
            }
            return redirect()->route('pelamar.lowongan.index')
                ->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }

    public function showApplicants(Lowongan $lowongan)
    {
        $pelamars = $lowongan->pelamars()
            ->with('user')
            ->paginate(15);

        return view('admin.lowongan.applicants', compact('lowongan', 'pelamars'));
    }

    public function showApplicantTahapan(Lowongan $lowongan, Pelamar $pelamar)
    {
        $tahapanAktif = TahapanRekrutmen::aktif()->orderBy('id')->get();

        $tahapanPelamar = [];
        foreach ($tahapanAktif as $tahapan) {
            $pelamarTahapan = PelamarTahapan::where('pelamar_id', $pelamar->id)
                ->where('lowongan_id', $lowongan->id)
                ->where('tahapan_id', $tahapan->id)
                ->first();

            if ($pelamarTahapan) {
                $tahapanPelamar[] = $pelamarTahapan;
            } else {
                $tahapanPelamar[] = (object)[
                    'id' => null,
                    'pelamar_id' => $pelamar->id,
                    'lowongan_id' => $lowongan->id,
                    'tahapan_id' => $tahapan->id,
                    'status' => 'menunggu',
                    'status_text' => 'Menunggu',
                    'catatan' => null,
                    'tanggal_mulai' => null,
                    'tanggal_selesai' => null,
                    'tahapan' => $tahapan,
                    'created_at' => null,
                    'updated_at' => null,
                ];
            }
        }

        return view('admin.lowongan.applicant_tahapan', compact('lowongan', 'pelamar', 'tahapanPelamar'));
    }

    public function updateTahapanStatus(Lowongan $lowongan, Pelamar $pelamar, Request $request)
    {
        $request->validate([
            'tahapan_id' => 'required|exists:tahapan_rekrutmen,id',
            'status' => 'required|in:menunggu,diproses,lolos,gagal',
            'catatan' => 'nullable|string',
        ]);

        $newStatus = $request->status;
        $tahapan = TahapanRekrutmen::find($request->tahapan_id);

        $previousTahapan = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->where('lowongan_id', $lowongan->id)
            ->where('tahapan_id', '<', $tahapan->id)
            ->where('status', 'lolos')
            ->orderBy('tahapan_id', 'desc')
            ->first();

        if ($tahapan->id > 1 && !$previousTahapan && $newStatus !== 'gagal') {
            return redirect()->back()->with('error', 'Tahapan sebelumnya belum lolos. Harap selesaikan tahapan sebelumnya terlebih dahulu.');
        }

        DB::beginTransaction();

        try {
            $pelamarTahapan = PelamarTahapan::firstOrNew([
                'pelamar_id' => $pelamar->id,
                'lowongan_id' => $lowongan->id,
                'tahapan_id' => $request->tahapan_id,
            ]);

            if (!$pelamarTahapan->exists) {
                $pelamarTahapan->tanggal_mulai = now();
            }

            if ($newStatus === 'lolos' && $pelamarTahapan->status !== 'lolos') {
                $pelamarTahapan->tanggal_selesai = now();
            }

            $pelamarTahapan->status = $newStatus;
            $pelamarTahapan->catatan = $request->catatan;
            $pelamarTahapan->save();

            if (str_contains(strtolower($tahapan->nama_tahapan), 'screening') || str_contains(strtolower($tahapan->nama_tahapan), 'berkas')) {
                $berkas = Berkas::where('pelamar_id', $pelamar->id)->first();

                if ($berkas) {
                    if ($newStatus === 'lolos') {
                        $berkas->status_verifikasi = 'Diterima';
                        $berkas->catatan_admin = $request->catatan ?? 'Berkas diterima. Lolos seleksi administrasi.';
                        $berkas->save();
                        Log::info("✅ Berkas untuk pelamar {$pelamar->id} diupdate menjadi Diterima");
                    } elseif ($newStatus === 'gagal') {
                        $berkas->status_verifikasi = 'Ditolak';
                        $berkas->catatan_admin = $request->catatan ?? 'Berkas ditolak. Tidak lolos seleksi administrasi.';
                        $berkas->save();
                        Log::info("✅ Berkas untuk pelamar {$pelamar->id} diupdate menjadi Ditolak");
                    } else {
                        $berkas->status_verifikasi = 'Pending';
                        $berkas->catatan_admin = $request->catatan ?? 'Menunggu verifikasi berkas.';
                        $berkas->save();
                    }
                } else {
                    Log::warning("⚠️ Tidak ditemukan berkas untuk pelamar {$pelamar->id}");
                }
            }

            if ($newStatus === 'lolos') {
                $nextTahapan = TahapanRekrutmen::aktif()
                    ->where('id', '>', $tahapan->id)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($nextTahapan) {
                    $nextPelamarTahapan = PelamarTahapan::firstOrNew([
                        'pelamar_id' => $pelamar->id,
                        'lowongan_id' => $lowongan->id,
                        'tahapan_id' => $nextTahapan->id,
                    ]);

                    if (!$nextPelamarTahapan->exists) {
                        $nextPelamarTahapan->status = 'diproses';
                        $nextPelamarTahapan->tanggal_mulai = now();
                        $nextPelamarTahapan->save();
                        Log::info("✅ Tahapan {$nextTahapan->nama_tahapan} dibuka menjadi DIPROSES untuk pelamar {$pelamar->id}");
                    } elseif ($nextPelamarTahapan->status === 'menunggu') {
                        $nextPelamarTahapan->status = 'diproses';
                        $nextPelamarTahapan->tanggal_mulai = now();
                        $nextPelamarTahapan->save();
                        Log::info("✅ Tahapan {$nextTahapan->nama_tahapan} diubah menjadi DIPROSES untuk pelamar {$pelamar->id}");
                    }
                }
            } elseif ($newStatus === 'gagal') {
                $nextTahapans = TahapanRekrutmen::aktif()
                    ->where('id', '>', $tahapan->id)
                    ->get();

                foreach ($nextTahapans as $nextTahapan) {
                    $nextPelamarTahapan = PelamarTahapan::firstOrNew([
                        'pelamar_id' => $pelamar->id,
                        'lowongan_id' => $lowongan->id,
                        'tahapan_id' => $nextTahapan->id,
                    ]);
                    $nextPelamarTahapan->status = 'gagal';
                    $nextPelamarTahapan->catatan = 'Tahapan sebelumnya gagal';
                    $nextPelamarTahapan->tanggal_selesai = now();
                    $nextPelamarTahapan->save();
                }
            }

            $lowongan->updateStatusIfNeeded();

            DB::commit();

            $message = $newStatus === 'lolos'
                ? 'Status tahapan berhasil diperbarui ke Lolos. Tahapan selanjutnya telah dibuka.'
                : 'Status tahapan berhasil diperbarui.';
            return redirect()->back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating tahapan status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function showLowongan(Lowongan $lowongan)
    {
        if ($lowongan->status !== 'buka' || $lowongan->tanggal_tutup < now()) {
            return redirect()->route('pelamar.lowongan.index')->with('error', 'Lowongan tidak tersedia.');
        }

        return view('pelamar.lowongan.show', compact('lowongan'));
    }
    public function welcome()
    {
        $lowongans = Lowongan::where('status', 'buka')
            ->whereDate('tanggal_tutup', '>=', now())
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('welcome', compact('lowongans'));
    }
}
