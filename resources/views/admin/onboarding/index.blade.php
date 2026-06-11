@extends('layouts.admin')

@section('title', 'Manajemen Onboarding')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div
                        class="card-header bg-primary text-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="card-title mb-0 fw-semibold">
                                <i class="fas fa-user-plus me-2"></i> Manajemen Onboarding
                            </h5>
                            <small class="opacity-75 mt-1 d-block">Kelola data onboarding karyawan baru</small>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.spk.index') }}" class="btn btn-light btn-sm px-3 py-2">
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

                        <div class="card border-0 shadow-sm rounded-3 mb-4">
                            <div class="card-body p-3">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0" id="tableSearch"
                                                placeholder="Cari karyawan...">
                                        </div>
                                    </div>
                                    <div class="col-md-auto">
                                        <select class="form-select" id="statusFilter" style="width: 160px;">
                                            <option value="all">Semua Status</option>
                                            <option value="selesai">Selesai</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive border rounded-3">
                            <table class="table table-bordered table-hover mb-0" id="onboardingTable">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center px-3 py-3" style="width: 5%">#</th>
                                        <th class="px-3 py-3" style="width: 15%">NIP</th>
                                        <th class="px-3 py-3" style="width: 25%">Nama Karyawan</th>
                                        <th class="px-3 py-3" style="width: 15%">Divisi</th>
                                        <th class="px-3 py-3" style="width: 15%">Atasan Langsung</th>
                                        <th class="text-center px-3 py-3" style="width: 15%">Tanggal Masuk</th>
                                        <th class="text-center px-3 py-3" style="width: 10%">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($onboardings as $item)
                                        <tr>
                                            <td class="text-center px-3 py-3">{{ $loop->iteration }}</td>
                                            <td class="px-1 py-3">
                                                <span class="px-3 py-2">
                                                    {{ $item->nip ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3">
                                                <div class="fw-semibold text-dark">{{ $item->pelamar->nama_lengkap }}</div>
                                            </td>
                                            <td class="px-3 py-3">{{ $item->divisi }}</td>
                                            <td class="px-3 py-3">{{ $item->atasan_langsung }}</td>
                                            <td class="text-center px-3 py-3">
                                                {{ \Carbon\Carbon::parse($item->tanggal_masuk)->translatedFormat('d M Y') }}
                                            </td>
                                            <td class="text-center px-3 py-3">
                                                <span
                                                    class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                    <i class="fas fa-check-circle me-1"></i> Selesai
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-users fa-4x text-muted mb-3 opacity-25"></i>
                                                    <p class="mb-0 text-muted fw-semibold">Belum ada data onboarding</p>
                                                    <small class="text-muted">Data onboarding akan muncul setelah hiring
                                                        dikonfirmasi</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                Total karyawan: <strong class="fw-bold">{{ $onboardings->count() }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('tableSearch').addEventListener('keyup', function() {
                let input = this.value.toLowerCase();
                let rows = document.querySelectorAll('#onboardingTable tbody tr');
                rows.forEach(row => {
                    let text = row.textContent.toLowerCase();
                    row.style.display = text.includes(input) ? '' : 'none';
                });
            });

            document.getElementById('statusFilter').addEventListener('change', function() {
                let status = this.value;
                let rows = document.querySelectorAll('#onboardingTable tbody tr');
                rows.forEach(row => {
                    if (status === 'all') {
                        row.style.display = '';
                    } else {
                        let statusCell = row.querySelector('td:last-child span');
                        if (statusCell) {
                            let statusText = statusCell.textContent.toLowerCase();
                            row.style.display = statusText.includes(status) ? '' : 'none';
                        } else {
                            row.style.display = 'none';
                        }
                    }
                });
            });
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

            .rounded-3 {
                border-radius: 12px !important;
            }

            .bg-opacity-10 {
                --bs-bg-opacity: 0.1;
            }
        </style>
    @endpush
@endsection
