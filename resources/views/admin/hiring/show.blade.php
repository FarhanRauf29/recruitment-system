@extends('layouts.admin')

@section('title', 'Detail Hiring')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div
                        class="card-header bg-primary text-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="fas fa-user-check me-2"></i> Detail Hiring
                            </h5>
                            <small class="opacity-75 mt-1 d-block">Informasi lengkap proses hiring kandidat</small>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.hiring.index', $lowongan->id) }}"
                                class="btn btn-light btn-sm px-3 py-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="alert alert-info bg-light border-start border-4 border-info mb-4 py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-circle text-info me-3 fs-5"></i>
                                <div>
                                    <span class="text-muted small d-block">Kandidat</span>
                                    <strong class="fs-6">{{ $hiring->pelamar->nama_lengkap }}</strong>
                                    <br><small class="text-muted">{{ $hiring->pelamar->email }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3 mb-4">
                                    <div class="card-header bg-white border-bottom pb-2 pt-3 px-4">
                                        <h6 class="fw-bold mb-0 text-primary">
                                            <i class="fas fa-info-circle me-2"></i> Status Hiring
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="text-muted d-block mb-1">Status Saat Ini</span>
                                                @if ($hiring->status == 'hired')
                                                    <span class="badge bg-success px-3 py-2 rounded-pill fs-6">
                                                        <i class="fas fa-check-circle me-1"></i> Hired
                                                    </span>
                                                @elseif($hiring->status == 'probation')
                                                    <span class="badge bg-warning px-3 py-2 rounded-pill fs-6">
                                                        <i class="fas fa-clock me-1"></i> Probation
                                                    </span>
                                                @elseif($hiring->status == 'failed')
                                                    <span class="badge bg-danger px-3 py-2 rounded-pill fs-6">
                                                        <i class="fas fa-times-circle me-1"></i> Failed
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary px-3 py-2 rounded-pill fs-6">
                                                        <i class="fas fa-hourglass-half me-1"></i> Pending
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                                <i class="fas fa-user-check text-primary fa-2x"></i>
                                            </div>
                                        </div>
                                        @if ($hiring->tanggal_hired)
                                            <div class="mt-3 pt-2 border-top">
                                                <small class="text-muted">Tanggal Hiring</small>
                                                <div class="fw-semibold">
                                                    {{ \Carbon\Carbon::parse($hiring->tanggal_hired)->translatedFormat('d F Y, H:i') }}
                                                    WIB</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3 mb-4">
                                    <div class="card-header bg-white border-bottom pb-2 pt-3 px-4">
                                        <h6 class="fw-bold mb-0 text-primary">
                                            <i class="fas fa-file-signature me-2"></i> Detail Penawaran
                                        </h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <div class="detail-item">
                                            <div class="detail-label">Posisi</div>
                                            <div class="detail-value fw-semibold">{{ $hiring->offering->posisi ?? '-' }}
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Gaji</div>
                                            <div class="detail-value fw-bold text-primary">Rp
                                                {{ number_format($hiring->offering->gaji ?? 0, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Tipe Kontrak</div>
                                            <div class="detail-value">
                                                <span
                                                    class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                                                    {{ ucfirst($hiring->offering->tipe_kontrak ?? '-') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="detail-item">
                                            <div class="detail-label">Tanggal Mulai Kerja</div>
                                            <div class="detail-value">
                                                {{ $hiring->offering->tanggal_mulai_kerja ? \Carbon\Carbon::parse($hiring->offering->tanggal_mulai_kerja)->translatedFormat('d F Y') : '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($hiring->status == 'probation')
                            <div class="card border-0 shadow-sm rounded-3 mb-4">
                                <div class="card-header bg-white border-bottom pb-2 pt-3 px-4">
                                    <h6 class="fw-bold mb-0 text-primary">
                                        <i class="fas fa-chart-line me-2"></i> Masa Probation
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <div class="detail-label">Tanggal Mulai</div>
                                                <div class="detail-value">
                                                    {{ $hiring->tanggal_probation_start ? \Carbon\Carbon::parse($hiring->tanggal_probation_start)->translatedFormat('d F Y') : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="detail-item">
                                                <div class="detail-label">Tanggal Berakhir</div>
                                                <div class="detail-value">
                                                    {{ $hiring->tanggal_probation_end ? \Carbon\Carbon::parse($hiring->tanggal_probation_end)->translatedFormat('d F Y') : '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="detail-item">
                                                <div class="detail-label">Hasil Probation</div>
                                                <div class="detail-value">
                                                    @if ($hiring->probation_result == 'passed')
                                                        <span class="badge bg-success px-3 py-2 rounded-pill"><i
                                                                class="fas fa-check-circle me-1"></i> Lulus</span>
                                                    @elseif($hiring->probation_result == 'failed')
                                                        <span class="badge bg-danger px-3 py-2 rounded-pill"><i
                                                                class="fas fa-times-circle me-1"></i> Tidak Lulus</span>
                                                    @elseif($hiring->probation_result == 'extended')
                                                        <span class="badge bg-warning px-3 py-2 rounded-pill"><i
                                                                class="fas fa-clock me-1"></i> Diperpanjang</span>
                                                    @else
                                                        <span class="badge bg-secondary px-3 py-2 rounded-pill">Belum
                                                            diproses</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if (
                                        $hiring->probation_result != 'passed' &&
                                            $hiring->probation_result != 'failed' &&
                                            $hiring->probation_result != 'extended')
                                        <form action="{{ route('admin.hiring.probation', [$lowongan->id, $hiring->id]) }}"
                                            method="POST" class="mt-4 pt-3 border-top">
                                            @csrf
                                            <div class="row g-3">
                                                <div class="col-md-8">
                                                    <select name="probation_result" class="form-select rounded-3" required>
                                                        <option value="">-- Pilih Hasil Probation --</option>
                                                        <option value="passed">Lulus (Tetap diangkat menjadi karyawan tetap)
                                                        </option>
                                                        <option value="failed">Tidak Lulus (Tidak diangkat)</option>
                                                        <option value="extended">Perpanjang masa probation 3 bulan</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-primary w-100 rounded-pill">
                                                        <i class="fas fa-save me-1"></i> Simpan Hasil
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if ($hiring->catatan)
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-white border-bottom pb-2 pt-3 px-4">
                                    <h6 class="fw-bold mb-0 text-primary">
                                        <i class="fas fa-sticky-note me-2"></i> Catatan Internal HR
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <div class="bg-light p-3 rounded-3">
                                        <p class="mb-0 text-muted">{{ $hiring->catatan }}</p>
                                    </div>
                                    <div class="small text-muted mt-2">
                                        <i class="fas fa-lock me-1"></i> Catatan hanya terlihat oleh internal HR
                                    </div>
                                </div>
                            </div>
                        @endif

                        <hr class="my-4">

                        <div class="d-flex gap-3">
                            <a href="{{ route('admin.hiring.index', $lowongan->id) }}"
                                class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                            @if ($hiring->status == 'hired' && !$hiring->onboarding)
                                <a href="{{ route('admin.onboarding.create', [$lowongan->id, $hiring->id]) }}"
                                    class="btn btn-success px-4 py-2 rounded-pill">
                                    <i class="fas fa-user-plus me-2"></i> Isi Data Onboarding
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .detail-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0;
                border-bottom: 1px solid #e9ecef;
            }

            .detail-item:last-child {
                border-bottom: none;
            }

            .detail-label {
                font-size: 0.85rem;
                color: #6c757d;
                min-width: 140px;
            }

            .detail-value {
                flex: 1;
                text-align: right;
                color: #2c3e50;
            }

            .bg-opacity-10 {
                --bs-bg-opacity: 0.1;
            }

            @media (max-width: 768px) {
                .detail-item {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 5px;
                }

                .detail-value {
                    text-align: left;
                    width: 100%;
                }
            }
        </style>
    @endpush
@endsection
