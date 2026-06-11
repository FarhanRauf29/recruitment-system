@extends('layouts.pelamar')

@section('title', 'Daftar Lowongan Tersedia')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 py-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-white bg-opacity-20 p-2">
                                <i class="fas fa-briefcase fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Daftar Lowongan Tersedia</h5>
                                <p class="small mb-0 opacity-75">Temukan dan daftarkan diri Anda pada lowongan yang sesuai
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-3">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @forelse($lowongans as $lowongan)
                            @php
                                $isRegistered =
                                    Auth::user()->pelamar &&
                                    Auth::user()->pelamar->isRegisteredForLowongan($lowongan->id);
                                $slotTersedia = $lowongan->slot - $lowongan->pelamars()->count();
                                $statusBadge = $lowongan->status == 'buka' ? 'success' : 'secondary';
                                $statusIcon = $lowongan->status == 'buka' ? 'check-circle' : 'lock';
                            @endphp
                            <div class="card mb-4 border-0 shadow-sm rounded-4 hover-card">
                                <div class="card-body p-4">
                                    <div
                                        class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <h5 class="fw-bold mb-0 text-primary">{{ $lowongan->judul_lowongan }}</h5>
                                                <span class="badge bg-{{ $statusBadge }} rounded-pill px-3">
                                                    <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                                    {{ ucfirst($lowongan->status) }}
                                                </span>
                                            </div>
                                            <p class="text-muted mb-2">
                                                {{ Str::limit($lowongan->deskripsi ?? 'Tidak ada deskripsi', 120) }}</p>
                                            <div class="d-flex flex-wrap gap-3 mb-3">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-primary bg-opacity-10 p-1">
                                                        <i class="fas fa-calendar-alt text-primary fa-sm"></i>
                                                    </div>
                                                    <span class="small">Buka:
                                                        {{ \Carbon\Carbon::parse($lowongan->tanggal_buka)->format('d M Y') }}</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-danger bg-opacity-10 p-1">
                                                        <i class="fas fa-calendar-times text-danger fa-sm"></i>
                                                    </div>
                                                    <span class="small">Tutup:
                                                        {{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('d M Y') }}</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-info bg-opacity-10 p-1">
                                                        <i class="fas fa-users text-info fa-sm"></i>
                                                    </div>
                                                    <span class="small">Slot Tersedia: <strong>{{ $slotTersedia }}</strong>
                                                        / {{ $lowongan->slot }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-2">
                                            @if ($isRegistered)
                                                <button class="btn btn-success rounded-pill px-4" disabled>
                                                    <i class="fas fa-check-circle me-1"></i> Sudah Terdaftar
                                                </button>
                                            @elseif($slotTersedia > 0 && $lowongan->status == 'buka')
                                                <form action="{{ route('pelamar.lowongan.apply', $lowongan) }}"
                                                    method="POST" class="form-daftar d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-gradient-primary rounded-pill px-4 btn-submit-daftar"
                                                        data-judul="{{ addslashes($lowongan->judul_lowongan) }}"
                                                        data-deskripsi="{{ addslashes(Str::limit($lowongan->deskripsi ?? 'Tidak ada deskripsi', 100)) }}"
                                                        data-tanggal="{{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('d M Y') }}">
                                                        <i class="fas fa-file-signature me-1"></i> Daftar Sekarang
                                                    </button>
                                                </form>
                                            @elseif($slotTersedia <= 0)
                                                <button class="btn btn-secondary rounded-pill px-4" disabled>
                                                    <i class="fas fa-user-times me-1"></i> Slot Penuh
                                                </button>
                                            @else
                                                <button class="btn btn-secondary rounded-pill px-4" disabled>
                                                    <i class="fas fa-lock me-1"></i> Ditutup
                                                </button>
                                            @endif
                                            <a href="{{ route('pelamar.lowongan.show', $lowongan) }}"
                                                class="btn btn-outline-primary rounded-pill px-4">
                                                <i class="fas fa-info-circle me-1"></i> Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="rounded-circle bg-light d-inline-flex p-4 mb-3">
                                    <i class="fas fa-briefcase fa-3x text-muted"></i>
                                </div>
                                <h5 class="text-muted">Tidak Ada Lowongan Tersedia</h5>
                                <p class="text-muted">Silakan periksa kembali nanti untuk lowongan baru.</p>
                            </div>
                        @endforelse

                        @if ($lowongans->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $lowongans->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.form-daftar');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const btn = this.querySelector('.btn-submit-daftar');
                    const judul = btn.getAttribute('data-judul');
                    const deskripsi = btn.getAttribute('data-deskripsi');
                    const tanggalTutup = btn.getAttribute('data-tanggal');
                    const originalText = btn.innerHTML;

                    Swal.fire({
                        title: 'Konfirmasi Pendaftaran',
                        html: `
                    <div class="text-start">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 mb-3">
                            <strong><i class="fas fa-info-circle me-2"></i> Informasi Lowongan:</strong>
                        </div>
                        <div class="mb-3 p-3 bg-light rounded-3">
                            <div class="mb-2"><strong>Judul Lowongan:</strong><br>${judul}</div>
                            <div class="mb-2"><strong>Deskripsi:</strong><br>${deskripsi}</div>
                            <div><strong>Tanggal Tutup:</strong><br><span class="text-danger fw-bold">${tanggalTutup}</span></div>
                        </div>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Perhatian:</strong> Pendaftaran tidak dapat dibatalkan setelah Anda mengkonfirmasi.
                        </div>
                        <div class="text-center p-3 bg-primary bg-opacity-10 rounded-3">
                            <strong>Apakah Anda yakin ingin mendaftar pada lowongan ini?</strong>
                        </div>
                    </div>
                `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#0d6efd',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: '<i class="fas fa-check me-2"></i> Ya, Daftar Sekarang!',
                        cancelButtonText: '<i class="fas fa-times me-2"></i> Batal',
                        backdrop: true,
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Memproses Pendaftaran',
                                html: '<div class="py-3"><i class="fas fa-spinner fa-pulse fa-3x text-primary mb-3"></i><p>Sedang mendaftarkan Anda...</p></div>',
                                allowOutsideClick: false,
                                showConfirmButton: false
                            });

                            btn.innerHTML =
                                '<i class="fas fa-spinner fa-spin me-1"></i> Memproses...';
                            btn.disabled = true;

                            fetch(this.action, {
                                    method: 'POST',
                                    body: new FormData(this),
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                })
                                .then(response => response.text())
                                .then(text => {
                                    if (text.includes('success') || text.includes(
                                            'redirect')) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: 'Anda berhasil mendaftar di lowongan ini.',
                                            timer: 2000,
                                            showConfirmButton: false
                                        }).then(() => window.location.reload());
                                    } else {
                                        let errorMsg = 'Terjadi kesalahan';
                                        if (text.includes('sudah mendaftar')) errorMsg =
                                            'Anda sudah mendaftar di lowongan ini.';
                                        else if (text.includes('penuh')) errorMsg =
                                            'Slot lowongan sudah penuh.';
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal!',
                                            text: errorMsg,
                                            confirmButtonColor: '#0d6efd'
                                        });
                                        btn.innerHTML = originalText;
                                        btn.disabled = false;
                                    }
                                })
                                .catch(() => {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'Terjadi kesalahan. Silakan coba lagi.',
                                        confirmButtonColor: '#0d6efd'
                                    });
                                    btn.innerHTML = originalText;
                                    btn.disabled = false;
                                });
                        }
                    });
                });
            });
        });
    </script>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        }

        .btn-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
            color: white;
        }

        .bg-opacity-20 {
            --bs-bg-opacity: 0.2;
        }

        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px -8px rgba(0, 0, 0, 0.15) !important;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .rounded-top-4 {
            border-top-left-radius: 1rem !important;
            border-top-right-radius: 1rem !important;
        }
    </style>
@endsection
