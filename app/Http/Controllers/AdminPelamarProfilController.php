<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
use App\Models\RiwayatPendidikan;
use App\Models\RiwayatOrganisasi;
use App\Models\RiwayatMagang;
use App\Models\RiwayatPengalaman;
use App\Models\RiwayatBahasa;
use App\Models\RiwayatKeahlian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminPelamarProfilController extends Controller
{
    public function show($id)
    {
        $pelamar = Pelamar::with('user')->findOrFail($id);

        $pendidikan = RiwayatPendidikan::where('pelamar_id', $pelamar->id)->orderBy('tahun_lulus', 'desc')->get();
        $organisasi = RiwayatOrganisasi::where('pelamar_id', $pelamar->id)->orderBy('tahun', 'desc')->get();
        $magang = RiwayatMagang::where('pelamar_id', $pelamar->id)->orderBy('id', 'desc')->get();
        $pengalaman = RiwayatPengalaman::where('pelamar_id', $pelamar->id)->orderBy('tahun_masuk', 'desc')->get();
        $bahasa = RiwayatBahasa::where('pelamar_id', $pelamar->id)->get();
        $keahlian = RiwayatKeahlian::where('pelamar_id', $pelamar->id)->get();

        $pendidikanOptions = Pelamar::pendidikanOptions();
        $jenisKelaminOptions = Pelamar::jenisKelaminOptions();
        $kewarganegaraanOptions = Pelamar::kewarganegaraanOptions();

        return view('admin.pelamar.profil', compact(
            'pelamar',
            'pendidikan',
            'organisasi',
            'magang',
            'pengalaman',
            'bahasa',
            'keahlian',
            'pendidikanOptions',
            'jenisKelaminOptions',
            'kewarganegaraanOptions'
        ));
    }
}
