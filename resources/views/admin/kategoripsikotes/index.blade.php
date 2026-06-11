@extends('layouts.admin')
@section('title', 'Kategori Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-tags me-2"></i> Daftar Kategori Psikotes</h5>
                            <small>Total: {{ $kategoris->total() }} kategori</small>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.kategoripsikotes.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Kategori
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
                                <form action="{{ route('admin.kategoripsikotes.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama kategori..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.kategoripsikotes.index') }}"
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
                                        <th>Kode Kategori</th>
                                        <th>Nama Kategori</th>
                                        <th>Jenis</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kategoris as $key => $kategori)
                                        <tr>
                                            <td class="text-center">{{ $kategoris->firstItem() + $key }}</td>
                                            <td>{{ $kategori->kode_kategori }}</td>
                                            <td>{{ $kategori->nama_kategori }}</td>
                                            <td><span
                                                    class="badge {{ $kategori->jenis == 'kognitif' ? 'bg-primary' : ($kategori->jenis == 'kepribadian' ? 'bg-warning' : 'bg-success') }}">{{ ucfirst($kategori->jenis) }}</span>
                                            </td>
                                            <td>{{ Str::limit($kategori->deskripsi, 50) ?: '-' }}</td>
                                            <td><span
                                                    class="badge {{ $kategori->aktif ? 'bg-success' : 'bg-secondary' }}">{{ $kategori->aktif ? 'Aktif' : 'Nonaktif' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.kategoripsikotes.show', $kategori->id) }}"
                                                        class="btn btn-outline-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('admin.kategoripsikotes.edit', $kategori->id) }}"
                                                        class="btn btn-outline-warning" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Hapus kategori ini?')) document.getElementById('delete-{{ $kategori->id }}').submit();"><i
                                                            class="fas fa-trash"></i></button>
                                                    <form id="delete-{{ $kategori->id }}"
                                                        action="{{ route('admin.kategoripsikotes.destroy', $kategori->id) }}"
                                                        method="POST" class="d-none">@csrf @method('DELETE')</form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-tags fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data kategori</p>
                                                <a href="{{ route('admin.kategoripsikotes.create') }}"
                                                    class="btn btn-primary btn-sm mt-2">Tambah Kategori</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($kategoris->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">Menampilkan {{ $kategoris->firstItem() }} -
                                    {{ $kategoris->lastItem() }} dari {{ $kategoris->total() }} hasil</div>
                                <div>{{ $kategoris->withQueryString()->links('pagination::bootstrap-4') }}</div>
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
