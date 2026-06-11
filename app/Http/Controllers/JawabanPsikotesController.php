<?php

namespace App\Http\Controllers;

use App\Models\JawabanPsikotes;
use App\Models\SoalPsikotes;
use App\Models\SesiPsikotes;
use App\Models\HasilPsikotes;
use App\Models\PelamarTahapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JawabanPsikotesController extends Controller
{
    public function index()
    {
        $jawabans = JawabanPsikotes::with(['pelamar', 'soalPsikotes', 'sesiPsikotes'])->latest()->paginate(10);
        return view('admin.jawabanpsikotes.index', compact('jawabans'));
    }

    public function create()
    {
        return redirect()->route('admin.jawabanpsikotes.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'soal_psikotes_id' => 'required|exists:soal_psikotes,id',
            'jawaban' => 'required|string'
        ]);

        $soal = SoalPsikotes::findOrFail($request->soal_psikotes_id);

        $sesi = SesiPsikotes::where('pelamar_id', auth('pelamar')->id())
            ->where('selesai', false)
            ->latest()
            ->first();

        $benar = null;
        $skor = null;
        $skorKepribadian = null;
        $skorMinatBakat = null;

        if ($soal->jenis === 'kognitif') {
            $benar = strtoupper($request->jawaban) === strtoupper($soal->kunci_jawaban) ? 1 : 0;
            $skor = $benar === 1 ? ($soal->bobot_nilai ?? 1) : 0;
        }

        if ($soal->jenis === 'kepribadian') {
            $bobotOpsi = $soal->bobot_opsi;
            $skorKepribadian = $bobotOpsi[$request->jawaban] ?? 0;
        }

        if ($soal->jenis === 'minat') {
            $bobotOpsi = $soal->bobot_opsi;
            $skorMinatBakat = $bobotOpsi[$request->jawaban] ?? 0;
        }

        JawabanPsikotes::updateOrCreate(
            [
                'pelamar_id' => auth('pelamar')->id(),
                'soal_id' => $soal->id,
                'sesi_psikotes_id' => $sesi ? $sesi->id : null,
            ],
            [
                'jawaban' => $request->jawaban,
                'benar' => $benar,
                'skor' => $skor,
                'skor_kepribadian' => $skorKepribadian,
                'skor_minat_bakat' => $skorMinatBakat,
                'waktu_jawab' => now(),
                'waktu_menjawab_detik' => $request->waktu_menjawab_detik ?? null,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Jawaban berhasil disimpan']);
    }

    public function show($id)
    {
        $jawaban = JawabanPsikotes::with(['pelamar', 'soalPsikotes', 'sesiPsikotes'])->findOrFail($id);
        return view('admin.jawabanpsikotes.show', compact('jawaban'));
    }

    public function edit($id)
    {
        return redirect()->route('admin.jawabanpsikotes.show', $id);
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.jawabanpsikotes.show', $id);
    }

    public function destroy($id)
    {
        $jawaban = JawabanPsikotes::findOrFail($id);
        $jawaban->delete();
        return redirect()->route('admin.jawabanpsikotes.index')
            ->with('success', 'Jawaban psikotes berhasil dihapus.');
    }

    public function submitAll(Request $request)
    {
        $request->validate([
            'sesi_token' => 'required|exists:sesi_psikotes,token_sesi'
        ]);

        $user = Auth::user();
        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return response()->json(['error' => 'Data pelamar tidak ditemukan.'], 404);
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
                'selesai' => true,
                'status' => 'selesai',
                'waktu_selesai' => now(),
                'waktu_terakhir_aktivitas' => now()
            ]);

            $hasilController = new HasilPsikotesController();
            $hasil = $hasilController->hitungHasilPsikotes($sesi);

            if (!$hasil || $hasil->nilai_kognitif === null || $hasil->nilai_kepribadian === null || $hasil->nilai_minat === null) {
                $hasilController->recalculateAllCategories($sesi);
            }

            if ($sesi->pelamarTahapan) {
                $sesi->pelamarTahapan->update([
                    'status' => 'selesai',
                    'catatan' => 'Telah menyelesaikan tes psikotes'
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'redirect' => route('pelamar.psikotes.hasil', $sesi->token_sesi)
        ]);
    }

    public function pauseTes(Request $request)
    {
        $request->validate([
            'sesi_token' => 'required|exists:sesi_psikotes,token_sesi',
            'current_soal_index' => 'required|integer|min:0'
        ]);

        $user = Auth::user();
        $pelamar = $user->pelamar;

        if (!$pelamar) {
            return response()->json(['error' => 'Data pelamar tidak ditemukan.'], 404);
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

        $sesi->update([
            'current_soal_index' => $request->current_soal_index,
            'waktu_terakhir_aktivitas' => now(),
            'status' => 'dijeda'
        ]);

        return response()->json(['success' => true, 'message' => 'Tes di-pause. Anda dapat melanjutkan nanti.']);
    }
}
