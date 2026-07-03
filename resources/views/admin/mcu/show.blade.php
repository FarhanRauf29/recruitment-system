@extends('layouts.admin')

@section('title', 'Detail Medical Check Up')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="fas fa-heartbeat me-2"></i> Detail Medical Check Up
                            </h4>
                            <p class="small mb-0 mt-1 opacity-75">Informasi lengkap jadwal dan hasil MCU pelamar</p>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.mcu.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <a href="{{ route('admin.mcu.edit', $mcu->id) }}" class="btn btn-warning btn-sm ms-1">
                                <i class="fas fa-edit me-1"></i> Edit Jadwal
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-user"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Nama Pelamar</small>
                                        <h6 class="mb-0">{{ $mcu->pelamar->nama_lengkap ?? '-' }}</h6>
                                        <small class="text-muted">{{ $mcu->pelamar->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-briefcase"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Lowongan</small>
                                        <h6 class="mb-0">{{ $mcu->lowongan->judul_lowongan ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Tanggal MCU</small>
                                        <h6 class="mb-0">
                                            {{ Carbon\Carbon::parse($mcu->jadwal_mcu)->translatedFormat('l, d F Y') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-clock"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Pukul</small>
                                        <h6 class="mb-0">{{ Carbon\Carbon::parse($mcu->jadwal_mcu)->format('H:i') }} WIB
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Lokasi MCU</small>
                                        <h6 class="mb-0">{{ $mcu->lokasi_mcu }}</h6>
                                        <small class="text-muted">{{ $mcu->alamat_lokasi }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-flag-checkered"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Status MCU</small>
                                        <h6 class="mb-0">
                                            <span
                                                class="badge bg-{{ $mcu->status_badge }} px-3 py-2">{{ $mcu->status_text }}</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            @if ($mcu->dokter_pemeriksa)
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-icon"><i class="fas fa-user-md"></i></div>
                                        <div class="info-content">
                                            <small class="text-muted d-block">Dokter Pemeriksa</small>
                                            <h6 class="mb-0">{{ $mcu->dokter_pemeriksa }}</h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($mcu->nama_rs)
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-icon"><i class="fas fa-hospital"></i></div>
                                        <div class="info-content">
                                            <small class="text-muted d-block">Rumah Sakit</small>
                                            <h6 class="mb-0">{{ $mcu->nama_rs }}</h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($mcu->kondisi_kesehatan)
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-icon"><i class="fas fa-stethoscope"></i></div>
                                        <div class="info-content">
                                            <small class="text-muted d-block">Kondisi Kesehatan</small>
                                            <h6 class="mb-0">
                                                <span
                                                    class="badge bg-{{ $mcu->kondisi_kesehatan == 'Sangat Sehat' ? 'success' : ($mcu->kondisi_kesehatan == 'Sehat' ? 'primary' : ($mcu->kondisi_kesehatan == 'Cukup Sehat' ? 'warning' : 'danger')) }} px-3 py-2">
                                                    {{ $mcu->kondisi_kesehatan }}
                                                </span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($mcu->nilai_mcu)
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-icon"><i class="fas fa-chart-line"></i></div>
                                        <div class="info-content">
                                            <small class="text-muted d-block">Skor MCU</small>
                                            <h6 class="mb-0">
                                                <span class="badge bg-primary px-3 py-2">
                                                    @php
                                                        $skorTampil = $mcu->nilai_mcu;
                                                        if ($skorTampil > 100) {
                                                            $skorTampil = $skorTampil / 100;
                                                        }
                                                        echo number_format($skorTampil, 2, ',', '.');
                                                    @endphp
                                                </span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($mcu->keterangan)
                                <div class="col-12">
                                    <div class="info-card full-width">
                                        <div class="info-icon"><i class="fas fa-info-circle"></i></div>
                                        <div class="info-content">
                                            <small class="text-muted d-block">Keterangan Tambahan</small>
                                            <p class="mb-0">{{ $mcu->keterangan }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            @if ($mcu->catatan_admin)
                                <div class="col-12">
                                    <div class="info-card full-width">
                                        <div class="info-icon"><i class="fas fa-sticky-note"></i></div>
                                        <div class="info-content">
                                            <small class="text-muted d-block">Catatan Admin</small>
                                            <p class="mb-0">{{ $mcu->catatan_admin }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        <h5 class="text-primary mb-3"><i class="fas fa-clipboard-list me-2"></i>Hasil Pemeriksaan</h5>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-weight"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Tinggi Badan</small>
                                        <h6 class="mb-0">{{ $mcu->tinggi_badan ? $mcu->tinggi_badan . ' cm' : '-' }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-weight"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Berat Badan</small>
                                        <h6 class="mb-0">{{ $mcu->berat_badan ? $mcu->berat_badan . ' kg' : '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-calculator"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">BMI</small>
                                        <h6 class="mb-0">{{ $mcu->bmi ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-heart"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Tekanan Darah</small>
                                        <h6 class="mb-0">{{ $mcu->tekanan_darah ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-eye"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Visus Mata</small>
                                        <h6 class="mb-0">{{ $mcu->visus_mata ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-eye"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Buta Warna</small>
                                        <h6 class="mb-0">{{ $mcu->buta_warna ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-syringe"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Hasil Narkoba</small>
                                        <h6 class="mb-0">{{ $mcu->hasil_narkoba ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-notes-medical"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Riwayat Penyakit</small>
                                        <h6 class="mb-0">{{ $mcu->riwayat_penyakit ?? 'Tidak ada' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('admin.mcu.input_hasil', $mcu->id) }}" class="btn btn-success">
                                <i class="fas fa-stethoscope me-1"></i> Input Hasil MCU
                            </a>
                            <a href="{{ route('admin.mcu.surat_panggilan', $mcu->id) }}" class="btn btn-primary">
                                <i class="fas fa-download me-1"></i> Unduh Surat Panggilan
                            </a>
                            @if ($mcu->kondisi_kesehatan)
                                <a href="{{ route('admin.mcu.surat_hasil', $mcu->id) }}" class="btn btn-info">
                                    <i class="fas fa-file-pdf me-1"></i> Unduh Hasil MCU
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .info-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .info-card.full-width {
            width: 100%;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .info-content {
            flex: 1;
        }

        .info-content small {
            font-size: 0.7rem;
        }

        .info-content h6 {
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .info-card {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endsection
