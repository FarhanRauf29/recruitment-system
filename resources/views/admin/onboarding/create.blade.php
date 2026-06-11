@extends('layouts.admin')

@section('title', 'Data Onboarding Karyawan')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3 px-4">
                        <h4 class="card-title mb-0 fw-semibold">
                            <i class="fas fa-user-plus me-2"></i> Data Onboarding Karyawan
                        </h4>
                        <small class="opacity-75 mt-1 d-block">
                            <i class="fas fa-user me-1"></i> Kandidat: {{ $hiring->pelamar->nama_lengkap }} |
                            <i class="fas fa-briefcase me-1 ms-2"></i> Lowongan: {{ $lowongan->judul_lowongan }}
                        </small>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('admin.onboarding.store', [$lowongan->id, $hiring->id]) }}" method="POST">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-building text-primary me-1"></i> Divisi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="divisi"
                                        class="form-control @error('divisi') is-invalid @enderror"
                                        value="{{ old('divisi') }}" placeholder="Contoh: Teknologi Informasi" required>
                                    @error('divisi')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user-tie text-primary me-1"></i> Atasan Langsung
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="atasan_langsung"
                                        class="form-control @error('atasan_langsung') is-invalid @enderror"
                                        value="{{ old('atasan_langsung') }}" placeholder="Nama atasan langsung" required>
                                    @error('atasan_langsung')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-map-marker-alt text-primary me-1"></i> Lokasi Kerja
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="lokasi_kerja"
                                        class="form-control @error('lokasi_kerja') is-invalid @enderror"
                                        value="{{ old('lokasi_kerja') }}"
                                        placeholder="Contoh: Jakarta, Gedung Utama Lantai 5" required>
                                    @error('lokasi_kerja')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-calendar-alt text-primary me-1"></i> Tanggal Masuk
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="tanggal_masuk"
                                        class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                        value="{{ old('tanggal_masuk') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('tanggal_masuk')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-envelope text-primary me-1"></i> Email Kantor
                                    </label>
                                    <input type="email" name="email_kantor"
                                        class="form-control @error('email_kantor') is-invalid @enderror"
                                        value="{{ old('email_kantor') }}" placeholder="email@perusahaan.com">
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-info-circle me-1"></i> Email resmi perusahaan
                                    </small>
                                    @error('email_kantor')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-phone text-primary me-1"></i> No Telepon Kantor
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">+62</span>
                                        <input type="text" name="no_telepon_kantor"
                                            class="form-control @error('no_telepon_kantor') is-invalid @enderror"
                                            value="{{ old('no_telepon_kantor') }}" placeholder="81234567890">
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-info-circle me-1"></i> Nomor telepon kantor/extensi
                                    </small>
                                    @error('no_telepon_kantor')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-sticky-note text-primary me-1"></i> Catatan
                                    </label>
                                    <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="4"
                                        placeholder="Tambahkan catatan penting untuk onboarding...">{{ old('catatan') }}</textarea>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-lock me-1"></i> Catatan hanya terlihat oleh internal HR
                                    </small>
                                    @error('catatan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                                    <i class="fas fa-save me-2"></i> Simpan & Generate NIP
                                </button>
                                <a href="{{ route('admin.hiring.index', $lowongan->id) }}"
                                    class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                    <i class="fas fa-arrow-left me-2"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .form-control:focus {
                border-color: #86b7fe;
                box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
            }

            .btn {
                transition: all 0.2s ease;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .rounded-3 {
                border-radius: 12px !important;
            }
        </style>
    @endpush
@endsection
