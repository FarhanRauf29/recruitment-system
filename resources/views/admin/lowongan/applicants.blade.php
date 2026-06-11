@extends('layouts.admin')
@section('title', 'Daftar Pelamar - Lowongan')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h3 class="card-title mb-0">
                                <i class="fas fa-users me-2"></i> Daftar Pelamar
                            </h3>
                            <p class="small mb-0 mt-1 opacity-75">Lowongan: {{ $lowongan->judul_lowongan }}</p>
                            <p class="small mb-0">Total: {{ $pelamars->total() }} pelamar</p>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.lowongan.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center" style="width: 5%">#</th>
                                        <th class="text-center" style="width: 20%">Nama Lengkap</th>
                                        <th class="text-center" style="width: 25%">Email</th>
                                        <th class="text-center" style="width: 15%">Status</th>
                                        <th class="text-center" style="width: 15%">Tanggal Daftar</th>
                                        <th class="text-center" style="width: 10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pelamars as $key => $pelamar)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $pelamars->firstItem() + $key }}</td>
                                            <td><strong>{{ $pelamar->nama_lengkap }}</strong></td>
                                            <td>{{ $pelamar->email }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle me-1"></i> Sudah Terdaftar
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($pelamar->pivot->created_at)->format('d/m/Y') }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.lowongan.applicant.tahapan', [$lowongan, $pelamar]) }}"
                                                    class="btn btn-sm btn-outline-info" data-bs-toggle="tooltip"
                                                    title="Detail Tahapan">
                                                    <i class="fas fa-list"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="fas fa-user-slash fa-4x text-muted mb-3"></i>
                                                <h5 class="text-muted">Belum ada pelamar</h5>
                                                <p class="text-muted small">Belum ada pelamar yang mendaftar untuk lowongan
                                                    ini</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3 align-items-center">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    Menampilkan {{ $pelamars->firstItem() ?? 0 }} - {{ $pelamars->lastItem() ?? 0 }}
                                    dari {{ $pelamars->total() }} data
                                </small>
                            </div>
                            <div class="col-md-6">
                                @if ($pelamars->hasPages())
                                    <div class="d-flex justify-content-end">
                                        {{ $pelamars->withQueryString()->links('pagination::bootstrap-5') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    </script>
@endsection
