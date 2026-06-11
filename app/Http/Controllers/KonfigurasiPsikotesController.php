<?php

namespace App\Http\Controllers;

use App\Models\KonfigurasiPsikotes;
use App\Models\Lowongan;
use App\Models\SesiPsikotes;
use App\Models\KategoriPsikotes;
use Illuminate\Http\Request;

class KonfigurasiPsikotesController extends Controller
{
    public function index()
    {
        $konfigurasis = KonfigurasiPsikotes::with('lowongan')->latest()->paginate(10);
        return view('admin.konfigurasipsikotes.index', compact('konfigurasis'));
    }

    public function create()
    {
        $lowongans = Lowongan::all();
        $kategoriKognitif = KategoriPsikotes::where('jenis', 'kognitif')->get();
        $kategoriKepribadian = KategoriPsikotes::where('jenis', 'kepribadian')->get();
        $kategoriMinat = KategoriPsikotes::where('jenis', 'minat')->get();
        return view('admin.konfigurasipsikotes.create', compact('lowongans', 'kategoriKognitif', 'kategoriKepribadian', 'kategoriMinat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongan,id',
            'jumlah_soal_kognitif' => 'required|integer|min:0',
            'jumlah_soal_kepribadian' => 'required|integer|min:0',
            'jumlah_soal_minat' => 'required|integer|min:0',
            'durasi_menit' => 'required|integer|min:1',
            'batas_nilai_kelulusan' => 'required|integer|min:0|max:100',
            'kategori_kognitif' => 'array|nullable',
            'kategori_kepribadian' => 'array|nullable',
            'kategori_minat' => 'array|nullable',
        ]);

        $totalSoal = $request->jumlah_soal_kognitif + $request->jumlah_soal_kepribadian + $request->jumlah_soal_minat;

        KonfigurasiPsikotes::create([
            'lowongan_id' => $request->lowongan_id,
            'jumlah_soal_total' => $totalSoal,
            'jumlah_soal_kognitif' => $request->jumlah_soal_kognitif,
            'jumlah_soal_kepribadian' => $request->jumlah_soal_kepribadian,
            'jumlah_soal_minat' => $request->jumlah_soal_minat,
            'durasi_menit' => $request->durasi_menit,
            'acak_soal' => true,
            'acak_opsi' => true,
            'batas_nilai_kelulusan' => $request->batas_nilai_kelulusan,
            'kategori_kognitif' => $request->kategori_kognitif,
            'kategori_kepribadian' => $request->kategori_kepribadian,
            'kategori_minat' => $request->kategori_minat,
            'aktif' => true
        ]);

        return redirect()->route('admin.konfigurasipsikotes.index')
            ->with('success', 'Konfigurasi psikotes berhasil dibuat.');
    }

    public function show($id)
    {
        $konfigurasi = KonfigurasiPsikotes::with(['lowongan', 'sesiPsikotes'])->findOrFail($id);
        return view('admin.konfigurasipsikotes.show', compact('konfigurasi'));
    }

    public function edit($id)
    {
        $konfigurasi = KonfigurasiPsikotes::findOrFail($id);
        $lowongans = Lowongan::all();
        $kategoriKognitif = KategoriPsikotes::where('jenis', 'kognitif')->get();
        $kategoriKepribadian = KategoriPsikotes::where('jenis', 'kepribadian')->get();
        $kategoriMinat = KategoriPsikotes::where('jenis', 'minat')->get();
        return view('admin.konfigurasipsikotes.edit', compact('konfigurasi', 'lowongans', 'kategoriKognitif', 'kategoriKepribadian', 'kategoriMinat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lowongan_id' => 'required|exists:lowongan,id',
            'jumlah_soal_kognitif' => 'required|integer|min:0',
            'jumlah_soal_kepribadian' => 'required|integer|min:0',
            'jumlah_soal_minat' => 'required|integer|min:0',
            'durasi_menit' => 'required|integer|min:1',
            'batas_nilai_kelulusan' => 'required|integer|min:0|max:100',
            'kategori_kognitif' => 'array|nullable',
            'kategori_kepribadian' => 'array|nullable',
            'kategori_minat' => 'array|nullable',
        ]);

        $konfigurasi = KonfigurasiPsikotes::findOrFail($id);
        $totalSoal = $request->jumlah_soal_kognitif + $request->jumlah_soal_kepribadian + $request->jumlah_soal_minat;

        $konfigurasi->update([
            'lowongan_id' => $request->lowongan_id,
            'jumlah_soal_total' => $totalSoal,
            'jumlah_soal_kognitif' => $request->jumlah_soal_kognitif,
            'jumlah_soal_kepribadian' => $request->jumlah_soal_kepribadian,
            'jumlah_soal_minat' => $request->jumlah_soal_minat,
            'durasi_menit' => $request->durasi_menit,
            'batas_nilai_kelulusan' => $request->batas_nilai_kelulusan,
            'kategori_kognitif' => $request->kategori_kognitif,
            'kategori_kepribadian' => $request->kategori_kepribadian,
            'kategori_minat' => $request->kategori_minat,
        ]);

        $this->updateAllSesiPsikotesByKonfigurasi($konfigurasi);

        return redirect()->route('admin.konfigurasipsikotes.index')
            ->with('success', 'Konfigurasi psikotes berhasil diperbarui.');
    }

    private function updateAllSesiPsikotesByKonfigurasi(KonfigurasiPsikotes $konfigurasi)
    {
        $sesiList = SesiPsikotes::where('konfigurasi_id', $konfigurasi->id)->get();

        foreach ($sesiList as $sesi) {
            $soalIds = collect();

            if ($konfigurasi->jumlah_soal_kognitif > 0) {
                $soalKognitif = \App\Models\SoalPsikotes::where('jenis', 'kognitif')
                    ->inRandomOrder()
                    ->limit($konfigurasi->jumlah_soal_kognitif)
                    ->pluck('id');
                $soalIds = $soalIds->concat($soalKognitif);
            }

            if ($konfigurasi->jumlah_soal_kepribadian > 0) {
                $soalKepribadian = \App\Models\SoalPsikotes::where('jenis', 'kepribadian')
                    ->inRandomOrder()
                    ->limit($konfigurasi->jumlah_soal_kepribadian)
                    ->pluck('id');
                $soalIds = $soalIds->concat($soalKepribadian);
            }

            if ($konfigurasi->jumlah_soal_minat > 0) {
                $soalMinat = \App\Models\SoalPsikotes::where('jenis', 'minat')
                    ->inRandomOrder()
                    ->limit($konfigurasi->jumlah_soal_minat)
                    ->pluck('id');
                $soalIds = $soalIds->concat($soalMinat);
            }

            $totalSoal = $soalIds->count();

            $sesi->update([
                'soal_terpilih' => $soalIds->toArray(),
                'jumlah_soal_ditampilkan' => $totalSoal,
                'durasi_menit' => $konfigurasi->durasi_menit,
            ]);
        }
    }

    public function destroy($id)
    {
        $konfigurasi = KonfigurasiPsikotes::findOrFail($id);
        $konfigurasi->delete();

        return redirect()->route('admin.konfigurasipsikotes.index')
            ->with('success', 'Konfigurasi psikotes berhasil dihapus.');
    }

    public function getDurasi($lowonganId)
    {
        $konfigurasi = KonfigurasiPsikotes::where('lowongan_id', $lowonganId)->first();

        if (!$konfigurasi) {
            return response()->json(['error' => 'Konfigurasi tidak ditemukan'], 404);
        }

        return response()->json([
            'durasi_menit' => $konfigurasi->durasi_menit,
            'lowongan' => $konfigurasi->lowongan->judul_lowongan
        ]);
    }
}
