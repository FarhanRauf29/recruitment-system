@extends('layouts.admin')
@section('title', 'Edit Kategori Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i> Edit Kategori Psikotes
                        </h4>
                        <p class="small mb-0 mt-1 opacity-75">Perbarui informasi kategori psikotes</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.kategoripsikotes.update', $kategori->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Kode Kategori <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-code text-primary"></i></span>
                                            <input type="text" name="kode_kategori"
                                                class="form-control @error('kode_kategori') is-invalid @enderror"
                                                value="{{ old('kode_kategori', $kategori->kode_kategori) }}" required>
                                        </div>
                                        <small class="text-muted">Kode unik untuk kategori psikotes</small>
                                        @error('kode_kategori')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nama Kategori <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-tag text-primary"></i></span>
                                            <input type="text" name="nama_kategori"
                                                class="form-control @error('nama_kategori') is-invalid @enderror"
                                                value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                                        </div>
                                        @error('nama_kategori')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Urutan</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-sort-numeric-down text-primary"></i></span>
                                            <input type="number" name="urutan"
                                                class="form-control @error('urutan') is-invalid @enderror"
                                                value="{{ old('urutan', $kategori->urutan) }}" min="0">
                                        </div>
                                        @error('urutan')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jenis <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-layer-group text-primary"></i></span>
                                            <select name="jenis" class="form-select @error('jenis') is-invalid @enderror"
                                                required>
                                                <option value="kognitif"
                                                    {{ old('jenis', $kategori->jenis) == 'kognitif' ? 'selected' : '' }}>
                                                    Kognitif</option>
                                                <option value="kepribadian"
                                                    {{ old('jenis', $kategori->jenis) == 'kepribadian' ? 'selected' : '' }}>
                                                    Kepribadian</option>
                                                <option value="minat"
                                                    {{ old('jenis', $kategori->jenis) == 'minat' ? 'selected' : '' }}>Minat
                                                </option>
                                            </select>
                                        </div>
                                        @error('jenis')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Deskripsi</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-align-left text-primary"></i></span>
                                            <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                                        </div>
                                        @error('deskripsi')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan
                                    Perubahan</button>
                                <a href="{{ route('admin.kategoripsikotes.index') }}" class="btn btn-secondary"><i
                                        class="fas fa-arrow-left me-1"></i> Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
