@extends('layouts.pelamar')

@section('title', 'Progress Rekrutmen Saya')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="bg-primary rounded" style="width: 4px; height: 24px;"></div>
                        <h4 class="fw-bold mb-0" style="color: #1e293b;">Progress Rekrutmen</h4>
                    </div>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-2">
                        <p class="text-muted small ms-2 mb-0">Pantau status lamaran Anda di sini</p>
                        <a href="{{ route('pelamar.lowongan.index') }}" class="btn btn-primary rounded-pill px-4 py-2">
                            <i class="fas fa-briefcase me-2"></i>Cari Lowongan Lain
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success border-0 bg-success bg-opacity-10 rounded-3 py-3 px-4 mb-4"
                        style="border-left: 3px solid #10b981;">
                        <div class="d-flex gap-3 align-items-center">
                            <i class="fas fa-check-circle text-success fs-5"></i>
                            <p class="mb-0 small">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 rounded-3 py-3 px-4 mb-4"
                        style="border-left: 3px solid #ef4444;">
                        <div class="d-flex gap-3 align-items-center">
                            <i class="fas fa-exclamation-circle text-danger fs-5"></i>
                            <p class="mb-0 small">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @forelse($tahapanByLowongan as $lowonganId => $tahapans)
                    @php
                        $lowongan = $lowongans[$lowonganId] ?? null;
                    @endphp

                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-3">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: #1e293b;">
                                        <i class="fas fa-briefcase text-primary me-2"></i>
                                        {{ $lowongan->judul_lowongan ?? 'Lowongan' }}
                                    </h5>
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                            <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                            Dibuka:
                                            {{ \Carbon\Carbon::parse($lowongan->tanggal_buka)->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                            <i class="fas fa-calendar-times me-1 text-danger"></i>
                                            Ditutup:
                                            {{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4 pt-0">
                            @php
                                $sortedTahapans = $tahapans->sortBy(function ($item) {
                                    return $item->tahapan->urutan ?? 999;
                                });
                            @endphp

                            @if ($sortedTahapans->count() > 0)
                                <div class="row g-4">
                                    @foreach ($sortedTahapans as $index => $tahapan)
                                        @php
                                            $statusColor =
                                                [
                                                    'menunggu' => 'secondary',
                                                    'diproses' => 'primary',
                                                    'lolos' => 'success',
                                                    'gagal' => 'danger',
                                                ][$tahapan->status] ?? 'secondary';

                                            $statusIcon =
                                                [
                                                    'menunggu' => 'clock',
                                                    'diproses' => 'spinner fa-pulse',
                                                    'lolos' => 'check-circle',
                                                    'gagal' => 'times-circle',
                                                ][$tahapan->status] ?? 'circle';
                                        @endphp
                                        <div class="col-md-6 col-lg-3">
                                            <div class="card border-0 shadow-sm rounded-3 h-100 tahapan-card">
                                                <div class="card-body p-3 text-center">
                                                    <div class="mb-3">
                                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3"
                                                            style="width: 70px; height: 70px; background: {{ $statusColor == 'primary' ? 'rgba(37, 99, 235, 0.1)' : ($statusColor == 'success' ? 'rgba(16, 185, 129, 0.1)' : ($statusColor == 'danger' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(100, 116, 139, 0.1)')) }};">
                                                            <i
                                                                class="fas fa-{{ $statusIcon }} fa-2x text-{{ $statusColor }}"></i>
                                                        </div>
                                                    </div>
                                                    <h6 class="fw-bold mb-2" style="color: #1e293b;">
                                                        {{ $tahapan->tahapan->nama_tahapan ?? 'Tahapan' }}
                                                    </h6>
                                                    <span
                                                        class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} px-3 py-1 rounded-pill mb-2">
                                                        {{ ucfirst($tahapan->status) }}
                                                    </span>
                                                    @if ($tahapan->tanggal_mulai)
                                                        <div class="small text-muted mt-2">
                                                            <i class="fas fa-calendar-alt me-1"></i>
                                                            {{ \Carbon\Carbon::parse($tahapan->tanggal_mulai)->translatedFormat('d M Y') }}
                                                        </div>
                                                    @endif
                                                    <div class="mt-3">
                                                        @if ($tahapan->status == 'diproses')
                                                            <a href="{{ route('pelamar.pelamartahapan.show', $tahapan->id) }}"
                                                                class="btn btn-primary btn-sm px-3 py-1 rounded-pill w-100">
                                                                <i class="fas fa-eye me-1"></i> Lihat Detail
                                                            </a>
                                                        @elseif($tahapan->status == 'lolos')
                                                            <button
                                                                class="btn btn-success btn-sm px-3 py-1 rounded-pill w-100"
                                                                disabled>
                                                                <i class="fas fa-check-circle me-1"></i> Selesai
                                                            </button>
                                                        @elseif($tahapan->status == 'gagal')
                                                            <button
                                                                class="btn btn-danger btn-sm px-3 py-1 rounded-pill w-100"
                                                                disabled>
                                                                <i class="fas fa-times-circle me-1"></i> Ditutup
                                                            </button>
                                                        @else
                                                            <button
                                                                class="btn btn-secondary btn-sm px-3 py-1 rounded-pill w-100"
                                                                disabled>
                                                                <i class="fas fa-lock me-1"></i> Terkunci
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                        style="width: 80px; height: 80px;">
                                        <i class="fas fa-folder-open text-muted fa-2x"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-1" style="color: #1e293b;">Belum Ada Tahapan</h6>
                                    <p class="text-muted small mb-0">Tahapan seleksi akan muncul setelah lamaran diproses
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                        <div class="card-body p-5">
                            <div class="mb-4">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 100px; height: 100px;">
                                    <i class="fas fa-inbox text-muted fa-3x"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: #1e293b;">Belum Ada Lamaran Aktif</h5>
                            <p class="text-muted mb-4">Anda belum mendaftar di lowongan mana pun.</p>
                            <a href="{{ route('pelamar.lowongan.index') }}" class="btn btn-primary rounded-pill px-5 py-2">
                                <i class="fas fa-briefcase me-2"></i>Lihat Lowongan Tersedia
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .rounded-4 {
            border-radius: 20px !important;
        }

        .rounded-3 {
            border-radius: 12px !important;
        }

        .rounded-pill {
            border-radius: 50px !important;
        }

        .shadow-sm {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03) !important;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .btn-primary {
            background: #2563eb;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-success,
        .btn-danger,
        .btn-secondary {
            transition: all 0.2s;
        }

        .tahapan-card {
            transition: all 0.2s ease;
            background: white;
        }

        .tahapan-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02) !important;
        }

        @media (max-width: 768px) {
            .card-header .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }
    </style>
@endsection
@extends('layouts.pelamar')

@section('title', 'Progress Rekrutmen Saya')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="bg-primary rounded" style="width: 4px; height: 24px;"></div>
                        <h4 class="fw-bold mb-0" style="color: #1e293b;">Progress Rekrutmen</h4>
                    </div>
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-2">
                        <p class="text-muted small ms-2 mb-0">Pantau status lamaran Anda di sini</p>
                        <a href="{{ route('pelamar.lowongan.index') }}" class="btn btn-primary rounded-pill px-4 py-2">
                            <i class="fas fa-briefcase me-2"></i>Cari Lowongan Lain
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success border-0 bg-success bg-opacity-10 rounded-3 py-3 px-4 mb-4"
                        style="border-left: 3px solid #10b981;">
                        <div class="d-flex gap-3 align-items-center">
                            <i class="fas fa-check-circle text-success fs-5"></i>
                            <p class="mb-0 small">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 rounded-3 py-3 px-4 mb-4"
                        style="border-left: 3px solid #ef4444;">
                        <div class="d-flex gap-3 align-items-center">
                            <i class="fas fa-exclamation-circle text-danger fs-5"></i>
                            <p class="mb-0 small">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @forelse($tahapanByLowongan as $lowonganId => $tahapans)
                    @php
                        $lowongan = $lowongans[$lowonganId] ?? null;
                    @endphp

                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-3">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                <div>
                                    <h5 class="fw-bold mb-1" style="color: #1e293b;">
                                        <i class="fas fa-briefcase text-primary me-2"></i>
                                        {{ $lowongan->judul_lowongan ?? 'Lowongan' }}
                                    </h5>
                                    <div class="d-flex flex-wrap gap-2 mt-2">
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                            <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                            Dibuka:
                                            {{ \Carbon\Carbon::parse($lowongan->tanggal_buka)->translatedFormat('d M Y') }}
                                        </span>
                                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                                            <i class="fas fa-calendar-times me-1 text-danger"></i>
                                            Ditutup:
                                            {{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4 pt-0">
                            @php
                                $sortedTahapans = $tahapans->sortBy(function ($item) {
                                    return $item->tahapan->urutan ?? 999;
                                });
                            @endphp

                            @if ($sortedTahapans->count() > 0)
                                <div class="row g-4">
                                    @foreach ($sortedTahapans as $index => $tahapan)
                                        @php
                                            $statusColor =
                                                [
                                                    'menunggu' => 'secondary',
                                                    'diproses' => 'primary',
                                                    'lolos' => 'success',
                                                    'gagal' => 'danger',
                                                ][$tahapan->status] ?? 'secondary';

                                            $statusIcon =
                                                [
                                                    'menunggu' => 'clock',
                                                    'diproses' => 'spinner fa-pulse',
                                                    'lolos' => 'check-circle',
                                                    'gagal' => 'times-circle',
                                                ][$tahapan->status] ?? 'circle';
                                        @endphp
                                        <div class="col-md-6 col-lg-3">
                                            <div class="card border-0 shadow-sm rounded-3 h-100 tahapan-card">
                                                <div class="card-body p-3 text-center">
                                                    <div class="mb-3">
                                                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center p-3"
                                                            style="width: 70px; height: 70px; background: {{ $statusColor == 'primary' ? 'rgba(37, 99, 235, 0.1)' : ($statusColor == 'success' ? 'rgba(16, 185, 129, 0.1)' : ($statusColor == 'danger' ? 'rgba(239, 68, 68, 0.1)' : 'rgba(100, 116, 139, 0.1)')) }};">
                                                            <i
                                                                class="fas fa-{{ $statusIcon }} fa-2x text-{{ $statusColor }}"></i>
                                                        </div>
                                                    </div>
                                                    <h6 class="fw-bold mb-2" style="color: #1e293b;">
                                                        {{ $tahapan->tahapan->nama_tahapan ?? 'Tahapan' }}
                                                    </h6>
                                                    <span
                                                        class="badge bg-{{ $statusColor }} bg-opacity-10 text-{{ $statusColor }} px-3 py-1 rounded-pill mb-2">
                                                        {{ ucfirst($tahapan->status) }}
                                                    </span>
                                                    @if ($tahapan->tanggal_mulai)
                                                        <div class="small text-muted mt-2">
                                                            <i class="fas fa-calendar-alt me-1"></i>
                                                            {{ \Carbon\Carbon::parse($tahapan->tanggal_mulai)->translatedFormat('d M Y') }}
                                                        </div>
                                                    @endif
                                                    <div class="mt-3">
                                                        @if ($tahapan->status == 'diproses')
                                                            <a href="{{ route('pelamar.pelamartahapan.show', $tahapan->id) }}"
                                                                class="btn btn-primary btn-sm px-3 py-1 rounded-pill w-100">
                                                                <i class="fas fa-eye me-1"></i> Lihat Detail
                                                            </a>
                                                        @elseif($tahapan->status == 'lolos')
                                                            <button
                                                                class="btn btn-success btn-sm px-3 py-1 rounded-pill w-100"
                                                                disabled>
                                                                <i class="fas fa-check-circle me-1"></i> Selesai
                                                            </button>
                                                        @elseif($tahapan->status == 'gagal')
                                                            <button
                                                                class="btn btn-danger btn-sm px-3 py-1 rounded-pill w-100"
                                                                disabled>
                                                                <i class="fas fa-times-circle me-1"></i> Ditutup
                                                            </button>
                                                        @else
                                                            <button
                                                                class="btn btn-secondary btn-sm px-3 py-1 rounded-pill w-100"
                                                                disabled>
                                                                <i class="fas fa-lock me-1"></i> Terkunci
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                                        style="width: 80px; height: 80px;">
                                        <i class="fas fa-folder-open text-muted fa-2x"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-1" style="color: #1e293b;">Belum Ada Tahapan</h6>
                                    <p class="text-muted small mb-0">Tahapan seleksi akan muncul setelah lamaran diproses
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="card border-0 shadow-sm rounded-4 text-center py-5">
                        <div class="card-body p-5">
                            <div class="mb-4">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 100px; height: 100px;">
                                    <i class="fas fa-inbox text-muted fa-3x"></i>
                                </div>
                            </div>
                            <h5 class="fw-semibold mb-2" style="color: #1e293b;">Belum Ada Lamaran Aktif</h5>
                            <p class="text-muted mb-4">Anda belum mendaftar di lowongan mana pun.</p>
                            <a href="{{ route('pelamar.lowongan.index') }}"
                                class="btn btn-primary rounded-pill px-5 py-2">
                                <i class="fas fa-briefcase me-2"></i>Lihat Lowongan Tersedia
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        .rounded-4 {
            border-radius: 20px !important;
        }

        .rounded-3 {
            border-radius: 12px !important;
        }

        .rounded-pill {
            border-radius: 50px !important;
        }

        .shadow-sm {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03) !important;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .btn-primary {
            background: #2563eb;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-success,
        .btn-danger,
        .btn-secondary {
            transition: all 0.2s;
        }

        .tahapan-card {
            transition: all 0.2s ease;
            background: white;
        }

        .tahapan-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.02) !important;
        }

        @media (max-width: 768px) {
            .card-header .d-flex {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }
    </style>
@endsection
