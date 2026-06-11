@extends('layouts.admin')

@section('title', 'Detail Penawaran Kerja')

@section('content')
    <div class="container-fluid py-4 bg-light">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-primary text-white py-4 px-5">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h4 class="mb-1 fw-bold">
                                    <i class="fas fa-file-contract me-2"></i>
                                    Detail Penawaran Kerja
                                </h4>
                                <p class="mb-0 small opacity-75">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Informasi lengkap mengenai penawaran kerja yang telah dikirim kepada kandidat
                                </p>
                            </div>
                            <div>
                                <a href="{{ route('admin.offering.index', $lowongan->id) }}"
                                    class="btn btn-light rounded-pill px-4">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-5 pb-5 pt-4">
                        <!-- Status Banner -->
                        <div
                            class="alert mb-4 p-4 rounded-4 border-0
                            {{ $offering->status == 'accepted'
                                ? 'bg-success bg-opacity-10 border-start border-4 border-success'
                                : ($offering->status == 'sent'
                                    ? 'bg-warning bg-opacity-10 border-start border-4 border-warning'
                                    : 'bg-danger bg-opacity-10 border-start border-4 border-danger') }}">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div
                                        class="rounded-circle p-3
                                        {{ $offering->status == 'accepted'
                                            ? 'bg-success bg-opacity-25'
                                            : ($offering->status == 'sent'
                                                ? 'bg-warning bg-opacity-25'
                                                : 'bg-danger bg-opacity-25') }}">
                                        <i
                                            class="fas fa-2x
                                            {{ $offering->status == 'accepted'
                                                ? 'fa-check-circle text-success'
                                                : ($offering->status == 'sent'
                                                    ? 'fa-paper-plane text-warning'
                                                    : 'fa-times-circle text-danger') }}">
                                        </i>
                                    </div>
                                    <div>
                                        <div class="fw-bold fs-5 mb-1 text-dark">{{ $offering->posisi }}</div>
                                        <div class="text-muted small">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div
                                        class="badge px-4 py-2 rounded-pill fs-6
                                        {{ $offering->status == 'accepted' ? 'bg-success' : ($offering->status == 'sent' ? 'bg-warning' : 'bg-danger') }}">
                                        <i
                                            class="fas me-2
                                            {{ $offering->status == 'accepted'
                                                ? 'fa-check-circle'
                                                : ($offering->status == 'sent'
                                                    ? 'fa-paper-plane'
                                                    : 'fa-times-circle') }}">
                                        </i>
                                        {{ $offering->status == 'accepted'
                                            ? 'DISETUJUI'
                                            : ($offering->status == 'sent'
                                                ? 'MENUNGGU RESPON'
                                                : 'DITOLAK') }}
                                    </div>
                                    {{-- @if ($offering->status == 'accepted')
                                        <div class="small text-success mt-2">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            Diterima
                                            {{ \Carbon\Carbon::parse($offering->tanggal_diterima)->diffForHumans() }}
                                        </div>
                                    @endif --}}
                                </div>
                            </div>
                        </div>

                        <!-- Two Column Layout -->
                        <div class="row g-4">
                            <!-- Left Column -->
                            <div class="col-lg-6">
                                <!-- Detail Card -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-header bg-white border-bottom pb-3 pt-4 px-4">
                                        <h6 class="fw-bold mb-0 text-primary">
                                            <i class="fas fa-clipboard-list me-2"></i>
                                            Detail Penawaran
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-user-circle text-primary me-2"></i>
                                                Nama Lengkap
                                            </div>
                                            <div class="detail-value fw-semibold text-dark">
                                                {{ $offering->pelamar->nama_lengkap }}
                                            </div>
                                        </div>

                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-envelope text-primary me-2"></i>
                                                Alamat Email
                                            </div>
                                            <div class="detail-value text-dark">
                                                {{ $offering->pelamar->email }}
                                            </div>
                                        </div>

                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-file-contract text-primary me-2"></i>
                                                Tipe Kontrak
                                            </div>
                                            <div class="detail-value">
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                                    {{ ucfirst($offering->tipe_kontrak) }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-money-bill-wave text-primary me-2"></i>
                                                Gaji yang Ditawarkan
                                            </div>
                                            <div class="detail-value">
                                                <span class="fw-bold text-primary fs-5">Rp
                                                    {{ number_format($offering->gaji, 0, ',', '.') }}</span>
                                                @if ($offering->gaji > 15000000)
                                                    <span class="badge bg-warning bg-opacity-10 text-warning ms-2">Perlu
                                                        Approval</span>
                                                @endif
                                                <div class="small text-muted mt-1">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Gaji per bulan sebelum pajak
                                                </div>
                                            </div>
                                        </div>

                                        <div class="detail-item">
                                            <div class="detail-label">
                                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                Tanggal Mulai Kerja
                                            </div>
                                            <div class="detail-value">
                                                {{ \Carbon\Carbon::parse($offering->tanggal_mulai_kerja)->translatedFormat('d F Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Catatan HR Card -->
                                @if ($offering->catatan_hr)
                                    <div class="card border-0 shadow-sm rounded-4">
                                        <div class="card-header bg-white border-bottom pb-3 pt-4 px-4">
                                            <h6 class="fw-bold mb-0 text-primary">
                                                <i class="fas fa-sticky-note me-2"></i>
                                                Catatan Internal HR
                                            </h6>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="bg-light p-3 rounded-3">
                                                <p class="mb-0 text-muted">{{ $offering->catatan_hr }}</p>
                                            </div>
                                            <div class="small text-muted mt-2">
                                                <i class="fas fa-lock me-1"></i> Catatan hanya terlihat oleh internal HR
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Right Column -->
                            <div class="col-lg-6">
                                <!-- Timeline Card -->
                                <div class="card border-0 shadow-sm rounded-4 mb-4">
                                    <div class="card-header bg-white border-bottom pb-3 pt-4 px-4">
                                        <h6 class="fw-bold mb-0 text-primary">
                                            <i class="fas fa-history me-2"></i>
                                            Linimasa Penawaran
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="timeline">
                                            <!-- Created Timeline -->
                                            <div class="timeline-item">
                                                <div class="timeline-icon bg-primary bg-opacity-10">
                                                    <i class="fas fa-plus text-primary"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center flex-wrap mb-1">
                                                        <div class="fw-semibold text-dark">Penawaran Dibuat</div>
                                                        <div class="small text-muted">
                                                            {{ $offering->created_at->diffForHumans() }}</div>
                                                    </div>
                                                    <div class="small text-muted">
                                                        {{ $offering->created_at->translatedFormat('d F Y, H:i') }} WIB
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Sent Timeline -->
                                            <div class="timeline-item">
                                                <div class="timeline-icon bg-primary bg-opacity-10">
                                                    <i class="fas fa-paper-plane text-primary"></i>
                                                </div>
                                                <div class="timeline-content">
                                                    <div
                                                        class="d-flex justify-content-between align-items-center flex-wrap mb-1">
                                                        <div class="fw-semibold text-dark">Penawaran Dikirim</div>
                                                        <div class="small text-muted">
                                                            {{ \Carbon\Carbon::parse($offering->tanggal_dikirim)->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                    <div class="small text-muted">
                                                        {{ \Carbon\Carbon::parse($offering->tanggal_dikirim)->translatedFormat('d F Y, H:i') }}
                                                        WIB
                                                    </div>
                                                    <div class="small mt-2">
                                                        <i class="fas fa-hourglass-half text-warning me-1"></i>
                                                        <span class="text-muted">Batas respon:</span>
                                                        <span
                                                            class="fw-semibold">{{ \Carbon\Carbon::parse($offering->batas_respon)->translatedFormat('d F Y') }}</span>
                                                        @if (!now()->gt($offering->batas_respon))
                                                            @php
                                                                $daysLeft = max(
                                                                    0,
                                                                    now()->diffInDays($offering->batas_respon, false),
                                                                );
                                                                $daysLeft = floor($daysLeft);
                                                            @endphp
                                                            <span class="text-success ms-1">
                                                                (Sisa {{ $daysLeft }} hari)
                                                            </span>
                                                        @else
                                                            <span class="text-danger ms-1">(Sudah lewat batas)</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Response Timeline -->
                                            @if ($offering->status == 'accepted')
                                                <div class="timeline-item">
                                                    <div class="timeline-icon bg-success bg-opacity-10">
                                                        <i class="fas fa-check-circle text-success"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center flex-wrap mb-1">
                                                            <div class="fw-semibold text-success">Penawaran Diterima</div>
                                                            <div class="small text-muted">
                                                                {{ \Carbon\Carbon::parse($offering->tanggal_diterima)->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                        <div class="small text-muted">
                                                            {{ \Carbon\Carbon::parse($offering->tanggal_diterima)->translatedFormat('d F Y, H:i') }}
                                                            WIB
                                                        </div>
                                                        @php
                                                            $responseTime = \Carbon\Carbon::parse(
                                                                $offering->tanggal_dikirim,
                                                            )->diffInMinutes($offering->tanggal_diterima);
                                                            $responseHours = floor($responseTime / 60);
                                                            $responseMinutes = $responseTime % 60;
                                                        @endphp
                                                        <div class="small text-success mt-2">
                                                            <i class="fas fa-tachometer-alt me-1"></i>
                                                            Waktu respons: {{ $responseHours }} jam {{ $responseMinutes }}
                                                            menit
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($offering->status == 'rejected')
                                                <div class="timeline-item">
                                                    <div class="timeline-icon bg-danger bg-opacity-10">
                                                        <i class="fas fa-times-circle text-danger"></i>
                                                    </div>
                                                    <div class="timeline-content">
                                                        <div
                                                            class="d-flex justify-content-between align-items-center flex-wrap mb-1">
                                                            <div class="fw-semibold text-danger">Penawaran Ditolak</div>
                                                            <div class="small text-muted">
                                                                {{ $offering->updated_at->diffForHumans() }}</div>
                                                        </div>
                                                        <div class="small text-muted">
                                                            {{ $offering->updated_at->translatedFormat('d F Y, H:i') }} WIB
                                                        </div>
                                                        @if ($offering->alasan_penolakan)
                                                            <div
                                                                class="alert alert-danger bg-danger bg-opacity-10 border-0 small mt-2 mb-0 p-2 rounded-2">
                                                                <i class="fas fa-comment-dots me-1"></i>
                                                                Alasan: {{ $offering->alasan_penolakan }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('styles')
            <style>
                .rounded-4 {
                    border-radius: 20px !important;
                }

                .bg-opacity-10 {
                    --bs-bg-opacity: 0.1;
                }

                .bg-opacity-25 {
                    --bs-bg-opacity: 0.25;
                }

                .detail-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 14px 0;
                    border-bottom: 1px solid #e9ecef;
                }

                .detail-item:last-child {
                    border-bottom: none;
                }

                .detail-label {
                    font-size: 0.85rem;
                    color: #6c757d;
                    min-width: 160px;
                }

                .detail-label i {
                    width: 20px;
                }

                .detail-value {
                    flex: 1;
                    text-align: right;
                    color: #2c3e50;
                }

                .timeline {
                    position: relative;
                    padding-left: 30px;
                }

                .timeline::before {
                    content: '';
                    position: absolute;
                    left: 12px;
                    top: 8px;
                    bottom: 8px;
                    width: 2px;
                    background: linear-gradient(to bottom, #0d6efd, #e9ecef);
                }

                .timeline-item {
                    position: relative;
                    padding-bottom: 28px;
                }

                .timeline-item:last-child {
                    padding-bottom: 0;
                }

                .timeline-icon {
                    position: absolute;
                    left: -30px;
                    top: 0;
                    width: 34px;
                    height: 34px;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background: white;
                    z-index: 1;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                }

                .timeline-content {
                    background: #f8f9fa;
                    padding: 14px 18px;
                    border-radius: 14px;
                    margin-left: 8px;
                    transition: all 0.2s;
                }

                .timeline-content:hover {
                    background: #ffffff;
                    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
                }

                .info-mini-card {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 12px;
                    background: #f8f9fa;
                    border-radius: 12px;
                    transition: all 0.2s;
                }

                .info-mini-card:hover {
                    background: #e9ecef;
                    transform: translateY(-2px);
                }

                .info-mini-icon {
                    width: 40px;
                    height: 40px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .info-mini-content {
                    flex: 1;
                }

                @media (max-width: 768px) {
                    .detail-item {
                        flex-direction: column;
                        align-items: flex-start;
                        gap: 6px;
                    }

                    .detail-value {
                        text-align: left;
                        width: 100%;
                    }

                    .timeline {
                        padding-left: 20px;
                    }

                    .timeline-icon {
                        left: -20px;
                        width: 28px;
                        height: 28px;
                    }

                    .timeline-content {
                        margin-left: 4px;
                    }

                    .info-mini-card {
                        flex-direction: column;
                        text-align: center;
                    }
                }
            </style>
        @endpush
    @endsection
