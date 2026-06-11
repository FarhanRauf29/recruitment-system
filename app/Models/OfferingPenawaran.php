<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OfferingPenawaran extends Model
{
    use HasFactory;

    protected $table = 'offering_penawaran';

    protected $fillable = [
        'pelamar_id',
        'lowongan_id',
        'posisi',
        'gaji',
        'gaji_counter',
        'tipe_kontrak',
        'durasi_kontrak',
        'masa_probation',
        'tanggal_mulai_kerja',
        'catatan_hr',
        'batas_respon',
        'status',
        'alasan_penolakan',
        'alasan_counter',
        'tanggal_dikirim',
        'tanggal_diterima',
        'tanggal_ditolak',
        'tanggal_counter',
        'approved_by',
        'tanggal_approval',
        'created_by'
    ];

    protected $casts = [
        'gaji' => 'decimal:2',
        'gaji_counter' => 'decimal:2',
        'tanggal_mulai_kerja' => 'date',
        'batas_respon' => 'date',
        'tanggal_dikirim' => 'datetime',
        'tanggal_diterima' => 'datetime',
        'tanggal_ditolak' => 'datetime',
        'tanggal_counter' => 'datetime',
        'tanggal_approval' => 'datetime'
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class);
    }

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function hiring(): HasOne
    {
        return $this->hasOne(Hiring::class, 'offering_id');
    }

    public function isAccepted()
    {
        return $this->status === 'accepted';
    }

    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    public function isExpired()
    {
        return $this->batas_respon && now()->gt($this->batas_respon) && $this->status === 'sent';
    }

    public function needsApproval()
    {
        return $this->gaji > 15000000 && $this->status === 'pending_approval';
    }
}
