<?php

namespace App\Http\Controllers;

use App\Models\PelamarTahapan;
use App\Models\Pelamar;
use App\Models\Lowongan;
use App\Models\TahapanRekrutmen;
use App\Models\MCU;
use App\Models\Wawancara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelamarTahapanController extends Controller
{
    public function index()
    {
        $pelamarTahapans = PelamarTahapan::with(['pelamar', 'lowongan', 'tahapanRekrutmen'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.pelamartahapan.index', compact('pelamarTahapans'));
    }

    public function create()
    {
        $pelamars = Pelamar::orderBy('nama_lengkap')->get();
        $lowongans = Lowongan::orderBy('judul_lowongan')->get();
        $tahapans = TahapanRekrutmen::orderBy('urutan')->get();
        $statusOptions = ['menunggu', 'diproses', 'lolos', 'gagal'];
        return view('admin.pelamartahapan.create', compact('pelamars', 'lowongans', 'tahapans', 'statusOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelamar_id' => 'required|exists:pelamar,id',
            'lowongan_id' => 'required|exists:lowongan,id',
            'tahapan_id' => 'required|exists:tahapan_rekrutmen,id',
            'status' => 'required|in:menunggu,diproses,lolos,gagal',
            'catatan' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);
        PelamarTahapan::create($request->all());
        return redirect()->route('admin.pelamartahapan.index')->with('success', 'Data tahapan pelamar berhasil ditambahkan.');
    }

    public function show(PelamarTahapan $pelamartahapan)
    {
        $pelamartahapan->load(['pelamar', 'lowongan', 'tahapanRekrutmen']);
        return view('admin.pelamartahapan.show', compact('pelamartahapan'));
    }

    public function edit(PelamarTahapan $pelamartahapan)
    {
        $pelamars = Pelamar::orderBy('nama_lengkap')->get();
        $lowongans = Lowongan::orderBy('judul_lowongan')->get();
        $tahapans = TahapanRekrutmen::orderBy('urutan')->get();
        $statusOptions = ['menunggu', 'diproses', 'lolos', 'gagal'];
        return view('admin.pelamartahapan.edit', compact('pelamartahapan', 'pelamars', 'lowongans', 'tahapans', 'statusOptions'));
    }

    public function update(Request $request, PelamarTahapan $pelamartahapan)
    {
        $request->validate([
            'pelamar_id' => 'required|exists:pelamar,id',
            'lowongan_id' => 'required|exists:lowongan,id',
            'tahapan_id' => 'required|exists:tahapan_rekrutmen,id',
            'status' => 'required|in:menunggu,diproses,lolos,gagal',
            'catatan' => 'nullable|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        ]);
        $pelamartahapan->update($request->all());
        return redirect()->route('admin.pelamartahapan.index')->with('success', 'Data tahapan pelamar berhasil diperbarui.');
    }

    public function destroy(PelamarTahapan $pelamartahapan)
    {
        $pelamartahapan->delete();
        return redirect()->route('admin.pelamartahapan.index')->with('success', 'Data tahapan pelamar berhasil dihapus.');
    }

    public function pelamarIndex(Request $request)
    {
        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }
        $tahapanByLowongan = PelamarTahapan::with(['tahapan', 'lowongan'])
            ->where('pelamar_id', $pelamar->id)
            ->get()
            ->groupBy('lowongan_id');
        $lowonganIds = $tahapanByLowongan->keys();
        $lowongans = Lowongan::whereIn('id', $lowonganIds)->get()->keyBy('id');
        return view('pelamar.pelamartahapan.index', compact('tahapanByLowongan', 'lowongans'));
    }

    public function pelamarShow($id)
    {
        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }
        $tahapan = PelamarTahapan::with(['tahapan', 'lowongan', 'berkas'])
            ->where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->first();
        if (!$tahapan) {
            return redirect()->route('pelamar.pelamartahapan.index')->with('error', 'Data tahapan tidak ditemukan.');
        }
        $allTahapan = PelamarTahapan::with('tahapan')
            ->where('pelamar_id', $pelamar->id)
            ->where('lowongan_id', $tahapan->lowongan_id)
            ->orderBy('id')
            ->get();
        $currentIndex = $allTahapan->search(function ($item) use ($id) {
            return $item->id == $id;
        });
        $prevTahapan = $currentIndex > 0 ? $allTahapan[$currentIndex - 1] : null;
        $nextTahapan = $currentIndex < $allTahapan->count() - 1 ? $allTahapan[$currentIndex + 1] : null;
        $mcu = collect();
        if (str_contains(strtolower($tahapan->tahapan->nama_tahapan ?? ''), 'medical') || str_contains(strtolower($tahapan->tahapan->nama_tahapan ?? ''), 'mcu')) {
            $mcu = MCU::where('pelamar_tahapan_id', $tahapan->id)
                ->orWhere(function ($q) use ($pelamar, $tahapan) {
                    $q->where('pelamar_id', $pelamar->id)
                        ->where('lowongan_id', $tahapan->lowongan_id);
                })
                ->get();
        }
        $wawancara = collect();
        if (str_contains(strtolower($tahapan->tahapan->nama_tahapan ?? ''), 'wawancara') || str_contains(strtolower($tahapan->tahapan->nama_tahapan ?? ''), 'interview')) {
            $wawancara = Wawancara::where('pelamar_tahapan_id', $tahapan->id)
                ->orWhere(function ($q) use ($pelamar, $tahapan) {
                    $q->where('pelamar_id', $pelamar->id)
                        ->where('lowongan_id', $tahapan->lowongan_id);
                })
                ->get();
        }
        if (empty($tahapan->catatan) || $tahapan->catatan == $tahapan->tahapan->nama_tahapan) {
            $tahapan->catatan = $this->getDefaultCatatan($tahapan);
        }
        return view('pelamar.pelamartahapan.show', compact('tahapan', 'prevTahapan', 'nextTahapan', 'allTahapan', 'mcu', 'wawancara'));
    }

    private function getDefaultCatatan($tahapan)
    {
        $tahapanNama = strtolower($tahapan->tahapan->nama_tahapan ?? '');
        if (str_contains($tahapanNama, 'screening') || str_contains($tahapanNama, 'berkas')) {
            if ($tahapan->status == 'lolos') {
                return 'Berkas lengkap dan sesuai, dinyatakan lolos ke tahap selanjutnya.';
            } elseif ($tahapan->status == 'gagal') {
                return 'Berkas tidak memenuhi persyaratan yang ditentukan.';
            }
            return 'Menunggu verifikasi berkas oleh admin.';
        }
        if (str_contains($tahapanNama, 'psikotes')) {
            if ($tahapan->status == 'lolos') {
                return 'Tes psikotes telah selesai dan dinyatakan lulus.';
            } elseif ($tahapan->status == 'gagal') {
                return 'Tes psikotes telah selesai dan dinyatakan tidak lulus.';
            }
            return 'Menunggu pelaksanaan tes psikotes.';
        }
        if (str_contains($tahapanNama, 'medical') || str_contains($tahapanNama, 'mcu')) {
            if ($tahapan->status == 'lolos') {
                return 'Medical check up selesai dan dinyatakan sehat/lulus.';
            } elseif ($tahapan->status == 'gagal') {
                return 'Medical check up tidak memenuhi persyaratan kesehatan.';
            } elseif ($tahapan->status == 'diproses') {
                return 'Medical check up sedang dalam proses.';
            }
            return 'Menunggu jadwal medical check up.';
        }
        if (str_contains($tahapanNama, 'wawancara') || str_contains($tahapanNama, 'interview')) {
            if ($tahapan->status == 'lolos') {
                return 'Wawancara selesai dan dinyatakan lulus.';
            } elseif ($tahapan->status == 'gagal') {
                return 'Wawancara selesai dan dinyatakan tidak lulus.';
            } elseif ($tahapan->status == 'diproses') {
                return 'Wawancara sedang dalam proses.';
            }
            return 'Menunggu jadwal wawancara.';
        }
        return $tahapan->status == 'lolos' ? 'Tahapan ini telah selesai dan dinyatakan lolos.' : 'Tahapan ini sedang dalam proses.';
    }

    public function uploadBerkas(Request $request, $id)
    {
        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }
        $tahapan = PelamarTahapan::where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->first();
        if (!$tahapan) {
            return redirect()->route('pelamar.pelamartahapan.index')->with('error', 'Data tahapan tidak ditemukan.');
        }
        if ($tahapan->status != 'diproses') {
            return redirect()->route('pelamar.pelamartahapan.show', $tahapan->id)->with('error', 'Akses ditolak. Anda tidak dapat mengupload berkas untuk tahapan ini.');
        }
        $request->validate([
            'berkas' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'keterangan' => 'nullable|string|max:500',
        ]);
        $file = $request->file('berkas');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('berkas_pelamar/' . $pelamar->id . '/tahapan_' . $tahapan->id, $fileName, 'public');
        \App\Models\Berkas::create([
            'pelamar_id' => $pelamar->id,
            'pelamar_tahapan_id' => $tahapan->id,
            'dokumen_lain_file' => $filePath,
            'keterangan' => $request->keterangan,
            'status_verifikasi' => 'Pending',
        ]);
        return redirect()->route('pelamar.pelamartahapan.show', $tahapan->id)->with('success', 'Berkas berhasil diupload.');
    }
}
