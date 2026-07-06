@extends('layouts.admin')
@section('title', 'Daftar Lowongan')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i> Daftar Lowongan</h5>
                            <small>Total: {{ $lowongans->total() }} lowongan</small>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.lowongan.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Lowongan
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
                                <form action="{{ route('admin.lowongan.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari judul lowongan..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.lowongan.index') }}"
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
                                        <th>Judul Lowongan</th>
                                        <th class="text-center">Tanggal Buka</th>
                                        <th class="text-center">Tanggal Tutup</th>
                                        <th class="text-center">Slot</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($lowongans as $key => $lowongan)
                                        <tr>
                                            <td class="text-center">{{ $lowongans->firstItem() + $key }}</td>
                                            <td><strong>{{ $lowongan->judul_lowongan }}</strong><br></td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($lowongan->tanggal_buka)->format('d F Y') }}</td>
                                            <td class="text-center">
                                                {{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->format('d F Y') }}</td>
                                            <td class="text-center">
                                                {{ $lowongan->getJumlahTerisiAttribute() }}/{{ $lowongan->slot }}
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge {{ $lowongan->status == 'buka' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($lowongan->status) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.lowongan.show', $lowongan->id) }}"
                                                        class="btn btn-outline-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('admin.lowongan.applicants', $lowongan->id) }}"
                                                        class="btn btn-outline-success" title="Daftar Pelamar"><i
                                                            class="fas fa-users"></i></a>
                                                    <a href="{{ route('admin.lowongan.edit', $lowongan->id) }}"
                                                        class="btn btn-outline-warning" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Hapus lowongan ini?')) document.getElementById('delete-{{ $lowongan->id }}').submit();"><i
                                                            class="fas fa-trash"></i></button>
                                                    <form id="delete-{{ $lowongan->id }}"
                                                        action="{{ route('admin.lowongan.destroy', $lowongan->id) }}"
                                                        method="POST" class="d-none">@csrf @method('DELETE')</form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-briefcase fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data lowongan</p>
                                                <a href="{{ route('admin.lowongan.create') }}"
                                                    class="btn btn-primary btn-sm mt-2">Tambah Lowongan</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($lowongans->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">Menampilkan {{ $lowongans->firstItem() }} -
                                    {{ $lowongans->lastItem() }} dari {{ $lowongans->total() }} hasil</div>
                                <div>{{ $lowongans->withQueryString()->links('pagination::bootstrap-4') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
