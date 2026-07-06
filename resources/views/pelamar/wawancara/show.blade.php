@extends('layouts.pelamar')

@section('title', 'Detail Wawancara')

@section('content')
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-0">
                    <div class="card-header bg-primary text-white py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chalkboard-user fa-2x me-3"></i>
                                <div>
                                    <h5 class="mb-0 fw-bold">Detail Wawancara</h5>
                                    <small class="opacity-75">Informasi lengkap jadwal dan hasil wawancara</small>
                                </div>
                            </div>
                            <a href="{{ route('pelamar.wawancara.index') }}"
                                class="btn btn-light btn-sm rounded-pill px-3 mt-2 mt-sm-0">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 rounded-3">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" style="font-size:0.9rem;">
                                <tbody>
                                    <tr>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="width:20%; padding:10px 16px;">Nama Pelamar</td>
                                        <td style="padding:10px 16px;">
                                            <span class="fw-bold">{{ $wawancara->pelamar->nama_lengkap ?? '-' }}</span>
                                        </td>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="width:20%; padding:10px 16px;">Lowongan</td>
                                        <td style="padding:10px 16px;">
                                            <span class="fw-bold">{{ $wawancara->lowongan->judul_lowongan ?? '-' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="padding:10px 16px;">Tanggal Wawancara</td>
                                        <td style="padding:10px 16px;">
                                            <span
                                                class="fw-bold">{{ Carbon\Carbon::parse($wawancara->jadwal_wawancara)->format('l, d F Y') }}</span>
                                            <br><small
                                                class="text-muted">{{ Carbon\Carbon::parse($wawancara->jadwal_wawancara)->format('H:i') }}
                                                WIB</small>
                                        </td>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="padding:10px 16px;">Status</td>
                                        <td style="padding:10px 16px;">
                                            <span
                                                class="badge bg-{{ $wawancara->status_badge }} rounded-pill px-3 py-2">{{ $wawancara->status_text }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="padding:10px 16px;">Pewawancara</td>
                                        <td style="padding:10px 16px;">
                                            <span class="fw-bold">{{ $wawancara->pewawancara ?? '-' }}</span>
                                        </td>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="padding:10px 16px;">Metode Wawancara</td>
                                        <td style="padding:10px 16px;">
                                            <span
                                                class="badge bg-{{ $wawancara->jenis_wawancara == 'online' ? 'success' : 'warning' }} rounded-pill px-3 py-1">
                                                <i
                                                    class="fas fa-{{ $wawancara->jenis_wawancara == 'online' ? 'video' : 'building' }} me-1"></i>
                                                {{ $wawancara->jenis_wawancara == 'online' ? 'Online' : 'Offline' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>


                        @if ($wawancara->jenis_wawancara == 'online')
                            <div class="mt-3">
                                <div class="card border-0 bg-light bg-opacity-50 rounded-3">
                                    <div class="card-body p-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <span class="fw-semibold text-muted small text-uppercase">Link
                                                    Wawancara</span>
                                            </div>
                                            <div class="col-md-9">
                                                @if ($wawancara->link_wawancara)
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="text-truncate" style="max-width:300px;">
                                                            <i class="fas fa-link text-primary me-1"></i>
                                                            {{ $wawancara->link_wawancara }}
                                                        </span>
                                                        <a href="{{ $wawancara->link_wawancara }}" target="_blank"
                                                            class="btn btn-primary btn-sm rounded-pill px-3">
                                                            <i class="fas fa-video me-1"></i> Join Interview
                                                        </a>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($wawancara->jenis_wawancara == 'offline')
                            <div class="mt-3">
                                <div class="card border-0 bg-light bg-opacity-50 rounded-3">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span class="fw-semibold text-muted small text-uppercase">Lokasi
                                                    Wawancara</span>
                                            </div>
                                            <div class="col-md-9">
                                                <span class="fw-bold">{{ $wawancara->lokasi_wawancara ?? '-' }}</span>
                                                @if ($wawancara->alamat_lokasi)
                                                    <br><small class="text-muted">{{ $wawancara->alamat_lokasi }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($wawancara->keterangan)
                            <div class="mt-3">
                                <div class="card border-0 bg-light bg-opacity-50 rounded-3">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span class="fw-semibold text-muted small text-uppercase">Keterangan
                                                    Tambahan</span>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $wawancara->keterangan }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($wawancara->catatan_admin)
                            <div class="mt-3">
                                <div class="card border-0 bg-light bg-opacity-50 rounded-3">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <span class="fw-semibold text-muted small text-uppercase">Catatan
                                                    Admin</span>
                                            </div>
                                            <div class="col-md-9">
                                                {{ $wawancara->catatan_admin }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($wawancara->status == 'dijadwalkan')
                            <hr class="my-4">
                            <div class="alert alert-warning border-0 rounded-3 d-flex align-items-center">
                                <i class="fas fa-clock fa-2x me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Konfirmasi Kehadiran</h6>
                                    <p class="mb-2 small">Silakan konfirmasi kehadiran Anda untuk jadwal wawancara ini.</p>
                                    <form action="{{ route('pelamar.wawancara.konfirmasi', $wawancara->id) }}"
                                        method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-4">
                                            <i class="fas fa-check-circle me-1"></i> Konfirmasi Kehadiran
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if ($wawancara->status == 'lolos')
                            <hr class="my-4">
                            <div class="alert alert-success border-0 rounded-3 d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">Selamat!</h6>
                                    <p class="mb-0 small">Anda dinyatakan <strong>LOLOS</strong> Wawancara. Silakan lanjut
                                        ke tahap selanjutnya.</p>
                                </div>
                            </div>
                        @endif

                        @if ($wawancara->status == 'gagal')
                            <hr class="my-4">
                            <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center">
                                <i class="fas fa-times-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">Informasi</h6>
                                    <p class="mb-0 small">Anda dinyatakan <strong>TIDAK LOLOS</strong> Wawancara.</p>
                                </div>
                            </div>
                        @endif

                        <div class="card border-0 bg-light bg-opacity-50 rounded-3">
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-primary mb-2">
                                    <i class="fas fa-upload me-2"></i>Upload Dokumen Pendukung
                                </h6>
                                <form action="{{ route('pelamar.wawancara.upload-dokumen', $wawancara->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="d-flex gap-2 align-items-end">
                                        <div class="flex-grow-1">
                                            <input type="file" name="dokumen_pendukung_file"
                                                class="form-control form-control-sm" accept=".pdf,.jpg,.jpeg,.png"
                                                required>
                                            <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Max 5MB)</small>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4"
                                                style="white-space:nowrap;">
                                                <i class="fas fa-upload me-1"></i> Upload
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                @if ($wawancara->dokumen_pendukung_file)
                                    <div class="mt-2">
                                        <a href="{{ route('file.view', ['model' => 'wawancara', 'id' => $wawancara->id, 'field' => 'dokumen_pendukung_file']) }}"
                                            target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                            <i class="fas fa-file me-1"></i> Lihat Dokumen Terupload
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table-bordered td {
            border-color: #dee2e6;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }

        .alert {
            border-radius: 8px;
            padding: 14px 18px;
        }

        .alert-success {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            color: #1b5e20;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3e0, #ffe0b2);
            color: #e65100;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fce4ec, #f8bbd0);
            color: #b71c1c;
        }

        .btn-sm {
            font-size: 0.78rem;
        }

        .rounded-pill {
            border-radius: 50px !important;
        }
    </style>
@endsection
