@extends('layouts.admin')
@section('title', 'Konfigurasi Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-cog me-2"></i> Konfigurasi Psikotes</h5>
                            <small>Total: {{ $konfigurasis->total() }} konfigurasi</small>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.konfigurasipsikotes.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Konfigurasi
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
                                <form action="{{ route('admin.konfigurasipsikotes.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari lowongan..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.konfigurasipsikotes.index') }}"
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
                                        <th>Lowongan</th>
                                        <th class="text-center">Total Soal</th>
                                        <th class="text-center">Kognitif</th>
                                        <th class="text-center">Kepribadian</th>
                                        <th class="text-center">Minat</th>
                                        <th class="text-center">Durasi</th>
                                        <th class="text-center">Nilai</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($konfigurasis as $key => $konfigurasi)
                                        <td class="text-center">{{ $konfigurasis->firstItem() + $key }}</td>
                                        <td><strong>{{ $konfigurasi->lowongan->judul_lowongan ?? '-' }}</strong><br></td>
                                        <td class="text-center">{{ $konfigurasi->jumlah_soal_total }} Soal</td>
                                        <td class="text-center">{{ $konfigurasi->jumlah_soal_kognitif }} Soal</td>
                                        <td class="text-center">{{ $konfigurasi->jumlah_soal_kepribadian }} Soal</td>
                                        <td class="text-center">{{ $konfigurasi->jumlah_soal_minat }} Soal</td>
                                        <td class="text-center">{{ $konfigurasi->durasi_menit }} Menit</td>
                                        <td class="text-center">{{ $konfigurasi->batas_nilai_kelulusan }}%</td>
                                        <td class="text-center"><span
                                                class="badge {{ $konfigurasi->aktif ? 'bg-success' : 'bg-secondary' }}">{{ $konfigurasi->aktif ? 'Aktif' : 'Nonaktif' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.konfigurasipsikotes.show', $konfigurasi->id) }}"
                                                    class="btn btn-outline-info" title="Detail"><i
                                                        class="fas fa-eye"></i></a>
                                                <a href="{{ route('admin.konfigurasipsikotes.edit', $konfigurasi->id) }}"
                                                    class="btn btn-outline-warning" title="Edit"><i
                                                        class="fas fa-edit"></i></a>
                                                <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                    onclick="if(confirm('Hapus konfigurasi ini?')) document.getElementById('delete-{{ $konfigurasi->id }}').submit();"><i
                                                        class="fas fa-trash"></i></button>
                                                <form id="delete-{{ $konfigurasi->id }}"
                                                    action="{{ route('admin.konfigurasipsikotes.destroy', $konfigurasi->id) }}"
                                                    method="POST" class="d-none">@csrf @method('DELETE')</form>
                                            </div>
                                        </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="text-center py-4">
                                                <i class="fas fa-cog fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data konfigurasi</p>
                                                <a href="{{ route('admin.konfigurasipsikotes.create') }}"
                                                    class="btn btn-primary btn-sm mt-2">Tambah Konfigurasi</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($konfigurasis->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">Menampilkan {{ $konfigurasis->firstItem() }} -
                                    {{ $konfigurasis->lastItem() }} dari {{ $konfigurasis->total() }} hasil</div>
                                <div>{{ $konfigurasis->withQueryString()->links('pagination::bootstrap-4') }}</div>
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
