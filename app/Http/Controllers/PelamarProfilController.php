<?php

namespace App\Http\Controllers;

use App\Models\Pelamar;
use App\Models\RiwayatPendidikan;
use App\Models\RiwayatOrganisasi;
use App\Models\RiwayatMagang;
use App\Models\RiwayatPengalaman;
use App\Models\RiwayatBahasa;
use App\Models\RiwayatKeahlian;
use App\Models\PenilaianKandidat;
use App\Models\Kriteria;
use App\Models\PelamarTahapan;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PelamarProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        if (!$pelamar) {
            $pelamar = Pelamar::create([
                'user_id' => $user->id,
                'nama_lengkap' => $user->name,
                'email' => $user->email,
            ]);
        }

        $pendidikan = RiwayatPendidikan::where('pelamar_id', $pelamar->id)->orderBy('tahun_lulus', 'desc')->get();
        $organisasi = RiwayatOrganisasi::where('pelamar_id', $pelamar->id)->orderBy('tahun', 'desc')->get();
        $magang = RiwayatMagang::where('pelamar_id', $pelamar->id)->orderBy('id', 'desc')->get();
        $pengalaman = RiwayatPengalaman::where('pelamar_id', $pelamar->id)->orderBy('tahun_masuk', 'desc')->get();
        $bahasa = RiwayatBahasa::where('pelamar_id', $pelamar->id)->get();
        $keahlian = RiwayatKeahlian::where('pelamar_id', $pelamar->id)->get();

        $pendidikanOptions = Pelamar::pendidikanOptions();
        $jenisKelaminOptions = Pelamar::jenisKelaminOptions();
        $kewarganegaraanOptions = Pelamar::kewarganegaraanOptions();

        $progres = $this->hitungProgres($pelamar, $pendidikan, $organisasi, $magang, $pengalaman, $bahasa, $keahlian);
        $nilaiAdministrasi = $this->hitungNilaiAdministrasi($pelamar, $pendidikan, $pengalaman, $magang, $organisasi, $bahasa, $keahlian);

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->with('lowongan')
            ->get();

        return view('pelamar.profil.index', compact(
            'pelamar',
            'pendidikan',
            'organisasi',
            'magang',
            'pengalaman',
            'bahasa',
            'keahlian',
            'pendidikanOptions',
            'jenisKelaminOptions',
            'kewarganegaraanOptions',
            'progres',
            'nilaiAdministrasi',
            'lowonganTerdaftar'
        ));
    }

    private function hitungProgres($pelamar, $pendidikan, $organisasi, $magang, $pengalaman, $bahasa, $keahlian)
    {
        $total = 9;
        $terisi = 0;

        if ($pelamar->nama_depan && $pelamar->nama_belakang) $terisi++;
        if ($pelamar->tempat_lahir && $pelamar->tanggal_lahir) $terisi++;
        if ($pelamar->nomor_handphone && $pelamar->nomor_identitas) $terisi++;
        if ($pelamar->provinsi && $pelamar->alamat_domisili) $terisi++;
        if ($pendidikan->count() > 0) $terisi++;
        if ($organisasi->count() > 0) $terisi++;
        if ($magang->count() > 0) $terisi++;
        if ($pengalaman->count() > 0) $terisi++;
        if ($bahasa->count() > 0) $terisi++;
        if ($keahlian->count() > 0) $terisi++;

        return round(($terisi / $total) * 100);
    }

    private function hitungNilaiAdministrasi($pelamar, $pendidikan, $pengalaman, $magang, $organisasi, $bahasa, $keahlian)
    {
        $nilaiPendidikan = $this->nilaiPendidikan($pendidikan);
        $nilaiPengalaman = $this->nilaiPengalaman($pengalaman);
        $nilaiMagang = $this->nilaiMagang($magang);
        $nilaiOrganisasi = $this->nilaiOrganisasi($organisasi);
        $nilaiBahasa = $this->nilaiBahasa($bahasa);
        $nilaiKeahlian = $this->nilaiKeahlian($keahlian);

        $totalNilai = ($nilaiPendidikan * 0.30) + ($nilaiPengalaman * 0.25) + ($nilaiMagang * 0.10) + ($nilaiOrganisasi * 0.15) + ($nilaiBahasa * 0.10) + ($nilaiKeahlian * 0.10);

        return round($totalNilai, 2);
    }

    private function nilaiPendidikan($pendidikan)
    {
        $tingkatNilai = [
            'SD' => 40,
            'SMP' => 50,
            'SMA/SMK' => 60,
            'D3' => 75,
            'D4' => 80,
            'S1' => 85,
            'S2' => 100,
        ];

        if ($pendidikan->isEmpty()) {
            return 0;
        }

        $tertinggi = $pendidikan->sortByDesc(function ($item) use ($tingkatNilai) {
            return $tingkatNilai[$item->tingkat] ?? 0;
        })->first();

        return $tingkatNilai[$tertinggi->tingkat] ?? 60;
    }

    private function nilaiPengalaman($pengalaman)
    {
        if ($pengalaman->isEmpty()) {
            return 40;
        }

        $totalBulan = 0;
        foreach ($pengalaman as $exp) {
            $tahunMasuk = (int)$exp->tahun_masuk;
            $tahunKeluar = $exp->is_current ? date('Y') : (int)$exp->tahun_keluar;
            if ($tahunKeluar && $tahunMasuk) {
                $totalBulan += ($tahunKeluar - $tahunMasuk) * 12;
            }
        }

        if ($totalBulan >= 60) return 100;
        if ($totalBulan >= 36) return 90;
        if ($totalBulan >= 24) return 80;
        if ($totalBulan >= 12) return 70;
        if ($totalBulan > 0) return 60;
        return 40;
    }

    private function nilaiMagang($magang)
    {
        $jumlah = $magang->count();
        if ($jumlah > 1) return 100;
        if ($jumlah == 1) return 70;
        return 30;
    }

    private function nilaiOrganisasi($organisasi)
    {
        if ($organisasi->isEmpty()) {
            return 30;
        }

        $posisiNilai = [
            'Ketua' => 100,
            'Wakil Ketua' => 95,
            'Sekretaris' => 90,
            'Bendahara' => 90,
            'Koordinator' => 85,
            'Pengurus' => 85,
            'Anggota' => 70,
        ];

        $nilaiTertinggi = 0;
        foreach ($organisasi as $org) {
            foreach ($posisiNilai as $key => $nilai) {
                if (stripos($org->jabatan, $key) !== false) {
                    $nilaiTertinggi = max($nilaiTertinggi, $nilai);
                    break;
                }
            }
        }

        return $nilaiTertinggi > 0 ? $nilaiTertinggi : 70;
    }

    private function nilaiBahasa($bahasa)
    {
        $jumlah = $bahasa->count();
        if ($jumlah > 2) return 100;
        if ($jumlah == 2) return 75;
        if ($jumlah == 1) return 50;
        return 30;
    }

    private function nilaiKeahlian($keahlian)
    {
        $jumlah = $keahlian->count();
        if ($jumlah > 5) return 100;
        if ($jumlah >= 3) return 80;
        if ($jumlah >= 1) return 60;
        return 40;
    }

    public function updateProfil(Request $request)
    {
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $rules = [
            'nama_depan' => 'nullable|string|max:255',
            'nama_belakang' => 'nullable|string|max:255',
            'jenis_kelamin' => 'nullable|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'kewarganegaraan' => 'nullable|string|max:100',
            'nomor_handphone' => 'nullable|string|max:15',
            'nomor_whatsapp' => 'nullable|string|max:15',
            'nomor_identitas' => 'nullable|string|max:20|unique:pelamar,nomor_identitas,' . ($pelamar->id ?? 'NULL'),
            'provinsi' => 'nullable|string|max:100',
            'kab_kota' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'desa_kelurahan' => 'nullable|string|max:100',
            'alamat_domisili' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string',
            'jurusan' => 'nullable|string|max:255',
            'gaji_diharapkan' => 'nullable|string|max:255',
        ];

        $request->validate($rules);
        $pelamar->update($request->all());

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function simpanNilaiAdministrasi($pelamarId, $lowonganId)
    {
        $pelamar = Pelamar::find($pelamarId);
        if (!$pelamar) return;

        $pendidikan = RiwayatPendidikan::where('pelamar_id', $pelamarId)->get();
        $pengalaman = RiwayatPengalaman::where('pelamar_id', $pelamarId)->get();
        $magang = RiwayatMagang::where('pelamar_id', $pelamarId)->get();
        $organisasi = RiwayatOrganisasi::where('pelamar_id', $pelamarId)->get();
        $bahasa = RiwayatBahasa::where('pelamar_id', $pelamarId)->get();
        $keahlian = RiwayatKeahlian::where('pelamar_id', $pelamarId)->get();

        $nilai = $this->hitungNilaiAdministrasi($pelamar, $pendidikan, $pengalaman, $magang, $organisasi, $bahasa, $keahlian);

        $kriteriaAdministrasi = Kriteria::where('nama_kriteria', 'like', '%administrasi%')
            ->orWhere('nama_kriteria', 'like', '%berkas%')
            ->orWhere('kode_kriteria', 'C1')
            ->first();

        if ($kriteriaAdministrasi) {
            PenilaianKandidat::updateOrCreate(
                [
                    'pelamar_id' => $pelamarId,
                    'lowongan_id' => $lowonganId,
                    'kriteria_id' => $kriteriaAdministrasi->id,
                ],
                [
                    'nilai' => $nilai,
                    'catatan' => 'Nilai otomatis dari profil pelamar',
                ]
            );
        }
    }

    public function storePendidikan(Request $request)
    {
        $request->validate([
            'tingkat' => 'required|string',
            'nama_instansi' => 'required|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'tahun_masuk' => 'nullable|string|max:4',
            'tahun_lulus' => 'required|string|max:4',
            'nilai' => 'nullable|string|max:10',
        ]);

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        RiwayatPendidikan::create([
            'pelamar_id' => $pelamar->id,
            'tingkat' => $request->tingkat,
            'nama_instansi' => $request->nama_instansi,
            'jurusan' => $request->jurusan,
            'tahun_masuk' => $request->tahun_masuk,
            'tahun_lulus' => $request->tahun_lulus,
            'nilai' => $request->nilai,
        ]);

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data pendidikan berhasil ditambahkan.');
    }

    public function updatePendidikan(Request $request, $id)
    {
        $request->validate([
            'tingkat' => 'required|string',
            'nama_instansi' => 'required|string|max:255',
            'tahun_lulus' => 'required|string|max:4',
        ]);

        $pendidikan = RiwayatPendidikan::findOrFail($id);
        $pendidikan->update($request->all());

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data pendidikan berhasil diperbarui.');
    }

    public function destroyPendidikan($id)
    {
        RiwayatPendidikan::findOrFail($id)->delete();
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data pendidikan berhasil dihapus.');
    }

    public function storeOrganisasi(Request $request)
    {
        $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'tahun' => 'required|string|max:4',
            'deskripsi' => 'nullable|string',
        ]);

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        RiwayatOrganisasi::create([
            'pelamar_id' => $pelamar->id,
            'nama_organisasi' => $request->nama_organisasi,
            'jabatan' => $request->jabatan,
            'tahun' => $request->tahun,
            'deskripsi' => $request->deskripsi,
        ]);

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data organisasi berhasil ditambahkan.');
    }

    public function updateOrganisasi(Request $request, $id)
    {
        $request->validate([
            'nama_organisasi' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'tahun' => 'required|string|max:4',
        ]);

        $organisasi = RiwayatOrganisasi::findOrFail($id);
        $organisasi->update($request->all());

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data organisasi berhasil diperbarui.');
    }

    public function destroyOrganisasi($id)
    {
        RiwayatOrganisasi::findOrFail($id)->delete();
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data organisasi berhasil dihapus.');
    }

    public function storeMagang(Request $request)
    {
        $request->validate([
            'perusahaan' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'durasi' => 'required|string',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date',
            'deskripsi' => 'nullable|string',
        ]);

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        RiwayatMagang::create([
            'pelamar_id' => $pelamar->id,
            'perusahaan' => $request->perusahaan,
            'posisi' => $request->posisi,
            'lokasi' => $request->lokasi,
            'durasi' => $request->durasi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'deskripsi' => $request->deskripsi,
        ]);

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data magang berhasil ditambahkan.');
    }

    public function updateMagang(Request $request, $id)
    {
        $request->validate([
            'perusahaan' => 'required|string|max:255',
            'posisi' => 'required|string|max:255',
            'durasi' => 'required|string',
        ]);

        $magang = RiwayatMagang::findOrFail($id);
        $magang->update($request->all());

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data magang berhasil diperbarui.');
    }

    public function destroyMagang($id)
    {
        RiwayatMagang::findOrFail($id)->delete();
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data magang berhasil dihapus.');
    }

    public function storePengalaman(Request $request)
    {
        $request->validate([
            'perusahaan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'lokasi' => 'nullable|string|max:255',
            'tahun_masuk' => 'required|string|max:4',
            'tahun_keluar' => 'nullable|string|max:4',
            'is_current' => 'boolean',
            'deskripsi' => 'nullable|string',
        ]);

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        RiwayatPengalaman::create([
            'pelamar_id' => $pelamar->id,
            'perusahaan' => $request->perusahaan,
            'jabatan' => $request->jabatan,
            'lokasi' => $request->lokasi,
            'tahun_masuk' => $request->tahun_masuk,
            'tahun_keluar' => $request->tahun_keluar,
            'is_current' => $request->is_current ?? false,
            'deskripsi' => $request->deskripsi,
        ]);

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data pengalaman kerja berhasil ditambahkan.');
    }

    public function updatePengalaman(Request $request, $id)
    {
        $request->validate([
            'perusahaan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'tahun_masuk' => 'required|string|max:4',
        ]);

        $pengalaman = RiwayatPengalaman::findOrFail($id);
        $pengalaman->update($request->all());

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data pengalaman kerja berhasil diperbarui.');
    }

    public function destroyPengalaman($id)
    {
        RiwayatPengalaman::findOrFail($id)->delete();
        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data pengalaman kerja berhasil dihapus.');
    }

    public function storeBahasa(Request $request)
    {
        $request->validate([
            'nama_bahasa' => 'required|string|max:100',
            'tingkat' => 'required|in:Pasif,Aktif,Lancar,Native',
            'sertifikat_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $sertifikatPath = null;
        if ($request->hasFile('sertifikat_file')) {
            $file = $request->file('sertifikat_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $sertifikatPath = $file->storeAs('sertifikat/bahasa', $filename, 'public');
        }

        RiwayatBahasa::create([
            'pelamar_id' => $pelamar->id,
            'nama_bahasa' => $request->nama_bahasa,
            'tingkat' => $request->tingkat,
            'sertifikat' => $sertifikatPath,
        ]);

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data bahasa berhasil ditambahkan.');
    }

    public function updateBahasa(Request $request, $id)
    {
        $request->validate([
            'nama_bahasa' => 'required|string|max:100',
            'tingkat' => 'required|in:Pasif,Aktif,Lancar,Native',
        ]);

        $bahasa = RiwayatBahasa::findOrFail($id);
        $bahasa->update($request->all());

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data bahasa berhasil diperbarui.');
    }

    public function destroyBahasa($id)
    {
        $bahasa = RiwayatBahasa::findOrFail($id);
        if ($bahasa->sertifikat && Storage::disk('public')->exists($bahasa->sertifikat)) {
            Storage::disk('public')->delete($bahasa->sertifikat);
        }
        $bahasa->delete();

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data bahasa berhasil dihapus.');
    }

    public function storeKeahlian(Request $request)
    {
        $request->validate([
            'nama_keahlian' => 'required|string|max:100',
            'tingkat' => 'nullable|string|max:50',
            'sertifikat_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $sertifikatPath = null;
        if ($request->hasFile('sertifikat_file')) {
            $file = $request->file('sertifikat_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $sertifikatPath = $file->storeAs('sertifikat/keahlian', $filename, 'public');
        }

        RiwayatKeahlian::create([
            'pelamar_id' => $pelamar->id,
            'nama_keahlian' => $request->nama_keahlian,
            'tingkat' => $request->tingkat,
            'sertifikat' => $sertifikatPath,
        ]);

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data keahlian berhasil ditambahkan.');
    }

    public function updateKeahlian(Request $request, $id)
    {
        $request->validate([
            'nama_keahlian' => 'required|string|max:100',
        ]);

        $keahlian = RiwayatKeahlian::findOrFail($id);
        $keahlian->update($request->all());

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data keahlian berhasil diperbarui.');
    }

    public function destroyKeahlian($id)
    {
        $keahlian = RiwayatKeahlian::findOrFail($id);
        if ($keahlian->sertifikat && Storage::disk('public')->exists($keahlian->sertifikat)) {
            Storage::disk('public')->delete($keahlian->sertifikat);
        }
        $keahlian->delete();

        $pelamar = Pelamar::where('user_id', Auth::id())->first();

        $lowonganTerdaftar = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->pluck('lowongan_id')
            ->unique();

        foreach ($lowonganTerdaftar as $lowonganId) {
            $this->simpanNilaiAdministrasi($pelamar->id, $lowonganId);
        }

        return redirect()->route('pelamar.profil.index')->with('success', 'Data keahlian berhasil dihapus.');
    }
}
