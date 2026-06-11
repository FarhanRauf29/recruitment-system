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
                                        <h6 class="mb-0">{{ Carbon\Carbon::parse($mcu->jadwal_mcu)->format('l, d F Y') }}
                                        </h6>
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
                            @if ($mcu->kondisi_kesehatan)
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-icon"><i class="fas fa-stethoscope"></i></div>
                                        <div class="info-content">
                                            <small class="text-muted d-block">Kondisi Kesehatan</small>
                                            <h6 class="mb-0">
                                                <span
                                                    class="badge bg-{{ $mcu->kondisi_kesehatan == 'Sangat Sehat' ? 'success' : ($mcu->kondisi_kesehatan == 'Sehat' ? 'primary' : ($mcu->kondisi_kesehatan == 'Cukup' ? 'warning' : 'danger')) }} px-3 py-2">
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
                                            <small class="text-muted d-block">Nilai MCU</small>
                                            <h6 class="mb-0">
                                                <span class="badge bg-primary px-3 py-2">{{ $mcu->nilai_mcu }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
                        </div>

                        @if ($mcu->keterangan)
                            <div class="info-card full-width mt-2">
                                <div class="info-icon"><i class="fas fa-info-circle"></i></div>
                                <div class="info-content">
                                    <small class="text-muted d-block">Keterangan Tambahan</small>
                                    <p class="mb-0">{{ $mcu->keterangan }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($mcu->catatan_admin)
                            <div class="info-card full-width mt-2">
                                <div class="info-icon"><i class="fas fa-sticky-note"></i></div>
                                <div class="info-content">
                                    <small class="text-muted d-block">Catatan Admin</small>
                                    <p class="mb-0">{{ $mcu->catatan_admin }}</p>
                                </div>
                            </div>
                        @endif

                        <hr class="my-4">

                        @if ($mcu->hasil_mcu_file || $mcu->dokumen_pendukung_file || $mcu->surat_mcu_file)
                            <div class="detail-section">
                                <div class="detail-header">
                                    <i class="fas fa-folder-open"></i>
                                    <h6 class="mb-0">Dokumen MCU</h6>
                                </div>
                                <div class="detail-body">
                                    <div class="d-flex flex-wrap gap-3">
                                        @if ($mcu->hasil_mcu_file)
                                            <a href="{{ route('file.view', ['model' => 'mcu', 'id' => $mcu->id, 'field' => 'hasil_mcu_file']) }}"
                                                target="_blank" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-file-pdf me-1"></i> Hasil MCU
                                            </a>
                                        @endif
                                        @if ($mcu->dokumen_pendukung_file)
                                            <a href="{{ route('file.view', ['model' => 'mcu', 'id' => $mcu->id, 'field' => 'dokumen_pendukung_file']) }}"
                                                target="_blank" class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-paperclip me-1"></i> Dokumen Pendukung
                                            </a>
                                        @endif
                                        @if ($mcu->surat_mcu_file)
                                            <a href="{{ route('admin.mcu.generate-surat', $mcu->id) }}"
                                                class="btn btn-outline-success btn-sm">
                                                <i class="fas fa-print me-1"></i> Cetak Surat MCU
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="detail-section">
                            <div class="detail-header">
                                <i class="fas fa-upload"></i>
                                <h6 class="mb-0">Upload Hasil MCU</h6>
                            </div>
                            <div class="detail-body">
                                <form action="{{ route('admin.mcu.upload-hasil', $mcu->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">File Hasil MCU</label>
                                            <input type="file" name="hasil_mcu_file" class="form-control"
                                                accept=".pdf,.jpg,.jpeg,.png" required>
                                            <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Max 5MB)</small>
                                        </div>
                                        {{-- <div class="col-md-3">
                                            <label class="form-label fw-semibold">Kondisi Kesehatan</label>
                                            <select name="kondisi_kesehatan" class="form-select">
                                                <option value="">-- Pilih Kondisi --</option>
                                                <option value="Sangat Sehat">Sangat Sehat </option>
                                                <option value="Sehat">Sehat </option>
                                                <option value="Cukup">Cukup </option>
                                                <option value="Kurang">Kurang </option>
                                                <option value="Tidak Lulus">Tidak Lulus </option>
                                            </select>
                                        </div> --}}
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Keterangan Hasil</label>
                                            <input type="text" name="hasil_mcu_keterangan" class="form-control"
                                                placeholder="Keterangan hasil">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">&nbsp;</label>
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-upload me-1"></i> Upload
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="detail-section">
                            <div class="detail-header">
                                <i class="fas fa-clipboard-check"></i>
                                <h6 class="mb-0">Verifikasi Hasil MCU</h6>
                            </div>
                            <div class="detail-body">
                                <form action="{{ route('admin.mcu.verifikasi', $mcu->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Status Verifikasi</label>
                                            <select name="status" class="form-select" required>
                                                <option value="">-- Pilih Status --</option>
                                                <option value="lolos">Lolos MCU</option>
                                                <option value="gagal">Gagal MCU</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Kondisi Kesehatan</label>
                                            <select name="kondisi_kesehatan" class="form-select">
                                                <option value="">-- Pilih Kondisi --</option>
                                                <option value="Sangat Sehat">Sangat Sehat </option>
                                                <option value="Sehat">Sehat </option>
                                                <option value="Cukup">Cukup </option>
                                                <option value="Kurang">Kurang </option>
                                                <option value="Tidak Lulus">Tidak Lulus </option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-semibold">Catatan Admin</label>
                                            <input type="text" name="catatan_admin" class="form-control"
                                                placeholder="Catatan hasil verifikasi">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">&nbsp;</label>
                                            <button type="submit" class="btn btn-success w-100">
                                                <i class="fas fa-check-circle me-1"></i> Verifikasi
                                            </button>
                                        </div>
                                    </div>
                                </form>
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
            margin-bottom: 15px;
        }

        .info-card.full-width {
            width: 100%;
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
            margin-bottom: 25px;
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 15px;
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
            padding: 0 0 0 28px;
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
