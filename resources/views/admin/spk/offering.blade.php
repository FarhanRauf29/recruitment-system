@extends('layouts.admin')

@section('title', 'Kandidat Offering')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-white bg-opacity-20 p-2">
                                    <i class="fas fa-briefcase fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Kandidat Offering</h5>
                                    <p class="small mb-0 opacity-75">Lowongan: {{ $lowongan->judul_lowongan }}</p>
                                </div>
                            </div>
                            <div>
                                <a href="{{ route('admin.spk.hasil', $lowongan->id) }}"
                                    class="btn btn-light btn-sm rounded-pill px-3">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="bg-light">
                                    <th style="width: 35%">Nama Pelamar</th>
                                    <th class="text-center" style="width: 15%">Nilai Akhir</th>
                                    <th class="text-center" style="width: 15%">Metode</th>
                                    <th class="text-center" style="width: 25%">Tanggal Approval</th>
                                    <th class="text-center" style="width: 10%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kandidatOffering as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $item->pelamar->nama_lengkap }}</div>
                                                        <small class="text-muted">{{ $item->pelamar->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center fw-bold text-primary">
                                                {{ number_format($item->nilai_akhir_spk, 4) }}</td>
                                            <td class="text-center"><span
                                                    class="badge bg-info rounded-pill px-3 py-2">{{ strtoupper($item->metode_terbaik) }}</span>
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($item->tanggal_approval)->format('d/m/Y H:i') }}
                                                WIB</td>
                                            <td class="text-center">
                                                <span class="badge bg-success rounded-pill px-3 py-2">
                                                    <i class="fas fa-check-circle me-1"></i> Approved Offering
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <div class="rounded-circle bg-light d-inline-flex p-4 mb-3">
                                                    <i class="fas fa-briefcase fa-3x text-muted"></i>
                                                </div>
                                                <h5 class="text-muted">Belum Ada Kandidat Offering</h5>
                                                <p class="text-muted mb-0">Belum ada kandidat yang di-approve ke tahap
                                                    offering</p>
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

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        }

        .bg-opacity-20 {
            --bs-bg-opacity: 0.2;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }
    </style>
@endsection
