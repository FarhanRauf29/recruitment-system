@extends('layouts.admin')

@section('title', 'Manajemen Hiring')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div
                        class="card-header bg-primary text-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="fas fa-user-check me-2"></i> Manajemen Hiring
                            </h5>
                            <small class="opacity-75 mt-1 d-block">Konfirmasi dan kelola proses hiring kandidat</small>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.spk.index') }}" class="btn btn-light btn-sm px-3 py-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke SPK
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-lg me-2"></i>
                                    <span>{{ session('success') }}</span>
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle fa-lg me-2"></i>
                                    <span>{{ session('error') }}</span>
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        <div class="alert alert-info bg-light border-start border-4 border-info mb-4 py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-briefcase text-info me-3 fs-5"></i>
                                <div>
                                    <span class="text-muted small d-block">Lowongan Aktif</span>
                                    <strong class="fs-6">{{ $lowongan->judul_lowongan }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="tab-navigation-wrapper mb-4">
                            <ul class="nav nav-tabs border-bottom-0"
                                style="flex-wrap: nowrap; overflow-x: auto; gap: 8px; scrollbar-width: thin;">
                                <li class="nav-item" style="flex-shrink: 0;">
                                    <button class="nav-link active py-2 px-4 rounded-3" data-bs-toggle="tab"
                                        data-bs-target="#pending">
                                        <i class="fas fa-clock me-2"></i> Menunggu Hiring
                                        <span
                                            class="badge bg-warning ms-2 rounded-pill">{{ $offeringAccepted->count() }}</span>
                                    </button>
                                </li>
                                <li class="nav-item" style="flex-shrink: 0;">
                                    <button class="nav-link py-2 px-4 rounded-3" data-bs-toggle="tab"
                                        data-bs-target="#probation">
                                        <i class="fas fa-chart-line me-2"></i> Masa Probation
                                        <span class="badge bg-info ms-2 rounded-pill">{{ $hiringProbation->count() }}</span>
                                    </button>
                                </li>
                                <li class="nav-item" style="flex-shrink: 0;">
                                    <button class="nav-link py-2 px-4 rounded-3" data-bs-toggle="tab"
                                        data-bs-target="#hired">
                                        <i class="fas fa-check-circle me-2"></i> Selesai (Hired)
                                        <span
                                            class="badge bg-success ms-2 rounded-pill">{{ $hiringCompleted->count() }}</span>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content mt-3">
                            <!-- Tab Menunggu Hiring -->
                            <div class="tab-pane fade show active" id="pending">
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center px-3 py-3" style="width: 5%">#</th>
                                                <th class="px-3 py-3" style="width: 30%">Kandidat</th>
                                                <th class="px-3 py-3" style="width: 25%">Posisi</th>
                                                <th class="text-center px-3 py-3" style="width: 15%">Gaji</th>
                                                <th class="text-center px-3 py-3" style="width: 15%">Status</th>
                                                <th class="text-center px-3 py-3" style="width: 10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($offeringAccepted as $item)
                                                @php
                                                    $existingHiring = \App\Models\Hiring::where(
                                                        'offering_id',
                                                        $item->id,
                                                    )->first();
                                                @endphp
                                                <tr>
                                                    <td class="text-center px-3 py-3">{{ $loop->iteration }}</td>
                                                    <td class="px-3 py-3">
                                                        <div class="fw-semibold text-dark">
                                                            {{ $item->pelamar->nama_lengkap }}</div>
                                                    </td>
                                                    <td class="px-3 py-3">{{ $item->posisi }}</td>
                                                    <td class="text-center px-3 py-3">
                                                        <span class="fw-semibold">Rp
                                                            {{ number_format($item->gaji, 0, ',', '.') }}</span>
                                                    </td>
                                                    <td class="text-center px-3 py-3">
                                                        <span
                                                            class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                                            <i class="fas fa-clock me-1"></i> Menunggu Hiring
                                                        </span>
                                                    </td>
                                                    <td class="text-center px-3 py-3">
                                                        @if ($existingHiring && $existingHiring->status != 'pending')
                                                            <a href="{{ route('admin.hiring.show', [$lowongan->id, $existingHiring->id]) }}"
                                                                class="btn btn-info btn-sm px-3 py-1 rounded-pill">
                                                                <i class="fas fa-eye me-1"></i> Detail
                                                            </a>
                                                        @else
                                                            <a href="{{ route('admin.hiring.create', [$lowongan->id, $item->id]) }}"
                                                                class="btn btn-primary btn-sm px-3 py-1 rounded-pill">
                                                                <i class="fas fa-check-circle me-1"></i> Konfirmasi
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5">
                                                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                                        <p class="mb-0 text-muted">Belum ada kandidat yang perlu di-hiring
                                                        </p>
                                                        <small class="text-muted">Kandidat akan muncul setelah penawaran
                                                            diterima</small>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab Masa Probation -->
                            <div class="tab-pane fade" id="probation">
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center px-3 py-3" style="width: 5%">#</th>
                                                <th class="px-3 py-3" style="width: 30%">Kandidat</th>
                                                <th class="px-3 py-3" style="width: 25%">Posisi</th>
                                                <th class="text-center px-3 py-3" style="width: 15%">Gaji</th>
                                                <th class="text-center px-3 py-3" style="width: 15%">Status</th>
                                                <th class="text-center px-3 py-3" style="width: 10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($hiringProbation as $item)
                                                <tr>
                                                    <td class="text-center px-3 py-3">{{ $loop->iteration }}</td>
                                                    <td class="px-3 py-3">
                                                        <div class="fw-semibold text-dark">
                                                            {{ $item->pelamar->nama_lengkap }}</div>

                                                    </td>
                                                    <td class="px-3 py-3">{{ $item->offering->posisi ?? '-' }}</td>
                                                    <td class="text-center px-3 py-3">
                                                        <span class="fw-semibold">Rp
                                                            {{ number_format($item->offering->gaji ?? 0, 0, ',', '.') }}</span>
                                                    </td>
                                                    <td class="text-center px-3 py-3">
                                                        <span
                                                            class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                                            <i class="fas fa-chart-line me-1"></i> Masa Probation
                                                        </span>
                                                        @if ($item->tanggal_probation_end)
                                                            <br><small class="text-muted">Sampai:
                                                                {{ \Carbon\Carbon::parse($item->tanggal_probation_end)->translatedFormat('d M Y') }}</small>
                                                        @endif
                                                    </td>
                                                    <td class="text-center px-3 py-3">
                                                        <a href="{{ route('admin.hiring.show', [$lowongan->id, $item->id]) }}"
                                                            class="btn btn-info btn-sm px-3 py-1 rounded-pill">
                                                            <i class="fas fa-eye me-1"></i> Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5">
                                                        <i class="fas fa-chart-line fa-3x text-muted mb-3 d-block"></i>
                                                        <p class="mb-0 text-muted">Belum ada kandidat dalam masa probation
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab Selesai (Hired) -->
                            <div class="tab-pane fade" id="hired">
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center px-3 py-3" style="width: 5%">#</th>
                                                <th class="px-3 py-3" style="width: 30%">Kandidat</th>
                                                <th class="px-3 py-3" style="width: 20%">Posisi</th>
                                                <th class="text-center px-3 py-3" style="width: 10%">NIP</th>
                                                <th class="text-center px-3 py-3" style="width: 15%">Status</th>
                                                <th class="text-center px-3 py-3" style="width: 20%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($hiringCompleted as $item)
                                                <tr>
                                                    <td class="text-center px-3 py-3">{{ $loop->iteration }}</td>
                                                    <td class="px-3 py-3">
                                                        <div class="fw-semibold text-dark">
                                                            {{ $item->pelamar->nama_lengkap }}</div>
                                                    </td>
                                                    <td class="px-3 py-3">{{ $item->offering->posisi ?? '-' }}</td>
                                                    <td class="text-center px-3 py-3">
                                                        <span
                                                            class="badge bg-secondary rounded-pill">{{ $item->onboarding->nip ?? '-' }}</span>
                                                    </td>
                                                    <td class="text-center px-3 py-3">
                                                        <span
                                                            class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                            <i class="fas fa-check-circle me-1"></i> Hired
                                                        </span>
                                                    </td>
                                                    <td class="text-center px-3 py-3">
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <a href="{{ route('admin.hiring.show', [$lowongan->id, $item->id]) }}"
                                                                class="btn btn-info btn-sm px-3 py-1 rounded-pill">
                                                                <i class="fas fa-eye me-1"></i> Detail
                                                            </a>
                                                            @if ($item->onboarding)
                                                                <a href="{{ route('admin.onboarding.show', [$lowongan->id, $item->onboarding->id]) }}"
                                                                    class="btn btn-success btn-sm px-3 py-1 rounded-pill">
                                                                    <i class="fas fa-user-plus me-1"></i> Onboarding
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-5">
                                                        <i class="fas fa-check-circle fa-3x text-muted mb-3 d-block"></i>
                                                        <p class="mb-0 text-muted">Belum ada kandidat yang selesai hiring
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Total menunggu hiring: <strong>{{ $offeringAccepted->count() }}</strong> |
                                Masa probation: <strong>{{ $hiringProbation->count() }}</strong> |
                                Selesai: <strong>{{ $hiringCompleted->count() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
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

            .tab-navigation-wrapper {
                width: 100%;
                overflow-x: auto;
                overflow-y: visible;
                scrollbar-width: thin;
            }

            .nav-tabs .nav-link {
                background: #f8f9fa;
                color: #6c757d;
                font-weight: 500;
                transition: all 0.2s ease;
                border: 1px solid #dee2e6;
                white-space: nowrap;
            }

            .nav-tabs .nav-link:hover {
                background: #e9ecef;
                color: #0d6efd;
            }

            .nav-tabs .nav-link.active {
                background: #ffffff;
                color: #0d6efd;
                border-color: #dee2e6;
                border-bottom-color: #ffffff;
                font-weight: 600;
            }

            .bg-opacity-10 {
                --bs-bg-opacity: 0.1;
            }

            .rounded-3 {
                border-radius: 12px !important;
            }
        </style>
    @endpush
@endsection
