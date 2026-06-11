@extends('layouts.admin')

@section('title', 'Daftar Berkas Pelamar')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Daftar Berkas Pelamar</h5>
                            <small>Total: {{ $berkas->total() }} berkas</small>
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
                                <form action="{{ route('admin.berkas.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama pelamar..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.berkas.index') }}" class="btn btn-outline-secondary"><i
                                                    class="fas fa-times"></i></a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <form action="{{ route('admin.berkas.index') }}" method="GET">
                                    <select name="lowongan_id" class="form-select filter-select"
                                        onchange="this.form.submit()">
                                        <option value="">-- Semua Lowongan --</option>
                                        @foreach ($lowongans as $lowongan)
                                            <option value="{{ $lowongan->id }}"
                                                {{ request('lowongan_id') == $lowongan->id ? 'selected' : '' }}>
                                                {{ $lowongan->judul_lowongan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama Pelamar</th>
                                        <th>Lowongan</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Tanggal Upload</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($berkas as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $berkas->firstItem() + $key }}</td>
                                            <td>
                                                <strong>{{ $item->pelamar->nama_lengkap ?? '-' }}</strong><br>
                                            </td>
                                            <td>
                                                <span
                                                    class="text-center">{{ $item->pelamarTahapan->lowongan->judul_lowongan ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge {{ $item->status_verifikasi == 'Diterima' ? 'bg-success' : ($item->status_verifikasi == 'Ditolak' ? 'bg-danger' : 'bg-warning') }}">
                                                    {{ $item->status_verifikasi }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                {{ $item->created_at ? $item->created_at->format('d F Y') : '-' }}
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.berkas.show', $item->id) }}"
                                                        class="btn btn-outline-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Yakin ingin menghapus data ini?')) document.getElementById('delete-{{ $item->id }}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <form id="delete-{{ $item->id }}"
                                                        action="{{ route('admin.berkas.destroy', $item->id) }}"
                                                        method="POST" class="d-none">@csrf @method('DELETE')</form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-file-alt fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data berkas</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($berkas->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    Menampilkan {{ $berkas->firstItem() }} - {{ $berkas->lastItem() }} dari
                                    {{ $berkas->total() }} hasil
                                </div>
                                <div>
                                    {{ $berkas->withQueryString()->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    </script>
@endsection
