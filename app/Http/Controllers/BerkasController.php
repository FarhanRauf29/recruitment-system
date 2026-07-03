<?php

namespace App\Http\Controllers;

use App\Models\Berkas;
use App\Models\Pelamar;
use App\Models\PelamarTahapan;
use App\Models\TahapanRekrutmen;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BerkasController extends Controller
{
    private $fileFields = [
        'cv_file',
        'surat_lamaran_file',
        'ijazah_file',
        'transkrip_nilai_file',
        'ktp_file',
        'pas_foto_file',
        'sertifikat_file',
        'skck_file',
        'surat_keterangan_sehat_file',
        'portofolio_file',
        'kartu_keluarga_file',
        'dokumen_lain_file'
    ];

    private $requiredFields = [
        'cv_file',
        'surat_lamaran_file',
        'ijazah_file',
        'transkrip_nilai_file',
        'ktp_file',
        'pas_foto_file',
        'skck_file',
        'surat_keterangan_sehat_file'
    ];

    private $optionalFields = [
        'sertifikat_file',
        'portofolio_file',
        'kartu_keluarga_file',
        'dokumen_lain_file'
    ];

    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($user->isAdmin()) {
            return $this->adminIndex();
        }

        return $this->pelamarIndex($user);
    }

    private function adminIndex()
    {
        $query = Berkas::with(['pelamar.user', 'pelamarTahapan.lowongan']);

        if (request('search')) {
            $search = request('search');
            $query->whereHas('pelamar', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (request('lowongan_id')) {
            $query->whereHas('pelamarTahapan', function ($q) {
                $q->where('lowongan_id', request('lowongan_id'));
            });
        }

        $berkas = $query->latest()->paginate(10)->withQueryString();
        $lowongans = Lowongan::where('status', 'buka')->get();

        return view('admin.berkas.index', compact('berkas', 'lowongans'));
    }

    private function pelamarIndex($user)
    {
        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $berkas = Berkas::where('pelamar_id', $pelamar->id)
            ->with('pelamarTahapan.lowongan')
            ->latest()
            ->paginate(10);

        return view('pelamar.berkas.index', compact('berkas'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $lowonganId = $request->get('lowongan_id');
        $availableLowongans = PelamarTahapan::with('lowongan')
            ->where('pelamar_id', $pelamar->id)
            ->where('status', 'diproses')
            ->whereHas('tahapan', function ($q) {
                $q->where('nama_tahapan', 'like', '%screening%')
                    ->orWhere('nama_tahapan', 'like', '%berkas%');
            })
            ->get();

        $existingBerkas = Berkas::where('pelamar_id', $pelamar->id)
            ->whereNotNull('pelamar_tahapan_id')
            ->pluck('pelamar_tahapan_id')
            ->toArray();

        return view('pelamar.berkas.create', compact('availableLowongans', 'existingBerkas', 'lowonganId'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk mengunggah berkas.');
        }

        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar) {
            return redirect()->route('pelamar.profile')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
        }

        $request->validate([
            'pelamar_tahapan_id' => 'required|exists:pelamar_tahapan,id',
        ]);

        $existingBerkas = Berkas::where('pelamar_id', $pelamar->id)
            ->where('pelamar_tahapan_id', $request->pelamar_tahapan_id)
            ->first();

        if ($existingBerkas) {
            return redirect()->back()->with('error', 'Anda sudah mengupload berkas untuk lowongan ini. Silakan edit berkas yang sudah ada.');
        }

        $pelamarTahapan = PelamarTahapan::with('tahapan')->find($request->pelamar_tahapan_id);

        if (!$pelamarTahapan || $pelamarTahapan->pelamar_id != $pelamar->id) {
            return redirect()->back()->with('error', 'Data tahapan tidak valid.');
        }

        $tahapanNama = strtolower($pelamarTahapan->tahapan->nama_tahapan ?? '');
        if (!str_contains($tahapanNama, 'screening') && !str_contains($tahapanNama, 'berkas')) {
            return redirect()->back()->with('error', 'Anda hanya dapat mengupload berkas pada tahap Screening Berkas.');
        }

        $rules = $this->fileValidationRules();
        $rules['pelamar_tahapan_id'] = 'required|exists:pelamar_tahapan,id';

        $request->validate($rules);

        $data = [
            'pelamar_id' => $pelamar->id,
            'pelamar_tahapan_id' => $request->pelamar_tahapan_id,
            'status_verifikasi' => 'Pending',
        ];

        $uploadedFields = [];
        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                $data[$field] = $request->file($field)->store("berkas/pelamar_{$pelamar->id}/lowongan_{$pelamarTahapan->lowongan_id}", 'public');
                $uploadedFields[] = $field;
            }
        }

        $missingRequired = array_diff($this->requiredFields, $uploadedFields);
        if (!empty($missingRequired)) {
            $missingNames = [];
            $fieldLabels = [
                'cv_file' => 'CV',
                'surat_lamaran_file' => 'Surat Lamaran',
                'ijazah_file' => 'Ijazah',
                'transkrip_nilai_file' => 'Transkrip Nilai',
                'ktp_file' => 'KTP',
                'pas_foto_file' => 'Pas Foto',
                'skck_file' => 'SKCK',
                'surat_keterangan_sehat_file' => 'Surat Keterangan Sehat'
            ];
            foreach ($missingRequired as $field) {
                $missingNames[] = $fieldLabels[$field] ?? $field;
            }
            return redirect()->back()->with('error', 'Harap upload semua berkas wajib: ' . implode(', ', $missingNames));
        }

        Berkas::create($data);

        return redirect()->route('pelamar.berkas.index')
            ->with('success', 'Berkas berhasil diunggah. Silakan tunggu verifikasi dari admin.');
    }

    public function show(Berkas $berkas)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $berkas->load(['pelamar.user', 'pelamarTahapan.lowongan', 'pelamarTahapan.tahapan']);

        if ($user->isAdmin()) {
            return view('admin.berkas.show', compact('berkas'));
        }

        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar || $berkas->pelamar_id != $pelamar->id) {
            abort(403, 'Anda tidak memiliki akses ke berkas ini.');
        }

        return view('pelamar.berkas.show', compact('berkas'));
    }

    public function edit(Berkas $berkas)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->isAdmin()) {
            $berkas->load(['pelamar.user', 'pelamarTahapan.lowongan', 'pelamarTahapan.tahapan']);
            return view('admin.berkas.edit', compact('berkas'));
        }

        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar || $berkas->pelamar_id != $pelamar->id) {
            abort(403, 'Anda tidak memiliki akses ke berkas ini.');
        }

        if ($berkas->status_verifikasi != 'Pending') {
            return redirect()->route('pelamar.berkas.index')
                ->with('error', 'Berkas sudah diverifikasi, tidak dapat diedit lagi.');
        }

        return view('pelamar.berkas.edit', compact('berkas'));
    }

    public function update(Request $request, Berkas $berkas)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login untuk melanjutkan.');
        }

        if ($user->isAdmin()) {
            return $this->adminUpdate($request, $berkas);
        }

        $pelamar = Pelamar::where('user_id', $user->id)->first();
        if (!$pelamar || $berkas->pelamar_id != $pelamar->id) {
            abort(403, 'Anda tidak memiliki akses ke berkas ini.');
        }

        if ($berkas->status_verifikasi != 'Pending') {
            return redirect()->route('pelamar.berkas.index')
                ->with('error', 'Berkas sudah diverifikasi, tidak dapat diedit lagi.');
        }

        $request->validate($this->fileValidationRules());

        $data = [];
        $uploadedFields = [];
        foreach ($this->fileFields as $field) {
            if ($request->hasFile($field)) {
                if ($berkas->$field) {
                    Storage::disk('public')->delete($berkas->$field);
                }
                $data[$field] = $request->file($field)->store("berkas/pelamar_{$pelamar->id}/lowongan_{$berkas->pelamarTahapan->lowongan_id}", 'public');
                $uploadedFields[] = $field;
            }
        }

        $existingFields = [];
        foreach ($this->requiredFields as $field) {
            if ($berkas->$field) {
                $existingFields[] = $field;
            }
        }

        $allFields = array_merge($existingFields, $uploadedFields);
        $missingRequired = array_diff($this->requiredFields, $allFields);

        if (!empty($missingRequired)) {
            $missingNames = [];
            $fieldLabels = [
                'cv_file' => 'CV',
                'surat_lamaran_file' => 'Surat Lamaran',
                'ijazah_file' => 'Ijazah',
                'transkrip_nilai_file' => 'Transkrip Nilai',
                'ktp_file' => 'KTP',
                'pas_foto_file' => 'Pas Foto',
                'skck_file' => 'SKCK',
                'surat_keterangan_sehat_file' => 'Surat Keterangan Sehat'
            ];
            foreach ($missingRequired as $field) {
                $missingNames[] = $fieldLabels[$field] ?? $field;
            }
            return redirect()->back()->with('error', 'Harap upload semua berkas wajib: ' . implode(', ', $missingNames));
        }

        if (count($data) > 0) {
            $berkas->update($data);
        }

        return redirect()->route('pelamar.berkas.index')
            ->with('success', 'Berkas berhasil diperbarui.');
    }

    private function adminUpdate(Request $request, Berkas $berkas)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:Pending,Diterima,Ditolak',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $newStatus = $request->status_verifikasi;
            $oldStatus = $berkas->status_verifikasi;

            $berkas->status_verifikasi = $newStatus;
            $berkas->catatan_admin = $request->catatan_admin;
            $berkas->tanggal_verifikasi = Carbon::now('Asia/Jakarta');
            $berkas->save();

            $pelamarTahapan = PelamarTahapan::with('tahapan')->find($berkas->pelamar_tahapan_id);

            if ($pelamarTahapan) {
                $now = Carbon::now('Asia/Jakarta');
                $pelamarId = $berkas->pelamar_id;
                $lowonganId = $pelamarTahapan->lowongan_id;
                $currentTahapanId = $pelamarTahapan->tahapan_id;
                $currentUrutan = $pelamarTahapan->tahapan->urutan;

                if ($newStatus === 'Diterima' && $oldStatus !== 'Diterima') {
                    $pelamarTahapan->status = 'lolos';
                    $pelamarTahapan->tanggal_selesai = $now;
                    $pelamarTahapan->catatan = 'Berkas lengkap dan sesuai, dinyatakan lolos ke tahap selanjutnya.';
                    $pelamarTahapan->save();

                    $allTahapans = TahapanRekrutmen::orderBy('urutan')->get();
                    $nextTahapanFound = false;

                    foreach ($allTahapans as $tahap) {
                        if ($tahap->urutan <= $currentUrutan) {
                            continue;
                        }

                        $existingNext = PelamarTahapan::where('pelamar_id', $pelamarId)
                            ->where('lowongan_id', $lowonganId)
                            ->where('tahapan_id', $tahap->id)
                            ->first();

                        if ($existingNext) {
                            if ($nextTahapanFound === false) {
                                if ($existingNext->status === 'menunggu' || $existingNext->status === 'belum_dimulai' || $existingNext->status === 'terkunci') {
                                    $existingNext->status = 'diproses';
                                    $existingNext->tanggal_mulai = $now;
                                    $existingNext->catatan = 'Tahapan ini terbuka setelah lolos tahap sebelumnya.';
                                    $existingNext->save();
                                    $nextTahapanFound = true;
                                }
                            } else {
                                if ($existingNext->status === 'diproses') {
                                    $existingNext->status = 'menunggu';
                                    $existingNext->tanggal_mulai = null;
                                    $existingNext->catatan = 'Menunggu giliran tahapan ini.';
                                    $existingNext->save();
                                }
                            }
                        } else {
                            if ($nextTahapanFound === false) {
                                PelamarTahapan::create([
                                    'pelamar_id' => $pelamarId,
                                    'lowongan_id' => $lowonganId,
                                    'tahapan_id' => $tahap->id,
                                    'status' => 'diproses',
                                    'tanggal_mulai' => $now,
                                    'catatan' => 'Tahapan ini terbuka setelah lolos tahap sebelumnya.',
                                ]);
                                $nextTahapanFound = true;
                            } else {
                                PelamarTahapan::create([
                                    'pelamar_id' => $pelamarId,
                                    'lowongan_id' => $lowonganId,
                                    'tahapan_id' => $tahap->id,
                                    'status' => 'menunggu',
                                    'tanggal_mulai' => null,
                                    'catatan' => 'Menunggu giliran tahapan ini.',
                                ]);
                            }
                        }
                    }

                    PelamarTahapan::where('pelamar_id', $pelamarId)
                        ->where('lowongan_id', $lowonganId)
                        ->where('tahapan_id', '!=', $currentTahapanId)
                        ->where('status', 'menunggu')
                        ->where('status', '!=', 'diproses')
                        ->update(['status' => 'menunggu']);
                } elseif ($newStatus === 'Ditolak' && $oldStatus !== 'Ditolak') {
                    $pelamarTahapan->status = 'gagal';
                    $pelamarTahapan->tanggal_selesai = $now;
                    $pelamarTahapan->catatan = $request->catatan_admin ?: 'Berkas tidak memenuhi persyaratan yang ditentukan.';
                    $pelamarTahapan->save();

                    PelamarTahapan::where('pelamar_id', $pelamarId)
                        ->where('lowongan_id', $lowonganId)
                        ->where('status', 'menunggu')
                        ->orWhere('status', 'diproses')
                        ->orWhere('status', 'belum_dimulai')
                        ->update(['status' => 'terkunci']);
                } elseif ($newStatus === 'Pending') {
                    if ($oldStatus === 'Diterima') {
                        $pelamarTahapan->status = 'diproses';
                        $pelamarTahapan->tanggal_mulai = $now;
                        $pelamarTahapan->tanggal_selesai = null;
                        $pelamarTahapan->catatan = 'Menunggu verifikasi ulang berkas.';
                        $pelamarTahapan->save();

                        PelamarTahapan::where('pelamar_id', $pelamarId)
                            ->where('lowongan_id', $lowonganId)
                            ->where('status', 'diproses')
                            ->where('tahapan_id', '!=', $currentTahapanId)
                            ->update(['status' => 'menunggu']);
                    } else {
                        $pelamarTahapan->status = 'menunggu';
                        $pelamarTahapan->tanggal_mulai = $now;
                        $pelamarTahapan->tanggal_selesai = null;
                        $pelamarTahapan->catatan = 'Menunggu verifikasi berkas.';
                        $pelamarTahapan->save();

                        PelamarTahapan::where('pelamar_id', $pelamarId)
                            ->where('lowongan_id', $lowonganId)
                            ->where('status', 'diproses')
                            ->where('tahapan_id', '!=', $currentTahapanId)
                            ->update(['status' => 'menunggu']);
                    }
                }
            }

            DB::commit();

            $message = $newStatus === 'Diterima' ? 'Berkas diterima. Pelamar lolos ke tahap selanjutnya.' : ($newStatus === 'Ditolak' ? 'Berkas ditolak. Pelamar dinyatakan tidak lolos.' : 'Status berkas diubah menjadi Pending.');

            return redirect()->route('admin.berkas.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating berkas status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    public function destroy(Berkas $berkas)
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        DB::beginTransaction();
        try {
            foreach ($this->fileFields as $field) {
                if ($berkas->$field) {
                    Storage::disk('public')->delete($berkas->$field);
                }
            }

            $berkas->delete();

            DB::commit();

            return redirect()->route('admin.berkas.index')
                ->with('success', 'Berkas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting berkas: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus berkas.');
        }
    }

    private function fileValidationRules()
    {
        $rules = [];
        foreach ($this->fileFields as $field) {
            $rules[$field] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }
        return $rules;
    }
}
