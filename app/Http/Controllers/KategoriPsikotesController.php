<?php

namespace App\Http\Controllers;

use App\Models\KategoriPsikotes;
use Illuminate\Http\Request;

class KategoriPsikotesController extends Controller
{
    public function index()
    {
        $kategoris = KategoriPsikotes::latest()->paginate(10);
        return view('admin.kategoripsikotes.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategoripsikotes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'kode_kategori' => 'required|string|unique:kategori_psikotes,kode_kategori',
            'jenis' => 'required|in:kognitif,kepribadian,minat',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:0',
        ]);

        KategoriPsikotes::create($request->all());

        return redirect()->route('admin.kategoripsikotes.index')
            ->with('success', 'Kategori psikotes berhasil dibuat.');
    }

    public function show($id)
    {
        $kategori = KategoriPsikotes::findOrFail($id);
        return view('admin.kategoripsikotes.show', compact('kategori'));
    }

    public function edit($id)
    {
        $kategori = KategoriPsikotes::findOrFail($id);
        return view('admin.kategoripsikotes.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'kode_kategori' => 'required|string|unique:kategori_psikotes,kode_kategori,' . $id,
            'jenis' => 'required|in:kognitif,kepribadian,minat',
            'deskripsi' => 'nullable|string',
            'urutan' => 'required|integer|min:0',
        ]);

        $kategori = KategoriPsikotes::findOrFail($id);
        $kategori->update($request->all());

        return redirect()->route('admin.kategoripsikotes.index')
            ->with('success', 'Kategori psikotes berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriPsikotes::findOrFail($id);

        // Cek apakah kategori masih digunakan
        if ($kategori->soalPsikotes()->count() > 0) {
            return redirect()->route('admin.kategoripsikotes.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh soal psikotes.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategoripsikotes.index')
            ->with('success', 'Kategori psikotes berhasil dihapus.');
    }
}
