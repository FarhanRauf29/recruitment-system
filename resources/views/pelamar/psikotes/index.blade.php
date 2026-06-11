@extends('layouts.pelamar')

@section('title', 'Tes Psikotes Saya')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-gradient-primary text-white py-3 px-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-white bg-opacity-20 p-2">
                                <i class="fas fa-brain fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Tes Psikotes Saya</h5>
                                <p class="small mb-0 opacity-75">Pantau status dan hasil tes psikotes Anda</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-lg me-2 text-success"></i>
                                    <span>{{ session('success') }}</span>
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle fa-lg me-2 text-danger"></i>
                                    <span>{{ session('error') }}</span>
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        @forelse($tahapanPsikotes as $index => $tahapan)
                            @php
                                $statusConfig = [
                                    'belum_dimulai' => [
                                        'color' => 'secondary',
                                        'icon' => 'clock',
                                        'text' => 'Menunggu Jadwal',
                                    ],
                                    'diproses' => [
                                        'color' => 'primary',
                                        'icon' => 'play-circle',
                                        'text' => 'Siap Dikerjakan',
                                    ],
                                    'lolos' => ['color' => 'success', 'icon' => 'check-circle', 'text' => 'Lulus'],
                                    'gagal' => ['color' => 'danger', 'icon' => 'times-circle', 'text' => 'Tidak Lulus'],
                                ];
                                $config = $statusConfig[$tahapan->status] ?? $statusConfig['belum_dimulai'];
                                $statusColor = $config['color'];
                                $statusIcon = $config['icon'];
                                $statusText = $config['text'];
                            @endphp
                            <div class="card mb-4 border-0 shadow-sm rounded-3 hover-card">
                                <div class="card-body p-4">
                                    <div class="row align-items-start">
                                        <div class="col-lg-7 mb-3 mb-lg-0">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="rounded-circle p-3 flex-shrink-0"
                                                    style="background: rgba(var(--bs-{{ $statusColor }}-rgb), 0.1);">
                                                    <i
                                                        class="fas fa-{{ $statusIcon }} fa-xl text-{{ $statusColor }}"></i>
                                                </div>
                                                <div>
                                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                                        <h5 class="fw-bold mb-0">
                                                            {{ $tahapan->tahapan->nama_tahapan ?? 'Psikotes' }}</h5>
                                                        <span class="badge bg-{{ $statusColor }} rounded-pill px-3 py-1">
                                                            {{ $statusText }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-3">
                                                        <div class="small text-muted">
                                                            <i class="fas fa-briefcase me-1 text-primary"></i>
                                                            {{ $tahapan->lowongan->judul_lowongan ?? '-' }}
                                                        </div>
                                                        <div class="small text-muted">
                                                            <i class="fas fa-clock me-1 text-primary"></i>
                                                            {{ $tahapan->tanggal_mulai ? Carbon\Carbon::parse($tahapan->tanggal_mulai)->format('d M Y H:i') : 'Jadwal belum ditentukan' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                                                @if ($tahapan->status == 'diproses')
                                                    <a href="{{ route('pelamar.psikotes.token', $tahapan->id) }}"
                                                        class="btn btn-gradient-primary rounded-pill px-4">
                                                        <i class="fas fa-play-circle me-2"></i>Mulai Tes
                                                    </a>
                                                @elseif($tahapan->status == 'lolos')
                                                    <button class="btn btn-success rounded-pill px-4" disabled>
                                                        <i class="fas fa-check-circle me-2"></i>Lulus
                                                    </button>
                                                    <a href="{{ route('pelamar.psikotes.hasil', $tahapan->id) }}"
                                                        class="btn btn-outline-info rounded-pill px-4">
                                                        <i class="fas fa-chart-bar me-2"></i>Lihat Hasil
                                                    </a>
                                                @elseif($tahapan->status == 'gagal')
                                                    <button class="btn btn-danger rounded-pill px-4" disabled>
                                                        <i class="fas fa-times-circle me-2"></i>Tidak Lulus
                                                    </button>
                                                    <a href="{{ route('pelamar.psikotes.hasil', $tahapan->id) }}"
                                                        class="btn btn-outline-info rounded-pill px-4">
                                                        <i class="fas fa-chart-bar me-2"></i>Lihat Hasil
                                                    </a>
                                                @else
                                                    <button class="btn btn-secondary rounded-pill px-4" disabled>
                                                        <i class="fas fa-clock me-2"></i>Menunggu
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-3">

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="info-box">
                                                <div class="info-icon bg-primary">
                                                    <i class="fas fa-calendar-alt"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Tanggal Mulai</span>
                                                    <span
                                                        class="info-value">{{ $tahapan->tanggal_mulai ? Carbon\Carbon::parse($tahapan->tanggal_mulai)->format('d M Y H:i') : '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-box">
                                                <div class="info-icon bg-danger">
                                                    <i class="fas fa-calendar-check"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Batas Akhir</span>
                                                    <span
                                                        class="info-value">{{ $tahapan->tanggal_selesai ? Carbon\Carbon::parse($tahapan->tanggal_selesai)->format('d M Y H:i') : '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-box">
                                                <div class="info-icon bg-warning">
                                                    <i class="fas fa-hourglass-half"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Durasi Pengerjaan</span>
                                                    <span
                                                        class="info-value">{{ $tahapan->konfigurasi->durasi_menit ?? 60 }}
                                                        Menit</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($tahapan->catatan)
                                        <div class="mt-3 p-3 bg-light rounded-3">
                                            <i class="fas fa-sticky-note text-warning me-2"></i>
                                            <span class="small">{{ $tahapan->catatan }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-brain fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak Ada Tes Psikotes</h5>
                                    <p class="text-muted">Anda belum terdaftar dalam tahapan psikotes untuk lowongan
                                        manapun.</p>
                                    <a href="{{ route('pelamar.lowongan.index') }}"
                                        class="btn btn-primary rounded-pill px-4 mt-2">
                                        <i class="fas fa-briefcase me-2"></i>Lihat Lowongan
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
            color: white;
        }

        .bg-opacity-20 {
            --bs-bg-opacity: 0.2;
        }

        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px -8px rgba(0, 0, 0, 0.15) !important;
        }

        .info-box {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .info-box:hover {
            background: #e9ecef;
            transform: translateX(3px);
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .info-content {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 11px;
            color: #6c757d;
            letter-spacing: 0.3px;
        }

        .info-value {
            font-size: 13px;
            font-weight: 600;
            color: #2c3e50;
        }

        .empty-state {
            padding: 40px 20px;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .rounded-3 {
            border-radius: 0.75rem !important;
        }
    </style>
@endsection
