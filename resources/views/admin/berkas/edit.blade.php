@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg">
                    <div
                        class="card-header bg-gradient-primary text-white d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h4 class="mb-0">
                            <i class="fas fa-clipboard-check me-2"></i> Verifikasi Berkas Pelamar
                        </h4>
                        <a href="{{ route('admin.berkas.show', $berkas->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
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

                        <div class="alert alert-info d-flex align-items-start p-3"
                            style="background: linear-gradient(135deg, #e3f2fd 0%, #ffffff 100%); border-left: 4px solid #1a73e8;">
                            <i class="fas fa-info-circle me-3 fs-4 text-primary mt-1"></i>
                            <div>
                                <strong>Petunjuk Verifikasi:</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    <li>Pilih status verifikasi yang sesuai</li>
                                    <li>Berikan catatan yang jelas jika diperlukan</li>
                                    <li>Status "Diterima" akan membuat pelamar lolos ke tahap selanjutnya</li>
                                    <li>Status "Ditolak" akan mengakhiri proses seleksi pelamar</li>
                                </ul>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm"
                                    style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 flex-shrink-0">
                                                <img src="{{ $berkas->pelamar->foto_profil ? asset('storage/' . $berkas->pelamar->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($berkas->pelamar->nama_lengkap ?? 'User') . '&background=3498db&color=fff' }}"
                                                    class="rounded-circle" width="50" height="50" alt="Foto">
                                            </div>
                                            <div class="flex-grow-1" style="min-width:0;">
                                                <h6 class="mb-1">{{ $berkas->pelamar->nama_lengkap ?? 'Tidak diketahui' }}
                                                </h6>
                                                <p class="text-muted mb-0 small"><i class="fas fa-envelope me-1"></i>
                                                    {{ $berkas->pelamar->email ?? '-' }}</p>
                                                <p class="text-muted mb-0 small"><i class="fas fa-phone me-1"></i>
                                                    {{ $berkas->pelamar->nomor_handphone ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm"
                                    style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                                    <div class="card-body p-3">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <div class="p-2 bg-white rounded-3 border">
                                                    <small class="text-muted d-block">Tanggal Upload</small>
                                                    <span
                                                        class="fw-semibold d-block mt-1 small">{{ $berkas->created_at ? $berkas->created_at->translatedFormat('d F Y H:i') : '-' }}</span>
                                                </div>
                                            </div>
                                            @if ($berkas->tanggal_verifikasi)
                                                <div class="col-12">
                                                    <div class="p-2 bg-white rounded-3 border border-success">
                                                        <small class="text-muted d-block">Tanggal Verifikasi</small>
                                                        <span
                                                            class="fw-semibold text-success d-block mt-1 small">{{ $berkas->tanggal_verifikasi ? $berkas->tanggal_verifikasi->translatedFormat('d F Y H:i') : '-' }}</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.berkas.update', $berkas->id) }}" method="POST" id="verifikasiForm">
                            @csrf
                            @method('PUT')

                            <div class="card border-0 shadow-sm mb-4"
                                style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                                <div class="card-header bg-transparent border-0 pb-0">
                                    <h5 class="mb-0 text-primary">
                                        <i class="fas fa-edit me-2"></i> Form Verifikasi
                                    </h5>
                                </div>
                                <div class="card-body pt-3">
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <div class="mb-0">
                                                <label class="form-label fw-bold">Pilih Status Verifikasi <span
                                                        class="text-danger">*</span></label>
                                                <select name="status_verifikasi" class="form-select" required>
                                                    <option value="Pending"
                                                        {{ $berkas->status_verifikasi == 'Pending' ? 'selected' : '' }}>
                                                        Pending - Menunggu Verifikasi
                                                    </option>
                                                    <option value="Diterima"
                                                        {{ $berkas->status_verifikasi == 'Diterima' ? 'selected' : '' }}>
                                                        Diterima - Berkas Lengkap
                                                    </option>
                                                    <option value="Ditolak"
                                                        {{ $berkas->status_verifikasi == 'Ditolak' ? 'selected' : '' }}>
                                                        Ditolak - Berkas Tidak Sesuai
                                                    </option>
                                                </select>
                                                <small class="text-muted d-block mt-1">Pilih status verifikasi untuk berkas
                                                    pelamar</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-0">
                                                <label class="form-label fw-bold d-none d-md-block">&nbsp;</label>
                                                <div class="p-2 bg-light rounded-3 border d-flex align-items-center"
                                                    style="min-height: 48px;">
                                                    <span class="text-muted me-2 small">Status saat ini:</span>
                                                    <span
                                                        class="badge {{ $berkas->status_verifikasi == 'Diterima' ? 'bg-success' : ($berkas->status_verifikasi == 'Ditolak' ? 'bg-danger' : 'bg-warning') }}">
                                                        <i
                                                            class="fas {{ $berkas->status_verifikasi == 'Diterima' ? 'fa-check-circle' : ($berkas->status_verifikasi == 'Ditolak' ? 'fa-times-circle' : 'fa-clock') }} me-1"></i>
                                                        {{ $berkas->status_verifikasi }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="mb-0">
                                                <label class="form-label fw-bold">Catatan Admin</label>
                                                <textarea name="catatan_admin" class="form-control" rows="3"
                                                    placeholder="Berikan catatan untuk pelamar (contoh: Dokumen lengkap, silakan lanjut ke tahap berikutnya)">{{ old('catatan_admin', $berkas->catatan_admin) }}</textarea>
                                                <small class="text-muted d-block mt-1">Catatan akan ditampilkan kepada
                                                    pelamar</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap gap-3 justify-content-end">
                                <button type="submit" class="btn btn-primary px-4" id="submitBtn"
                                    style="background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%); border: none;">
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
        .bg-gradient-primary {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%) !important;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #1a73e8;
            box-shadow: 0 0 0 0.25rem rgba(26, 115, 232, 0.25);
        }

        .btn-primary {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(26, 115, 232, 0.4);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-2px);
        }

        .rounded-3 {
            border-radius: 0.5rem !important;
        }

        .badge {
            font-weight: 500;
        }

        .alert-info {
            border-left: 4px solid #1a73e8;
        }

        .form-control {
            border-radius: 0.5rem;
            padding: 0.6rem 1rem;
        }

        textarea.form-control {
            min-height: 100px;
        }

        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1rem !important;
            }

            .card-body .p-3 {
                padding: 0.75rem !important;
            }

            .card-body .p-2 {
                padding: 0.5rem !important;
            }

            .btn {
                font-size: 0.875rem;
                padding: 0.4rem 0.8rem;
            }

            .d-flex.gap-3 {
                gap: 0.5rem !important;
            }

            .btn.px-4 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .form-select {
                font-size: 0.9rem;
                padding: 0.4rem 0.8rem;
            }

            .badge.fs-6 {
                font-size: 0.8rem !important;
            }

            .alert-info ul {
                padding-left: 1.2rem;
            }

            .alert-info ul li {
                font-size: 0.85rem;
            }

            .col-md-4 .p-2 {
                margin-top: 0.25rem;
            }

            .card-body .row.g-2 {
                margin-left: -0.25rem;
                margin-right: -0.25rem;
            }

            .card-body .row.g-2>[class*="col-"] {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }
        }

        @media (max-width: 768px) {
            .row.g-3>[class*="col-"] {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
        }

        @media (min-width: 768px) {
            .card-body .p-3 {
                padding: 1.25rem !important;
            }
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
