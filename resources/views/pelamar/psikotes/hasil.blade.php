@extends('layouts.pelamar')

@section('title', 'Hasil Tes Psikotes')

@section('content')
    <div class="container-fluid px-4 py-5 bg-light min-vh-100">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9">

                <!-- Main Card -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                    <!-- Header Corporate -->
                    <div class="card-header bg-white px-5 py-4 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-7">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="logo-placeholder bg-primary bg-opacity-10 rounded-3 p-3">
                                        <i class="fas fa-chart-line text-primary fs-3"></i>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold text-dark mb-1">Laporan Hasil Assessment</h4>
                                        <p class="text-muted small mb-0">Psychological Test Report</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-5 text-md-end mt-3 mt-md-0">
                                <div class="bg-light rounded-3 p-2 d-inline-block">
                                    <span class="small text-muted">Report ID:</span>
                                    <span
                                        class="small fw-semibold text-dark ms-1">{{ substr($sesi->token_sesi, 0, 8) }}{{ rand(100, 999) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-5 py-4">

                        <!-- Info Pelamar & Lowongan -->
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-box bg-primary bg-opacity-10 rounded-3 p-2">
                                        <i class="fas fa-user-circle text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small mb-1">Nama Pelamar</div>
                                        <div class="fw-semibold text-dark">{{ Auth::user()->pelamar->nama_lengkap ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="icon-box bg-primary bg-opacity-10 rounded-3 p-2">
                                        <i class="fas fa-briefcase text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small mb-1">Posisi yang Dilamar</div>
                                        <div class="fw-semibold text-dark">{{ $sesi->lowongan->judul_lowongan ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Final Result Card -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <div class="section-indicator bg-primary rounded-3" style="width: 4px; height: 20px;"></div>
                                <h5 class="fw-semibold text-dark mb-0">Hasil Akhir Assessment</h5>
                                <span class="badge bg-light text-muted ms-2 fw-normal">Kesimpulan</span>
                            </div>

                            <div
                                class="result-card border rounded-3 p-4 {{ $hasil->lulus ? 'border-success' : 'border-danger' }}">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center gap-4 flex-wrap">
                                            <div class="text-center">
                                                <div
                                                    class="display-1 fw-bold {{ $hasil->lulus ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($hasil->nilai_akhir, 0) }}
                                                </div>
                                                <div class="text-muted small">Overall Score</div>
                                            </div>
                                            <div>
                                                @if ($hasil->lulus)
                                                    <div class="badge bg-success text-white px-4 py-2 rounded-pill mb-2">
                                                        <i class="fas fa-check-circle me-1"></i> RECOMMENDED
                                                    </div>
                                                    <div class="fw-semibold text-dark">Kandidat Memenuhi Standar</div>
                                                    <div class="small text-muted">Skor di atas batas kelulusan yang
                                                        ditentukan</div>
                                                @else
                                                    <div class="badge bg-danger text-white px-4 py-2 rounded-pill mb-2">
                                                        <i class="fas fa-times-circle me-1"></i> NOT RECOMMENDED
                                                    </div>
                                                    <div class="fw-semibold text-dark">Kandidat Belum Memenuhi Standar</div>
                                                    <div class="small text-muted">Skor di bawah batas kelulusan yang
                                                        ditentukan</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3 mt-md-0">
                                        <div class="bg-light rounded-3 p-3">
                                            <div class="small text-muted mb-2">Standar Kelulusan</div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <span
                                                    class="fw-bold fs-4 text-dark">{{ $hasil->konfigurasi->batas_nilai_kelulusan ?? 60 }}%</span>
                                                <i class="fas fa-flag-checkered text-muted"></i>
                                            </div>
                                            <div class="progress mt-2" style="height: 4px;">
                                                <div class="progress-bar {{ $hasil->lulus ? 'bg-success' : 'bg-danger' }}"
                                                    role="progressbar"
                                                    style="width: {{ $hasil->konfigurasi->batas_nilai_kelulusan ?? 60 }}%;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Jawaban Tabel -->
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-4">
                                <div class="section-indicator bg-primary rounded-3" style="width: 4px; height: 20px;">
                                </div>
                                <h5 class="fw-semibold text-dark mb-0">Detail Jawaban Assessment</h5>
                                <span class="badge bg-light text-muted ms-2 fw-normal">{{ $jawaban->count() }} Item</span>
                            </div>

                            <div class="table-responsive">
                                <table class="assessment-table table w-100">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="55%">Item Assessment</th>
                                            <th width="10%">Answer</th>
                                            <th width="15%">Dimension</th>
                                            <th width="15%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($jawaban as $index => $jwb)
                                            @php
                                                $soal = $jwb->soalPsikotes;
                                                $jenisSoal = $soal?->jenis ?? 'kognitif';

                                                if ($jenisSoal == 'kognitif') {
                                                    $benar =
                                                        $soal &&
                                                        $soal->kunci_jawaban &&
                                                        strtoupper($jwb->jawaban) == strtoupper($soal->kunci_jawaban);
                                                    $status = $benar ? 'Correct' : 'Incorrect';
                                                    $dimension = 'Cognitive';
                                                } elseif ($jenisSoal == 'kepribadian') {
                                                    $status = 'Completed';
                                                    $dimension = 'Personality';
                                                } else {
                                                    $status = 'Completed';
                                                    $dimension = 'Interest';
                                                }
                                            @endphp
                                            <tr>
                                                <td class="text-muted">{{ $loop->iteration }}</td>
                                                <td class="text-dark">
                                                    {{ Str::limit(strip_tags($soal?->pertanyaan ?? '-'), 100) }}</td>
                                                <td class="text-primary fw-semibold">{{ $jwb->jawaban }}</td>
                                                <td class="text-secondary">{{ $dimension }}</td>
                                                <td>
                                                    @if ($jenisSoal == 'kognitif')
                                                        @if ($benar)
                                                            <span class="text-success">✓ Correct</span>
                                                        @else
                                                            <span class="text-danger">✗ Incorrect</span>
                                                        @endif
                                                    @else
                                                        <span class="text-secondary">● Completed</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="border-top mt-5 pt-4">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                                <div class="text-muted small">
                                    <i class="far fa-calendar-check me-1"></i>
                                    Assessment completed:
                                    {{ $hasil->waktu_selesai ? \Carbon\Carbon::parse($hasil->waktu_selesai)->format('d F Y H:i') : now()->format('d F Y H:i') }}
                                </div>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('pelamar.dashboard') }}"
                                        class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                        <i class="fas fa-arrow-left me-2"></i>Dashboard
                                    </a>
                                    <button type="button" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm"
                                        onclick="window.print()">
                                        <i class="fas fa-print me-2"></i>Print Report
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer Note -->
                <div class="text-center mt-4">
                    <p class="small text-muted">
                        <i class="fas fa-lock me-1"></i>
                        This is an official assessment report. The results are confidential and should be handled according
                        to company policy.
                    </p>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Background */
        .bg-light {
            background-color: #f5f7fa !important;
        }

        /* Card Corporate */
        .rounded-4 {
            border-radius: 20px !important;
        }

        .shadow-lg {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08) !important;
        }

        /* Metric Card */
        .metric-card {
            transition: all 0.2s ease;
            background: white;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        /* Result Card */
        .result-card {
            background: white;
            transition: all 0.2s ease;
        }

        .result-card.border-success {
            border-left-width: 4px !important;
            border-left-style: solid !important;
        }

        .result-card.border-danger {
            border-left-width: 4px !important;
            border-left-style: solid !important;
        }

        /* Assessment Table */
        .assessment-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .assessment-table thead th {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
        }

        .assessment-table tbody td {
            padding: 14px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            vertical-align: middle;
        }

        .assessment-table tbody tr:hover td {
            background-color: #fafbfc;
        }

        /* Icon Box */
        .icon-box {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Display */
        .display-1 {
            font-size: 4rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .display-6 {
            font-size: 1.75rem;
            font-weight: 600;
        }

        /* Badge */
        .badge.bg-success,
        .badge.bg-danger {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* Progress */
        .progress {
            border-radius: 10px;
            background-color: #e9ecef;
        }

        /* Print Styles */
        @media print {

            body,
            .bg-light {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .btn,
            .btn-outline-secondary,
            .btn-primary,
            button,
            .breadcrumb,
            .no-print {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            .metric-card {
                border: 1px solid #ddd !important;
                background: white !important;
            }

            .text-success,
            .text-danger,
            .text-primary {
                color: black !important;
            }

            .bg-success,
            .bg-danger,
            .bg-primary {
                background: transparent !important;
                border: 1px solid #ddd !important;
            }

            .progress-bar {
                background: #ccc !important;
            }

            .assessment-table thead th {
                background: #f0f0f0 !important;
                color: black !important;
            }

            .assessment-table tbody td {
                border-bottom: 1px solid #ddd !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .card-body {
                padding: 1.5rem !important;
            }

            .display-1 {
                font-size: 2.5rem !important;
            }

            .display-6 {
                font-size: 1.25rem !important;
            }

            .assessment-table tbody td {
                font-size: 12px;
                padding: 10px 8px;
            }
        }
    </style>
@endpush
