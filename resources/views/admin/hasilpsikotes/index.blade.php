@extends('layouts.admin')
@section('title', 'Hasil Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Daftar Hasil Psikotes</h5>
                            <small>Total: {{ $hasils->total() }} hasil</small>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row g-2 mb-3 align-items-center">
                            <div class="col-md-4">
                                <form action="{{ route('admin.hasilpsikotes.index') }}" method="GET" id="filterForm">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama pelamar..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.hasilpsikotes.index') }}"
                                                class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select" id="statusFilter">
                                    <option value="">-- Semua Status --</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Lulus</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Tidak Lulus
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Pelamar</th>
                                        <th>Lowongan</th>
                                        <th class="text-center">Kognitif</th>
                                        <th class="text-center">Kepribadian</th>
                                        <th class="text-center">Minat</th>
                                        <th class="text-center">Nilai Akhir</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($hasils as $key => $hasil)
                                        <tr>
                                            <td class="text-center">{{ $hasils->firstItem() + $key }}</td>
                                            <td>
                                                <strong>{{ $hasil->pelamar->nama_lengkap ?? '-' }}</strong>
                                            </td>
                                            <td>{{ $hasil->lowongan->judul_lowongan ?? '-' }}</td>
                                            <td class="text-center">
                                                {{ number_format($hasil->nilai_kognitif, 2) ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($hasil->nilai_kepribadian, 2) ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                {{ number_format($hasil->nilai_minat ?? 0, 2) }}
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    style="color: {{ ($hasil->nilai_akhir ?? 0) >= 70 ? '#28a745' : '#dc3545' }}; font-weight: 600;">
                                                    {{ number_format($hasil->nilai_akhir, 2) ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    style="color: {{ $hasil->lulus ? '#28a745' : '#dc3545' }}; font-weight: 600;">
                                                    {{ $hasil->lulus ? 'Lulus' : 'Tidak Lulus' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.hasilpsikotes.show', $hasil->id) }}"
                                                        class="btn btn-outline-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Hapus hasil ini? Data akan dihitung ulang saat pelamar melihat kembali.')) document.getElementById('delete-{{ $hasil->id }}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <form id="delete-{{ $hasil->id }}"
                                                        action="{{ route('admin.hasilpsikotes.destroy', $hasil->id) }}"
                                                        method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="fas fa-chart-bar fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data hasil psikotes</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($hasils->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">Menampilkan {{ $hasils->firstItem() }} -
                                    {{ $hasils->lastItem() }} dari {{ $hasils->total() }} hasil</div>
                                <div>{{ $hasils->withQueryString()->links('pagination::bootstrap-4') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Filter status
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    if (this.value) {
                        url.searchParams.set('status', this.value);
                    } else {
                        url.searchParams.delete('status');
                    }
                    window.location.href = url.toString();
                });
            }
        </script>
    @endpush
@endsection
