<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Onboarding extends Model
{
    use HasFactory;

    protected $table = 'onboarding';

    protected $fillable = [
        'pelamar_id',
        'lowongan_id',
        'hiring_id',
        'divisi',
        'atasan_langsung',
        'lokasi_kerja',
        'tanggal_masuk',
        'nip',
        'email_kantor',
        'no_telepon_kantor',
        'catatan',
        'dokumen_yang_disiapkan',
        'checklist_onboarding',
        'status',
        'tanggal_selesai',
        'completed_by'
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_selesai' => 'datetime',
        'dokumen_yang_disiapkan' => 'array',
        'checklist_onboarding' => 'array'
    ];

    public function pelamar(): BelongsTo
    {
        return $this->belongsTo(Pelamar::class);
    }

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class);
    }

    public function hiring(): BelongsTo
    {
        return $this->belongsTo(Hiring::class);
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function generateNIP()
    {
        $year = date('Y');
        $lastEmployee = self::whereYear('created_at', $year)->count();
        $nip = $year . str_pad($lastEmployee + 1, 6, '0', STR_PAD_LEFT);
        $this->update(['nip' => $nip]);
        return $nip;
    }

    public function getChecklistProgress()
    {
        if (!$this->checklist_onboarding) return 0;
        $total = count($this->checklist_onboarding);
        $completed = collect($this->checklist_onboarding)->filter(function ($item) {
            return isset($item['completed']) && $item['completed'] === true;
        })->count();
        return $total > 0 ? round(($completed / $total) * 100, 2) : 0;
    }
}
