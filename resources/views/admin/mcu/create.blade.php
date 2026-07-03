@extends('layouts.admin')

@section('title', 'Tambah Jadwal Medical Check Up')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Jadwal Medical Check Up
                        </h4>
                        <p class="small mb-0 mt-1 opacity-75">Buat jadwal MCU untuk pelamar yang lolos tahap sebelumnya</p>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.mcu.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Pilih Pelamar & Lowongan <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-user text-primary"></i></span>
                                            <select name="pelamar_tahapan_id"
                                                class="form-select @error('pelamar_tahapan_id') is-invalid @enderror"
                                                required>
                                                <option value="">-- Pilih Pelamar --</option>
                                                @foreach ($pelamarTahapans as $pt)
                                                    <option value="{{ $pt->id }}"
                                                        {{ old('pelamar_tahapan_id') == $pt->id ? 'selected' : '' }}>
                                                        {{ $pt->pelamar->nama_lengkap }} -
                                                        {{ $pt->lowongan->judul_lowongan }}
                                                        ({{ $pt->tahapan->nama_tahapan }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($pelamarTahapans->isEmpty())
                                            <div class="alert alert-warning mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Semua pelamar yang lolos ke tahap MCU sudah memiliki jadwal MCU.
                                            </div>
                                        @endif
                                        @error('pelamar_tahapan_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal & Jam MCU <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-calendar-alt text-primary"></i></span>
                                            <input type="datetime-local" name="jadwal_mcu"
                                                class="form-control @error('jadwal_mcu') is-invalid @enderror"
                                                value="{{ old('jadwal_mcu') }}" required>
                                        </div>
                                        @error('jadwal_mcu')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Lokasi MCU <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-map-marker-alt text-primary"></i></span>
                                            <input type="text" name="lokasi_mcu"
                                                class="form-control @error('lokasi_mcu') is-invalid @enderror"
                                                value="{{ old('lokasi_mcu', '') }}" required>
                                        </div>
                                        @error('lokasi_mcu')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Alamat Lengkap Lokasi</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-address-card text-primary"></i></span>
                                            <textarea name="alamat_lokasi" class="form-control" rows="2">{{ old('alamat_lokasi') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nama Rumah Sakit</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-hospital text-primary"></i></span>
                                            <input type="text" name="nama_rs" class="form-control"
                                                value="{{ old('nama_rs') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Dokter Pemeriksa</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-user-md text-primary"></i></span>
                                            <input type="text" name="dokter_pemeriksa" class="form-control"
                                                value="{{ old('dokter_pemeriksa') }}" placeholder="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Keterangan Tambahan</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-info-circle text-primary"></i></span>
                                            <textarea name="keterangan" class="form-control" rows="2"
                                                placeholder="Persiapan MCU, dokumen yang perlu dibawa, dll">{{ old('keterangan') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan
                                    Jadwal</button>
                                <a href="{{ route('admin.mcu.index') }}" class="btn btn-secondary"><i
                                        class="fas fa-arrow-left me-1"></i> Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
