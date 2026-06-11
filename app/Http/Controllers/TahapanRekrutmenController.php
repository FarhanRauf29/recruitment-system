<?php

namespace App\Http\Controllers;

use App\Models\TahapanRekrutmen;
use Illuminate\Http\Request;

class TahapanRekrutmenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TahapanRekrutmen::orderBy('urutan');

        if ($search = $request->get('search')) {
            $query->where('nama_tahapan', 'like', "%{$search}%");
        }

        $tahapanrekrutmens = $query->paginate(10)->withQueryString();
        return view('admin.tahapanrekrutmen.index', compact('tahapanrekrutmens'));
    }

    public function create()
    {
        $statusOptions = TahapanRekrutmen::statusOptions();
        return view('admin.tahapanrekrutmen.create', compact('statusOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_tahapan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        TahapanRekrutmen::create($request->all());

        return redirect()->route('admin.tahapanrekrutmen.index')->with('success', 'Tahapan rekrutmen berhasil ditambahkan.');
    }

    public function show(TahapanRekrutmen $tahapanrekrutmen)
    {
        return view('admin.tahapanrekrutmen.show', compact('tahapanrekrutmen'));
    }

    public function edit(TahapanRekrutmen $tahapanrekrutmen)
    {
        $statusOptions = TahapanRekrutmen::statusOptions();
        return view('admin.tahapanrekrutmen.edit', compact('tahapanrekrutmen', 'statusOptions'));
    }

    public function update(Request $request, TahapanRekrutmen $tahapanrekrutmen)
    {
        $request->validate([
            'nama_tahapan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:1',
            'status' => 'required|in:aktif,tidak_aktif',
        ]);

        $tahapanrekrutmen->update($request->all());

        return redirect()->route('admin.tahapanrekrutmen.index')->with('success', 'Tahapan rekrutmen berhasil diperbarui.');
    }

    public function destroy(TahapanRekrutmen $tahapanrekrutmen)
    {
        if ($tahapanrekrutmen->pelamarTahapans()->exists()) {
            return redirect()->back()->with('error', 'Tahapan tidak dapat dihapus karena sudah digunakan.');
        }

        $tahapanrekrutmen->delete();

        return redirect()->route('admin.tahapanrekrutmen.index')->with('success', 'Tahapan rekrutmen berhasil dihapus.');
    }
}
