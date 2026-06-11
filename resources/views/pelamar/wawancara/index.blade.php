@extends('layouts.pelamar')

@section('title', 'Jadwal Wawancara Saya')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-white bg-opacity-20 p-2">
                                <i class="fas fa-chalkboard-user fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Jadwal Wawancara Saya</h5>
                                <p class="small mb-0 opacity-75">Informasi jadwal wawancara Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-lg me-2 text-success"></i>
                                    <span>{{ session('success') }}</span>
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        @forelse($wawancara as $item)
                            @php
                                $statusConfig = [
                                    'dijadwalkan' => [
                                        'color' => 'warning',
                                        'icon' => 'calendar',
                                        'text' => 'Dijadwalkan',
                                    ],
                                    'dikonfirmasi' => [
                                        'color' => 'info',
                                        'icon' => 'check-circle',
                                        'text' => 'Dikonfirmasi',
                                    ],
                                    'hadir' => ['color' => 'primary', 'icon' => 'user-check', 'text' => 'Hadir'],
                                    'tidak_hadir' => [
                                        'color' => 'danger',
                                        'icon' => 'user-times',
                                        'text' => 'Tidak Hadir',
                                    ],
                                    'selesai' => [
                                        'color' => 'secondary',
                                        'icon' => 'flag-checkered',
                                        'text' => 'Selesai',
                                    ],
                                    'verifikasi' => [
                                        'color' => 'dark',
                                        'icon' => 'clipboard-check',
                                        'text' => 'Verifikasi',
                                    ],
                                    'lolos' => ['color' => 'success', 'icon' => 'check-circle', 'text' => 'Lolos'],
                                    'gagal' => ['color' => 'danger', 'icon' => 'times-circle', 'text' => 'Gagal'],
                                ];
                                $config = $statusConfig[$item->status] ?? $statusConfig['dijadwalkan'];
                            @endphp
                            <div class="card mb-4 border-0 shadow-sm rounded-3 hover-card">
                                <div class="card-body p-4">
                                    <div class="row align-items-start">
                                        <div class="col-lg-7 mb-3 mb-lg-0">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="rounded-circle p-3 flex-shrink-0"
                                                    style="background: rgba(var(--bs-{{ $config['color'] }}-rgb), 0.1);">
                                                    <i
                                                        class="fas fa-{{ $config['icon'] }} fa-xl text-{{ $config['color'] }}"></i>
                                                </div>
                                                <div>
                                                    <h5 class="fw-bold mb-2">
                                                        {{ $item->lowongan->judul_lowongan ?? 'Lowongan' }}</h5>
                                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                                        <span
                                                            class="badge bg-{{ $config['color'] }} rounded-pill px-3 py-1">
                                                            <i class="fas fa-{{ $config['icon'] }} me-1"></i>
                                                            {{ $config['text'] }}
                                                        </span>
                                                        <span
                                                            class="badge bg-{{ $item->jenis_wawancara == 'online' ? 'success' : 'warning' }} rounded-pill px-3 py-1">
                                                            <i
                                                                class="fas fa-{{ $item->jenis_wawancara == 'online' ? 'video' : 'building' }} me-1"></i>
                                                            {{ $item->jenis_wawancara == 'online' ? 'Online' : 'Offline' }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex flex-wrap gap-3 mt-2">
                                                        <div class="small text-muted">
                                                            <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                                            {{ Carbon\Carbon::parse($item->jadwal_wawancara)->format('d F Y H:i') }}
                                                            WIB
                                                        </div>
                                                        @if ($item->jenis_wawancara == 'offline')
                                                            <div class="small text-muted">
                                                                <i class="fas fa-map-marker-alt me-1 text-danger"></i>
                                                                {{ \Str::limit($item->lokasi_wawancara, 40) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-5">
                                            <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                                                <a href="{{ route('pelamar.wawancara.show', $item->id) }}"
                                                    class="btn btn-outline-primary rounded-pill px-4">
                                                    <i class="fas fa-eye me-2"></i>Detail
                                                </a>
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
                                                    <span class="info-label">Tanggal</span>
                                                    <span
                                                        class="info-value">{{ Carbon\Carbon::parse($item->jadwal_wawancara)->format('d F Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-box">
                                                <div class="info-icon bg-danger">
                                                    <i class="fas fa-clock"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Pukul</span>
                                                    <span
                                                        class="info-value">{{ Carbon\Carbon::parse($item->jadwal_wawancara)->format('H:i') }}
                                                        WIB</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="info-box">
                                                <div class="info-icon bg-info">
                                                    <i class="fas fa-user-tie"></i>
                                                </div>
                                                <div class="info-content">
                                                    <span class="info-label">Pewawancara</span>
                                                    <span class="info-value">{{ $item->pewawancara ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if ($item->keterangan)
                                        <div class="mt-3 p-3 bg-light rounded-3">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            <span class="small">{{ $item->keterangan }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-chalkboard-user fa-4x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum Ada Jadwal Wawancara</h5>
                                    <p class="text-muted">Belum ada jadwal wawancara untuk Anda.</p>
                                    <a href="{{ route('pelamar.dashboard') }}"
                                        class="btn btn-primary rounded-pill px-4 mt-2">
                                        <i class="fas fa-home me-2"></i>Kembali ke Dashboard
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
            width: 38px;
            height: 38px;
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
