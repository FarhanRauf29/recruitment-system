<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lowongan extends Model
{
    use HasFactory;

    protected $table = 'lowongan';

    protected $fillable = [
        'judul_lowongan',
        'deskripsi',
        'persyaratan',
        'tanggal_buka',
        'tanggal_tutup',
        'status',
        'slot',
    ];

    protected $casts = [
        'tanggal_buka' => 'date',
        'tanggal_tutup' => 'date',
        'slot' => 'integer',
    ];

    public static function statusOptions()
    {
        return [
            'buka' => 'Buka',
            'tutup' => 'Tutup',
        ];
    }

    public function pelamars(): BelongsToMany
    {
        return $this->belongsToMany(Pelamar::class, 'lowongan_pelamar', 'lowongan_id', 'pelamar_id')
            ->withPivot('status', 'created_at', 'updated_at')
            ->withTimestamps();
    }

    public function pelamarTahapans(): HasMany
    {
        return $this->hasMany(PelamarTahapan::class, 'lowongan_id');
    }

    public function getJumlahTerisiAttribute()
    {
        return $this->pelamarTahapans()
            ->whereIn('spk_status', ['approved', 'hired', 'hired_completed', 'onboarding_completed'])
            ->orWhere(function ($q) {
                $q->whereNull('spk_status')
                    ->where('status', 'lolos');
            })
            ->count();
    }

    public function getSlotTersediaAttribute()
    {
        return $this->slot - $this->getJumlahTerisiAttribute();
    }

    public function isSlotAvailable()
    {
        return $this->getJumlahTerisiAttribute() < $this->slot;
    }

    public function updateStatusIfNeeded()
    {
        if ($this->status === 'buka' && !$this->isSlotAvailable()) {
            $this->update(['status' => 'tutup']);
        } elseif ($this->status === 'tutup' && $this->isSlotAvailable()) {
            $this->update(['status' => 'buka']);
        }
    }

    public function createTahapanForPelamar($pelamar)
    {
        $tahapans = TahapanRekrutmen::aktif()->orderBy('id')->get();

        if ($tahapans->isEmpty()) {
            throw new \Exception('Tidak ada tahapan rekrutmen yang aktif.');
        }

        $firstTahapanId = $tahapans->first()->id;

        foreach ($tahapans as $tahapan) {
            if ($tahapan->id == $firstTahapanId) {
                $status = 'diproses';
                $tanggalMulai = now();
            } else {
                $status = 'menunggu';
                $tanggalMulai = null;
            }

            PelamarTahapan::updateOrCreate(
                [
                    'pelamar_id' => $pelamar->id,
                    'lowongan_id' => $this->id,
                    'tahapan_id' => $tahapan->id,
                ],
                [
                    'status' => $status,
                    'tanggal_mulai' => $tanggalMulai,
                    'tanggal_selesai' => null,
                    'catatan' => null,
                ]
            );
        }
    }
}
