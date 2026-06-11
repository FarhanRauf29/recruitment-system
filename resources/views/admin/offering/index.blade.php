@extends('layouts.admin')

@section('title', 'Manajemen Penawaran Kerja')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div
                        class="card-header bg-primary text-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="fas fa-file-signature me-2"></i> Manajemen Penawaran Kerja
                            </h5>
                            <small class="opacity-75 mt-1 d-block">Kelola dan kirim penawaran kerja untuk kandidat
                                terpilih</small>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.spk.hasil', $lowongan->id) }}" class="btn btn-light btn-sm px-3 py-2">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke SPK
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-lg me-2"></i>
                                    <span>{{ session('success') }}</span>
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle fa-lg me-2"></i>
                                    <span>{{ session('error') }}</span>
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        <div class="alert alert-info bg-light border-start border-4 border-info mb-4 py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-briefcase text-info me-3 fs-5"></i>
                                <div>
                                    <span class="text-muted small d-block">Lowongan Aktif</span>
                                    <strong class="fs-6">{{ $lowongan->judul_lowongan }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Navigation - Perbaikan agar tidak turun ke bawah -->
                        <div class="tab-navigation-wrapper mb-4">
                            <ul class="nav nav-tabs border-bottom-0"
                                style="flex-wrap: nowrap; overflow-x: auto; gap: 8px; scrollbar-width: thin;">
                                <li class="nav-item" style="flex-shrink: 0;">
                                    <button class="nav-link active py-2 px-4 rounded-3" data-bs-toggle="tab"
                                        data-bs-target="#pending">
                                        <i class="fas fa-clock me-2"></i> Menunggu Offering
                                        <span
                                            class="badge bg-warning ms-2 rounded-pill">{{ $kandidatApproved->count() }}</span>
                                    </button>
                                </li>
                                <li class="nav-item" style="flex-shrink: 0;">
                                    <button class="nav-link py-2 px-4 rounded-3" data-bs-toggle="tab"
                                        data-bs-target="#sent">
                                        <i class="fas fa-paper-plane me-2"></i> Penawaran Dikirim
                                        <span class="badge bg-primary ms-2 rounded-pill">{{ $offeringSent->count() }}</span>
                                    </button>
                                </li>
                                <li class="nav-item" style="flex-shrink: 0;">
                                    <button class="nav-link py-2 px-4 rounded-3" data-bs-toggle="tab"
                                        data-bs-target="#accepted">
                                        <i class="fas fa-check-circle me-2"></i> Diterima
                                        <span
                                            class="badge bg-success ms-2 rounded-pill">{{ $offeringAccepted->count() }}</span>
                                    </button>
                                </li>
                                <li class="nav-item" style="flex-shrink: 0;">
                                    <button class="nav-link py-2 px-4 rounded-3" data-bs-toggle="tab"
                                        data-bs-target="#rejected">
                                        <i class="fas fa-times-circle me-2"></i> Ditolak
                                        <span
                                            class="badge bg-danger ms-2 rounded-pill">{{ $offeringRejected->count() }}</span>
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <div class="tab-content mt-3">
                            <!-- Tab Menunggu Offering -->
                            <div class="tab-pane fade show active" id="pending">
                                <div class="table-responsive border rounded-3">
                                    <table class="table table-bordered table-hover mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center px-3 py-3" style="width: 5%;">#</th>
                                                <th class="px-3 py-3" style="width: 30%;">Kandidat</th>
                                                <th class="text-center px-3 py-3" style="width: 15%;">Nilai SPK</th>
                                                <th class="text-center px-3 py-3" style="width: 15%;">Metode</th>
                                                <th class="text-center px-3 py-3" style="width: 15%;">Status</th>
                                                <th class="text-center px-3 py-3" style="width: 20%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($kandidatApproved as $kandidat)
                                                <tr>
                                                    <td class="text-center px-3 py-3">{{ $loop->iteration }}</td>
                                                    <td class="px-3 py-3">
                                                        <div>
                                                            <div class="fw-semibold text-dark">
                                                                {{ $kandidat->pelamar->nama_lengkap }}</div>

                                                        </div>
                                </div>
                                </td>
                                <td class="text-center px-3 py-3">
                                    <span
                                        class="fw-bold text-primary">{{ number_format($kandidat->nilai_akhir_spk ?? 0, 4) }}</span>
                                </td>
                                <td class="text-center px-3 py-3">
                                    <span
                                        class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">{{ strtoupper($kandidat->metode_terbaik ?? '-') }}</span>
                                </td>
                                <td class="text-center px-3 py-3">
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                        <i class="fas fa-check-circle me-1"></i> Approved
                                    </span>
                                </td>
                                <td class="text-center px-3 py-3">
                                    <a href="{{ route('admin.offering.create', [$lowongan->id, $kandidat->pelamar_id]) }}"
                                        class="btn btn-primary btn-sm px-3 py-1 rounded-pill">
                                        <i class="fas fa-file-signature me-1"></i> Buat Penawaran
                                    </a>
                                </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-inbox fa-4x text-muted mb-3 opacity-25"></i>
                                            <p class="mb-0 text-muted fw-semibold">Belum ada kandidat yang
                                                di-approve</p>
                                            <small class="text-muted">Kandidat akan muncul setelah proses
                                                SPK selesai</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                                </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Penawaran Dikirim -->
                        <div class="tab-pane fade" id="sent">
                            <div class="table-responsive border rounded-3">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center px-3 py-3" style="width: 5%;">#</th>
                                            <th class="px-3 py-3" style="width: 25%;">Kandidat</th>
                                            <th class="px-3 py-3" style="width: 20%;">Posisi</th>
                                            <th class="text-center px-3 py-3" style="width: 15%;">Gaji</th>
                                            <th class="text-center px-3 py-3" style="width: 15%;">Batas Respon</th>
                                            <th class="text-center px-3 py-3" style="width: 20%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($offeringSent as $offering)
                                            <tr>
                                                <td class="text-center px-3 py-3">{{ $loop->iteration }}</td>
                                                <td class="px-3 py-3">
                                                    <div class="fw-semibold text-dark">
                                                        {{ $offering->pelamar->nama_lengkap }}</div>
                                                <td class="px-3 py-3">{{ $offering->posisi }}</td>
                                                <td class="text-center px-3 py-3">
                                                    <span class="fw-semibold">Rp
                                                        {{ number_format($offering->gaji, 0, ',', '.') }}</span>
                                                </td>
                                                <td class="text-center px-3 py-3">
                                                    <span
                                                        class="badge {{ now()->gt($offering->batas_respon) ? 'bg-danger bg-opacity-10 text-danger' : 'bg-warning bg-opacity-10 text-warning' }} px-3 py-2 rounded-pill">
                                                        {{ \Carbon\Carbon::parse($offering->batas_respon)->translatedFormat('d M Y') }}
                                                    </span>
                                                </td>
                                                <td class="text-center px-3 py-3">
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a href="{{ route('admin.offering.show', [$lowongan->id, $offering->id]) }}"
                                                            class="btn btn-outline-info btn-sm px-3 py-1 rounded-pill"
                                                            title="Detail">
                                                            <i class="fas fa-eye me-1"></i> Detail
                                                        </a>
                                                        <button type="button"
                                                            class="btn btn-outline-warning btn-sm px-3 py-1 rounded-pill"
                                                            title="Kirim Ulang"
                                                            onclick="resendOffer({{ $offering->id }})">
                                                            <i class="fas fa-paper-plane me-1"></i> Kirim
                                                        </button>
                                                        <button type="button"
                                                            class="btn btn-outline-danger btn-sm px-3 py-1 rounded-pill"
                                                            title="Batalkan" onclick="cancelOffer({{ $offering->id }})">
                                                            <i class="fas fa-ban me-1"></i> Batal
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i
                                                            class="fas fa-envelope-open fa-4x text-muted mb-3 opacity-25"></i>
                                                        <p class="mb-0 text-muted fw-semibold">Belum ada penawaran yang
                                                            dikirim</p>
                                                        <small class="text-muted">Penawaran akan muncul setelah Anda
                                                            membuat penawaran</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Diterima -->
                        <div class="tab-pane fade" id="accepted">
                            <div class="table-responsive border rounded-3">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center px-3 py-3" style="width: 5%;">#</th>
                                            <th class="px-3 py-3" style="width: 25%;">Kandidat</th>
                                            <th class="px-3 py-3" style="width: 20%;">Posisi</th>
                                            <th class="text-center px-3 py-3" style="width: 15%;">Gaji</th>
                                            <th class="text-center px-3 py-3" style="width: 15%;">Tgl Diterima</th>
                                            <th class="text-center px-3 py-3" style="width: 20%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($offeringAccepted as $offering)
                                            <tr>
                                                <td class="text-center px-3 py-3">{{ $loop->iteration }}</td>
                                                <td class="px-3 py-3">
                                                    <div class="fw-semibold text-dark">
                                                        {{ $offering->pelamar->nama_lengkap }}</div>
                                                </td>
                                                <td class="px-3 py-3">{{ $offering->posisi }}</td>
                                                <td class="text-center px-3 py-3">
                                                    <span class="fw-semibold text-success">Rp
                                                        {{ number_format($offering->gaji, 0, ',', '.') }}</span>
                                                </td>
                                                <td class="text-center px-3 py-3">
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                        {{ \Carbon\Carbon::parse($offering->tanggal_diterima)->translatedFormat('d M Y H:i') }}
                                                    </span>
                                                </td>
                                                <td class="text-center px-3 py-3">
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        <a href="{{ route('admin.offering.show', [$lowongan->id, $offering->id]) }}"
                                                            class="btn btn-outline-info btn-sm px-3 py-1 rounded-pill"
                                                            title="Detail">
                                                            <i class="fas fa-eye me-1"></i> Detail
                                                        </a>
                                                        <a href="{{ route('admin.hiring.create', [$lowongan->id, $offering->id]) }}"
                                                            class="btn btn-outline-success btn-sm px-3 py-1 rounded-pill"
                                                            title="Proses Hiring">
                                                            <i class="fas fa-user-check me-1"></i> Hiring
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i
                                                            class="fas fa-check-circle fa-4x text-muted mb-3 opacity-25"></i>
                                                        <p class="mb-0 text-muted fw-semibold">Belum ada penawaran yang
                                                            diterima</p>
                                                        <small class="text-muted">Penawaran yang diterima kandidat akan
                                                            muncul di sini</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tab Ditolak -->
                        <div class="tab-pane fade" id="rejected">
                            <div class="table-responsive border rounded-3">
                                <table class="table table-bordered table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="text-center px-3 py-3" style="width: 5%;">#</th>
                                            <th class="px-3 py-3" style="width: 20%;">Kandidat</th>
                                            <th class="px-3 py-3" style="width: 20%;">Posisi</th>
                                            <th class="text-center px-3 py-3" style="width: 15%;">Gaji</th>
                                            <th class="text-center px-3 py-3" style="width: 15%;">Status</th>
                                            <th class="text-center px-3 py-3" style="width: 25%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($offeringRejected as $offering)
                                            <tr>
                                                <td class="text-center px-3 py-3">{{ $loop->iteration }}</td>
                                                <td class="px-3 py-3">
                                                    <div class="fw-semibold text-dark">
                                                        {{ $offering->pelamar->nama_lengkap }}</div>
                                                </td>
                                                <td class="px-3 py-3">{{ $offering->posisi }}</td>
                                                <td class="text-center px-3 py-3">
                                                    <span class="fw-semibold">Rp
                                                        {{ number_format($offering->gaji, 0, ',', '.') }}</span>
                                                </td>
                                                <td class="text-center px-3 py-3">
                                                    <span
                                                        class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                                        <i class="fas fa-times-circle me-1"></i> Ditolak
                                                    </span>
                                                </td>
                                                <td class="text-center px-3 py-3">
                                                    <button type="button"
                                                        class="btn btn-outline-secondary btn-sm px-3 py-1 rounded-pill"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#rejectReasonModal{{ $offering->id }}"
                                                        title="Lihat Alasan">
                                                        <i class="fas fa-comment-dots me-1"></i> Lihat Alasan
                                                    </button>
                                                    <a href="{{ route('admin.offering.show', [$lowongan->id, $offering->id]) }}"
                                                        class="btn btn-outline-info btn-sm px-3 py-1 rounded-pill"
                                                        title="Detail">
                                                        <i class="fas fa-eye me-1"></i> Detail
                                                    </a>
                                                </td>
                                            </tr>


                                            <div class="modal fade" id="rejectReasonModal{{ $offering->id }}"
                                                tabindex="-1">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content"
                                                        style="border-radius: 16px; overflow: hidden;">
                                                        <div class="modal-header border-0 pt-4 px-4"
                                                            style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i class="fas fa-comment-dots text-white"></i>
                                                                <h5 class="modal-title fw-bold text-white">Alasan Penolakan
                                                                </h5>
                                                            </div>
                                                            <button type="button" class="btn-close btn-close-white"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body p-4">
                                                            <div class="d-flex align-items-start gap-3">
                                                                <div class="rounded-circle bg-danger bg-opacity-10 p-2">
                                                                    <i class="fas fa-user-circle text-danger"></i>
                                                                </div>
                                                                <div>
                                                                    <p class="fw-semibold mb-1">
                                                                        {{ $offering->pelamar->nama_lengkap }}</p>
                                                                    <p class="small text-muted mb-0">
                                                                        {{ $offering->pelamar->email }}</p>
                                                                </div>
                                                            </div>
                                                            <hr>
                                                            <div class="bg-light rounded-3 p-3">
                                                                <div class="d-flex gap-2">
                                                                    <i
                                                                        class="fas fa-quote-left text-danger opacity-50"></i>
                                                                    <p class="small mb-0" style="color: #475569;">
                                                                        {{ $offering->alasan_penolakan ?? 'Tidak memberikan alasan' }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            @if ($offering->tanggal_ditolak)
                                                                <div class="mt-3 small text-muted text-end">
                                                                    <i class="fas fa-clock me-1"></i>
                                                                    Ditolak:
                                                                    {{ \Carbon\Carbon::parse($offering->tanggal_ditolak)->translatedFormat('d F Y, H:i') }}
                                                                    WIB
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer border-0 pb-4 px-4">
                                                            <button type="button"
                                                                class="btn btn-secondary rounded-pill px-4"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i
                                                            class="fas fa-times-circle fa-4x text-muted mb-3 opacity-25"></i>
                                                        <p class="mb-0 text-muted fw-semibold">Belum ada penawaran yang
                                                            ditolak</p>
                                                        <small class="text-muted">Penawaran yang ditolak kandidat akan
                                                            muncul di sini</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <form id="resendForm" action="" method="POST" class="d-none">
        @csrf
        @method('POST')
    </form>

    <form id="cancelForm" action="" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            function resendOffer(id) {
                if (confirm('Apakah Anda yakin ingin mengirim ulang penawaran ini?')) {
                    let form = document.getElementById('resendForm');
                    form.action = `{{ url('admin/offering') }}/${id}/resend`;
                    form.submit();
                }
            }

            function cancelOffer(id) {
                if (confirm('Apakah Anda yakin ingin membatalkan penawaran ini?')) {
                    let form = document.getElementById('cancelForm');
                    form.action = `{{ url('admin/offering') }}/${id}/cancel`;
                    form.submit();
                }
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .table-bordered {
                border: 1px solid #dee2e6 !important;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #dee2e6 !important;
            }

            .table-hover tbody tr:hover {
                background-color: #f8f9fa !important;
            }

            .tab-navigation-wrapper {
                width: 100%;
                overflow-x: auto;
                overflow-y: visible;
                scrollbar-width: thin;
                -webkit-overflow-scrolling: touch;
            }

            .tab-navigation-wrapper .nav-tabs {
                display: flex;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                width: max-content;
                min-width: 100%;
                border-bottom: none;
            }

            .tab-navigation-wrapper .nav-item {
                flex-shrink: 0 !important;
                margin-bottom: 0 !important;
            }

            .nav-tabs .nav-link {
                background: #f8f9fa;
                color: #6c757d;
                font-weight: 500;
                transition: all 0.2s ease;
                border: 1px solid #dee2e6;
                white-space: nowrap;
            }

            .nav-tabs .nav-link:hover {
                background: #e9ecef;
                color: #0d6efd;
                border-color: #dee2e6;
            }

            .nav-tabs .nav-link.active {
                background: #ffffff;
                color: #0d6efd;
                border-color: #dee2e6;
                border-bottom-color: #ffffff;
                font-weight: 600;
            }

            .btn-outline-info:hover,
            .btn-outline-warning:hover,
            .btn-outline-danger:hover,
            .btn-outline-success:hover {
                transform: translateY(-1px);
            }

            .rounded-3 {
                border-radius: 12px !important;
            }

            .bg-opacity-10 {
                --bs-bg-opacity: 0.1;
            }

            .tab-navigation-wrapper::-webkit-scrollbar {
                height: 4px;
            }

            .tab-navigation-wrapper::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 10px;
            }

            .tab-navigation-wrapper::-webkit-scrollbar-thumb {
                background: #c1c1c1;
                border-radius: 10px;
            }

            .tab-navigation-wrapper::-webkit-scrollbar-thumb:hover {
                background: #a8a8a8;
            }
        </style>
    @endpush
@endsection
