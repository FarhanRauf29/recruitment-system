@extends('layouts.admin')

@section('title', 'Manajemen Wawancara')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="fas fa-chalkboard-user me-2"></i> Manajemen Wawancara
                            </h4>
                            <p class="small mb-0 mt-1 opacity-75">Kelola jadwal dan hasil wawancara pelamar</p>
                        </div>
                        <div class="d-flex gap-2 mt-2 mt-md-0">
                            <a href="{{ route('admin.wawancara.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Jadwal
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <form action="{{ route('admin.wawancara.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama pelamar atau lowongan..."
                                            value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.wawancara.index') }}"
                                                class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select filter-select" data-filter="status">
                                    <option value="">-- Semua Status --</option>
                                    <option value="dijadwalkan" {{ request('status') == 'dijadwalkan' ? 'selected' : '' }}>
                                        Dijadwalkan</option>
                                    <option value="dikonfirmasi"
                                        {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                                    <option value="hadir" {{ request('status') == 'hadir' ? 'selected' : '' }}>Hadir
                                    </option>
                                    <option value="tidak_hadir" {{ request('status') == 'tidak_hadir' ? 'selected' : '' }}>
                                        Tidak Hadir</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai
                                    </option>
                                    <option value="verifikasi" {{ request('status') == 'verifikasi' ? 'selected' : '' }}>
                                        Verifikasi</option>
                                    <option value="lolos" {{ request('status') == 'lolos' ? 'selected' : '' }}>Lolos
                                    </option>
                                    <option value="gagal" {{ request('status') == 'gagal' ? 'selected' : '' }}>Gagal
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
                                        <th>Jadwal Wawancara</th>
                                        <th>Jenis</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($wawancara as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $wawancara->firstItem() + $key }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </div>
                                                    <div>
                                                        <strong>{{ $item->pelamar->nama_lengkap ?? '-' }}</strong><br>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                {{ Carbon\Carbon::parse($item->jadwal_wawancara)->format('d F Y H:i') }}
                                                WIB<br>

                                            </td>
                                            <td>
                                                @if ($item->jenis_wawancara == 'online')
                                                    <span class="badge bg-success"><i class="fas fa-video me-1"></i>
                                                        Online</span>
                                                @else
                                                    <span class="badge bg-warning"><i class="fas fa-building me-1"></i>
                                                        Offline</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-{{ $item->status_badge }}">{{ $item->status_text }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.wawancara.show', $item->id) }}"
                                                        class="btn btn-outline-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('admin.wawancara.edit', $item->id) }}"
                                                        class="btn btn-outline-warning" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Yakin ingin menghapus?')) document.getElementById('delete-{{ $item->id }}').submit();"><i
                                                            class="fas fa-trash"></i></button>
                                                    <form id="delete-{{ $item->id }}"
                                                        action="{{ route('admin.wawancara.destroy', $item->id) }}"
                                                        method="POST" class="d-none">@csrf @method('DELETE')</form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-chalkboard-user fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data wawancara</p>
                                                <a href="{{ route('admin.wawancara.create') }}"
                                                    class="btn btn-primary btn-sm mt-2">Tambah Jadwal</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($wawancara->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    Menampilkan {{ $wawancara->firstItem() }} - {{ $wawancara->lastItem() }} dari
                                    {{ $wawancara->total() }} hasil
                                </div>
                                <div>
                                    {{ $wawancara->withQueryString()->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.filter-select').forEach(select => {
            select.addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set(this.getAttribute('data-filter'), this.value);
                window.location.href = url.toString();
            });
        });
    </script>
@endsection
