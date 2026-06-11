@extends('layouts.admin')
@section('title', 'Detail Lowongan')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="fas fa-briefcase me-2"></i> Detail Lowongan
                            </h4>
                            <p class="small mb-0 mt-1 opacity-75">Informasi lengkap lowongan pekerjaan</p>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.lowongan.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                            <a href="{{ route('admin.lowongan.edit', $lowongan->id) }}" class="btn btn-warning btn-sm ms-1">
                                <i class="fas fa-edit me-1"></i> Edit Lowongan
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
                                                <i class="fas fa-briefcase fa-3x"></i>
                                            </div>
                                            <h3 class="mb-2">{{ $lowongan->judul_lowongan }}</h3>
                                            @if ($lowongan->status == 'buka')
                                                <span class="badge bg-success px-3 py-2"><i
                                                        class="fas fa-check-circle me-1"></i> Buka</span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2"><i class="fas fa-ban me-1"></i>
                                                    Tutup</span>
                                            @endif
                                        </div>

                                        <div class="row g-4">
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-calendar-alt"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Tanggal Buka</small>
                                                        <h6 class="mb-0">
                                                            {{ \Carbon\Carbon::parse($lowongan->tanggal_buka)->translatedFormat('l, d F Y') }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-calendar-times"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Tanggal Tutup</small>
                                                        <h6 class="mb-0">
                                                            {{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->translatedFormat('l, d F Y') }}
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-users"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Slot Lowongan</small>
                                                        <h6 class="mb-0"><span
                                                                class="badge bg-info">{{ $lowongan->pelamars()->count() }}/{{ $lowongan->slot }}</span>
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="fas fa-tag"></i>
                                                    </div>
                                                    <div class="info-content">
                                                        <small class="text-muted d-block">Status Lowongan</small>
                                                        <h6 class="mb-0">{{ ucfirst($lowongan->status) }}</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <div class="detail-section">
                                            <div class="detail-header">
                                                <i class="fas fa-align-left"></i>
                                                <h6 class="mb-0">Deskripsi Pekerjaan</h6>
                                            </div>
                                            <div class="detail-body">
                                                <p class="mb-0">{{ $lowongan->deskripsi ?: '-' }}</p>
                                            </div>
                                        </div>

                                        <div class="detail-section">
                                            <div class="detail-header">
                                                <i class="fas fa-check-circle"></i>
                                                <h6 class="mb-0">Persyaratan</h6>
                                            </div>
                                            <div class="detail-body">
                                                <p class="mb-0">{{ $lowongan->persyaratan ?: '-' }}</p>
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
                                                        <small class="text-muted">Dibuat Pada</small>
                                                        <p class="mb-0">
                                                            {{ $lowongan->created_at->translatedFormat('l, d F Y') }}

                                                    </div>
                                                    <div class="col-md-6">
                                                        <small class="text-muted">Terakhir Diperbarui</small>
                                                        <p class="mb-0">
                                                            {{ $lowongan->updated_at->translatedFormat('l, d F Y') }}

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info mt-4 mb-0">
                                            <div class="d-flex">
                                                <i class="fas fa-info-circle fa-lg me-3"></i>
                                                <div>
                                                    <strong>Informasi Penting</strong><br>
                                                    <small>Slot lowongan akan otomatis tertutup jika jumlah pelamar sudah
                                                        mencapai slot yang ditentukan.</small>
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
