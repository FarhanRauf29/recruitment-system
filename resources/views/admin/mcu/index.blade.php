@extends('layouts.admin')

@section('title', 'Manajemen Medical Check Up')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-heartbeat me-2"></i> Manajemen Medical Check Up</h5>
                            <small>Total: {{ $mcu->total() }} jadwal MCU</small>
                        </div>
                        <a href="{{ route('admin.mcu.create') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
                            <i class="fas fa-plus me-1"></i> Tambah Jadwal MCU
                        </a>
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
                                <form action="{{ route('admin.mcu.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama pelamar atau lowongan..."
                                            value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.mcu.index') }}" class="btn btn-outline-secondary"><i
                                                    class="fas fa-times"></i></a>
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
                                        <th>Jadwal MCU</th>
                                        <th>Lokasi</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Skor</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mcu as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $mcu->firstItem() + $key }}</td>
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
                                                {{ Carbon\Carbon::parse($item->jadwal_mcu)->format('d F Y') }}<br>
                                                <small
                                                    class="text-muted">{{ Carbon\Carbon::parse($item->jadwal_mcu)->format('H:i') }}
                                                    WIB</small>
                                            </td>
                                            <td>
                                                <small>{{ \Str::limit($item->lokasi_mcu, 30) }}</small>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-{{ $item->status_badge }}">{{ $item->status_text }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($item->nilai_mcu)
                                                    @php
                                                        $skorTampil = $item->nilai_mcu;
                                                        if ($skorTampil > 100) {
                                                            $skorTampil = $skorTampil / 100;
                                                        }
                                                        echo number_format($skorTampil, 2, ',', '.');
                                                    @endphp
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.mcu.show', $item->id) }}"
                                                        class="btn btn-outline-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('admin.mcu.edit', $item->id) }}"
                                                        class="btn btn-outline-warning" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <a href="{{ route('admin.mcu.surat_panggilan', $item->id) }}"
                                                        class="btn btn-outline-success" title="Unduh Surat Panggilan"><i
                                                            class="fas fa-download"></i></a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Yakin ingin menghapus jadwal MCU ini?')) document.getElementById('delete-{{ $item->id }}').submit();"><i
                                                            class="fas fa-trash"></i></button>
                                                    <form id="delete-{{ $item->id }}"
                                                        action="{{ route('admin.mcu.destroy', $item->id) }}" method="POST"
                                                        class="d-none">@csrf @method('DELETE')</form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-heartbeat fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data MCU</p>
                                                <a href="{{ route('admin.mcu.create') }}"
                                                    class="btn btn-primary btn-sm mt-2">Tambah Jadwal MCU</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($mcu->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    Menampilkan {{ $mcu->firstItem() }} - {{ $mcu->lastItem() }} dari {{ $mcu->total() }}
                                    hasil
                                </div>
                                <div>
                                    {{ $mcu->withQueryString()->links('pagination::bootstrap-4') }}
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
