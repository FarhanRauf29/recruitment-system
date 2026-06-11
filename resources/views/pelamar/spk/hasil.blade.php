@extends('layouts.pelamar')

@section('title', 'Hasil Seleksi SPK')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="bg-primary rounded" style="width: 4px; height: 24px;"></div>
                        <h4 class="fw-bold mb-0" style="color: #1e293b;">Hasil Seleksi SPK</h4>
                    </div>
                    <p class="text-muted small ms-2">Hasil akhir seleksi berdasarkan perhitungan SPK</p>
                </div>

                @if ($statusSPK)
                    @php
                        $catatan = $statusSPK->catatan_spk ?? '';
                        $catatanLines = explode("\n", $catatan);
                    @endphp

                    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">

                        <div class="px-4 py-3" style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-trophy text-white fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-white">Selamat! Anda Lolos Seleksi</h5>
                                    <p class="small text-white opacity-90 mb-0">Anda dinyatakan lolos seleksi berdasarkan
                                        perhitungan SPK</p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">

                            <div class="alert mb-4 py-3 px-4 rounded-2"
                                style="background: #eff6ff; border-left: 3px solid #2563eb;">
                                <div class="d-flex gap-3 align-items-center">
                                    <i class="fas fa-gift text-primary fs-5"></i>
                                    <div>
                                        <span class="small fw-semibold d-block" style="color: #1e293b;">Informasi
                                            Penting</span>
                                        <p class="small text-muted mb-0">Anda akan melanjutkan ke tahap Offering Kerja.
                                            Informasi lebih lanjut akan dikirim Ketahapan Selanjutnya.</p>
                                    </div>
                                </div>
                            </div>

                            @if ($statusSPK->nilai_akhir_spk)
                                <div class="mb-4">
                                    <h6 class="fw-semibold mb-3" style="color: #1e293b;">
                                        <i class="fas fa-chart-line text-primary me-2"></i>Hasil Perhitungan SPK
                                    </h6>
                                    <div class="bg-light rounded-3 p-4">
                                        <div class="row g-4">
                                            <div class="col-sm-6">
                                                <div class="border-bottom pb-2 mb-2">
                                                    <span class="text-muted small text-uppercase">Nilai Akhir SPK</span>
                                                </div>
                                                <div class="d-flex align-items-baseline gap-2">
                                                    <span
                                                        class="fw-bold text-primary fs-2">{{ number_format($statusSPK->nilai_akhir_spk, 4) }}</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="border-bottom pb-2 mb-2">
                                                    <span class="text-muted small text-uppercase">Metode Perhitungan</span>
                                                </div>
                                                <div>
                                                    <span
                                                        class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fs-6">
                                                        <i class="fas fa-calculator me-1"></i>
                                                        {{ strtoupper($statusSPK->metode_terbaik ?? 'SAW') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            @endif

                            @if ($catatan)
                                <div class="mb-4">
                                    <h6 class="fw-semibold mb-3" style="color: #1e293b;">
                                        <i class="fas fa-history text-primary me-2"></i>Ringkasan Proses Seleksi
                                    </h6>
                                    <div class="bg-light rounded-3 p-3">
                                        <div class="timeline-sederhana">
                                            @foreach ($catatanLines as $index => $line)
                                                @if (trim($line))
                                                    @php
                                                        $isLast = $loop->last;
                                                        $icon = 'fa-circle';
                                                        $iconColor = 'text-primary';
                                                        $formattedLine = trim($line);

                                                        $formattedLine = preg_replace_callback(
                                                            '/(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})/',
                                                            function ($matches) {
                                                                return \Carbon\Carbon::parse(
                                                                    $matches[1] . ' ' . $matches[2],
                                                                )->translatedFormat('d F Y, H:i') . ' WIB';
                                                            },
                                                            $formattedLine,
                                                        );

                                                        $formattedLine = preg_replace_callback(
                                                            '/(\d{4}-\d{2}-\d{2})(?! \d{2}:\d{2}:\d{2})/',
                                                            function ($matches) {
                                                                return \Carbon\Carbon::parse(
                                                                    $matches[1],
                                                                )->translatedFormat('d F Y');
                                                            },
                                                            $formattedLine,
                                                        );

                                                        if (strpos($line, 'Lolos') !== false) {
                                                            $icon = 'fa-check-circle';
                                                            $iconColor = 'text-success';
                                                        } elseif (strpos($line, 'Offering dikirim') !== false) {
                                                            $icon = 'fa-paper-plane';
                                                            $iconColor = 'text-primary';
                                                        } elseif (strpos($line, 'menerima penawaran') !== false) {
                                                            $icon = 'fa-handshake';
                                                            $iconColor = 'text-success';
                                                        } elseif (strpos($line, 'ditolak') !== false) {
                                                            $icon = 'fa-times-circle';
                                                            $iconColor = 'text-danger';
                                                        }
                                                    @endphp
                                                    <div class="d-flex gap-3 mb-3 {{ !$isLast ? 'border-start border-2 border-primary border-opacity-25' : '' }}"
                                                        style="margin-left: 12px; padding-bottom: 12px;">
                                                        <div class="flex-shrink-0" style="margin-left: -12px;">
                                                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center shadow-sm"
                                                                style="width: 32px; height: 32px; border: 2px solid #2563eb;">
                                                                <i class="fas {{ $icon }} {{ $iconColor }}"
                                                                    style="font-size: 14px;"></i>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <p class="small mb-1" style="color: #1e293b; font-weight: 500;">
                                                                @if (strpos($line, 'Lolos') !== false)
                                                                    <i class="fas fa-trophy text-warning me-1"></i>Hasil
                                                                    Seleksi
                                                                @elseif(strpos($line, 'Offering dikirim') !== false)
                                                                    <i
                                                                        class="fas fa-envelope-open-text text-primary me-1"></i>Penawaran
                                                                    Dikirim
                                                                @elseif(strpos($line, 'menerima penawaran') !== false)
                                                                    <i
                                                                        class="fas fa-check-circle text-success me-1"></i>Penawaran
                                                                    Diterima
                                                                @else
                                                                    Informasi Proses
                                                                @endif
                                                            </p>
                                                            <p class="small mb-0" style="color: #475569;">
                                                                {{ $formattedLine }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                            <div class="card-body text-center py-5">
                                <div class="mb-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 80px; height: 80px;">
                                        <i class="fas fa-chart-line text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                </div>
                                <h6 class="fw-semibold mb-1" style="color: #1e293b;">Belum Ada Hasil Seleksi</h6>
                                <p class="text-muted small mb-0">Hasil seleksi akan ditampilkan setelah proses SPK selesai
                                    dilakukan oleh admin.</p>
                            </div>
                        </div>
                @endif
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
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .card {
            transition: all 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.02) !important;
        }

        .bg-light {
            background-color: #f8fafc !important;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .bg-opacity-20 {
            --bs-bg-opacity: 0.2;
        }

        .progress-bar {
            transition: width 0.8s ease;
        }

        .fs-2 {
            font-size: 1.75rem !important;
        }
    </style>
@endpush
