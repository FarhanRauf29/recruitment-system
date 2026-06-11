@extends('layouts.pelamar')

@section('content')
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div
                        class="card-header bg-white border-bottom py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h3 class="card-title mb-0 fw-bold text-dark">
                                <i class="fas fa-briefcase text-primary me-2"></i> {{ $lowongan->judul_lowongan }}
                            </h3>
                        </div>
                        <a href="{{ route('pelamar.lowongan.index') }}"
                            class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 rounded-3"
                        role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-4 p-md-5">
                        <div class="mb-5">
                            <h5 class="fw-bold text-primary mb-3 d-flex align-items-center">
                                <span class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                    <i class="fas fa-align-left"></i>
                                </span>
                                Deskripsi Pekerjaan
                            </h5>
                            <div class="text-secondary lh-lg">
                                {!! nl2br(e($lowongan->deskripsi)) !!}
                            </div>
                        </div>

                        <hr class="my-4 text-muted opacity-75">

                        <div>
                            <h5 class="fw-bold text-primary mb-3 d-flex align-items-center">
                                <span class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                    <i class="fas fa-clipboard-check"></i>
                                </span>
                                Persyaratan Kandidat
                            </h5>
                            <div class="text-secondary lh-lg">
                                {!! nl2br(e($lowongan->persyaratan)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 20px; z-index: 10;">
                    <div class="card-header bg-gradient bg-primary text-white py-3 rounded-top-4">
                        <h5 class="card-title mb-0 fw-bold"><i class="fas fa-info-circle me-2"></i>Informasi Lowongan</h5>
                    </div>
                    <div class="card-body p-4">
                        <ul class="list-group list-group-flush mb-4">
                            <li class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-alt text-muted me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted d-block">Tanggal Buka</small>
                                        <strong
                                            class="text-dark">{{ \Carbon\Carbon::parse($lowongan->tanggal_buka)->format('d M Y') }}</strong>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-calendar-times text-muted me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted d-block">Batas Akhir</small>
                                        <strong
                                            class="text-dark">{{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('d M Y') }}</strong>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item px-0 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-signal text-muted me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted d-block">Status</small>
                                        <span
                                            class="badge rounded-pill bg-{{ $lowongan->status == 'buka' ? 'success' : 'secondary' }} px-3 py-2">
                                            {{ ucfirst($lowongan->status) }}
                                        </span>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item px-0 py-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-users text-muted me-3 fa-lg"></i>
                                    <div>
                                        <small class="text-muted d-block">Pelamar</small>
                                        @php
                                            $terisi = $lowongan->pelamars()->count();
                                            $total = $lowongan->slot;
                                            $persentase = $total > 0 ? ($terisi / $total) * 100 : 0;
                                        @endphp
                                        <strong class="text-dark">{{ $terisi }} dari {{ $total }}
                                            slot</strong>
                                        <div class="progress mt-2" style="height: 8px; border-radius: 4px;">
                                            <div class="progress-bar bg-{{ $persentase >= 100 ? 'danger' : 'info' }}"
                                                role="progressbar" style="width: {{ $persentase }}%"
                                                aria-valuenow="{{ $persentase }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>

                        @php
                            $sudahMelamar = $lowongan
                                ->pelamars()
                                ->where('user_id', auth()->id())
                                ->exists();
                        @endphp

                        @if ($sudahMelamar)
                            <div class="d-grid mb-2">
                                <button
                                    class="btn btn-success d-flex align-items-center justify-content-center gap-2 py-3 fw-bold rounded-3"
                                    disabled>
                                    <i class="fas fa-check-circle"></i> Telah Melamar
                                </button>
                            </div>
                            <div class="text-center">
                                <small class="text-muted fst-italic">
                                    <i class="fas fa-info-circle me-1"></i> Proses seleksi sedang berlangsung
                                </small>
                            </div>
                        @elseif ($lowongan->status != 'buka')
                            <div class="d-grid">
                                <button
                                    class="btn btn-secondary d-flex align-items-center justify-content-center gap-2 py-3 fw-bold rounded-3"
                                    disabled>
                                    <i class="fas fa-lock"></i> Lowongan Ditutup
                                </button>
                            </div>
                        @elseif (!$lowongan->isSlotAvailable())
                            <div class="d-grid">
                                <button
                                    class="btn btn-warning d-flex align-items-center justify-content-center gap-2 py-3 fw-bold text-dark rounded-3"
                                    disabled>
                                    <i class="fas fa-user-times"></i> Slot Penuh
                                </button>
                            </div>
                        @else
                            <form action="{{ route('pelamar.lowongan.apply', $lowongan) }}" method="POST">
                                @csrf
                                <div class="d-grid mb-2">
                                    <button type="submit"
                                        class="btn btn-primary py-3 fw-bold rounded-3 shadow-sm hover-lift">
                                        <i class="fas fa-paper-plane me-2"></i> Lamar Posisi Ini
                                    </button>
                                </div>
                            </form>
                            <div class="text-center">
                                <small class="text-muted fst-italic">
                                    <i class="fas fa-shield-alt me-1"></i> Data aman dan terlindungi
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-lift:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(13, 110, 253, 0.35) !important;
        }

        .bg-gradient {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }
    </style>
@endsection
