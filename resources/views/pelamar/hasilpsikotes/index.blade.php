@extends('layouts.pelamar')
@section('title', 'Hasil Psikotes')

@section('content')
    <div class="container py-4">

        {{-- <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h3 class="fw-bold text-primary mb-1">
                    <i class="fas fa-clipboard-check me-2"></i>Riwayat Hasil Psikotes
                </h3>
                <p class="text-muted mb-0">
                    Pantau hasil psikotes yang telah Anda ikuti dan lihat detail penilaian Anda.
                </p>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('pelamar.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Dashboard
                </a>
            </div>
        </div> --}}

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white py-3">
                <div class="d-flex align-items-center">
                    <div class="me-3 fs-4">
                        <i class="fas fa-list-check"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">Daftar Hasil Psikotes</div>
                        <small class="opacity-75">Riwayat tes yang pernah Anda kerjakan</small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">

                @if ($hasils->count() == 0)

                    <div class="text-center py-5">
                        <i class="fas fa-folder-open text-muted mb-3" style="font-size: 4rem;"></i>
                        <h5 class="fw-semibold">Belum Ada Hasil Psikotes</h5>
                        <p class="text-muted mb-3">
                            Anda belum mengikuti psikotes. Silakan ikuti tes terlebih dahulu.
                        </p>
                        <a href="{{ route('pelamar.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-1"></i>Ke Dashboard
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th>Lowongan</th>
                                    <th class="text-center">Tanggal Tes</th>
                                    <th class="text-center">Nilai</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" width="12%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasils as $index => $hasil)
                                    <tr>
                                        <td class="text-center fw-bold">
                                            {{ $hasils->firstItem() + $index }}
                                        </td>

                                        <td>
                                            <div class="fw-semibold text-dark">
                                                {{ $hasil->lowongan->judul_lowongan ?? '-' }}
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-key me-1"></i>Sesi: {{ $hasil->sesi->token_sesi ?? '-' }}
                                            </small>
                                        </td>

                                        <td class="text-center">
                                            <div class="fw-semibold">
                                                {{ $hasil->waktu_selesai ? \Carbon\Carbon::parse($hasil->waktu_selesai)->format('d M Y') : '-' }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $hasil->waktu_selesai ? \Carbon\Carbon::parse($hasil->waktu_selesai)->format('H:i') : '' }}
                                            </small>
                                        </td>

                                        <td class="text-center">
                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 fs-6 rounded-pill">
                                                {{ number_format($hasil->nilai_akhir, 2) }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <span
                                                class="badge rounded-pill px-3 py-2
                                        bg-{{ $hasil->lulus ? 'success' : 'danger' }}">
                                                <i
                                                    class="fas fa-{{ $hasil->lulus ? 'check-circle' : 'times-circle' }} me-1"></i>
                                                {{ $hasil->lulus ? 'Lulus' : 'Tidak Lulus' }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            <a href="{{ route('pelamar.psikotes.hasil', $hasil->sesi->token_sesi) }}"
                                                class="btn btn-sm btn-primary rounded-pill px-3">
                                                <i class="fas fa-eye me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted small">
                            Menampilkan {{ $hasils->count() }} dari {{ $hasils->total() }} data
                        </div>
                        <div>
                            {{ $hasils->links() }}
                        </div>
                    </div>

                @endif

            </div>
        </div>
    </div>
@endsection
