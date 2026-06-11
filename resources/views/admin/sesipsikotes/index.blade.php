@extends('layouts.admin')
@section('title', 'Sesi Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-clock me-2"></i> Daftar Sesi Psikotes</h5>
                            <small>Total: {{ $sesis->total() }} sesi</small>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.sesipsikotes.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Sesi
                            </a>
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

                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <form action="{{ route('admin.sesipsikotes.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama pelamar..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.sesipsikotes.index') }}"
                                                class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Pelamar</th>
                                        <th>Lowongan</th>
                                        <th>Token</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Durasi</th>
                                        <th class="text-center">Soal</th>
                                        <th class="text-center">Waktu Mulai</th>
                                        <th class="text-center">Waktu Selesai</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sesis as $key => $sesi)
                                        <tr>
                                            <td class="text-center">{{ $sesis->firstItem() + $key }}</td>
                                            <td><strong>{{ $sesi->pelamar->nama_lengkap ?? '-' }}</strong></td>
                                            <td>{{ $sesi->lowongan->judul_lowongan ?? '-' }}</td>
                                            <td>{{ $sesi->token_sesi }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge {{ $sesi->status == 'belum_dimulai' ? 'bg-secondary' : ($sesi->status == 'sedang_berjalan' ? 'bg-primary' : ($sesi->status == 'dijeda' ? 'bg-warning' : 'bg-success')) }}">
                                                    {{ ucfirst(str_replace('_', ' ', $sesi->status)) }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $sesi->durasi_menit }} mnt</td>
                                            <td class="text-center">{{ $sesi->jumlah_soal_ditampilkan }}</td>
                                            <td class="text-center">
                                                {{ $sesi->waktu_mulai ? $sesi->waktu_mulai->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="text-center">
                                                {{ $sesi->waktu_selesai ? $sesi->waktu_selesai->format('d/m/Y H:i') : '-' }}
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.sesipsikotes.show', $sesi->id) }}"
                                                        class="btn btn-outline-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('admin.sesipsikotes.edit', $sesi->id) }}"
                                                        class="btn btn-outline-warning" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Hapus sesi ini?')) document.getElementById('delete-{{ $sesi->id }}').submit();"><i
                                                            class="fas fa-trash"></i></button>
                                                    <form id="delete-{{ $sesi->id }}"
                                                        action="{{ route('admin.sesipsikotes.destroy', $sesi->id) }}"
                                                        method="POST" class="d-none">@csrf @method('DELETE')</form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <i class="fas fa-clock fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data sesi psikotes</p>
                                                <a href="{{ route('admin.sesipsikotes.create') }}"
                                                    class="btn btn-primary btn-sm mt-2">Tambah Sesi</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($sesis->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">Menampilkan {{ $sesis->firstItem() }} -
                                    {{ $sesis->lastItem() }} dari {{ $sesis->total() }} hasil</div>
                                <div>{{ $sesis->withQueryString()->links('pagination::bootstrap-4') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        </script>
    @endpush
@endsection
