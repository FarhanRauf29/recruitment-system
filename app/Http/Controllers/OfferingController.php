<?php

namespace App\Http\Controllers;

use App\Models\OfferingPenawaran;
use App\Models\Hiring;
use App\Models\Pelamar;
use App\Models\Lowongan;
use App\Models\PelamarTahapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OfferingController extends Controller
{
    public function adminIndex($lowonganId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);

        $kandidatApproved = PelamarTahapan::with(['pelamar'])
            ->where('lowongan_id', $lowonganId)
            ->where('spk_status', 'approved')
            ->get();

        $offeringSent = OfferingPenawaran::with(['pelamar'])
            ->where('lowongan_id', $lowonganId)
            ->where('status', 'sent')
            ->get();

        $offeringAccepted = OfferingPenawaran::with(['pelamar'])
            ->where('lowongan_id', $lowonganId)
            ->where('status', 'accepted')
            ->get();

        $offeringRejected = OfferingPenawaran::with(['pelamar'])
            ->where('lowongan_id', $lowonganId)
            ->where('status', 'rejected')
            ->get();

        return view('admin.offering.index', compact('lowongan', 'kandidatApproved', 'offeringSent', 'offeringAccepted', 'offeringRejected'));
    }

    public function adminCreate($lowonganId, $pelamarId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);
        $pelamar = Pelamar::findOrFail($pelamarId);

        $hasilSPK = PelamarTahapan::where('pelamar_id', $pelamarId)
            ->where('lowongan_id', $lowonganId)
            ->first();

        return view('admin.offering.create', compact('lowongan', 'pelamar', 'hasilSPK'));
    }

    public function adminStore(Request $request, $lowonganId, $pelamarId)
    {
        $request->validate([
            'posisi' => 'required|string|max:255',
            'gaji' => 'required|numeric|min:0',
            'tipe_kontrak' => 'required|in:tetap,kontrak,probation',
            'durasi_kontrak' => 'nullable|string|max:100',
            'masa_probation' => 'nullable|integer|min:1|max:12',
            'tanggal_mulai_kerja' => 'required|date',
            'catatan_hr' => 'nullable|string',
            'batas_respon' => 'required|integer|min:1|max:30',
        ]);

        DB::beginTransaction();

        try {
            $batasRespon = (int) $request->batas_respon;

            $status = 'sent';
            if ($request->gaji > 15000000) {
                $status = 'pending_approval';
            }

            $offering = OfferingPenawaran::create([
                'pelamar_id' => $pelamarId,
                'lowongan_id' => $lowonganId,
                'posisi' => $request->posisi,
                'gaji' => $request->gaji,
                'tipe_kontrak' => $request->tipe_kontrak,
                'durasi_kontrak' => $request->durasi_kontrak,
                'masa_probation' => $request->masa_probation ?? 3,
                'tanggal_mulai_kerja' => $request->tanggal_mulai_kerja,
                'catatan_hr' => $request->catatan_hr,
                'batas_respon' => now()->addDays($batasRespon),
                'status' => $status,
                'tanggal_dikirim' => now(),
                'created_by' => Auth::id(),
            ]);

            PelamarTahapan::where('pelamar_id', $pelamarId)
                ->where('lowongan_id', $lowonganId)
                ->update([
                    'spk_status' => 'offering',
                    'catatan_spk' => DB::raw("CONCAT(IFNULL(catatan_spk, ''), '\nOffering dikirim: " . now() . "')")
                ]);

            DB::commit();

            return redirect()->route('admin.offering.index', $lowonganId)->with('success', 'Penawaran kerja berhasil dikirim ke ' . $offering->pelamar->nama_lengkap);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Store Offering Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengirim penawaran: ' . $e->getMessage());
        }
    }

    public function adminApprove($lowonganId, $offeringId)
    {
        $offering = OfferingPenawaran::findOrFail($offeringId);

        if ($offering->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Penawaran tidak perlu approval.');
        }

        DB::beginTransaction();

        try {
            $offering->update([
                'status' => 'sent',
                'approved_by' => Auth::id(),
                'tanggal_approval' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.offering.index', $lowonganId)
                ->with('success', 'Penawaran kerja berhasil di-approve dan dikirim ke pelamar.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal approve penawaran: ' . $e->getMessage());
        }
    }

    public function adminRejectApproval($lowonganId, $offeringId)
    {
        $offering = OfferingPenawaran::findOrFail($offeringId);

        if ($offering->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Penawaran tidak perlu approval.');
        }

        DB::beginTransaction();

        try {
            $offering->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'tanggal_approval' => now(),
                'alasan_penolakan' => 'Penawaran ditolak oleh atasan.',
            ]);

            PelamarTahapan::where('pelamar_id', $offering->pelamar_id)
                ->where('lowongan_id', $lowonganId)
                ->update(['spk_status' => 'approved']);

            DB::commit();

            return redirect()->route('admin.offering.index', $lowonganId)
                ->with('warning', 'Penawaran kerja ditolak.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menolak penawaran: ' . $e->getMessage());
        }
    }

    public function adminResend($lowonganId, $offeringId)
    {
        $offering = OfferingPenawaran::findOrFail($offeringId);

        if ($offering->status !== 'sent') {
            return redirect()->back()->with('error', 'Hanya penawaran yang sudah dikirim yang dapat di-resend.');
        }

        $offering->update([
            'tanggal_dikirim' => now(),
            'batas_respon' => now()->addDays(7),
        ]);

        return redirect()->route('admin.offering.index', $lowonganId)
            ->with('success', 'Penawaran kerja berhasil dikirim ulang.');
    }

    public function adminCancel($lowonganId, $offeringId)
    {
        $offering = OfferingPenawaran::findOrFail($offeringId);

        if (!in_array($offering->status, ['draft', 'sent', 'pending_approval'])) {
            return redirect()->back()->with('error', 'Penawaran sudah direspon, tidak dapat dibatalkan.');
        }

        $offering->update(['status' => 'expired']);

        PelamarTahapan::where('pelamar_id', $offering->pelamar_id)
            ->where('lowongan_id', $lowonganId)
            ->update(['spk_status' => 'approved']);

        return redirect()->route('admin.offering.index', $lowonganId)
            ->with('success', 'Penawaran kerja berhasil dibatalkan');
    }

    public function adminShow($lowonganId, $offeringId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);
        $offering = OfferingPenawaran::with(['pelamar', 'createdBy', 'approvedBy'])->findOrFail($offeringId);

        return view('admin.offering.show', compact('lowongan', 'offering'));
    }

    public function pelamarIndex()
    {
        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')->with('error', 'Data pelamar tidak ditemukan');
        }

        $offeringSent = OfferingPenawaran::with(['lowongan'])
            ->where('pelamar_id', $pelamar->id)
            ->where('status', 'sent')
            ->where('batas_respon', '>', now())
            ->get();

        $offeringHistory = OfferingPenawaran::with(['lowongan'])
            ->where('pelamar_id', $pelamar->id)
            ->whereIn('status', ['accepted', 'rejected', 'expired'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pelamar.offering.index', compact('offeringSent', 'offeringHistory'));
    }

    public function pelamarShow($id)
    {
        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')->with('error', 'Data pelamar tidak ditemukan');
        }

        $offering = OfferingPenawaran::with(['lowongan'])
            ->where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        if ($offering->status === 'expired') {
            return redirect()->route('pelamar.offering.index')->with('error', 'Penawaran ini sudah kadaluarsa.');
        }

        return view('pelamar.offering.show', compact('offering'));
    }

    public function pelamarAccept($id)
    {
        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')->with('error', 'Data pelamar tidak ditemukan');
        }

        $offering = OfferingPenawaran::where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        if ($offering->status !== 'sent') {
            return redirect()->back()->with('error', 'Penawaran sudah tidak dapat diterima.');
        }

        if ($offering->batas_respon && now()->gt($offering->batas_respon)) {
            $offering->update(['status' => 'expired']);
            return redirect()->back()->with('error', 'Penawaran sudah kadaluarsa.');
        }

        DB::beginTransaction();

        try {
            $offering->update([
                'status' => 'accepted',
                'tanggal_diterima' => now(),
            ]);

            Hiring::create([
                'pelamar_id' => $pelamar->id,
                'lowongan_id' => $offering->lowongan_id,
                'offering_id' => $offering->id,
                'status' => 'pending',
            ]);

            PelamarTahapan::where('pelamar_id', $pelamar->id)
                ->where('lowongan_id', $offering->lowongan_id)
                ->update([
                    'spk_status' => 'hired',
                    'catatan_spk' => DB::raw("CONCAT(IFNULL(catatan_spk, ''), '\nKandidat menerima penawaran: " . now() . "')")
                ]);

            DB::commit();

            return redirect()->route('pelamar.offering.index')
                ->with('success', 'Selamat! Anda telah menerima penawaran kerja. HR akan menghubungi Anda.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menerima penawaran: ' . $e->getMessage());
        }
    }

    public function pelamarReject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')->with('error', 'Data pelamar tidak ditemukan');
        }

        $offering = OfferingPenawaran::where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        if ($offering->status !== 'sent') {
            return redirect()->back()->with('error', 'Penawaran sudah tidak dapat ditolak.');
        }

        DB::beginTransaction();

        try {
            $offering->update([
                'status' => 'rejected',
                'alasan_penolakan' => $request->alasan_penolakan,
                'tanggal_ditolak' => now(),
            ]);

            PelamarTahapan::where('pelamar_id', $pelamar->id)
                ->where('lowongan_id', $offering->lowongan_id)
                ->update([
                    'spk_status' => 'rejected',
                    'catatan_spk' => DB::raw("CONCAT(IFNULL(catatan_spk, ''), '\nKandidat menolak penawaran: " . now() . "')")
                ]);

            DB::commit();

            return redirect()->route('pelamar.offering.index')
                ->with('info', 'Penawaran kerja ditolak. Terima kasih atas partisipasi Anda.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menolak penawaran: ' . $e->getMessage());
        }
    }

    public function pelamarCounter(Request $request, $id)
    {
        $request->validate([
            'gaji_counter' => 'required|numeric|min:0',
            'alasan_counter' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')->with('error', 'Data pelamar tidak ditemukan');
        }

        $offering = OfferingPenawaran::where('id', $id)
            ->where('pelamar_id', $pelamar->id)
            ->firstOrFail();

        if ($offering->status !== 'sent') {
            return redirect()->back()->with('error', 'Penawaran sudah tidak dapat dinegosiasi.');
        }

        $offering->update([
            'status' => 'counter',
            'gaji_counter' => $request->gaji_counter,
            'alasan_counter' => $request->alasan_counter,
            'tanggal_counter' => now(),
        ]);

        return redirect()->route('pelamar.offering.index')
            ->with('info', 'Counter offer Anda telah dikirim. HR akan memproses dalam waktu dekat.');
    }
}
