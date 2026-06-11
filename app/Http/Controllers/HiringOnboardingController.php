<?php

namespace App\Http\Controllers;

use App\Models\OfferingPenawaran;
use App\Models\Hiring;
use App\Models\Onboarding;
use App\Models\Pelamar;
use App\Models\Lowongan;
use App\Models\PelamarTahapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HiringOnboardingController extends Controller
{
    public function adminHiringIndex($lowonganId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);

        $offeringAccepted = OfferingPenawaran::with(['pelamar'])
            ->where('lowongan_id', $lowonganId)
            ->where('status', 'accepted')
            ->whereDoesntHave('hiring')
            ->get();

        $hiringPending = Hiring::with(['pelamar', 'offering'])
            ->where('lowongan_id', $lowonganId)
            ->where('status', 'pending')
            ->get();

        $hiringProbation = Hiring::with(['pelamar', 'offering'])
            ->where('lowongan_id', $lowonganId)
            ->where('status', 'probation')
            ->get();

        $hiringCompleted = Hiring::with(['pelamar', 'offering', 'onboarding'])
            ->where('lowongan_id', $lowonganId)
            ->where('status', 'hired')
            ->get();

        return view('admin.hiring.index', compact('lowongan', 'offeringAccepted', 'hiringPending', 'hiringProbation', 'hiringCompleted'));
    }

    public function adminHiringCreate($lowonganId, $offeringId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);
        $offering = OfferingPenawaran::with(['pelamar'])->findOrFail($offeringId);

        if ($offering->status !== 'accepted') {
            return redirect()->back()->with('error', 'Kandidat belum menerima penawaran.');
        }

        $existingHiring = Hiring::where('offering_id', $offeringId)->first();

        if ($existingHiring) {
            if ($existingHiring->status == 'hired') {
                return redirect()->route('admin.onboarding.create', [$lowonganId, $existingHiring->id])
                    ->with('success', 'Kandidat sudah di-hire. Silakan isi data onboarding.');
            } elseif ($existingHiring->status == 'probation') {
                return redirect()->route('admin.hiring.show', [$lowonganId, $existingHiring->id])
                    ->with('info', 'Kandidat sedang dalam masa probation.');
            } elseif ($existingHiring->status == 'pending') {
                return view('admin.hiring.edit', compact('lowongan', 'offering', 'existingHiring'));
            }
        }

        return view('admin.hiring.create', compact('lowongan', 'offering'));
    }

    public function adminHiringStore(Request $request, $lowonganId, $offeringId)
    {
        $request->validate([
            'catatan' => 'nullable|string',
            'start_probation' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $offering = OfferingPenawaran::findOrFail($offeringId);

            $existingHiring = Hiring::where('offering_id', $offeringId)->first();

            $hiringData = [
                'pelamar_id' => $offering->pelamar_id,
                'lowongan_id' => $lowonganId,
                'offering_id' => $offeringId,
                'catatan' => $request->catatan,
                'hired_by' => Auth::id(),
            ];

            if ($request->has('start_probation') && $offering->tipe_kontrak === 'probation') {
                $hiringData['status'] = 'probation';
                $hiringData['tanggal_probation_start'] = now();
                $hiringData['tanggal_probation_end'] = now()->addMonths($offering->masa_probation ?? 3);
                $hiringData['tanggal_hired'] = null;
                $hiringData['probation_result'] = 'pending';
            } else {
                $hiringData['status'] = 'hired';
                $hiringData['tanggal_hired'] = now();
                $hiringData['tanggal_probation_start'] = null;
                $hiringData['tanggal_probation_end'] = null;
                $hiringData['probation_result'] = null;
            }

            if ($existingHiring) {
                $hiring = $existingHiring;
                $hiring->update($hiringData);
            } else {
                $hiring = Hiring::create($hiringData);
            }

            $spkStatus = $hiring->status === 'probation' ? 'probation' : 'hired';

            PelamarTahapan::where('pelamar_id', $offering->pelamar_id)
                ->where('lowongan_id', $lowonganId)
                ->update([
                    'spk_status' => $spkStatus,
                    'status' => $hiring->status === 'hired' ? 'lolos' : 'diproses',
                    'catatan_spk' => DB::raw("CONCAT(IFNULL(catatan_spk, ''), '\nKandidat resmi diterima menjadi karyawan: " . now() . "')")
                ]);

            DB::commit();

            if ($hiring->status === 'probation') {
                return redirect()->route('admin.hiring.show', [$lowonganId, $hiring->id])
                    ->with('success', 'Kandidat berhasil di-hire dengan masa probation ' . ($offering->masa_probation ?? 3) . ' bulan.');
            }

            return redirect()->route('admin.onboarding.create', [$lowonganId, $hiring->id])
                ->with('success', 'Kandidat berhasil di-hire. Silakan isi data onboarding.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Store Hiring Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses hiring: ' . $e->getMessage());
        }
    }

    public function adminHiringShow($lowonganId, $hiringId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);
        $hiring = Hiring::with(['pelamar', 'offering'])->findOrFail($hiringId);

        return view('admin.hiring.show', compact('lowongan', 'hiring'));
    }

    public function adminProbationComplete(Request $request, $lowonganId, $hiringId)
    {
        $request->validate([
            'probation_result' => 'required|in:passed,failed,extended',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $hiring = Hiring::findOrFail($hiringId);

            if ($request->probation_result === 'passed') {
                $hiring->update([
                    'status' => 'hired',
                    'tanggal_hired' => now(),
                    'probation_result' => 'passed',
                    'catatan' => $request->catatan ? ($hiring->catatan ? $hiring->catatan . "\n" . $request->catatan : $request->catatan) : $hiring->catatan
                ]);

                PelamarTahapan::where('pelamar_id', $hiring->pelamar_id)
                    ->where('lowongan_id', $lowonganId)
                    ->update([
                        'spk_status' => 'hired',
                        'status' => 'lolos',
                        'catatan_spk' => DB::raw("CONCAT(IFNULL(catatan_spk, ''), '\nProbation selesai: Lulus - " . now() . "')")
                    ]);

                DB::commit();
                return redirect()->route('admin.onboarding.create', [$lowonganId, $hiring->id])
                    ->with('success', 'Probation berhasil diselesaikan. Karyawan resmi diangkat. Silakan isi data onboarding.');
            } elseif ($request->probation_result === 'failed') {
                $hiring->update([
                    'status' => 'failed',
                    'probation_result' => 'failed',
                    'catatan' => $request->catatan ? ($hiring->catatan ? $hiring->catatan . "\n" . $request->catatan : $request->catatan) : $hiring->catatan
                ]);

                PelamarTahapan::where('pelamar_id', $hiring->pelamar_id)
                    ->where('lowongan_id', $lowonganId)
                    ->update([
                        'spk_status' => 'probation_failed',
                        'status' => 'gagal',
                        'catatan_spk' => DB::raw("CONCAT(IFNULL(catatan_spk, ''), '\nProbation gagal: Tidak diangkat - " . now() . "')")
                    ]);

                DB::commit();
                return redirect()->route('admin.hiring.index', $lowonganId)
                    ->with('warning', 'Probation gagal. Karyawan tidak diangkat.');
            } else {
                $hiring->update([
                    'tanggal_probation_start' => now(),
                    'tanggal_probation_end' => now()->addMonths(3),
                    'probation_result' => 'extended',
                    'catatan' => $request->catatan ? ($hiring->catatan ? $hiring->catatan . "\n" . $request->catatan : $request->catatan) : $hiring->catatan
                ]);

                DB::commit();
                return redirect()->route('admin.hiring.show', [$lowonganId, $hiringId])
                    ->with('success', 'Probation diperpanjang 3 bulan.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Probation Complete Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memproses hasil probation: ' . $e->getMessage());
        }
    }

    public function adminOnboardingIndex($lowonganId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);
        $onboardings = Onboarding::with(['pelamar'])->where('lowongan_id', $lowonganId)->orderBy('created_at', 'desc')->get();
        return view('admin.onboarding.index', compact('lowongan', 'onboardings'));
    }

    public function adminOnboardingCreate($lowonganId, $hiringId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);
        $hiring = Hiring::with(['pelamar', 'offering'])->findOrFail($hiringId);

        if ($hiring->status === 'pending') {
            return redirect()->route('admin.hiring.create', [$lowonganId, $hiring->offering_id])
                ->with('warning', 'Silakan konfirmasi hiring terlebih dahulu.');
        }

        if ($hiring->status === 'probation') {
            return redirect()->route('admin.hiring.show', [$lowonganId, $hiringId])
                ->with('warning', 'Kandidat masih dalam masa probation. Selesaikan probation terlebih dahulu.');
        }

        if ($hiring->status === 'failed') {
            return redirect()->route('admin.hiring.index', $lowonganId)
                ->with('error', 'Kandidat gagal dalam masa probation dan tidak dapat melanjutkan ke onboarding.');
        }

        $existingOnboarding = Onboarding::where('hiring_id', $hiringId)->first();
        if ($existingOnboarding) {
            return redirect()->route('admin.onboarding.show', [$lowonganId, $existingOnboarding->id]);
        }

        return view('admin.onboarding.create', compact('lowongan', 'hiring'));
    }

    public function adminOnboardingStore(Request $request, $lowonganId, $hiringId)
    {
        $request->validate([
            'divisi' => 'required|string|max:255',
            'atasan_langsung' => 'required|string|max:255',
            'lokasi_kerja' => 'required|string|max:255',
            'tanggal_masuk' => 'required|date',
            'email_kantor' => 'nullable|email',
            'no_telepon_kantor' => 'nullable|string',
            'dokumen_yang_disiapkan' => 'nullable|array',
            'checklist_onboarding' => 'nullable|array',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $hiring = Hiring::findOrFail($hiringId);

            $onboarding = Onboarding::create([
                'pelamar_id' => $hiring->pelamar_id,
                'lowongan_id' => $lowonganId,
                'hiring_id' => $hiringId,
                'divisi' => $request->divisi,
                'atasan_langsung' => $request->atasan_langsung,
                'lokasi_kerja' => $request->lokasi_kerja,
                'tanggal_masuk' => $request->tanggal_masuk,
                'email_kantor' => $request->email_kantor,
                'no_telepon_kantor' => $request->no_telepon_kantor,
                'catatan' => $request->catatan,
                'dokumen_yang_disiapkan' => $request->dokumen_yang_disiapkan,
                'checklist_onboarding' => $request->checklist_onboarding,
                'status' => 'completed',
                'tanggal_selesai' => now(),
                'completed_by' => Auth::id(),
            ]);

            if (method_exists($onboarding, 'generateNIP')) {
                $onboarding->generateNIP();
            }

            PelamarTahapan::where('pelamar_id', $hiring->pelamar_id)
                ->where('lowongan_id', $lowonganId)
                ->update([
                    'spk_status' => 'onboarding_completed',
                    'catatan_spk' => DB::raw("CONCAT(IFNULL(catatan_spk, ''), '\nOnboarding selesai: " . now() . "')")
                ]);

            DB::commit();

            return redirect()->route('admin.onboarding.show', [$lowonganId, $onboarding->id])
                ->with('success', 'Data onboarding berhasil disimpan. NIP karyawan: ' . ($onboarding->nip ?? 'Tersedia'));
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Store Onboarding Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan data onboarding: ' . $e->getMessage());
        }
    }

    public function adminOnboardingShow($lowonganId, $onboardingId)
    {
        $lowongan = Lowongan::findOrFail($lowonganId);
        $onboarding = Onboarding::with(['pelamar', 'hiring', 'hiring.offering'])->findOrFail($onboardingId);

        return view('admin.onboarding.show', compact('lowongan', 'onboarding'));
    }

    public function pelamarStatus()
    {
        $user = Auth::user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')->with('error', 'Data pelamar tidak ditemukan');
        }

        $offering = OfferingPenawaran::with(['lowongan'])
            ->where('pelamar_id', $pelamar->id)
            ->where('status', 'accepted')
            ->first();

        $hiring = null;
        $onboarding = null;

        if ($offering) {
            $hiring = Hiring::where('offering_id', $offering->id)->first();
            if ($hiring) {
                $onboarding = Onboarding::where('hiring_id', $hiring->id)->first();
            }
        }

        return view('pelamar.status.index', compact('offering', 'hiring', 'onboarding'));
    }
}
