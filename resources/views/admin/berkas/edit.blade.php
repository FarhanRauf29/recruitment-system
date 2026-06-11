@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-clipboard-check me-2"></i> Verifikasi Berkas Pelamar
                        </h4>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2 fs-4"></i>
                                    <div>
                                        <h5 class="alert-heading mb-2">Terjadi kesalahan!</h5>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 fs-4"></i>
                            <div>
                                <strong>Petunjuk Verifikasi:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Anda hanya dapat mengubah status verifikasi dan memberikan catatan</li>
                                    <li>Pastikan memeriksa semua dokumen sebelum menentukan status</li>
                                    <li>Berikan catatan yang jelas jika status "Ditolak"</li>
                                </ul>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold">
                                <i class="fas fa-user me-2"></i> Informasi Pelamar
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $berkas->pelamar->nama_lengkap ?? 'Tidak diketahui' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $berkas->pelamar->user?->email ?? ($berkas->pelamar->email ?? 'Email tidak tersedia') }}"
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">No. Telepon</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $berkas->pelamar->no_telepon ?? '-' }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Tanggal Upload</label>
                                        <input type="text" class="form-control bg-light"
                                            value="{{ $berkas->created_at ? $berkas->created_at->format('l, d F Y') : '-' }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.berkas.update', $berkas->id) }}" method="POST" id="verifikasiForm">
                            @csrf
                            @method('PUT')

                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="fas fa-clipboard-check me-2"></i> Form Verifikasi
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Status Verifikasi</label>
                                                <select name="status_verifikasi" class="form-select" required>
                                                    <option value="Pending"
                                                        {{ $berkas->status_verifikasi == 'Pending' ? 'selected' : '' }}>
                                                        Pending
                                                    </option>
                                                    <option value="Diterima"
                                                        {{ $berkas->status_verifikasi == 'Diterima' ? 'selected' : '' }}>
                                                        Diterima
                                                    </option>
                                                    <option value="Ditolak"
                                                        {{ $berkas->status_verifikasi == 'Ditolak' ? 'selected' : '' }}>
                                                        Ditolak
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-0">
                                                <label class="form-label fw-bold">Catatan Admin</label>
                                                <textarea name="catatan_admin" class="form-control" rows="3" placeholder="Berikan catatan jika diperlukan">{{ old('catatan_admin', $berkas->catatan_admin) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('admin.berkas.index') }}" class="btn btn-outline-secondary me-md-2">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save me-1"></i> Simpan Verifikasi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .accordion-button:not(.collapsed) {
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--bs-primary);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>

    @push('scripts')
        <script>
            document.getElementById('verifikasiForm').addEventListener('submit', function() {
                const submitBtn = document.getElementById('submitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            });
        </script>
    @endpush
@endsection
