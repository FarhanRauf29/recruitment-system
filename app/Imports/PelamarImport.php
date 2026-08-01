<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Pelamar;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PelamarImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            $email = isset($row['email']) ? trim($row['email']) : null;
            if (!$email) {
                return null;
            }

            $existingUser = User::where('email', $email)->first();
            if ($existingUser) {
                return null;
            }

            $existingPelamar = Pelamar::where('email', $email)->first();
            if ($existingPelamar) {
                return null;
            }

            $password = isset($row['password_default']) && !empty($row['password_default'])
                ? $row['password_default']
                : '12345678';

            $user = User::create([
                'name' => trim(($row['nama_depan'] ?? '') . ' ' . ($row['nama_belakang'] ?? '')),
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'pelamar',
            ]);

            $tanggalLahir = null;
            if (isset($row['tanggal_lahir']) && !empty($row['tanggal_lahir'])) {
                if (is_numeric($row['tanggal_lahir'])) {
                    $tanggalLahir = date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($row['tanggal_lahir']));
                } else {
                    $tanggalLahir = date('Y-m-d', strtotime($row['tanggal_lahir']));
                }
            }

            $kabKota = isset($row['kabupaten_kota']) && !empty($row['kabupaten_kota'])
                ? $row['kabupaten_kota']
                : (isset($row['kab_kota']) ? $row['kab_kota'] : '');

            $nomorIdentitas = isset($row['nik']) ? (string) $row['nik'] : '';
            $gajiDiharapkan = isset($row['gaji_diharapkan']) ? (string) $row['gaji_diharapkan'] : '';

            return new Pelamar([
                'user_id' => $user->id,
                'nama_depan' => $row['nama_depan'] ?? '',
                'nama_belakang' => $row['nama_belakang'] ?? '',
                'nama_lengkap' => $row['nama_lengkap'] ?? '',
                'email' => $email,
                'jenis_kelamin' => $row['jenis_kelamin'] ?? '',
                'tempat_lahir' => $row['tempat_lahir'] ?? '',
                'tanggal_lahir' => $tanggalLahir,
                'kewarganegaraan' => $row['kewarganegaraan'] ?? 'WNI',
                'nomor_handphone' => isset($row['nomor_handphone']) ? (string) $row['nomor_handphone'] : '',
                'nomor_whatsapp' => isset($row['nomor_whatsapp']) ? (string) $row['nomor_whatsapp'] : '',
                'nomor_identitas' => $nomorIdentitas,
                'provinsi' => $row['provinsi'] ?? '',
                'kab_kota' => $row['kabupaten_kota'] ?? $row['kab_kota'] ?? '',
                'kecamatan' => $row['kecamatan'] ?? '',
                'desa_kelurahan' => $row['desa_kelurahan'] ?? '',
                'alamat_domisili' => $row['alamat_domisili'] ?? '',
                'pendidikan_terakhir' => $row['pendidikan_terakhir'] ?? '',
                'instansi' => $row['instansi'] ?? '',
                'jurusan' => $row['jurusan'] ?? '',
                'gaji_diharapkan' => $gajiDiharapkan,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Error processing row: ' . $e->getMessage());
        }
    }
}
