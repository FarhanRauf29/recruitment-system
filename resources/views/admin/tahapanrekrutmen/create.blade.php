@extends('layouts.admin')
@section('title', 'Tambah Tahapan Rekrutmen')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Tahapan Rekrutmen
                        </h3>
                        <p class="small mb-0 opacity-75">Buat tahapan baru untuk proses rekrutmen</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.tahapanrekrutmen.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="nama_tahapan" class="form-label fw-semibold">Nama Tahapan <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="fas fa-tag text-primary"></i>
                                            </span>
                                            <input type="text"
                                                class="form-control @error('nama_tahapan') is-invalid @enderror"
                                                id="nama_tahapan" name="nama_tahapan" value="{{ old('nama_tahapan') }}"
                                                placeholder="Contoh: Seleksi Administrasi" required>
                                        </div>
                                        @error('nama_tahapan')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="fas fa-align-left text-primary"></i>
                                            </span>
                                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3"
                                                placeholder="Jelaskan tahapan ini...">{{ old('deskripsi') }}</textarea>
                                        </div>
                                        @error('deskripsi')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="urutan" class="form-label fw-semibold">Urutan <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="fas fa-sort-numeric-down text-primary"></i>
                                            </span>
                                            <input type="number" class="form-control @error('urutan') is-invalid @enderror"
                                                id="urutan" name="urutan" min="1" value="{{ old('urutan', 1) }}"
                                                required>
                                        </div>
                                        <small class="text-muted">Urutan proses rekrutmen (1, 2, 3, ...)</small>
                                        @error('urutan')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label fw-semibold">Status <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white">
                                                <i class="fas fa-toggle-on text-primary"></i>
                                            </span>
                                            <select class="form-select @error('status') is-invalid @enderror" id="status"
                                                name="status" required>
                                                <option value="">Pilih Status</option>
                                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>
                                                    Aktif</option>
                                                <option value="tidak_aktif"
                                                    {{ old('status') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif
                                                </option>
                                            </select>
                                        </div>
                                        @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>
                                <a href="{{ route('admin.tahapanrekrutmen.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        .form-control,
        .form-select {
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
        }

        .input-group-text {
            border: 1px solid #dee2e6;
        }
    </style>
@endsection
