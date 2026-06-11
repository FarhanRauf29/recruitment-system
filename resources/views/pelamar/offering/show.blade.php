@extends('layouts.pelamar')

@section('title', 'Detail Penawaran')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="bg-primary rounded" style="width: 4px; height: 24px;"></div>
                        <h4 class="fw-bold mb-0" style="color: #1e293b;">Detail Penawaran Kerja</h4>
                    </div>
                    <p class="text-muted small ms-2">Informasi lengkap penawaran kerja untuk Anda</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success border-0 bg-success bg-opacity-10 rounded-3 py-3 px-4 mb-4"
                        style="border-left: 3px solid #10b981;">
                        <div class="d-flex gap-3 align-items-center">
                            <i class="fas fa-check-circle text-success fs-5"></i>
                            <p class="mb-0 small">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                    <div class="px-4 py-3" style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="fas fa-file-signature text-white fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-0 text-white">Penawaran Kerja</h5>
                                <p class="small text-white opacity-90 mb-0">{{ $offering->posisi }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="alert mb-4 py-3 px-4 rounded-2"
                            style="background: #eff6ff; border-left: 3px solid #f59e0b;">
                            <div class="d-flex gap-3 align-items-center">
                                <i class="fas fa-clock text-warning fs-5"></i>
                                <div>
                                    <span class="small fw-semibold d-block" style="color: #1e293b;">Batas Respon</span>
                                    <p class="small mb-0" style="color: #2563eb;">
                                        {{ \Carbon\Carbon::parse($offering->batas_respon)->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-semibold mb-3" style="color: #1e293b;">
                                <i class="fas fa-briefcase text-primary me-2"></i>Detail Pekerjaan
                            </h6>
                            <div class="bg-light rounded-3 p-4">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <span class="text-muted small">Posisi</span>
                                            <span class="fw-semibold small">{{ $offering->posisi }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <span class="text-muted small">Perusahaan</span>
                                            <span
                                                class="fw-semibold small">{{ $offering->lowongan->perusahaan->nama_perusahaan ?? 'RIAU SAKTI UNITED PLANTATION' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <span class="text-muted small">Gaji</span>
                                            <span class="fw-bold text-primary small">Rp
                                                {{ number_format($offering->gaji, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                            <span class="text-muted small">Tipe Kontrak</span>
                                            <span class="fw-semibold small">{{ ucfirst($offering->tipe_kontrak) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex justify-content-between align-items-center py-2">
                                            <span class="text-muted small">Tanggal Mulai</span>
                                            <span
                                                class="fw-semibold small">{{ \Carbon\Carbon::parse($offering->tanggal_mulai_kerja)->translatedFormat('d F Y') }}</span>
                                        </div>
                                    </div>
                                    @if ($offering->durasi_kontrak)
                                        <div class="col-sm-6">
                                            <div class="d-flex justify-content-between align-items-center py-2">
                                                <span class="text-muted small">Durasi Kontrak</span>
                                                <span class="fw-semibold small">{{ $offering->durasi_kontrak }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if ($offering->catatan_hr)
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3" style="color: #1e293b;">
                                    <i class="fas fa-sticky-note text-primary me-2"></i>Catatan HR
                                </h6>
                                <div class="bg-light rounded-3 p-3">
                                    <div class="d-flex gap-2">
                                        <i class="fas fa-quote-left text-primary opacity-50"></i>
                                        <p class="small mb-0" style="color: #475569;">{{ $offering->catatan_hr }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex flex-wrap gap-3 justify-content-center pt-3">
                            <form action="{{ route('pelamar.offering.accept', $offering->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success px-4 py-2 rounded-pill"
                                    onclick="return confirm('Yakin ingin menerima penawaran ini?')">
                                    <i class="fas fa-check-circle me-2"></i>Terima Penawaran
                                </button>
                            </form>
                            <button type="button" class="btn btn-warning px-4 py-2 rounded-pill" data-bs-toggle="modal"
                                data-bs-target="#counterModal" style="background: #fbbf24; border: none;">
                                <i class="fas fa-exchange-alt me-2"></i>Negosiasi
                            </button>
                            <button type="button" class="btn btn-outline-danger px-4 py-2 rounded-pill"
                                data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times-circle me-2"></i>Tolak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Negosiasi -->
    <div class="modal fade" id="counterModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 pt-4 px-4"
                    style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-exchange-alt text-white"></i>
                        <h5 class="modal-title fw-bold text-white">Ajukan Negosiasi Gaji</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pelamar.offering.counter', $offering->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted">Gaji yang diusulkan</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">Rp</span>
                                <input type="number" name="gaji_counter" class="form-control border-start-0"
                                    step="100000" min="0" required>
                            </div>
                            <div class="form-text text-muted small">Masukkan nominal gaji yang Anda usulkan</div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-muted">Alasan Negosiasi</label>
                            <textarea name="alasan_counter" class="form-control" rows="3"
                                placeholder="Jelaskan alasan Anda mengajukan negosiasi..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning rounded-pill px-4"
                            style="background: #f59e0b; border: none;">Kirim Negosiasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tolak dengan Alasan -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 pt-4 px-4"
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-times-circle text-white"></i>
                        <h5 class="modal-title fw-bold text-white">Tolak Penawaran</h5>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pelamar.offering.reject', $offering->id) }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-0">
                            <label class="form-label fw-semibold small text-muted">Alasan Penolakan <span
                                    class="text-danger">*</span></label>
                            <textarea name="alasan_penolakan" class="form-control" rows="4" required
                                placeholder="Mohon berikan alasan Anda menolak penawaran ini..."></textarea>
                            <div class="form-text text-muted small mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Alasan penolakan akan membantu kami meningkatkan proses rekrutmen ke depannya.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pb-4 px-4">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger rounded-pill px-4">
                            <i class="fas fa-times-circle me-2"></i>Ya, Tolak Penawaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .rounded-3 {
            border-radius: 12px !important;
        }

        .rounded-pill {
            border-radius: 50px !important;
        }

        .shadow-sm {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03) !important;
        }

        .btn-primary {
            background: #2563eb;
            border: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-success {
            background: #10b981;
            border: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .btn-warning {
            background: #f59e0b;
            border: none;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-warning:hover {
            background: #d97706;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
        }

        .btn-outline-danger {
            transition: all 0.2s;
        }

        .btn-outline-danger:hover {
            transform: translateY(-2px);
        }

        .bg-light {
            background-color: #f8fafc !important;
        }

        .bg-opacity-20 {
            --bs-bg-opacity: 0.2;
        }
    </style>
@endpush
