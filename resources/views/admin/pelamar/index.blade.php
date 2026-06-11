@extends('layouts.admin')
@section('title', 'Daftar Pelamar')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-users me-2"></i> Daftar Pelamar</h5>
                            <small>Total: {{ $pelamars->total() }} pelamar</small>
                        </div>
                        <div class="d-flex gap-2 mt-2 mt-md-0">
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-1"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.pelamar.export') }}"><i
                                                class="fas fa-file-excel me-2"></i> Export ke Excel</a></li>
                                </ul>
                            </div>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="fas fa-upload me-1"></i> Import Pelamar
                            </button>
                            <a href="{{ route('admin.pelamar.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Pelamar
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
                                <form action="{{ route('admin.pelamar.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama, email, atau NIK..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.pelamar.index') }}"
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
                                        <th>Foto</th>
                                        <th>Nama Lengkap</th>
                                        <th>NIK</th>
                                        <th>Email</th>
                                        <th>No. Handphone</th>
                                        <th>Pendidikan</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pelamars as $key => $pelamar)
                                        <tr>
                                            <td class="text-center">{{ $pelamars->firstItem() + $key }}</td>
                                            <td class="text-center">
                                                @if ($pelamar->foto_profil)
                                                    <img src="{{ Storage::url($pelamar->foto_profil) }}"
                                                        class="rounded-circle" width="40" height="40"
                                                        style="object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center"
                                                        style="width:40px;height:40px;"><i
                                                            class="fas fa-user text-secondary"></i></div>
                                                @endif
                                            </td>
                                            <td><strong>{{ $pelamar->nama_lengkap }}</strong></td>
                                            <td>{{ $pelamar->nomor_identitas }}</td>
                                            <td>{{ $pelamar->email }}</td>
                                            <td>{{ $pelamar->nomor_handphone }}</td>
                                            <td><span class="badge bg-secondary">{{ $pelamar->pendidikan_terakhir }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.pelamar.profil', $pelamar->id) }}"
                                                        class="btn btn-outline-success" title="Detail Profil"><i
                                                            class="fas fa-id-card"></i></a>
                                                    <a href="{{ route('admin.pelamar.show', $pelamar->id) }}"
                                                        class="btn btn-outline-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('admin.pelamar.edit', $pelamar->id) }}"
                                                        class="btn btn-outline-warning" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Yakin ingin menghapus pelamar ini?')) document.getElementById('delete-{{ $pelamar->id }}').submit();"><i
                                                            class="fas fa-trash"></i></button>
                                                    <form id="delete-{{ $pelamar->id }}"
                                                        action="{{ route('admin.pelamar.destroy', $pelamar->id) }}"
                                                        method="POST" class="d-none">@csrf @method('DELETE')</form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="fas fa-users-slash fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data pelamar</p>
                                                <a href="{{ route('admin.pelamar.create') }}"
                                                    class="btn btn-primary btn-sm mt-2">Tambah Pelamar</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($pelamars->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">Menampilkan {{ $pelamars->firstItem() }} -
                                    {{ $pelamars->lastItem() }} dari {{ $pelamars->total() }} hasil</div>
                                <div>{{ $pelamars->withQueryString()->links('pagination::bootstrap-4') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title"><i class="fas fa-file-import me-2 text-primary"></i> Import Data Pelamar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.pelamar.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong><i class="fas fa-info-circle me-1"></i> Petunjuk:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Download template Excel terlebih dahulu</li>
                                <li>Isi data sesuai format</li>
                                <li>File max 5MB (.xlsx, .xls, .csv)</li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih File Excel</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <a href="{{ route('admin.pelamar.template') }}" class="btn btn-outline-primary btn-sm"><i
                                class="fas fa-download me-1"></i> Download Template</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
