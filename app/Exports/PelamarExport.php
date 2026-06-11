<?php

namespace App\Exports;

use App\Models\Pelamar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PelamarExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Pelamar::all();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Depan',
            'Nama Belakang',
            'Nama Lengkap',
            'Email',
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
            'Gaji Diharapkan',
            'Tanggal Daftar'
        ];
    }

    public function map($pelamar): array
    {
        static $no = 1;
        return [
            $no++,
            $pelamar->nama_depan ?? '-',
            $pelamar->nama_belakang ?? '-',
            $pelamar->nama_lengkap ?? '-',
            $pelamar->email ?? '-',
            $pelamar->jenis_kelamin ?? '-',
            $pelamar->tempat_lahir ?? '-',
            $pelamar->tanggal_lahir ? $pelamar->tanggal_lahir->format('Y-m-d') : '-',
            $pelamar->kewarganegaraan ?? '-',
            $pelamar->nomor_handphone ?? '-',
            $pelamar->nomor_whatsapp ?? '-',
            $pelamar->nomor_identitas ?? '-',
            $pelamar->provinsi ?? '-',
            $pelamar->kab_kota ?? '-',
            $pelamar->kecamatan ?? '-',
            $pelamar->desa_kelurahan ?? '-',
            $pelamar->alamat_domisili ?? '-',
            $pelamar->pendidikan_terakhir ?? '-',
            $pelamar->jurusan ?? '-',
            $pelamar->gaji_diharapkan ?? '-',
            $pelamar->created_at ? $pelamar->created_at->format('d/m/Y H:i') : '-',
        ];
    }
}
