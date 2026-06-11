<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'bobot',
        'atribut',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function penilaian()
    {
        return $this->hasMany(PenilaianKandidat::class, 'kriteria_id');
    }

    public static function getBobotNormalisasi()
    {
        $totalBobot = self::where('is_active', true)->sum('bobot');
        $kriterias = self::where('is_active', true)->get();

        foreach ($kriterias as $kriteria) {
            $kriteria->bobot_normalisasi = $kriteria->bobot / $totalBobot;
        }

        return $kriterias;
    }
}
