@extends('layouts.pelamar')

@section('title', 'Penawaran Kerja')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="bg-primary rounded" style="width: 4px; height: 24px;"></div>
                        <h4 class="fw-bold mb-0" style="color: #1e293b;">Penawaran Kerja</h4>
                    </div>
                    <p class="text-muted small ms-2">Kelola penawaran kerja yang Anda terima</p>
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
                @if (session('info'))
                    <div class="alert alert-info border-0 bg-info bg-opacity-10 rounded-3 py-3 px-4 mb-4"
                        style="border-left: 3px solid #3b82f6;">
                        <div class="d-flex gap-3 align-items-center">
                            <i class="fas fa-info-circle text-primary fs-5"></i>
                            <p class="mb-0 small">{{ session('info') }}</p>
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

                <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <ul class="nav nav-tabs border-0 gap-2">
                            <li class="nav-item">
                                <a class="nav-link active fw-medium rounded-pill px-4" data-bs-toggle="tab" href="#aktif"
                                    style="color: #2563eb; background: #eff6ff;">
                                    <i class="fas fa-envelope me-2"></i>Penawaran Aktif
                                    <span class="badge bg-primary ms-2 rounded-pill">{{ $offeringSent->count() }}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-medium rounded-pill px-4" data-bs-toggle="tab" href="#riwayat"
                                    style="color: #64748b;">
                                    <i class="fas fa-history me-2"></i>Riwayat
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content">
                            <!-- Tab Penawaran Aktif -->
                            <div class="tab-pane fade show active" id="aktif">
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center px-3 py-3" style="width: 5%;">#</th>
                                                <th class="px-3 py-3" style="width: 25%;">Posisi</th>
                                                <th class="px-3 py-3" style="width: 20%;">Perusahaan</th>
                                                <th class="text-center px-3 py-3" style="width: 15%;">Gaji</th>
                                                <th class="text-center px-3 py-3" style="width: 10%;">Kontrak</th>
                                                <th class="text-center px-3 py-3" style="width: 15%;">Batas Respon</th>
                                                <th class="text-center px-3 py-3" style="width: 10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($offeringSent as $offering)
                                                @php
                                                    $daysLeft = \Carbon\Carbon::now()->diffInDays(
                                                        $offering->batas_respon,
                                                        false,
                                                    );
                                                    $daysLeft = max(0, floor($daysLeft));
                                                    $isExpired = now()->gt($offering->batas_respon);
                                                @endphp
                                                <tr>
                                                    <td class="text-center px-3 py-3">{{ $loop->iteration }}</td>
                                                    <td class="px-3 py-3">
                                                        <div class="fw-semibold text-dark">{{ $offering->posisi }}</div>
                                                    </td>
                                                    <td class="px-3 py-3">{{ $offering->lowongan->judul_lowongan }}</td>
                                                    <td class="text-center px-3 py-3">
                                                        <span class="fw-semibold text-primary">Rp
                                                            {{ number_format($offering->gaji, 0, ',', '.') }}</span>
                                                    </td>
                                                    <td class="text-center px-3 py-3">
                                                        <span
                                                            class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                                            {{ ucfirst($offering->tipe_kontrak) }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center px-3 py-3">
                                                        <div>
                                                            <span
                                                                class="badge {{ $isExpired ? 'bg-danger bg-opacity-10 text-danger' : 'bg-warning bg-opacity-10 text-warning' }} px-3 py-2 rounded-pill mb-1">
                                                                <i
                                                                    class="fas {{ $isExpired ? 'fa-times-circle' : 'fa-calendar-alt' }} me-1"></i>
                                                                {{ \Carbon\Carbon::parse($offering->batas_respon)->translatedFormat('d M Y') }}
                                                            </span>
                                                            @if (!$isExpired)
                                                                <div class="small text-success mt-1">
                                                                    <i class="fas fa-hourglass-half me-1"></i>
                                                                    Sisa {{ $daysLeft }} hari
                                                                </div>
                                                            @else
                                                                <div class="small text-danger mt-1">
                                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                                    Telah Berakhir
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="text-center px-3 py-3">
                                                        <a href="{{ route('pelamar.offering.show', $offering->id) }}"
                                                            class="btn btn-primary btn-sm px-3 py-1 rounded-pill">
                                                            <i class="fas fa-eye me-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-5">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <i class="fas fa-inbox fa-4x text-muted mb-3 opacity-25"></i>
                                                            <p class="mb-0 text-muted fw-semibold">Tidak Ada Penawaran Aktif
                                                            </p>
                                                            <small class="text-muted">Belum ada penawaran kerja yang
                                                                masuk</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab Riwayat -->
                            <div class="tab-pane fade" id="riwayat">
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center px-3 py-3" style="width: 5%;">#</th>
                                                <th class="px-3 py-3" style="width: 25%;">Posisi</th>
                                                <th class="px-3 py-3" style="width: 20%;">Perusahaan</th>
                                                <th class="text-center px-3 py-3" style="width: 15%;">Gaji</th>
                                                <th class="text-center px-3 py-3" style="width: 15%;">Status</th>
                                                <th class="text-center px-3 py-3" style="width: 10%;">Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($offeringHistory as $offering)
                                                <tr>
                                                    <td class="text-center px-3 py-3">{{ $loop->iteration }}</td>
                                                    <td class="px-3 py-3">
                                                        <div class="fw-semibold text-dark">{{ $offering->posisi }}</div>
                                                    </td>
                                                    <td class="px-3 py-3">{{ $offering->lowongan->judul_lowongan }}</td>
                                                    <td class="text-center px-3 py-3">
                                                        <span class="fw-semibold">Rp
                                                            {{ number_format($offering->gaji, 0, ',', '.') }}</span>
                                                    </td>
                                                    <td class="text-center px-3 py-3">
                                                        @if ($offering->status == 'accepted')
                                                            <span
                                                                class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                                <i class="fas fa-check-circle me-1"></i> Diterima
                                                            </span>
                                                        @elseif($offering->status == 'rejected')
                                                            <span
                                                                class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                                                <i class="fas fa-times-circle me-1"></i> Ditolak
                                                            </span>
                                                        @else
                                                            <span
                                                                class="badge bg-secondary bg-opacity-10 text-secondary px-3 py-2 rounded-pill">
                                                                <i class="fas fa-hourglass-end me-1"></i> Kadaluarsa
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center px-3 py-3">
                                                        <small>{{ \Carbon\Carbon::parse($offering->created_at)->translatedFormat('d M Y') }}</small>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center py-5">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <i class="fas fa-history fa-4x text-muted mb-3 opacity-25"></i>
                                                            <p class="mb-0 text-muted fw-semibold">Belum Ada Riwayat</p>
                                                            <small class="text-muted">Riwayat penawaran akan muncul di
                                                                sini</small>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .rounded-3 {
            border-radius: 12px !important;
        }

        .rounded-pill {
            border-radius: 50px !important;
        }

        .shadow-sm {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03) !important;
        }

        .table-bordered {
            border: 1px solid #dee2e6 !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6 !important;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        .nav-link.active {
            background: #eff6ff !important;
            color: #2563eb !important;
        }

        .nav-link {
            transition: all 0.2s;
        }

        .nav-link:hover:not(.active) {
            background: #f1f5f9;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .btn-primary {
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-1px);
        }
    </style>
@endpush
