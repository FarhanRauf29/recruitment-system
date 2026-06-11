<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Hiring extends Model
{
    use HasFactory;

    protected $table = 'hiring';

    protected $fillable = [
        'pelamar_id',
        'lowongan_id',
        'offering_id',
        'status',
        'tanggal_hired',
        'tanggal_probation_start',
        'tanggal_probation_end',
        'probation_result',
        'catatan',
        'hired_by'
    ];

    protected $casts = [
        'tanggal_hired' => 'datetime',
        'tanggal_probation_start' => 'datetime',
        'tanggal_probation_end' => 'datetime'
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class);
    }

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class);
    }

    public function offering(): BelongsTo
    {
        return $this->belongsTo(OfferingPenawaran::class, 'offering_id');
    }

    public function hiredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'hired_by');
    }

    public function onboarding()
    {
        return $this->hasOne(Onboarding::class);
    }

    public function startProbation()
    {
        $this->update([
            'status' => 'probation',
            'tanggal_probation_start' => now(),
            'tanggal_probation_end' => now()->addMonths($this->offering->masa_probation ?? 3),
            'probation_result' => 'pending'
        ]);
    }

    public function completeProbation($result, $notes = null)
    {
        $this->update([
            'probation_result' => $result,
            'catatan' => $notes
        ]);

        if ($result === 'passed') {
            $this->update(['status' => 'hired']);
        } elseif ($result === 'failed') {
            $this->update(['status' => 'cancelled']);
        } elseif ($result === 'extended') {
            $this->update(['tanggal_probation_end' => $this->tanggal_probation_end->addMonths(3)]);
        }
    }
}
