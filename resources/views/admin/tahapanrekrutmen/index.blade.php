@extends('layouts.admin')
@section('title', 'Daftar Tahapan Rekrutmen')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-tasks me-2"></i> Daftar Tahapan Rekrutmen</h5>
                            <small>Total: {{ $tahapanrekrutmens->total() }} tahapan</small>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.tahapanrekrutmen.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Tahapan
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
                                <form action="{{ route('admin.tahapanrekrutmen.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama tahapan..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.tahapanrekrutmen.index') }}"
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
                                        <th>Nama Tahapan</th>
                                        <th>Deskripsi</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tahapanrekrutmens as $key => $tahapan)
                                        <tr>
                                            <td class="text-center">{{ $tahapanrekrutmens->firstItem() + $key }}</td>
                                            <td><strong>{{ $tahapan->nama_tahapan }}</strong><br></td>
                                            <td>{{ Str::limit($tahapan->deskripsi, 50) ?: '-' }}</td>
                                            <td class="text-center"><span
                                                    class="badge {{ $tahapan->status == 'aktif' ? 'bg-success' : 'bg-secondary' }}">{{ $tahapan->status == 'aktif' ? 'Aktif' : 'Tidak Aktif' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.tahapanrekrutmen.show', $tahapan) }}"
                                                        class="btn btn-outline-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('admin.tahapanrekrutmen.edit', $tahapan) }}"
                                                        class="btn btn-outline-warning" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Hapus tahapan ini?')) document.getElementById('delete-{{ $tahapan->id }}').submit();"><i
                                                            class="fas fa-trash"></i></button>
                                                    <form id="delete-{{ $tahapan->id }}"
                                                        action="{{ route('admin.tahapanrekrutmen.destroy', $tahapan) }}"
                                                        method="POST" class="d-none">@csrf @method('DELETE')</form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-tasks fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data tahapan</p>
                                                <a href="{{ route('admin.tahapanrekrutmen.create') }}"
                                                    class="btn btn-primary btn-sm mt-2">Tambah Tahapan</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($tahapanrekrutmens->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">Menampilkan {{ $tahapanrekrutmens->firstItem() }} -
                                    {{ $tahapanrekrutmens->lastItem() }} dari {{ $tahapanrekrutmens->total() }} hasil
                                </div>
                                <div>{{ $tahapanrekrutmens->withQueryString()->links('pagination::bootstrap-4') }}</div>
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
