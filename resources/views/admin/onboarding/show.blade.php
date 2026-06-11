@extends('layouts.admin')

@section('title', 'Detail Onboarding')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div
                        class="card-header bg-primary text-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="fas fa-user-plus me-2"></i> Detail Onboarding Karyawan
                            </h5>
                            <small class="opacity-75 mt-1 d-block">Informasi lengkap data onboarding karyawan</small>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.onboarding.index', $lowongan->id) }}"
                                class="btn btn-light btn-sm px-3 py-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="alert alert-info bg-light border-start border-4 border-info mb-4 py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle text-info me-3 fs-5"></i>
                                <div>
                                    <span class="text-muted small d-block">Karyawan</span>
                                    <strong class="fs-6">{{ $onboarding->pelamar->nama_lengkap }}</strong>
                                    <br><small class="text-muted">{{ $onboarding->pelamar->email }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3 mb-4">
                                    <div class="card-header bg-white border-bottom pb-2 pt-3 px-4">
                                        <h6 class="fw-bold mb-0 text-primary">
                                            <i class="fas fa-id-card me-2"></i> Informasi Karyawan
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-qrcode text-primary me-2"></i>
                                                NIP
                                            </div>
                                            <div class="detail-value fw-semibold">{{ $onboarding->nip }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-building text-primary me-2"></i>
                                                Divisi
                                            </div>
                                            <div class="detail-value ">{{ $onboarding->divisi }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-user-tie text-primary me-2"></i>
                                                Nama Atasan
                                            </div>
                                            <div class="detail-value">{{ $onboarding->atasan_langsung }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                                Lokasi Kerja
                                            </div>
                                            <div class="detail-value">{{ $onboarding->lokasi_kerja }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                Tanggal Masuk
                                            </div>
                                            <div class="detail-value">
                                                {{ \Carbon\Carbon::parse($onboarding->tanggal_masuk)->translatedFormat('d F Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3 mb-4">
                                    <div class="card-header bg-white border-bottom pb-2 pt-3 px-4">
                                        <h6 class="fw-bold mb-0 text-primary">
                                            <i class="fas fa-phone-alt me-2"></i> Kontak Kantor
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        @if ($onboarding->email_kantor)
                                            <div class="detail-item">
                                                <div class="detail-label">
                                                    <i class="fas fa-envelope text-primary me-2"></i>
                                                    Email Kantor
                                                </div>
                                                <div class="detail-value">{{ $onboarding->email_kantor }}</div>
                                            </div>
                                        @endif
                                        @if ($onboarding->no_telepon_kantor)
                                            <div class="detail-item">
                                                <div class="detail-label">
                                                    <i class="fas fa-phone text-primary me-2"></i>
                                                    No Telepon Kantor
                                                </div>
                                                <div class="detail-value">+62 {{ $onboarding->no_telepon_kantor }}</div>
                                            </div>
                                        @endif
                                        @if (!$onboarding->email_kantor && !$onboarding->no_telepon_kantor)
                                            <div class="text-center py-4">
                                                <i class="fas fa-phone-slash fa-3x text-muted mb-2 d-block opacity-25"></i>
                                                <p class="text-muted mb-0">Belum ada data kontak kantor</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm rounded-3">
                                    <div class="card-header bg-white border-bottom pb-2 pt-3 px-4">
                                        <h6 class="fw-bold mb-0 text-primary">
                                            <i class="fas fa-info-circle me-2"></i> Informasi Lainnya
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-clock text-primary me-2"></i>
                                                Status
                                            </div>
                                            <div class="detail-value">
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                    <i class="fas fa-check-circle me-1"></i> Selesai
                                                </span>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-calendar-check text-primary me-2"></i>
                                                Tanggal Selesai
                                            </div>
                                            <div class="detail-value">
                                                {{ \Carbon\Carbon::parse($onboarding->tanggal_selesai)->translatedFormat('d F Y, H:i') }}
                                                WIB</div>
                                        </div>
                                        @if ($onboarding->catatan)
                                            <div class="detail-item">
                                                <div class="detail-label">
                                                    <i class="fas fa-sticky-note text-primary me-2"></i>
                                                    Catatan
                                                </div>
                                                <div class="detail-value">{{ $onboarding->catatan }}</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-3">
                            <a href="{{ route('admin.onboarding.index', $lowongan->id) }}"
                                class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                            <a href="#" class="btn btn-info px-4 py-2 rounded-pill" onclick="window.print()">
                                <i class="fas fa-print me-2"></i> Cetak Data
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .detail-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 12px 0;
                border-bottom: 1px solid #e9ecef;
            }

            .detail-item:last-child {
                border-bottom: none;
            }

            .detail-label {
                font-size: 0.85rem;
                color: #6c757d;
                min-width: 140px;
            }

            .detail-value {
                flex: 1;
                text-align: right;
                color: #2c3e50;
            }

            .rounded-3 {
                border-radius: 12px !important;
            }

            .bg-opacity-10 {
                --bs-bg-opacity: 0.1;
            }

            @media (max-width: 768px) {
                .detail-item {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 5px;
                }

                .detail-value {
                    text-align: left;
                    width: 100%;
                }
            }
        </style>
    @endpush
@endsection
