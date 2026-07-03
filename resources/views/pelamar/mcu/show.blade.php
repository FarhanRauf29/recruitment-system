@extends('layouts.pelamar')

@section('title', 'Detail Medical Check Up')

@section('content')
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-0">
                    <div class="card-header bg-primary text-white py-3 px-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-heartbeat fa-2x me-3"></i>
                                <div>
                                    <h5 class="mb-0 fw-bold">Detail Medical Check Up</h5>
                                    <small class="opacity-75">Informasi lengkap jadwal dan hasil pemeriksaan</small>
                                </div>
                            </div>
                            <a href="{{ route('pelamar.mcu.index') }}"
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

                        <!-- Tabel Informasi Utama -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" style="font-size:0.9rem;">
                                <tbody>
                                    <tr>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="width:20%; padding:10px 16px;">Nama Pelamar</td>
                                        <td style="padding:10px 16px;">
                                            <span class="fw-bold">{{ $mcu->pelamar->nama_lengkap ?? '-' }}</span>
                                        </td>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="width:20%; padding:10px 16px;">Lowongan</td>
                                        <td style="padding:10px 16px;">
                                            <span class="fw-bold">{{ $mcu->lowongan->judul_lowongan ?? '-' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="padding:10px 16px;">Tanggal MCU</td>
                                        <td style="padding:10px 16px;">
                                            <span
                                                class="fw-bold">{{ Carbon\Carbon::parse($mcu->jadwal_mcu)->format('l, d F Y') }}</span>
                                            <br><small
                                                class="text-muted">{{ Carbon\Carbon::parse($mcu->jadwal_mcu)->format('H:i') }}
                                                WIB</small>
                                        </td>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="padding:10px 16px;">Status</td>
                                        <td style="padding:10px 16px;">
                                            <span
                                                class="badge bg-{{ $mcu->status_badge }} rounded-pill px-3 py-2">{{ $mcu->status_text }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="padding:10px 16px;">Lokasi MCU</td>
                                        <td style="padding:10px 16px;">
                                            <span class="fw-bold">{{ $mcu->lokasi_mcu }}</span>
                                            <br><small class="text-muted">{{ $mcu->alamat_lokasi }}</small>
                                        </td>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="padding:10px 16px;">Dokter Pemeriksa</td>
                                        <td style="padding:10px 16px;">
                                            <span class="fw-bold">{{ $mcu->dokter_pemeriksa ?? '-' }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="padding:10px 16px;">Rumah Sakit</td>
                                        <td style="padding:10px 16px;">
                                            <span class="fw-bold">{{ $mcu->nama_rs ?? '-' }}</span>
                                        </td>
                                        <td class="bg-light fw-semibold text-muted small text-uppercase"
                                            style="padding:10px 16px;">Kondisi Kesehatan</td>
                                        <td style="padding:10px 16px;">
                                            @if ($mcu->kondisi_kesehatan)
                                                <span
                                                    class="badge bg-{{ $mcu->kondisi_kesehatan == 'Sangat Sehat' ? 'success' : ($mcu->kondisi_kesehatan == 'Sehat' ? 'primary' : ($mcu->kondisi_kesehatan == 'Cukup Sehat' ? 'warning' : 'danger')) }} rounded-pill px-3 py-2">
                                                    {{ $mcu->kondisi_kesehatan }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @if ($mcu->nilai_mcu)
                                        <tr>
                                            <td class="bg-light fw-semibold text-muted small text-uppercase"
                                                style="padding:10px 16px;">Skor MCU</td>
                                            <td style="padding:10px 16px;" colspan="3">
                                                <span class="fw-bold text-primary">
                                                    @php
                                                        $skorTampil = $mcu->nilai_mcu;
                                                        if ($skorTampil > 100) {
                                                            $skorTampil = $skorTampil / 100;
                                                        }
                                                        echo number_format($skorTampil, 2, ',', '.');
                                                    @endphp
                                                </span>
                                            </td>
                                        </tr>
                                    @endif
                                    @if ($mcu->keterangan)
                                        <tr>
                                            <td class="bg-light fw-semibold text-muted small text-uppercase"
                                                style="padding:10px 16px;">Keterangan Tambahan</td>
                                            <td style="padding:10px 16px;" colspan="3">{{ $mcu->keterangan }}</td>
                                        </tr>
                                    @endif
                                    @if ($mcu->catatan_admin)
                                        <tr>
                                            <td class="bg-light fw-semibold text-muted small text-uppercase"
                                                style="padding:10px 16px;">Catatan Admin</td>
                                            <td style="padding:10px 16px;" colspan="3">{{ $mcu->catatan_admin }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        @if ($mcu->status == 'dijadwalkan')
                            <hr class="my-4">
                            <div class="alert alert-warning border-0 rounded-3 d-flex align-items-center">
                                <i class="fas fa-clock fa-2x me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Konfirmasi Kehadiran</h6>
                                    <p class="mb-2 small">Silakan konfirmasi kehadiran Anda untuk jadwal MCU ini.</p>
                                    <form action="{{ route('pelamar.mcu.konfirmasi', $mcu->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-4">
                                            <i class="fas fa-check-circle me-1"></i> Konfirmasi Kehadiran
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        @if ($mcu->status == 'lolos')
                            <div class="alert alert-success border-0 rounded-3 d-flex align-items-center">
                                <i class="fas fa-check-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">Selamat!</h6>
                                    <p class="mb-0 small">Anda dinyatakan <strong>LOLOS</strong> Medical Check Up. Silakan
                                        lanjut ke tahap selanjutnya.</p>
                                </div>
                            </div>
                        @endif

                        @if ($mcu->status == 'gagal')
                            <div class="alert alert-danger border-0 rounded-3 d-flex align-items-center">
                                <i class="fas fa-times-circle fa-2x me-3"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">Informasi</h6>
                                    <p class="mb-0 small">Anda dinyatakan <strong>TIDAK LOLOS</strong> Medical Check Up.</p>
                                </div>
                            </div>
                        @endif

                        <hr class="my-4">

                        <!-- Upload Dokumen -->
                        <div class="card border-0 bg-light bg-opacity-50 rounded-3">
                            <div class="card-body p-3">
                                <h6 class="fw-bold text-primary mb-2">
                                    <i class="fas fa-upload me-2"></i>Upload Dokumen Pendukung
                                </h6>
                                <form action="{{ route('pelamar.mcu.upload_dokumen', $mcu->id) }}" method="POST"
                                    enctype="multipart/form-data">
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
                                @if ($mcu->dokumen_pendukung_file)
                                    <div class="mt-2">
                                        <a href="{{ route('file.view', ['model' => 'mcu', 'id' => $mcu->id, 'field' => 'dokumen_pendukung_file']) }}"
                                            target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                            <i class="fas fa-file me-1"></i> Lihat Dokumen Terupload
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex flex-wrap gap-2">
                            @if (in_array($mcu->status, ['dijadwalkan', 'dikonfirmasi']))
                                <a href="{{ route('pelamar.mcu.download_surat', $mcu->id) }}"
                                    class="btn btn-success rounded-pill px-4">
                                    <i class="fas fa-file-pdf me-1"></i> Download Surat Panggilan
                                </a>
                            @endif
                            @if ($mcu->kondisi_kesehatan)
                                <a href="{{ route('pelamar.mcu.download_surat_hasil', $mcu->id) }}"
                                    class="btn btn-primary text-white rounded-pill px-4">
                                    <i class="fas fa-file-pdf me-1"></i> Download Hasil MCU
                                </a>
                            @endif

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
