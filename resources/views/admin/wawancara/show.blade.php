@extends('layouts.admin')

@section('title', 'Detail Wawancara')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="fas fa-chalkboard-user me-2"></i> Detail Wawancara
                            </h4>
                            <p class="small mb-0 mt-1 opacity-75">Informasi lengkap jadwal dan hasil wawancara pelamar</p>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.wawancara.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <a href="{{ route('admin.wawancara.edit', $wawancara->id) }}"
                                class="btn btn-warning btn-sm ms-1">
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
                                        <h6 class="mb-0">{{ $wawancara->pelamar->nama_lengkap ?? '-' }}</h6>
                                        <small class="text-muted">{{ $wawancara->pelamar->email ?? '-' }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-briefcase"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Lowongan</small>
                                        <h6 class="mb-0">{{ $wawancara->lowongan->judul_lowongan ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Tanggal Wawancara</small>
                                        <h6 class="mb-0">
                                            {{ Carbon\Carbon::parse($wawancara->jadwal_wawancara)->format('l, d F Y') }}
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-clock"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Pukul</small>
                                        <h6 class="mb-0">
                                            {{ Carbon\Carbon::parse($wawancara->jadwal_wawancara)->format('H:i') }} WIB</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-video"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Jenis Wawancara</small>
                                        <h6 class="mb-0">
                                            @if ($wawancara->jenis_wawancara == 'online')
                                                <span class="badge bg-success">Online (Video Call)</span>
                                            @else
                                                <span class="badge bg-warning">Offline (Tatap Muka)</span>
                                            @endif
                                        </h6>
                                        @if ($wawancara->jenis_wawancara == 'online' && $wawancara->link_wawancara)
                                            <a href="{{ $wawancara->link_wawancara }}" target="_blank"
                                                class="btn btn-sm btn-primary mt-2">
                                                <i class="fas fa-video me-1"></i> Join Interview
                                            </a>
                                        @elseif($wawancara->jenis_wawancara == 'offline')
                                            <small
                                                class="text-muted d-block mt-1">{{ $wawancara->lokasi_wawancara }}</small>
                                            <small class="text-muted">{{ $wawancara->alamat_lokasi }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-flag-checkered"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Status Wawancara</small>
                                        <h6 class="mb-0">
                                            <span
                                                class="badge bg-{{ $wawancara->status_badge }} px-3 py-2">{{ $wawancara->status_text }}</span>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            @if ($wawancara->pewawancara)
                                <div class="col-md-6">
                                    <div class="info-card">
                                        <div class="info-icon"><i class="fas fa-user-tie"></i></div>
                                        <div class="info-content">
                                            <small class="text-muted d-block">Pewawancara</small>
                                            <h6 class="mb-0">{{ $wawancara->pewawancara }}</h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        @if ($wawancara->keterangan)
                            <div class="info-card full-width mt-2">
                                <div class="info-icon"><i class="fas fa-info-circle"></i></div>
                                <div class="info-content">
                                    <small class="text-muted d-block">Keterangan Tambahan</small>
                                    <p class="mb-0">{{ $wawancara->keterangan }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($wawancara->catatan_admin)
                            <div class="info-card full-width mt-2">
                                <div class="info-icon"><i class="fas fa-sticky-note"></i></div>
                                <div class="info-content">
                                    <small class="text-muted d-block">Catatan Admin</small>
                                    <p class="mb-0">{{ $wawancara->catatan_admin }}</p>
                                </div>
                            </div>
                        @endif

                        @if (
                            $wawancara->nilai_total ||
                                $wawancara->nilai_teknis ||
                                $wawancara->nilai_komunikasi ||
                                $wawancara->nilai_pengalaman ||
                                $wawancara->nilai_attitude)
                            <hr class="my-4">
                            <div class="detail-section">
                                <div class="detail-header">
                                    <i class="fas fa-star"></i>
                                    <h6 class="mb-0">Hasil Penilaian Wawancara</h6>
                                </div>
                                <div class="detail-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <div class="nilai-card">
                                                <div class="nilai-icon"><i class="fas fa-microchip"></i></div>
                                                <div class="nilai-info">
                                                    <small class="text-muted">Nilai Teknis</small>
                                                    <h4
                                                        class="mb-0 {{ ($wawancara->nilai_teknis ?? 0) >= 70 ? 'text-success' : 'text-danger' }}">
                                                        {{ $wawancara->nilai_teknis ?? '-' }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="nilai-card">
                                                <div class="nilai-icon"><i class="fas fa-comments"></i></div>
                                                <div class="nilai-info">
                                                    <small class="text-muted">Komunikasi</small>
                                                    <h4
                                                        class="mb-0 {{ ($wawancara->nilai_komunikasi ?? 0) >= 70 ? 'text-success' : 'text-danger' }}">
                                                        {{ $wawancara->nilai_komunikasi ?? '-' }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="nilai-card">
                                                <div class="nilai-icon"><i class="fas fa-briefcase"></i></div>
                                                <div class="nilai-info">
                                                    <small class="text-muted">Pengalaman</small>
                                                    <h4
                                                        class="mb-0 {{ ($wawancara->nilai_pengalaman ?? 0) >= 70 ? 'text-success' : 'text-danger' }}">
                                                        {{ $wawancara->nilai_pengalaman ?? '-' }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="nilai-card">
                                                <div class="nilai-icon"><i class="fas fa-smile"></i></div>
                                                <div class="nilai-info">
                                                    <small class="text-muted">Attitude</small>
                                                    <h4
                                                        class="mb-0 {{ ($wawancara->nilai_attitude ?? 0) >= 70 ? 'text-success' : 'text-danger' }}">
                                                        {{ $wawancara->nilai_attitude ?? '-' }}
                                                    </h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if ($wawancara->nilai_total)
                                        <div class="text-center mt-3">
                                            <span class="badge bg-primary rounded-pill px-4 py-2 fs-6">
                                                <i class="fas fa-calculator me-1"></i> Nilai Total:
                                                {{ $wawancara->nilai_total }}
                                            </span>
                                        </div>
                                    @endif
                                    @if ($wawancara->catatan_hr)
                                        <div class="mt-3 p-3 bg-light rounded">
                                            <small class="text-muted d-block mb-1"><i class="fas fa-sticky-note me-1"></i>
                                                Catatan HR:</small>
                                            <p class="mb-0">{{ $wawancara->catatan_hr }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($wawancara->hasil_wawancara_file)
                            <div class="detail-section">
                                <div class="detail-header">
                                    <i class="fas fa-folder-open"></i>
                                    <h6 class="mb-0">Dokumen Wawancara</h6>
                                </div>
                                <div class="detail-body">
                                    <a href="{{ route('file.view', ['model' => 'wawancara', 'id' => $wawancara->id, 'field' => 'hasil_wawancara_file']) }}"
                                        target="_blank" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-file-pdf me-1"></i> Hasil Wawancara
                                    </a>
                                </div>
                            </div>
                        @endif

                        <hr class="my-4">

                        <div class="detail-section">
                            <div class="detail-header">
                                <i class="fas fa-star"></i>
                                <h6 class="mb-0">Input Nilai Wawancara</h6>
                            </div>
                            <div class="detail-body">
                                <form action="{{ route('admin.wawancara.nilai', $wawancara->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <input type="number" name="nilai_teknis" class="form-control"
                                                placeholder="Nilai Teknis" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="nilai_komunikasi" class="form-control"
                                                placeholder="Komunikasi" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="nilai_pengalaman" class="form-control"
                                                placeholder="Pengalaman" min="0" max="100">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="number" name="nilai_attitude" class="form-control"
                                                placeholder="Attitude" min="0" max="100">
                                        </div>
                                        <div class="col-12 mt-2">
                                            <textarea name="catatan_hr" class="form-control" rows="2" placeholder="Catatan HR (opsional)"></textarea>
                                        </div>
                                        <div class="col-12 mt-2">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-save me-1"></i> Simpan Nilai
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="detail-section">
                            <div class="detail-header">
                                <i class="fas fa-upload"></i>
                                <h6 class="mb-0">Upload Hasil Wawancara</h6>
                            </div>
                            <div class="detail-body">
                                <form action="{{ route('admin.wawancara.upload-hasil', $wawancara->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-2">
                                        <div class="col-md-9">
                                            <input type="file" name="hasil_wawancara_file" class="form-control"
                                                accept=".pdf,.jpg,.jpeg,.png" required>
                                            <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Max 5MB)</small>
                                        </div>
                                        <div class="col-md-3">
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
                                <h6 class="mb-0">Verifikasi Hasil Wawancara</h6>
                            </div>
                            <div class="detail-body">
                                <form action="{{ route('admin.wawancara.verifikasi', $wawancara->id) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <select name="status" class="form-select" required>
                                                <option value="">-- Pilih Status Verifikasi --</option>
                                                <option value="lolos">✅ Lolos Wawancara</option>
                                                <option value="gagal">❌ Gagal Wawancara</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="catatan_admin" class="form-control"
                                                placeholder="Catatan hasil verifikasi (opsional)">
                                        </div>
                                        <div class="col-md-2">
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

        .nilai-card {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            justify-content: center;
        }

        .nilai-icon {
            width: 40px;
            height: 40px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
        }

        .nilai-info {
            text-align: left;
        }

        @media (max-width: 768px) {
            .info-card {
                flex-direction: column;
                text-align: center;
            }

            .detail-body {
                padding-left: 0;
            }

            .nilai-card {
                flex-direction: column;
                text-align: center;
            }

            .nilai-info {
                text-align: center;
            }
        }
    </style>
@endsection
