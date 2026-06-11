@extends('layouts.admin')
@section('title', 'Detail Kategori Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i> Detail Kategori Psikotes
                            </h4>
                            <p class="small mb-0 mt-1 opacity-75">Informasi lengkap kategori psikotes</p>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.kategoripsikotes.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                            <a href="{{ route('admin.kategoripsikotes.edit', $kategori->id) }}"
                                class="btn btn-warning btn-sm ms-1">
                                <i class="fas fa-edit me-1"></i> Edit Kategori
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
                                                <i class="fas fa-layer-group fa-3x"></i>
                                            </div>
                                            <h3 class="mb-2">{{ $kategori->nama_kategori }}</h3>
                                            @if ($kategori->aktif)
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
                                                        <i class="fas fa-code"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Kode Kategori</small>
                                                        <h6 class="mb-0"><span
                                                                class="badge bg-secondary">{{ $kategori->kode_kategori }}</span>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-layer-group"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Jenis Kategori</small>
                                                        <h6 class="mb-0">
                                                            <span
                                                                class="badge {{ $kategori->jenis == 'kognitif' ? 'bg-info' : ($kategori->jenis == 'kepribadian' ? 'bg-warning' : 'bg-success') }}">
                                                                {{ ucfirst($kategori->jenis) }}
                                                            </span>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-sort-numeric-down"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Urutan</small>
                                                        <h6 class="mb-0">{{ $kategori->urutan }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-question-circle"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Jumlah Soal</small>
                                                        <h6 class="mb-0">{{ $kategori->soalPsikotes()->count() }} soal
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        @if ($kategori->deskripsi)
                                            <div class="detail-section">
                                                <div class="detail-header">
                                                    <i class="fas fa-align-left"></i>
                                                    <h6 class="mb-0">Deskripsi</h6>
                                                </div>
                                                <div class="detail-body">
                                                    <p class="mb-0">{{ $kategori->deskripsi }}</p>
                                                </div>
                                            </div>
                                        @endif

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
                                                            {{ $kategori->created_at->translatedFormat('l, d F Y') }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Terakhir Diperbarui</small>
                                                        <p class="mb-0">
                                                            {{ $kategori->updated_at->translatedFormat('l, d F Y') }}</p>
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
