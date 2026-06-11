@extends('layouts.pelamar')

@section('title', 'Status Seleksi')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="bg-primary rounded" style="width: 4px; height: 24px;"></div>
                        <h4 class="fw-bold mb-0" style="color: #1e293b;">Status Seleksi</h4>
                    </div>
                    <p class="text-muted small ms-2">Informasi perkembangan proses rekrutmen Anda</p>
                </div>

                @if (!$offering)
                    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-inbox text-muted" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <h6 class="fw-semibold mb-1" style="color: #1e293b;">Belum Ada Penawaran</h6>
                            <p class="text-muted small mb-0">Silakan tunggu proses seleksi dari tim rekrutmen</p>
                        </div>
                    </div>
                @elseif($offering->status == 'sent')
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px; background: rgba(37, 99, 235, 0.1);">
                                    <i class="fas fa-envelope text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <span class="badge fw-medium px-3 py-1 rounded-pill"
                                        style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">Menunggu Respon</span>
                                    <h5 class="fw-bold mb-0 mt-1" style="color: #1e293b;">Penawaran Kerja Dikirim</h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px; background: #eff6ff;">
                                        <i class="fas fa-clock text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="small text-muted">Batas Respon</div>
                                        <div class="fw-semibold" style="color: #1e293b;">
                                            {{ \Carbon\Carbon::parse($offering->batas_respon)->format('d F Y') }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('pelamar.offering.index') }}" class="btn btn-primary px-4 rounded-pill">
                                    <i class="fas fa-eye me-2"></i>Lihat Penawaran
                                </a>
                            </div>
                        </div>
                    </div>
                @elseif($offering->status == 'accepted')
                    <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <!-- Primary Banner -->
                        <div class="px-4 py-3" style="background: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center"
                                    style="width: 48px; height: 48px;">
                                    <i class="fas fa-check-circle text-white fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-white">Penawaran Diterima</h5>
                                    <p class="small text-white opacity-90 mb-0">Selamat! Anda telah menerima penawaran kerja
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3" style="color: #1e293b;">
                                    <i class="fas fa-file-signature text-primary me-2"></i>Detail Penawaran
                                </h6>
                                <div class="bg-light rounded-3 p-3">
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">Posisi</span>
                                                <span class="fw-semibold small">{{ $offering->posisi }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">Gaji</span>
                                                <span class="fw-semibold text-primary small">Rp
                                                    {{ number_format($offering->gaji, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">Tipe Kontrak</span>
                                                <span
                                                    class="fw-semibold small">{{ ucfirst($offering->tipe_kontrak) }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted small">Tanggal Mulai</span>
                                                <span
                                                    class="fw-semibold small">{{ \Carbon\Carbon::parse($offering->tanggal_mulai_kerja)->format('d M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($hiring && $hiring->status == 'hired')
                                <div>
                                    <h6 class="fw-semibold mb-3" style="color: #1e293b;">
                                        <i class="fas fa-id-card text-primary me-2"></i>Data Karyawan
                                    </h6>
                                    <div class="bg-light rounded-3 p-3">
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">NIP</span>
                                                    <span class="fw-semibold small">{{ $onboarding->nip ?? '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">Divisi</span>
                                                    <span class="fw-semibold small">{{ $onboarding->divisi ?? '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">Atasan Langsung</span>
                                                    <span
                                                        class="fw-semibold small">{{ $onboarding->atasan_langsung ?? '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">Lokasi Kerja</span>
                                                    <span
                                                        class="fw-semibold small">{{ $onboarding->lokasi_kerja ?? '-' }}</span>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small">Tanggal Masuk</span>
                                                    <span
                                                        class="fw-semibold small">{{ \Carbon\Carbon::parse($onboarding->tanggal_masuk)->format('d M Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($hiring && $hiring->status == 'probation')
                                <div class="d-flex gap-3 p-3 rounded-3" style="background: #eff6ff;">
                                    <i class="fas fa-clock text-primary fs-5"></i>
                                    <div>
                                        <div class="fw-semibold mb-0" style="color: #1e40af;">Masa Probation</div>
                                        <p class="small mb-0" style="color: #2563eb;">Berlaku hingga
                                            {{ \Carbon\Carbon::parse($hiring->tanggal_probation_end)->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @elseif(!$hiring)
                                <div class="d-flex gap-3 p-3 rounded-3" style="background: #eff6ff;">
                                    <i class="fas fa-spinner fa-pulse text-primary fs-5"></i>
                                    <div>
                                        <div class="fw-semibold mb-0" style="color: #1e40af;">Sedang Diproses</div>
                                        <p class="small mb-0" style="color: #2563eb;">HR sedang memproses data Anda. Kami
                                            akan menginformasikan selanjutnya.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($offering->status == 'rejected')
                    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-times-circle text-primary" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <h6 class="fw-semibold mb-1" style="color: #1e293b;">Penawaran Ditolak</h6>
                            <p class="text-muted small mb-0">Terima kasih atas ketertarikan Anda bergabung dengan
                                perusahaan kami.</p>
                            <p class="text-muted small">Semoga sukses di kesempatan lain.</p>
                        </div>
                    </div>
                @elseif($offering->status == 'expired')
                    <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                    style="width: 80px; height: 80px;">
                                    <i class="fas fa-hourglass-end text-primary" style="font-size: 2rem;"></i>
                                </div>
                            </div>
                            <h6 class="fw-semibold mb-1" style="color: #1e293b;">Penawaran Kadaluarsa</h6>
                            <p class="text-muted small mb-0">Penawaran kerja ini sudah melewati batas waktu respons.</p>
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
            transform: translateY(-1px);
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
    </style>
@endpush
