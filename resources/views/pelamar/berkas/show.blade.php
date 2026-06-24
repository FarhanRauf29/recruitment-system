@extends('layouts.pelamar')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0"><i class="fas fa-file-alt text-primary me-2"></i>Detail Berkas Saya</h2>
            <a href="{{ route('pelamar.berkas.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Status Berkas</h5>
                <span
                    class="badge rounded-pill py-2 px-3 fs-6 {{ $berkas->status_verifikasi == 'Diterima' ? 'bg-success' : ($berkas->status_verifikasi == 'Ditolak' ? 'bg-danger' : 'bg-warning') }}">
                    <i
                        class="fas {{ $berkas->status_verifikasi == 'Diterima' ? 'fa-check-circle' : ($berkas->status_verifikasi == 'Ditolak' ? 'fa-times-circle' : 'fa-clock') }} me-1"></i>
                    {{ $berkas->status_verifikasi }}
                </span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-light rounded-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded me-3">
                                <i class="fas fa-calendar-alt text-primary fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted small">Tanggal Upload</p>
                                <p class="mb-0 fw-bold">
                                    {{ $berkas->created_at ? $berkas->created_at->translatedFormat('d F Y H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center p-3 bg-light rounded-3">
                            <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                                <i class="fas fa-sync-alt text-info fs-4"></i>
                            </div>
                            <div>
                                <p class="mb-0 text-muted small">Terakhir Diperbarui</p>
                                <p class="mb-0 fw-bold">
                                    {{ $berkas->updated_at ? $berkas->updated_at->translatedFormat('d F Y H:i') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                    @if ($berkas->tanggal_verifikasi)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                                    <i class="fas fa-check-circle text-success fs-4"></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted small">Tanggal Verifikasi</p>
                                    <p class="mb-0 fw-bold">
                                        {{ $berkas->tanggal_verifikasi ? $berkas->tanggal_verifikasi->translatedFormat('d F Y H:i') : '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if ($berkas->catatan_admin)
                    <div
                        class="alert {{ $berkas->status_verifikasi == 'Diterima' ? 'alert-success' : ($berkas->status_verifikasi == 'Ditolak' ? 'alert-danger' : 'alert-warning') }} mt-3">
                        <div class="d-flex align-items-center">
                            <i
                                class="fas {{ $berkas->status_verifikasi == 'Diterima' ? 'fa-check-circle' : ($berkas->status_verifikasi == 'Ditolak' ? 'fa-times-circle' : 'fa-info-circle') }} me-2 fs-4"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Catatan Admin</h6>
                                <p class="mb-0">{{ $berkas->catatan_admin }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-folder-open text-primary me-2"></i>Dokumen Saya</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    @foreach ([
            'cv_file' => ['Curriculum Vitae (CV)', 'fas fa-file-user text-info'],
            'surat_lamaran_file' => ['Surat Lamaran', 'fas fa-envelope-open-text text-primary'],
            'ijazah_file' => ['Ijazah', 'fas fa-graduation-cap text-success'],
            'transkrip_nilai_file' => ['Transkrip Nilai', 'fas fa-list-alt text-warning'],
            'ktp_file' => ['KTP', 'fas fa-id-card text-secondary'],
            'pas_foto_file' => ['Pas Foto', 'fas fa-camera text-danger'],
            'sertifikat_file' => ['Sertifikat', 'fas fa-certificate text-purple'],
            'skck_file' => ['SKCK', 'fas fa-file-contract text-dark'],
            'surat_keterangan_sehat_file' => ['Surat Keterangan Sehat', 'fas fa-heartbeat text-pink'],
            'portofolio_file' => ['Portofolio', 'fas fa-briefcase text-indigo'],
            'kartu_keluarga_file' => ['Kartu Keluarga', 'fas fa-users text-teal'],
            'dokumen_lain_file' => ['Dokumen Lainnya', 'fas fa-file-archive text-brown'],
        ] as $field => [$label, $icon])
                        <div class="col-md-6 col-lg-4">
                            <div
                                class="doc-card h-100 border rounded p-3 {{ $berkas->$field ? 'border-primary border-opacity-25' : 'border-light' }}">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="me-3"><i class="{{ $icon }} fs-3"></i></div>
                                    <h6 class="mb-0">{{ $label }}</h6>
                                </div>
                                @if ($berkas->$field && Storage::disk('public')->exists($berkas->$field))
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <a href="{{ asset('storage/' . $berkas->$field) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> Lihat
                                        </a>
                                        <a href="{{ asset('storage/' . $berkas->$field) }}" download
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-download me-1"></i> Unduh
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-3">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-exclamation-circle me-1"></i> Belum diunggah
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <style>
        .doc-card {
            transition: all 0.3s ease;
        }

        .doc-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-color: var(--bs-primary) !important;
        }

        .text-purple {
            color: #6f42c1 !important;
        }

        .text-pink {
            color: #e83e8c !important;
        }

        .text-indigo {
            color: #6610f2 !important;
        }

        .text-teal {
            color: #20c997 !important;
        }

        .text-brown {
            color: #795548 !important;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }
    </style>
@endsection
