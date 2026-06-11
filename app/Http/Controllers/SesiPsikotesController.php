<?php

namespace App\Http\Controllers;

use App\Models\SesiPsikotes;
use App\Models\Lowongan;
use App\Models\KonfigurasiPsikotes;
use App\Models\PelamarTahapan;
use App\Models\SoalPsikotes;
use App\Models\JawabanPsikotes;
use App\Models\HasilPsikotes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SesiPsikotesController extends Controller
{
    public function index()
    {
        $sesis = SesiPsikotes::with(['pelamar', 'lowongan', 'konfigurasi'])
            ->where('waktu_selesai', '>', now()->subDay())
            ->latest()
            ->paginate(10);
        return view('admin.sesipsikotes.index', compact('sesis'));
    }

    public function create()
    {
        $lowongans = Lowongan::whereHas('pelamarTahapans', function ($q) {
            $q->where('status', 'lolos');
        })->get();
        return view('admin.sesipsikotes.create', compact('lowongans'));
    }

    public function getPelamarByLowongan($lowonganId)
    {
        $pelamars = PelamarTahapan::where('lowongan_id', $lowonganId)
            ->where('status', 'lolos')
            ->with('pelamar.user', 'tahapan')
            ->get()
            ->map(function ($pt) {
                return [
                    'id' => $pt->pelamar_id,
                    'nama' => $pt->pelamar->user->name,
                    'email' => $pt->pelamar->user->email,
                    'tahapan_id' => $pt->id
                ];
            });
        return response()->json($pelamars);
    }

    public function store(Request $request)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongan,id',
            'waktu_mulai' => 'required|date|after:now',
            'jumlah_pelamar_per_sesi' => 'required|integer|min:1|max:100'
        ]);

        $konfigurasi = KonfigurasiPsikotes::where('lowongan_id', $request->lowongan_id)->first();
        if (!$konfigurasi) {
            return redirect()->back()->with('error', 'Konfigurasi psikotes belum tersedia untuk lowongan ini.');
        }

        $waktuMulai = Carbon::parse($request->waktu_mulai);
        $waktuSelesai = $waktuMulai->copy()->addMinutes($konfigurasi->durasi_menit);

        $pelamarTahapans = PelamarTahapan::where('lowongan_id', $request->lowongan_id)
            ->where('status', 'lolos')
            ->with('pelamar')
            ->get();

        if ($pelamarTahapans->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pelamar yang lolos screening untuk lowongan ini.');
        }

        $jumlahPelamar = $pelamarTahapans->count();
        $jumlahPerSesi = $request->jumlah_pelamar_per_sesi;
        $jumlahSesi = ceil($jumlahPelamar / $jumlahPerSesi);

        $soalIds = collect();
        if ($konfigurasi->jumlah_soal_kognitif > 0) {
            $soalKognitif = SoalPsikotes::where('jenis', 'kognitif')
                ->inRandomOrder()
                ->limit($konfigurasi->jumlah_soal_kognitif)
                ->pluck('id');
            $soalIds = $soalIds->concat($soalKognitif);
        }
        if ($konfigurasi->jumlah_soal_kepribadian > 0) {
            $soalKepribadian = SoalPsikotes::where('jenis', 'kepribadian')
                ->inRandomOrder()
                ->limit($konfigurasi->jumlah_soal_kepribadian)
                ->pluck('id');
            $soalIds = $soalIds->concat($soalKepribadian);
        }
        if ($konfigurasi->jumlah_soal_minat > 0) {
            $soalMinat = SoalPsikotes::where('jenis', 'minat')
                ->inRandomOrder()
                ->limit($konfigurasi->jumlah_soal_minat)
                ->pluck('id');
            $soalIds = $soalIds->concat($soalMinat);
        }

        $totalSoal = $soalIds->count();
        $createdSessions = 0;
        $chunks = $pelamarTahapans->chunk($jumlahPerSesi);

        foreach ($chunks as $index => $chunkPelamar) {
            foreach ($chunkPelamar as $pt) {
                do {
                    $token = Str::random(6);
                } while (SesiPsikotes::where('token_sesi', $token)->exists());

                SesiPsikotes::create([
                    'pelamar_id' => $pt->pelamar_id,
                    'lowongan_id' => $request->lowongan_id,
                    'pelamar_tahapan_id' => $pt->id,
                    'konfigurasi_id' => $konfigurasi->id,
                    'waktu_mulai' => $waktuMulai,
                    'waktu_selesai' => $waktuSelesai,
                    'durasi_menit' => $konfigurasi->durasi_menit,
                    'status' => 'belum_dimulai',
                    'selesai' => false,
                    'soal_terpilih' => $soalIds->toArray(),
                    'jumlah_soal_ditampilkan' => $totalSoal,
                    'token_sesi' => $token,
                    'ip_address' => null,
                    'user_agent' => null,
                    'jawaban_terisi' => [],
                    'waktu_pause' => null,
                    'durasi_terpakai_detik' => 0,
                ]);
                $createdSessions++;
            }
        }

        return redirect()->route('admin.sesipsikotes.index')
            ->with('success', 'Sesi psikotes berhasil dibuat untuk ' . $createdSessions . ' pelamar dalam ' . $chunks->count());
    }

    public function edit($id)
    {
        $sesi = SesiPsikotes::with(['pelamar', 'lowongan', 'konfigurasi'])->findOrFail($id);
        $lowongans = Lowongan::whereHas('pelamarTahapans', function ($q) {
            $q->where('status', 'lolos');
        })->get();
        return view('admin.sesipsikotes.edit', compact('sesi', 'lowongans'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongan,id',
            'waktu_mulai' => 'required|date|after:now',
        ]);

        $konfigurasi = KonfigurasiPsikotes::where('lowongan_id', $request->lowongan_id)->first();
        if (!$konfigurasi) {
            return redirect()->back()->with('error', 'Konfigurasi psikotes belum tersedia untuk lowongan ini.');
        }

        $waktuMulai = Carbon::parse($request->waktu_mulai);
        $waktuSelesai = $waktuMulai->copy()->addMinutes($konfigurasi->durasi_menit);

        $soalIds = collect();
        if ($konfigurasi->jumlah_soal_kognitif > 0) {
            $soalKognitif = SoalPsikotes::where('jenis', 'kognitif')
                ->inRandomOrder()
                ->limit($konfigurasi->jumlah_soal_kognitif)
                ->pluck('id');
            $soalIds = $soalIds->concat($soalKognitif);
        }
        if ($konfigurasi->jumlah_soal_kepribadian > 0) {
            $soalKepribadian = SoalPsikotes::where('jenis', 'kepribadian')
                ->inRandomOrder()
                ->limit($konfigurasi->jumlah_soal_kepribadian)
                ->pluck('id');
            $soalIds = $soalIds->concat($soalKepribadian);
        }
        if ($konfigurasi->jumlah_soal_minat > 0) {
            $soalMinat = SoalPsikotes::where('jenis', 'minat')
                ->inRandomOrder()
                ->limit($konfigurasi->jumlah_soal_minat)
                ->pluck('id');
            $soalIds = $soalIds->concat($soalMinat);
        }

        $totalSoal = $soalIds->count();
        $sesi = SesiPsikotes::findOrFail($id);

        $sesi->update([
            'lowongan_id' => $request->lowongan_id,
            'konfigurasi_id' => $konfigurasi->id,
            'waktu_mulai' => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'durasi_menit' => $konfigurasi->durasi_menit,
            'status' => 'belum_dimulai',
            'selesai' => false,
            'soal_terpilih' => $soalIds->toArray(),
            'jumlah_soal_ditampilkan' => $totalSoal,
            'jawaban_terisi' => [],
            'waktu_pause' => null,
            'durasi_terpakai_detik' => 0,
        ]);

        return redirect()->route('admin.sesipsikotes.index')
            ->with('success', 'Sesi psikotes berhasil diperbarui. Waktu selesai otomatis: ' . $waktuSelesai->format('d M Y H:i'));
    }

    public function show($id)
    {
        $sesi = SesiPsikotes::with([
            'pelamar.user',
            'lowongan',
            'konfigurasi',
            'jawabanPsikotes.soalPsikotes',
            'hasilPsikotes'
        ])->findOrFail($id);
        return view('admin.sesipsikotes.show', compact('sesi'));
    }

    public function destroy($id)
    {
        $sesi = SesiPsikotes::findOrFail($id);
        $sesi->delete();
        return redirect()->route('admin.sesipsikotes.index')
            ->with('success', 'Sesi psikotes berhasil dihapus.');
    }

    public function showTokenForm()
    {
        $user = Auth::user();
        $pelamar = $user->pelamar;

        if ($pelamar) {
            $sesiAktif = SesiPsikotes::where('pelamar_id', $pelamar->id)
                ->where('selesai', false)
                ->where('status', '!=', 'gagal')
                ->where('waktu_mulai', '<=', now())
                ->where('waktu_selesai', '>', now())
                ->first();

            if ($sesiAktif && session('valid_token') === $sesiAktif->token_sesi) {
                return redirect()->route('pelamar.psikotes.soal', $sesiAktif->token_sesi);
            }
        }

        return view('pelamar.psikotes.token');
    }

    public function startByToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string|exists:sesi_psikotes,token_sesi'
        ]);

        $user = Auth::user();
        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')->with('error', 'Data pelamar tidak ditemukan.');
        }

        $sesi = SesiPsikotes::where('token_sesi', $request->token)
            ->where('pelamar_id', $pelamar->id)
            ->first();

        if (!$sesi) {
            Log::warning('Token tidak valid atau bukan untuk pelamar ini: ' . $request->token . ' untuk pelamar ID: ' . $pelamar->id);
            return back()->with('error', 'Token tidak valid atau bukan untuk Anda.');
        }

        $tahapan = PelamarTahapan::where('id', $sesi->pelamar_tahapan_id)
            ->where('status', 'lolos')
            ->first();

        if (!$tahapan) {
            return back()->with('error', 'Anda belum lolos tahapan sebelumnya.');
        }

        $now = now();

        if ($now > $sesi->waktu_selesai) {
            if (!$sesi->selesai && $sesi->status !== 'gagal') {
                $this->autoSubmitTes($sesi);
            }
            return back()->with('error', 'Waktu tes telah berakhir. Anda dianggap gagal karena tidak mengikuti tes tepat waktu.');
        }

        if ($now < $sesi->waktu_mulai) {
            return back()->with('error', 'Tes belum dimulai. Waktu mulai: ' . $sesi->waktu_mulai->format('d M Y H:i'));
        }

        if ($sesi->selesai) {
            if ($sesi->status === 'gagal') {
                return back()->with('error', 'Anda sudah melewati batas waktu tes dan dianggap gagal.');
            }
            return redirect()->route('pelamar.psikotes.hasil', $sesi->token_sesi);
        }

        // PERBAIKAN: Simpan session dengan durasi lebih lama
        $request->session()->put('valid_token', $sesi->token_sesi);
        $request->session()->put('sesi_started_at', now()->timestamp);
        $request->session()->put('pelamar_id', $pelamar->id);

        $updateData = [
            'status' => 'sedang_berjalan',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'waktu_terakhir_aktivitas' => now(),
        ];

        if (is_null($sesi->waktu_mulai_pengerjaan)) {
            $updateData['waktu_mulai_pengerjaan'] = $now;
        }

        $sesi->update($updateData);

        return redirect()->route('pelamar.psikotes.soal', $sesi->token_sesi);
    }

    public function showSoalPsikotes($token)
    {
        $user = Auth::user();
        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')->with('error', 'Data pelamar tidak ditemukan.');
        }

        $validToken = session('valid_token');
        $sesiStartedAt = session('sesi_started_at');
        $sessionPelamarId = session('pelamar_id');

        if ($validToken !== $token || $sessionPelamarId !== $pelamar->id) {
            return redirect()->route('pelamar.psikotes.token')->with('warning', 'Silakan masukkan token terlebih dahulu.');
        }

        if ($sesiStartedAt && (now()->timestamp - $sesiStartedAt) > 28800) { // 8 jam
            session()->forget(['valid_token', 'sesi_started_at', 'pelamar_id']);
            return redirect()->route('pelamar.psikotes.token')->with('warning', 'Session telah kadaluarsa. Silakan masukkan token kembali.');
        }

        $sesi = SesiPsikotes::where('token_sesi', $token)
            ->where('pelamar_id', $pelamar->id)
            ->first();

        if (!$sesi) {
            session()->forget(['valid_token', 'sesi_started_at', 'pelamar_id']);
            return redirect()->route('pelamar.psikotes.token')->with('error', 'Sesi tidak ditemukan.');
        }

        $now = now();
        if ($now > $sesi->waktu_selesai && !$sesi->selesai) {
            $this->autoSubmitTes($sesi);
            session()->forget(['valid_token', 'sesi_started_at', 'pelamar_id']);
            return redirect()->route('pelamar.psikotes.hasil', $token)->with('info', 'Waktu tes telah habis. Jawaban telah disubmit otomatis.');
        }

        if ($sesi->selesai) {
            session()->forget(['valid_token', 'sesi_started_at', 'pelamar_id']);
            return redirect()->route('pelamar.psikotes.hasil', $token);
        }

        if ($sesi->status === 'dijeda') {
            $sesi->update([
                'status' => 'sedang_berjalan',
                'waktu_mulai_pengerjaan' => $now,
                'waktu_pause' => null,
                'waktu_terakhir_aktivitas' => $now,
            ]);
            Log::info('Status dijeda diperbaiki ke sedang_berjalan saat pelamar kembali ke soal.', ['sesi_id' => $sesi->id]);
        } else {

            $sesi->update(['waktu_terakhir_aktivitas' => $now]);
        }

        $waktuMulaiPengerjaan = $sesi->waktu_mulai_pengerjaan ?? $sesi->waktu_mulai;
        $durasiTerpakaiDetik = $sesi->durasi_terpakai_detik;

        if ($sesi->status === 'sedang_berjalan' && $waktuMulaiPengerjaan) {
            $waktuBerlalu = $now->diffInSeconds($waktuMulaiPengerjaan);
            $durasiTerpakaiDetik += $waktuBerlalu;
        }

        $sisaWaktuDetik = max(0, ($sesi->durasi_menit * 60) - $durasiTerpakaiDetik);

        $soalIds = $sesi->soal_terpilih ?? [];
        $soalTersedia = SoalPsikotes::whereIn('id', $soalIds)
            ->with('kategori')
            ->get()
            ->keyBy('id');

        $jawabanSudah = JawabanPsikotes::where('sesi_psikotes_id', $sesi->id)
            ->pluck('jawaban', 'soal_id')
            ->toArray();

        $progress = $soalIds ? round((count($jawabanSudah) / count($soalIds)) * 100, 2) : 0;
        $currentSoalIndex = $sesi->current_soal_index ?? 0;

        session([
            'sesi_started_at' => now()->timestamp,
            'sisa_waktu_detik' => $sisaWaktuDetik,
            'waktu_mulai_pengerjaan' => $waktuMulaiPengerjaan ? $waktuMulaiPengerjaan->timestamp : null,
            'durasi_terpakai_detik' => $durasiTerpakaiDetik
        ]);

        return view('pelamar.psikotes.soal', compact(
            'sesi',
            'soalTersedia',
            'jawabanSudah',
            'progress',
            'sisaWaktuDetik',
            'currentSoalIndex'
        ));
    }

    public function simpanJawaban(Request $request)
    {
        $request->validate([
            'soal_id' => 'required|exists:soal_psikotes,id',
            'jawaban' => 'required|string',
            'sesi_token' => 'required|exists:sesi_psikotes,token_sesi'
        ]);

        $user = Auth::user();
        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return response()->json(['error' => 'Data pelamar tidak ditemukan.'], 401);
        }

        // PERBAIKAN: Validasi session
        if (session('valid_token') !== $request->sesi_token || session('pelamar_id') !== $pelamar->id) {
            return response()->json(['error' => 'Session tidak valid.'], 401);
        }

        $sesi = SesiPsikotes::where('token_sesi', $request->sesi_token)
            ->where('pelamar_id', $pelamar->id)
            ->first();

        if (!$sesi) {
            return response()->json(['error' => 'Sesi tidak ditemukan.'], 404);
        }

        if ($sesi->selesai) {
            return response()->json(['error' => 'Tes sudah selesai.'], 400);
        }

        DB::transaction(function () use ($request, $sesi, $pelamar) {
            $jawaban = JawabanPsikotes::updateOrCreate(
                [
                    'sesi_psikotes_id' => $sesi->id,
                    'soal_id' => $request->soal_id
                ],
                [
                    'jawaban' => $request->jawaban,
                    'waktu_jawab' => now(),
                    'pelamar_id' => $pelamar->id,
                    'pelamar_tahapan_id' => $sesi->pelamar_tahapan_id,
                ]
            );

            $jawabanTerisi = $sesi->jawaban_terisi ?? [];
            $jawabanTerisi[$request->soal_id] = $request->jawaban;

            $sesi->update([
                'waktu_terakhir_aktivitas' => now(),
                'jawaban_terisi' => $jawabanTerisi
            ]);

            Log::info('Jawaban disimpan', [
                'sesi_id' => $sesi->id,
                'soal_id' => $request->soal_id,
                'jawaban' => $request->jawaban,
                'pelamar_id' => $pelamar->id
            ]);
        });

        return response()->json(['success' => true, 'message' => 'Jawaban berhasil disimpan']);
    }

    public function pauseTes(Request $request)
    {
        $request->validate([
            'sesi_token' => 'required|exists:sesi_psikotes,token_sesi',
            'current_soal_index' => 'required|integer'
        ]);

        $user = Auth::user();
        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return response()->json(['error' => 'Data pelamar tidak ditemukan.'], 401);
        }

        if (session('valid_token') !== $request->sesi_token || session('pelamar_id') !== $pelamar->id) {
            return response()->json(['error' => 'Session tidak valid.'], 401);
        }

        $sesi = SesiPsikotes::where('token_sesi', $request->sesi_token)
            ->where('pelamar_id', $pelamar->id)
            ->first();

        if (!$sesi) {
            return response()->json(['error' => 'Sesi tidak ditemukan.'], 404);
        }

        if ($sesi->selesai) {
            return response()->json(['error' => 'Tes sudah selesai.'], 400);
        }

        $now = now();
        $waktuMulaiPengerjaan = $sesi->waktu_mulai_pengerjaan ?? $sesi->waktu_mulai;
        $durasiTerpakaiBaru = $sesi->durasi_terpakai_detik;

        if ($sesi->status === 'sedang_berjalan' && $waktuMulaiPengerjaan) {
            $durasiTerpakaiBaru += $now->diffInSeconds($waktuMulaiPengerjaan);
        }

        DB::transaction(function () use ($sesi, $request, $now, $durasiTerpakaiBaru) {
            $sesi->update([
                'current_soal_index' => $request->current_soal_index,
                'waktu_terakhir_aktivitas' => $now,
                'status' => 'dijeda',
                'durasi_terpakai_detik' => $durasiTerpakaiBaru,
                'waktu_mulai_pengerjaan' => null,
                'waktu_pause' => $now
            ]);

            Log::info('Tes dijeda', [
                'sesi_id' => $sesi->id,
                'current_soal_index' => $request->current_soal_index,
                'durasi_terpakai' => $durasiTerpakaiBaru
            ]);
        });

        session()->put('tes_dijeda', true);

        return response()->json(['success' => true, 'message' => 'Tes dijeda. Anda dapat melanjutkan kapan saja.']);
    }

    public function resumeTes(Request $request)
    {
        $request->validate([
            'sesi_token' => 'required|exists:sesi_psikotes,token_sesi'
        ]);

        $user = Auth::user();
        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return response()->json(['error' => 'Data pelamar tidak ditemukan.'], 401);
        }

        if (session('valid_token') !== $request->sesi_token || session('pelamar_id') !== $pelamar->id) {
            return response()->json(['error' => 'Session tidak valid.'], 401);
        }

        $sesi = SesiPsikotes::where('token_sesi', $request->sesi_token)
            ->where('pelamar_id', $pelamar->id)
            ->first();

        if (!$sesi) {
            return response()->json(['error' => 'Sesi tidak ditemukan.'], 404);
        }

        if ($sesi->selesai) {
            return response()->json(['error' => 'Tes sudah selesai.'], 400);
        }

        DB::transaction(function () use ($sesi) {
            $sesi->update([
                'status' => 'sedang_berjalan',
                'waktu_mulai_pengerjaan' => now(),
                'waktu_pause' => null,
                'waktu_terakhir_aktivitas' => now()
            ]);

            Log::info('Tes dilanjutkan', ['sesi_id' => $sesi->id]);
        });

        session()->forget('tes_dijeda');

        return response()->json(['success' => true, 'message' => 'Tes dilanjutkan.']);
    }

    public function submitTes(Request $request)
    {
        try {
            $request->validate([
                'sesi_token' => 'required|exists:sesi_psikotes,token_sesi'
            ]);

            $user = Auth::user();
            $pelamar = $user->pelamar;

            if (!$pelamar) {
                return response()->json(['success' => false, 'error' => 'Data pelamar tidak ditemukan.'], 401);
            }

            if (session('valid_token') !== $request->sesi_token || session('pelamar_id') !== $pelamar->id) {
                return response()->json(['success' => false, 'error' => 'Session tidak valid.'], 401);
            }

            $sesi = SesiPsikotes::where('token_sesi', $request->sesi_token)
                ->where('pelamar_id', $pelamar->id)
                ->first();

            if (!$sesi) {
                return response()->json(['success' => false, 'error' => 'Sesi tidak ditemukan.'], 404);
            }

            if ($sesi->selesai) {
                return response()->json(['success' => false, 'error' => 'Tes sudah selesai.'], 400);
            }

            $totalSoal = count($sesi->soal_terpilih ?? []);
            $jawabanCount = JawabanPsikotes::where('sesi_psikotes_id', $sesi->id)->count();

            if ($jawabanCount < $totalSoal) {
                return response()->json(['success' => false, 'error' => 'Anda belum menjawab semua soal. Silakan jawab semua soal terlebih dahulu.'], 400);
            }

            $this->hitungHasilTes($sesi);

            session()->forget([
                'valid_token',
                'sesi_started_at',
                'pelamar_id',
                'sisa_waktu_detik',
                'waktu_mulai_pengerjaan',
                'durasi_terpakai_detik',
                'tes_dijeda'
            ]);

            return response()->json([
                'success' => true,
                'redirect' => route('pelamar.psikotes.hasil', $sesi->token_sesi)
            ]);
        } catch (\Exception $e) {
            Log::error('Submit tes error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'Terjadi kesalahan server: ' . $e->getMessage()], 500);
        }
    }

    private function hitungHasilTes($sesi)
    {
        DB::transaction(function () use ($sesi) {
            $jawaban = JawabanPsikotes::where('sesi_psikotes_id', $sesi->id)->get();
            $totalSkor = 0;
            $totalSoal = count($sesi->soal_terpilih);
            $soalTerjawab = $jawaban->count();
            $soalKognitifBenar = 0;
            $soalKognitifTotal = 0;
            $soalKepribadianBenar = 0;
            $soalKepribadianTotal = 0;

            $konfigurasi = KonfigurasiPsikotes::find($sesi->konfigurasi_id);
            $batasKelulusan = $konfigurasi ? $konfigurasi->batas_nilai_kelulusan : 60;

            foreach ($jawaban as $j) {
                $soal = SoalPsikotes::find($j->soal_id);
                if ($soal) {
                    if ($soal->jenis == 'kognitif') {
                        $soalKognitifTotal++;
                        if ($soal->kunci_jawaban == $j->jawaban) {
                            $totalSkor += $soal->bobot_nilai;
                            $soalKognitifBenar++;
                            $j->update(['benar' => true]);
                        } else {
                            $j->update(['benar' => false]);
                        }
                    } elseif ($soal->jenis == 'kepribadian') {
                        $soalKepribadianTotal++;
                        if ($soal->kunci_jawaban == $j->jawaban) {
                            $totalSkor += $soal->bobot_nilai;
                            $soalKepribadianBenar++;
                            $j->update(['benar' => true]);
                        } else {
                            $j->update(['benar' => false]);
                        }
                    } else {
                        if ($soal->kunci_jawaban == $j->jawaban) {
                            $totalSkor += $soal->bobot_nilai;
                            $j->update(['benar' => true]);
                        } else {
                            $j->update(['benar' => false]);
                        }
                    }
                }
            }

            $maxSkor = $totalSoal * 5;
            $persentase = $maxSkor > 0 ? round(($totalSkor / $maxSkor) * 100, 2) : 0;
            $lulus = $persentase >= $batasKelulusan;

            $nilaiKognitif = $soalKognitifTotal > 0 ? round(($soalKognitifBenar / $soalKognitifTotal) * 100, 2) : 0;
            $nilaiKepribadian = $soalKepribadianTotal > 0 ? round(($soalKepribadianBenar / $soalKepribadianTotal) * 100, 2) : 0;

            if ($persentase >= 85) {
                $rekomendasi = 'sangat_baik';
            } elseif ($persentase >= 70) {
                $rekomendasi = 'baik';
            } elseif ($persentase >= 50) {
                $rekomendasi = 'cukup';
            } elseif ($persentase >= 30) {
                $rekomendasi = 'kurang';
            } else {
                $rekomendasi = 'tidak_lulus';
            }

            HasilPsikotes::create([
                'pelamar_id' => $sesi->pelamar_id,
                'lowongan_id' => $sesi->lowongan_id,
                'pelamar_tahapan_id' => $sesi->pelamar_tahapan_id,
                'sesi_psikotes_id' => $sesi->id,
                'konfigurasi_id' => $sesi->konfigurasi_id,
                'total_soal_ditampilkan' => $totalSoal,
                'soal_dijawab' => $soalTerjawab,
                'soal_kognitif_ditampilkan' => $soalKognitifTotal,
                'soal_kepribadian_ditampilkan' => $soalKepribadianTotal,
                'soal_kognitif_benar' => $soalKognitifBenar,
                'soal_kognitif_salah' => $soalKognitifTotal - $soalKognitifBenar,
                'nilai_kognitif' => $nilaiKognitif,
                'nilai_kepribadian' => $nilaiKepribadian,
                'nilai_akhir' => $persentase,
                'keterangan' => $lulus ? "Lulus psikotes dengan nilai {$persentase}% (batas minimal {$batasKelulusan}%)" : "Tidak lulus psikotes dengan nilai {$persentase}% (batas minimal {$batasKelulusan}%)",
                'rekomendasi' => $rekomendasi,
                'waktu_selesai' => now(),
                'durasi_pengerjaan_detik' => $sesi->durasi_terpakai_detik,
                'lulus' => $lulus,
                'detail_analisis' => [
                    'total_skor' => $totalSkor,
                    'max_skor' => $maxSkor,
                    'jumlah_benar' => $soalKognitifBenar + $soalKepribadianBenar,
                    'jumlah_salah' => $totalSoal - ($soalKognitifBenar + $soalKepribadianBenar),
                    'batas_kelulusan' => $batasKelulusan
                ],
                'konfigurasi_penilaian' => [
                    'batas_lulus' => $batasKelulusan,
                    'bobot_per_soal' => 5
                ]
            ]);

            $sesi->update([
                'selesai' => true,
                'status' => 'selesai',
                'waktu_terakhir_aktivitas' => now()
            ]);

            Log::info('Tes selesai', [
                'sesi_id' => $sesi->id,
                'total_skor' => $totalSkor,
                'persentase' => $persentase,
                'batas_kelulusan' => $batasKelulusan,
                'lulus' => $lulus
            ]);
        });
    }

    private function autoSubmitTes($sesi)
    {
        DB::transaction(function () use ($sesi) {
            $jawaban = JawabanPsikotes::where('sesi_psikotes_id', $sesi->id)->get();
            $totalSkor = 0;
            $totalSoal = count($sesi->soal_terpilih);
            $soalTerjawab = $jawaban->count();
            $soalKognitifBenar = 0;
            $soalKognitifTotal = 0;
            $soalKepribadianBenar = 0;
            $soalKepribadianTotal = 0;

            $konfigurasi = KonfigurasiPsikotes::find($sesi->konfigurasi_id);
            $batasKelulusan = $konfigurasi ? $konfigurasi->batas_nilai_kelulusan : 60;

            foreach ($jawaban as $j) {
                $soal = SoalPsikotes::find($j->soal_id);
                if ($soal) {
                    if ($soal->jenis == 'kognitif') {
                        $soalKognitifTotal++;
                        if ($soal->kunci_jawaban == $j->jawaban) {
                            $totalSkor += $soal->bobot_nilai;
                            $soalKognitifBenar++;
                            $j->update(['benar' => true]);
                        } else {
                            $j->update(['benar' => false]);
                        }
                    } elseif ($soal->jenis == 'kepribadian') {
                        $soalKepribadianTotal++;
                        if ($soal->kunci_jawaban == $j->jawaban) {
                            $totalSkor += $soal->bobot_nilai;
                            $soalKepribadianBenar++;
                            $j->update(['benar' => true]);
                        } else {
                            $j->update(['benar' => false]);
                        }
                    } else {
                        if ($soal->kunci_jawaban == $j->jawaban) {
                            $totalSkor += $soal->bobot_nilai;
                            $j->update(['benar' => true]);
                        } else {
                            $j->update(['benar' => false]);
                        }
                    }
                }
            }

            $maxSkor = $totalSoal * 5;
            $persentase = $maxSkor > 0 ? round(($totalSkor / $maxSkor) * 100, 2) : 0;
            $lulus = $persentase >= $batasKelulusan;

            $nilaiKognitif = $soalKognitifTotal > 0 ? round(($soalKognitifBenar / $soalKognitifTotal) * 100, 2) : 0;
            $nilaiKepribadian = $soalKepribadianTotal > 0 ? round(($soalKepribadianBenar / $soalKepribadianTotal) * 100, 2) : 0;

            if ($persentase >= 85) {
                $rekomendasi = 'sangat_baik';
            } elseif ($persentase >= 70) {
                $rekomendasi = 'baik';
            } elseif ($persentase >= 50) {
                $rekomendasi = 'cukup';
            } elseif ($persentase >= 30) {
                $rekomendasi = 'kurang';
            } else {
                $rekomendasi = 'tidak_lulus';
            }

            HasilPsikotes::create([
                'pelamar_id' => $sesi->pelamar_id,
                'lowongan_id' => $sesi->lowongan_id,
                'pelamar_tahapan_id' => $sesi->pelamar_tahapan_id,
                'sesi_psikotes_id' => $sesi->id,
                'konfigurasi_id' => $sesi->konfigurasi_id,
                'total_soal_ditampilkan' => $totalSoal,
                'soal_dijawab' => $soalTerjawab,
                'soal_kognitif_ditampilkan' => $soalKognitifTotal,
                'soal_kepribadian_ditampilkan' => $soalKepribadianTotal,
                'soal_kognitif_benar' => $soalKognitifBenar,
                'soal_kognitif_salah' => $soalKognitifTotal - $soalKognitifBenar,
                'nilai_kognitif' => $nilaiKognitif,
                'nilai_kepribadian' => $nilaiKepribadian,
                'nilai_akhir' => $persentase,
                'keterangan' => $lulus ? "Lulus psikotes dengan nilai {$persentase}% (batas minimal {$batasKelulusan}%)" : "Tidak lulus psikotes dengan nilai {$persentase}% (batas minimal {$batasKelulusan}%)",
                'rekomendasi' => $rekomendasi,
                'waktu_selesai' => now(),
                'durasi_pengerjaan_detik' => $sesi->durasi_terpakai_detik,
                'lulus' => $lulus,
                'detail_analisis' => [
                    'total_skor' => $totalSkor,
                    'max_skor' => $maxSkor,
                    'jumlah_benar' => $soalKognitifBenar + $soalKepribadianBenar,
                    'jumlah_salah' => $totalSoal - ($soalKognitifBenar + $soalKepribadianBenar),
                    'batas_kelulusan' => $batasKelulusan
                ],
                'konfigurasi_penilaian' => [
                    'batas_lulus' => $batasKelulusan,
                    'bobot_per_soal' => 5
                ]
            ]);

            $sesi->update([
                'selesai' => true,
                'status' => 'selesai',
                'waktu_terakhir_aktivitas' => now()
            ]);

            Log::info('Tes auto submit', [
                'sesi_id' => $sesi->id,
                'total_skor' => $totalSkor,
                'persentase' => $persentase,
                'batas_kelulusan' => $batasKelulusan,
                'lulus' => $lulus
            ]);
        });
    }

    public function getInfoLowongan($lowonganId)
    {
        $jumlahPelamar = PelamarTahapan::where('lowongan_id', $lowonganId)
            ->where('status', 'lolos')
            ->count();

        $jumlahSesi = SesiPsikotes::where('lowongan_id', $lowonganId)->count();

        $konfigurasi = KonfigurasiPsikotes::where('lowongan_id', $lowonganId)->first();
        $durasiMenit = $konfigurasi ? $konfigurasi->durasi_menit : 0;

        return response()->json([
            'jumlah_pelamar' => $jumlahPelamar,
            'jumlah_sesi' => $jumlahSesi,
            'durasi_menit' => $durasiMenit
        ]);
    }

    public function debug(Request $request)
    {
        $token = $request->input('token');
        $pelamarId = $request->input('pelamar_id');
        Log::info('Debug request for token: ' . $token . ' and pelamar_id: ' . $pelamarId);
        $sesi = SesiPsikotes::where('token_sesi', $token)
            ->where('pelamar_id', $pelamarId)
            ->first();
        if ($sesi) {
            Log::info('Sesi found with ID: ' . $sesi->id);
            Log::info('Sesi status: ' . $sesi->status);
            Log::info('Sesi selesai: ' . $sesi->selesai);
            Log::info('Waktu mulai: ' . $sesi->waktu_mulai);
            Log::info('Waktu selesai: ' . $sesi->waktu_selesai);
            Log::info('Jawaban count: ' . $sesi->jawabanPsikotes()->count());
        } else {
            Log::warning('Sesi not found for token: ' . $token . ' and pelamar_id: ' . $pelamarId);
        }
        return response()->json([
            'message' => 'Debug info logged successfully',
            'sesi_found' => $sesi ? true : false,
            'sesi_data' => $sesi ? $sesi->toArray() : null
        ]);
    }
}
