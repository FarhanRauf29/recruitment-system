@extends('layouts.admin')
@section('title', 'Tambah Lowongan')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Lowongan Baru
                        </h3>
                        <p class="small mb-0 opacity-75">Buat lowongan pekerjaan baru</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.lowongan.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Judul Lowongan <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-tag text-primary"></i></span>
                                            <input type="text" name="judul_lowongan"
                                                class="form-control @error('judul_lowongan') is-invalid @enderror"
                                                value="{{ old('judul_lowongan') }}" required>
                                        </div>
                                        @error('judul_lowongan')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Deskripsi <span
                                                class="text-danger">*</span></label>
                                        <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" required>{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Persyaratan <span
                                                class="text-danger">*</span></label>
                                        <textarea name="persyaratan" class="form-control @error('persyaratan') is-invalid @enderror" rows="4" required>{{ old('persyaratan') }}</textarea>
                                        @error('persyaratan')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal Buka <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-calendar-alt text-primary"></i></span>
                                            <input type="date" name="tanggal_buka"
                                                class="form-control @error('tanggal_buka') is-invalid @enderror"
                                                value="{{ old('tanggal_buka') }}" required>
                                        </div>
                                        @error('tanggal_buka')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal Tutup <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-calendar-times text-danger"></i></span>
                                            <input type="date" name="tanggal_tutup"
                                                class="form-control @error('tanggal_tutup') is-invalid @enderror"
                                                value="{{ old('tanggal_tutup') }}" required>
                                        </div>
                                        @error('tanggal_tutup')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Slot Lowongan <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-users text-primary"></i></span>
                                            <input type="number" name="slot"
                                                class="form-control @error('slot') is-invalid @enderror"
                                                value="{{ old('slot', 0) }}" min="0" required>
                                        </div>
                                        @error('slot')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Status <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-toggle-on text-primary"></i></span>
                                            <select name="status" class="form-select @error('status') is-invalid @enderror"
                                                required>
                                                <option value="">-- Pilih Status --</option>
                                                @foreach ($statusOptions as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ old('status') == $value ? 'selected' : '' }}>
                                                        {{ $label }}</option>
                                                @endforeach
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
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>
                                    Simpan</button>
                                <a href="{{ route('admin.lowongan.index') }}" class="btn btn-secondary"><i
                                        class="fas fa-arrow-left me-1"></i> Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
