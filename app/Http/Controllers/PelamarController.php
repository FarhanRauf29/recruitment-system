<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelamar;
use App\Models\OfferingPenawaran;
use App\Models\PelamarTahapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PelamarImport;
use App\Exports\PelamarExport;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PelamarController extends Controller
{
    public function index()
    {
        $query = Pelamar::with('user');

        if (request('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_depan', 'like', "%{$search}%")
                    ->orWhere('nama_belakang', 'like', "%{$search}%")
                    ->orWhere('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('nomor_identitas', 'like', "%{$search}%");
            });
        }

        $pelamars = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pelamar.index', compact('pelamars'));
    }

    public function create()
    {
        $pendidikanOptions = Pelamar::pendidikanOptions();
        $jenisKelaminOptions = Pelamar::jenisKelaminOptions();
        $kewarganegaraanOptions = Pelamar::kewarganegaraanOptions();

        return view('admin.pelamar.create', compact('pendidikanOptions', 'jenisKelaminOptions', 'kewarganegaraanOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_depan'          => 'required|string|max:255',
            'nama_belakang'       => 'required|string|max:255',
            'jenis_kelamin'       => 'required|string|in:Laki-laki,Perempuan',
            'tempat_lahir'        => 'required|string|max:255',
            'kewarganegaraan'     => 'required|string|max:100',
            'nomor_handphone'     => 'required|string|max:15',
            'gaji_diharapkan'     => 'required|string|max:255',
            'alamat_domisili'     => 'required|string',
            'provinsi'            => 'required|string|max:100',
            'pendidikan_terakhir' => 'required|string',
            'kab_kota'            => 'required|string|max:100',
            'kecamatan'           => 'nullable|string|max:100',
            'desa_kelurahan'      => 'nullable|string|max:100',
            'jurusan'             => 'required|string|max:255',
            'tanggal_lahir'       => 'required|date',
            'nomor_identitas'     => 'required|string|max:20|unique:pelamar,nomor_identitas',
            'nomor_whatsapp'      => 'required|string|max:15',
            'email'               => 'required|email|unique:users,email|unique:pelamar,email',
            'password'            => 'nullable|string|min:8|confirmed',
            'foto_profil'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::transaction(function () use ($request) {
            $password = $request->filled('password') ? $request->password : '12345678';

            $user = User::create([
                'name'     => $request->nama_depan . ' ' . $request->nama_belakang,
                'email'    => $request->email,
                'password' => Hash::make($password),
                'role'     => 'pelamar',
            ]);

            $data = $request->except('password', 'password_confirmation', 'foto_profil');
            $data['user_id'] = $user->id;
            $data['nama_lengkap'] = $request->nama_depan . ' ' . $request->nama_belakang;

            if ($request->hasFile('foto_profil')) {
                $path = $request->file('foto_profil')->store('pelamar/profil', 'public');
                $data['foto_profil'] = $path;
            }

            Pelamar::create($data);
        });

        return redirect()->route('admin.pelamar.index')
            ->with('success', 'Pelamar dan akun berhasil dibuat.');
    }

    public function show(Pelamar $pelamar)
    {
        $pelamar->load('user');
        return view('admin.pelamar.show', compact('pelamar'));
    }

    public function edit(Pelamar $pelamar)
    {
        $pelamar->load('user');
        $pendidikanOptions = Pelamar::pendidikanOptions();
        $jenisKelaminOptions = Pelamar::jenisKelaminOptions();
        $kewarganegaraanOptions = Pelamar::kewarganegaraanOptions();

        return view('admin.pelamar.edit', compact('pelamar', 'pendidikanOptions', 'jenisKelaminOptions', 'kewarganegaraanOptions'));
    }

    public function update(Request $request, Pelamar $pelamar)
    {
        $request->validate([
            'nama_depan'          => 'required|string|max:255',
            'nama_belakang'       => 'required|string|max:255',
            'jenis_kelamin'       => 'required|string|in:Laki-laki,Perempuan',
            'tempat_lahir'        => 'required|string|max:255',
            'kewarganegaraan'     => 'required|string|max:100',
            'nomor_handphone'     => 'required|string|max:15',
            'gaji_diharapkan'     => 'required|string|max:255',
            'alamat_domisili'     => 'required|string',
            'provinsi'            => 'required|string|max:100',
            'pendidikan_terakhir' => 'required|string|in:' . implode(',', array_keys(Pelamar::pendidikanOptions())),
            'kab_kota'            => 'required|string|max:100',
            'kecamatan'           => 'nullable|string|max:100',
            'desa_kelurahan'      => 'nullable|string|max:100',
            'jurusan'             => 'required|string|max:255',
            'tanggal_lahir'       => 'required|date',
            'nomor_identitas'     => 'required|string|max:20|unique:pelamar,nomor_identitas,' . $pelamar->id,
            'nomor_whatsapp'      => 'required|string|max:15',
            'email'               => 'required|email|unique:users,email,' . ($pelamar->user_id ?? 0) . '|unique:pelamar,email,' . $pelamar->id,
            'password'            => 'nullable|string|min:8|confirmed',
            'foto_profil'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $pelamar) {
                if ($pelamar->user) {
                    $pelamar->user->update([
                        'name' => $request->nama_depan . ' ' . $request->nama_belakang,
                        'email' => $request->email,
                        'password' => $request->filled('password')
                            ? Hash::make($request->password)
                            : $pelamar->user->password,
                    ]);
                }

                $data = $request->only([
                    'nama_depan',
                    'nama_belakang',
                    'email',
                    'jenis_kelamin',
                    'tempat_lahir',
                    'kewarganegaraan',
                    'nomor_handphone',
                    'gaji_diharapkan',
                    'alamat_domisili',
                    'provinsi',
                    'pendidikan_terakhir',
                    'kab_kota',
                    'kecamatan',
                    'desa_kelurahan',
                    'jurusan',
                    'tanggal_lahir',
                    'nomor_identitas',
                    'nomor_whatsapp'
                ]);
                $data['nama_lengkap'] = $request->nama_depan . ' ' . $request->nama_belakang;

                if ($request->hasFile('foto_profil')) {
                    if ($pelamar->foto_profil) {
                        Storage::disk('public')->delete($pelamar->foto_profil);
                    }
                    $path = $request->file('foto_profil')->store('pelamar/profil', 'public');
                    $data['foto_profil'] = $path;
                }

                $pelamar->update($data);
            });

            return redirect()->route('admin.pelamar.index')
                ->with('success', 'Pelamar dan akun berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function destroy(Pelamar $pelamar)
    {
        try {
            if ($pelamar->foto_profil) {
                Storage::disk('public')->delete($pelamar->foto_profil);
            }

            if ($pelamar->user) {
                $pelamar->user->delete();
            }

            $pelamar->delete();

            return redirect()->route('admin.pelamar.index')
                ->with('success', 'Pelamar dan akun user berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pelamar.index')
                ->with('error', 'Gagal menghapus pelamar: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            Excel::import(new PelamarImport, $request->file('file'));
            return redirect()->route('admin.pelamar.index')
                ->with('success', 'Data pelamar berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->route('admin.pelamar.index')
                ->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new PelamarExport, 'data_pelamar_' . date('Y-m-d') . '.xlsx');
    }

    public function downloadTemplate()
    {
        try {
            $fileName = 'template_pelamar.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ];

            $callback = function () {
                $handle = fopen('php://output', 'w');

                $headerRow = [
                    'Nama Depan',
                    'Nama Belakang',
                    'Email',
                    'Password Default',
                    'Jenis Kelamin',
                    'Tempat Lahir',
                    'Tanggal Lahir',
                    'Kewarganegaraan',
                    'Nomor Handphone',
                    'Nomor Whatsapp',
                    'NIK',
                    'Provinsi',
                    'Kabupaten/Kota',
                    'Kecamatan',
                    'Desa/Kelurahan',
                    'Alamat Domisili',
                    'Pendidikan Terakhir',
                    'Jurusan',
                    'Gaji Diharapkan'
                ];

                fputcsv($handle, $headerRow);

                $exampleRow = [
                    'John',
                    'Doe',
                    'john@example.com',
                    '12345678',
                    'Laki-laki',
                    'Jakarta',
                    '1990-01-01',
                    'WNI',
                    '081234567890',
                    '081234567890',
                    '1234567890123456',
                    'DKI Jakarta',
                    'Jakarta Selatan',
                    'Kebayoran Baru',
                    'Pulo',
                    'Jl. Contoh No. 123',
                    'Sarjana (S1)',
                    'Teknik Informatika',
                    '5000000'
                ];

                fputcsv($handle, $exampleRow);
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            return redirect()->route('admin.pelamar.index')
                ->with('error', 'Gagal download template: ' . $e->getMessage());
        }
    }
    public function dashboardStats()
    {
        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        if (!$pelamar) {
            return response()->json([
                'active_offering' => 0,
                'selection_status' => 'pending',
                'notifications' => 0
            ]);
        }

        $activeOffering = OfferingPenawaran::where('pelamar_id', $pelamar->id)
            ->where('status', 'sent')
            ->where('batas_respon', '>', now())
            ->count();

        $pelamarTahapan = PelamarTahapan::where('pelamar_id', $pelamar->id)->first();

        return response()->json([
            'active_offering' => $activeOffering,
            'selection_status' => $pelamarTahapan->spk_status ?? 'pending',
            'notifications' => 0
        ]);
    }
}
