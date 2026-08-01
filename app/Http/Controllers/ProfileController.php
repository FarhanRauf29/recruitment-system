<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Pelamar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $pelamar = null;

        if ($user->role === 'pelamar') {
            $pelamar = Pelamar::where('user_id', $user->id)->first();
        }

        $pendidikanOptions = Pelamar::pendidikanOptions();
        $jenisKelaminOptions = Pelamar::jenisKelaminOptions();
        $kewarganegaraanOptions = Pelamar::kewarganegaraanOptions();

        return view('profile.edit', compact('user', 'pelamar', 'pendidikanOptions', 'jenisKelaminOptions', 'kewarganegaraanOptions'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        Log::info('=== PROFILE UPDATE START ===');
        Log::info('User ID: ' . $request->user()->id);
        Log::info('Role: ' . $request->user()->role);

        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->role === 'pelamar') {
            $pelamar = Pelamar::where('user_id', $user->id)->first();

            if ($pelamar) {
                $updateData = [
                    'nama_depan' => $request->nama_depan ?? explode(' ', $request->name, 2)[0] ?? '',
                    'nama_belakang' => $request->nama_belakang ?? (explode(' ', $request->name, 2)[1] ?? ''),
                    'nama_lengkap' => $request->name,
                    'email' => $request->email,
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'kewarganegaraan' => $request->kewarganegaraan,
                    'nomor_handphone' => $request->nomor_handphone,
                    'nomor_whatsapp' => $request->nomor_whatsapp,
                    'nomor_identitas' => $request->nomor_identitas,
                    'provinsi' => $request->provinsi,
                    'kab_kota' => $request->kab_kota,
                    'kecamatan' => $request->kecamatan,
                    'desa_kelurahan' => $request->desa_kelurahan,
                    'alamat_domisili' => $request->alamat_domisili,
                    'pendidikan_terakhir' => $request->pendidikan_terakhir,
                    'instansi' => $request->instansi,
                    'jurusan' => $request->jurusan,
                    'gaji_diharapkan' => $request->gaji_diharapkan,
                ];

                if ($request->hasFile('foto_profil')) {
                    $file = $request->file('foto_profil');
                    if ($file) {
                        try {
                            if ($pelamar->foto_profil) {
                                Storage::disk('public')->delete($pelamar->foto_profil);
                            }
                            $path = $file->store('pelamar/profil', 'public');
                            $updateData['foto_profil'] = $path;
                        } catch (\Exception $e) {
                            Log::error('Error upload foto:', ['error' => $e->getMessage()]);
                            return back()->with('error', 'Gagal mengupload foto: ' . $e->getMessage());
                        }
                    }
                }

                $pelamar->update($updateData);
            } else {
                $pelamarData = [
                    'user_id' => $user->id,
                    'nama_depan' => $request->nama_depan ?? explode(' ', $request->name, 2)[0] ?? '',
                    'nama_belakang' => $request->nama_belakang ?? (explode(' ', $request->name, 2)[1] ?? ''),
                    'nama_lengkap' => $request->name,
                    'email' => $request->email,
                    'jenis_kelamin' => $request->jenis_kelamin ?? '',
                    'tempat_lahir' => $request->tempat_lahir ?? '',
                    'tanggal_lahir' => $request->tanggal_lahir ?? null,
                    'kewarganegaraan' => $request->kewarganegaraan ?? 'WNI',
                    'nomor_handphone' => $request->nomor_handphone ?? '',
                    'nomor_whatsapp' => $request->nomor_whatsapp ?? '',
                    'nomor_identitas' => $request->nomor_identitas ?? '',
                    'provinsi' => $request->provinsi ?? '',
                    'kab_kota' => $request->kab_kota ?? '',
                    'kecamatan' => $request->kecamatan ?? '',
                    'desa_kelurahan' => $request->desa_kelurahan ?? '',
                    'alamat_domisili' => $request->alamat_domisili ?? '',
                    'pendidikan_terakhir' => $request->pendidikan_terakhir ?? '',
                    'instansi' => $request->instansi ?? '',
                    'jurusan' => $request->jurusan ?? '',
                    'gaji_diharapkan' => $request->gaji_diharapkan ?? '',
                ];

                if ($request->hasFile('foto_profil')) {
                    $file = $request->file('foto_profil');
                    if ($file) {
                        $path = $file->store('pelamar/profil', 'public');
                        $pelamarData['foto_profil'] = $path;
                    }
                }

                Pelamar::create($pelamarData);
            }

            return redirect()->route('pelamar.dashboard')->with('status', 'profile-updated');
        }

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
