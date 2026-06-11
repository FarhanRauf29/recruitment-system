@extends('layouts.admin')
@section('title', 'Detail Konfigurasi Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i> Detail Konfigurasi Psikotes
                            </h4>
                            <p class="small mb-0 mt-1 opacity-75">Informasi lengkap konfigurasi psikotes</p>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.konfigurasipsikotes.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                            <a href="{{ route('admin.konfigurasipsikotes.edit', $konfigurasi->id) }}"
                                class="btn btn-warning btn-sm ms-1">
                                <i class="fas fa-edit me-1"></i> Edit Konfigurasi
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10 mx-auto">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <div class="text-center mb-4">
                                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                                style="width: 80px; height: 80px;">
                                                <i class="fas fa-cog fa-3x"></i>
                                            </div>
                                            <h3 class="mb-2">{{ $konfigurasi->lowongan->judul_lowongan ?? '-' }}</h3>
                                            @if ($konfigurasi->aktif)
                                                <span class="badge bg-success px-3 py-2"><i
                                                        class="fas fa-check-circle me-1"></i> Aktif</span>
                                            @else
                                                <span class="badge bg-danger px-3 py-2"><i class="fas fa-ban me-1"></i>
                                                    Nonaktif</span>
                                            @endif
                                        </div>

                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-hourglass-half"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Durasi Pengerjaan</small>
                                                        <h6 class="mb-0">{{ $konfigurasi->durasi_menit }} menit</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-chart-line"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Batas Nilai Kelulusan</small>
                                                        <h6 class="mb-0">{{ $konfigurasi->batas_nilai_kelulusan }}%</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-brain"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Soal Kognitif</small>
                                                        <h6 class="mb-0">{{ $konfigurasi->jumlah_soal_kognitif }} soal
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-user-check"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Soal Kepribadian</small>
                                                        <h6 class="mb-0">{{ $konfigurasi->jumlah_soal_kepribadian }} soal
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-heart"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Soal Minat</small>
                                                        <h6 class="mb-0">{{ $konfigurasi->jumlah_soal_minat }} soal</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-calculator"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Total Soal</small>
                                                        <h6 class="mb-0">{{ $konfigurasi->jumlah_soal_total }} soal</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <div class="detail-section">
                                            <div class="detail-header">
                                                <i class="fas fa-tags"></i>
                                                <h6 class="mb-0">Kategori yang Diujikan</h6>
                                            </div>
                                            <div class="detail-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <small class="text-muted d-block mb-2">Kognitif</small>
                                                        <div>
                                                            @if ($konfigurasi->kategori_kognitif)
                                                                @foreach ($konfigurasi->kategori_kognitif as $kategoriId)
                                                                    @php $kategori = \App\Models\KategoriPsikotes::find($kategoriId); @endphp
                                                                    @if ($kategori)
                                                                        <span
                                                                            class="badge bg-info me-1 mb-1">{{ $kategori->nama_kategori }}</span>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                -
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted d-block mb-2">Kepribadian</small>
                                                        <div>
                                                            @if ($konfigurasi->kategori_kepribadian)
                                                                @foreach ($konfigurasi->kategori_kepribadian as $kategoriId)
                                                                    @php $kategori = \App\Models\KategoriPsikotes::find($kategoriId); @endphp
                                                                    @if ($kategori)
                                                                        <span
                                                                            class="badge bg-warning me-1 mb-1">{{ $kategori->nama_kategori }}</span>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                -
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted d-block mb-2">Minat</small>
                                                        <div>
                                                            @if ($konfigurasi->kategori_minat)
                                                                @foreach ($konfigurasi->kategori_minat as $kategoriId)
                                                                    @php $kategori = \App\Models\KategoriPsikotes::find($kategoriId); @endphp
                                                                    @if ($kategori)
                                                                        <span
                                                                            class="badge bg-success me-1 mb-1">{{ $kategori->nama_kategori }}</span>
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                -
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="detail-section">
                                            <div class="detail-header">
                                                <i class="fas fa-history"></i>
                                                <h6 class="mb-0">Informasi Lainnya</h6>
                                            </div>
                                            <div class="detail-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Tanggal Dibuat</small>
                                                        <p class="mb-0">
                                                            {{ $konfigurasi->created_at->translatedFormat('l, d F Y') }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Terakhir Diperbarui</small>
                                                        <p class="mb-0">
                                                            {{ $konfigurasi->updated_at->translatedFormat('l, d F Y') }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-6 mt-2">
                                                        <small class="text-muted">Jumlah Sesi Psikotes</small>
                                                        <p class="mb-0">{{ $konfigurasi->sesiPsikotes()->count() }} sesi
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            transition: all 0.2s;
        }

        .info-card:hover {
            background: #e9ecef;
        }

        .info-icon {
            width: 45px;
            height: 45px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 1.2rem;
        }

        .info-content {
            flex: 1;
        }

        .detail-section {
            margin-bottom: 20px;
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 12px;
        }

        .detail-header i {
            color: #2563eb;
            font-size: 1.1rem;
        }

        .detail-header h6 {
            margin: 0;
            font-weight: 600;
        }

        .detail-body {
            padding: 5px 0 5px 28px;
        }

        @media (max-width: 768px) {
            .info-card {
                flex-direction: column;
                text-align: center;
            }

            .detail-body {
                padding-left: 0;
            }
        }
    </style>
@endsection
