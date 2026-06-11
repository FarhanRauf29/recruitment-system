<?php

namespace App\Http\Controllers;

use App\Models\AktivitasPsikotes;
use Illuminate\Http\Request;

class AktivitasPsikotesController extends Controller
{
    public function index()
    {
        $aktivitas = AktivitasPsikotes::with(['pelamar', 'sesi', 'soal'])->latest()->paginate(10);
        return view('admin.aktivitaspsikotes.index', compact('aktivitas'));
    }

    public function create()
    {
        // Biasanya dibuat otomatis saat aktivitas terjadi
        return redirect()->route('admin.aktivitaspsikotes.index');
    }

    public function store(Request $request)
    {
        // Biasanya dibuat otomatis saat aktivitas terjadi
        return redirect()->route('admin.aktivitaspsikotes.index');
    }

    public function show($id)
    {
        $aktivitas = AktivitasPsikotes::with(['pelamar', 'sesi', 'soal'])->findOrFail($id);
        return view('admin.aktivitaspsikotes.show', compact('aktivitas'));
    }

    public function edit($id)
    {
        // Biasanya tidak diedit manual
        return redirect()->route('admin.aktivitaspsikotes.show', $id);
    }

    public function update(Request $request, $id)
    {
        // Biasanya tidak diupdate manual
        return redirect()->route('admin.aktivitaspsikotes.show', $id);
    }

    public function destroy($id)
    {
        $aktivitas = AktivitasPsikotes::findOrFail($id);
        $aktivitas->delete();

        return redirect()->route('admin.aktivitaspsikotes.index')
            ->with('success', 'Aktivitas psikotes berhasil dihapus.');
    }
}
