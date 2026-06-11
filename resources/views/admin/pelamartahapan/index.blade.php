@extends('layouts.admin')
@section('title', 'Daftar Tahapan Pelamar ')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-0">
                                <i class="fas fa-list-alt me-2"></i> Daftar Tahapan Pelamar
                            </h3>
                            <p class="small mb-0">Total: {{ $pelamarTahapans->total() }} Tahapan Pelamar</p>
                        </div>
                        <a href="{{ route('admin.pelamartahapan.create') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i> Tambah Tahapan
                        </a>
                    </div>

                    <div class="card-body">
                        {{-- Notifikasi --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Filter/Search --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form action="{{ route('admin.pelamartahapan.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari pelamar atau lowongan..." value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i>
                                        </button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.pelamartahapan.index') }}"
                                                class="btn btn-outline-secondary">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>

                        {{-- Tabel Data --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 5%;" class="text-center">#</th>
                                        <th>Pelamar</th>
                                        <th>Lowongan</th>
                                        <th>Tahapan</th>
                                        <th>Status</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th style="width: 20%;" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pelamarTahapans as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $pelamarTahapans->firstItem() + $key }}</td>
                                            <td>{{ $item->pelamar->nama_lengkap ?? '-' }}</td>
                                            <td>{{ $item->lowongan->judul_lowongan ?? '-' }}</td>
                                            <td>{{ $item->tahapanRekrutmen->nama_tahapan ?? '-' }}</td>
                                            <td>{{ ucfirst($item->status) }}</td>
                                            <td>{{ $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format(' d F Y H:i') : '-' }}
                                            </td>
                                            <td>{{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format(' d F Y H:i') : '-' }}
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.pelamartahapan.show', $item) }}"
                                                        class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.pelamartahapan.edit', $item) }}"
                                                        class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                        title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.pelamartahapan.destroy', $item) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="tooltip" title="Hapus"
                                                            onclick="return confirm('Yakin hapus data ini?')">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                                <p>Tidak ada data tahapan pelamar ditemukan</p>
                                                <a href="{{ route('admin.pelamartahapan.create') }}"
                                                    class="btn btn-primary btn-sm">
                                                    <i class="fas fa-plus me-1"></i> Tambah Tahapan
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        @if ($pelamarTahapans->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted small">

                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    {{ $pelamarTahapans->withQueryString()->links('pagination::bootstrap-5', [
                                        'class' => 'pagination-sm mb-0',
                                        'previous' => '<i class="fas fa-chevron-left"></i>',
                                        'next' => '<i class="fas fa-chevron-right"></i>',
                                        'useBootstrap' => true,
                                    ]) }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Enable Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
