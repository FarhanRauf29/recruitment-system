<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run()
    {
        $kriterias = [
            ['kode_kriteria' => 'C1', 'nama_kriteria' => 'Seleksi Administrasi (Berkas)', 'bobot' => 15, 'atribut' => 'benefit'],
            ['kode_kriteria' => 'C2', 'nama_kriteria' => 'Psikotes', 'bobot' => 25, 'atribut' => 'benefit'],
            ['kode_kriteria' => 'C3', 'nama_kriteria' => 'Medical Check Up (MCU)', 'bobot' => 25, 'atribut' => 'benefit'],
            ['kode_kriteria' => 'C4', 'nama_kriteria' => 'Wawancara', 'bobot' => 35, 'atribut' => 'benefit'],
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::create($kriteria);
        }
    }
}
