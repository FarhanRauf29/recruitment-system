@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div
                        class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-alt me-2"></i>
                            <h4 class="d-inline-block mb-0">Detail Berkas Pelamar</h4>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.berkas.index') }}" class="btn btn-light">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
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

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm"
                                    style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                                    <div class="card-header bg-transparent border-0">
                                        <h5 class="mb-0 text-primary">
                                            <i class="fas fa-user-circle me-2"></i> Informasi Pelamar
                                        </h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar me-3">
                                                <img src="{{ $berkas->pelamar->foto_profil ? asset('storage/' . $berkas->pelamar->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($berkas->pelamar->nama_lengkap ?? 'User') . '&background=3498db&color=fff' }}"
                                                    class="rounded-circle" width="60" height="60" alt="Foto Profil">
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $berkas->pelamar->nama_lengkap ?? 'Tidak diketahui' }}
                                                </h5>
                                                <p class="text-muted mb-0"><i class="fas fa-phone me-1"></i>
                                                    {{ $berkas->pelamar->nomor_handphone ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="row mt-3 pt-3 border-top">
                                            <div class="col-12">
                                                <div class="p-2 rounded-3">
                                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                    <span class="text-muted">Diunggah:</span>
                                                    <strong>
                                                        {{ $berkas->created_at ? $berkas->created_at->translatedFormat('d F Y, H:i') : '-' }}
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card h-100 border-0 shadow-sm"
                                    style="background: {{ $berkas->status_verifikasi == 'Diterima' ? 'linear-gradient(135deg, #e8f5e9 0%, #ffffff 100%)' : ($berkas->status_verifikasi == 'Ditolak' ? 'linear-gradient(135deg, #ffebee 0%, #ffffff 100%)' : 'linear-gradient(135deg, #fff8e1 0%, #ffffff 100%)') }}">
                                    <div class="card-header bg-transparent border-0">
                                        <h5 class="mb-0 text-primary">
                                            <i class="fas fa-clipboard-check me-2"></i> Status Verifikasi
                                        </h5>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                                            <span
                                                class="badge rounded-pill fs-6 p-2 {{ $berkas->status_verifikasi == 'Diterima' ? 'bg-success' : ($berkas->status_verifikasi == 'Ditolak' ? 'bg-danger' : 'bg-warning') }}">
                                                <i
                                                    class="fas {{ $berkas->status_verifikasi == 'Diterima' ? 'fa-check-circle' : ($berkas->status_verifikasi == 'Ditolak' ? 'fa-times-circle' : 'fa-clock') }} me-1"></i>
                                                {{ $berkas->status_verifikasi }}
                                            </span>
                                            <span class="text-muted small">
                                                <i class="fas fa-sync-alt me-1"></i> Terakhir:
                                                {{ $berkas->updated_at ? $berkas->updated_at->translatedFormat('d F Y H:i') : '-' }}
                                            </span>
                                        </div>

                                        @if ($berkas->tanggal_verifikasi)
                                            <div class="p-3 rounded-3 border" style="background: #f8f4ea;">
                                                <span
                                                    class="{{ $berkas->status_verifikasi == 'Diterima' ? 'text-success' : 'text-success' }}">
                                                    <i class="fas fa-check-circle me-1"></i> Diverifikasi:
                                                    {{ $berkas->tanggal_verifikasi ? $berkas->tanggal_verifikasi->translatedFormat('d F Y H:i') : '-' }}
                                                </span>
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <h6 class="mb-1 small text-muted">Catatan Admin:</h6>
                                            <div class="p-3 bg-white rounded-3 border">
                                                {{ $berkas->catatan_admin ?? 'Tidak ada catatan' }}
                                            </div>
                                        </div>

                                        <a href="{{ route('admin.berkas.edit', $berkas->id) }}"
                                            class="btn btn-warning text-white w-20">
                                            <i class="fas fa-edit me-1"></i> Edit Status Verifikasi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm"
                            style="background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);">
                            <div class="card-header bg-transparent border-0">
                                <h5 class="mb-0 text-primary">
                                    <i class="fas fa-folder-open me-2"></i> Dokumen Lamaran
                                </h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="row g-3">
                                    @php
                                        $documents = [
                                            'cv_file' => ['CV / Resume', 'fas fa-file-user', 'bg-info'],
                                            'surat_lamaran_file' => [
                                                'Surat Lamaran',
                                                'fas fa-envelope-open-text',
                                                'bg-primary',
                                            ],
                                            'ijazah_file' => ['Ijazah', 'fas fa-graduation-cap', 'bg-success'],
                                            'transkrip_nilai_file' => [
                                                'Transkrip Nilai',
                                                'fas fa-list-alt',
                                                'bg-warning',
                                            ],
                                            'ktp_file' => ['KTP', 'fas fa-id-card', 'bg-secondary'],
                                            'pas_foto_file' => ['Pas Foto', 'fas fa-camera', 'bg-purple'],
                                            'sertifikat_file' => ['Sertifikat', 'fas fa-certificate', 'bg-danger'],
                                            'skck_file' => ['SKCK', 'fas fa-file-contract', 'bg-dark'],
                                            'surat_keterangan_sehat_file' => [
                                                'Surat Sehat',
                                                'fas fa-heartbeat',
                                                'bg-pink',
                                            ],
                                            'portofolio_file' => ['Portofolio', 'fas fa-briefcase', 'bg-indigo'],
                                            'kartu_keluarga_file' => ['Kartu Keluarga', 'fas fa-users', 'bg-teal'],
                                            'dokumen_lain_file' => ['Dokumen Lain', 'fas fa-file-archive', 'bg-brown'],
                                        ];
                                    @endphp
                                    @foreach ($documents as $field => [$label, $icon, $color])
                                        <div class="col-md-4 mb-3">
                                            <div class="document-card card h-100 border-0 shadow-sm"
                                                style="background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-start">
                                                        <div
                                                            class="icon-container rounded-circle {{ $color }} me-3">
                                                            <i class="{{ $icon }} text-white"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">{{ $label }}</h6>
                                                            @if ($berkas->$field && Storage::disk('public')->exists($berkas->$field))
                                                                <div class="d-flex gap-2 mt-2 flex-wrap">
                                                                    <a href="{{ asset('storage/' . $berkas->$field) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">
                                                                        <i class="fas fa-eye me-1"></i> Lihat
                                                                    </a>
                                                                    <a href="{{ asset('storage/' . $berkas->$field) }}"
                                                                        download class="btn btn-sm btn-primary">
                                                                        <i class="fas fa-download me-1"></i> Unduh
                                                                    </a>
                                                                </div>
                                                                @php
                                                                    $fullPath = storage_path(
                                                                        'app/public/' . $berkas->$field,
                                                                    );
                                                                    $fileSize = file_exists($fullPath)
                                                                        ? round(filesize($fullPath) / 1024) . ' KB'
                                                                        : 'Ukuran tidak diketahui';
                                                                @endphp
                                                                <small class="d-block text-muted mt-1">Ukuran:
                                                                    {{ $fileSize }}</small>
                                                            @else
                                                                <span class="badge bg-light text-dark mt-2">
                                                                    <i class="fas fa-times-circle me-1"></i> Belum Diupload
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%) !important;
        }

        .avatar img {
            object-fit: cover;
            border: 3px solid #e0e0e0;
        }

        .icon-container {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .bg-pink {
            background-color: #e83e8c !important;
        }

        .bg-indigo {
            background-color: #6610f2 !important;
        }

        .bg-teal {
            background-color: #20c997 !important;
        }

        .bg-brown {
            background-color: #795548 !important;
        }

        .document-card {
            transition: all 0.3s ease;
        }

        .document-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
        }

        .btn-warning.text-white:hover {
            background-color: #e0a800;
            border-color: #d39e00;
        }

        .btn-warning.text-white {
            color: #ffffff !important;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .card-header .btn-warning {
            font-size: 0.9rem;
        }

        .rounded-3 {
            border-radius: 0.5rem !important;
        }

        .badge {
            font-weight: 500;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                let alerts = document.querySelectorAll('.alert-dismissible');
                alerts.forEach(function(alert) {
                    let bsAlert = new bootstrap.Alert(alert);
                    setTimeout(function() {
                        bsAlert.close();
                    }, 5000);
                });
            }, 1000);
        });
    </script>
@endsection
