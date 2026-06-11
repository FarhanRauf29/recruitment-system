@extends('layouts.admin')
@section('title', 'Aktivitas Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-history me-2"></i> Log Aktivitas Psikotes</h5>
                            <small>Total: {{ $aktivitas->total() }} aktivitas</small>
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
                                <form action="{{ route('admin.aktivitaspsikotes.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama pelamar..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.aktivitaspsikotes.index') }}"
                                                class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <select name="tipe" class="form-select filter-select" data-filter="tipe">
                                    <option value="">-- Semua Tipe --</option>
                                    <option value="mulai_tes" {{ request('tipe') == 'mulai_tes' ? 'selected' : '' }}>Mulai
                                        Tes</option>
                                    <option value="jawab_soal" {{ request('tipe') == 'jawab_soal' ? 'selected' : '' }}>Jawab
                                        Soal</option>
                                    <option value="pause_tes" {{ request('tipe') == 'pause_tes' ? 'selected' : '' }}>Pause
                                        Tes</option>
                                    <option value="lanjut_tes" {{ request('tipe') == 'lanjut_tes' ? 'selected' : '' }}>
                                        Lanjut Tes</option>
                                    <option value="selesai_tes" {{ request('tipe') == 'selesai_tes' ? 'selected' : '' }}>
                                        Selesai Tes</option>
                                    <option value="lihat_hasil" {{ request('tipe') == 'lihat_hasil' ? 'selected' : '' }}>
                                        Lihat Hasil</option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Pelamar</th>
                                        <th class="text-center">Tipe Aktivitas</th>
                                        <th>Soal</th>
                                        <th class="text-center">Jawaban</th>
                                        <th>IP Address</th>
                                        <th class="text-center">Waktu</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($aktivitas as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $aktivitas->firstItem() + $key }}</td>
                                            <td><strong>{{ $item->pelamar->nama_lengkap ?? '-' }}</strong><br><small
                                                    class="text-muted">{{ $item->pelamar->email ?? '-' }}</small></td>
                                            <td class="text-center">
                                                @php
                                                    $tipeClass = 'secondary';
                                                    if ($item->tipe_aktivitas == 'mulai_tes') {
                                                        $tipeClass = 'primary';
                                                    } elseif ($item->tipe_aktivitas == 'jawab_soal') {
                                                        $tipeClass = 'info';
                                                    } elseif ($item->tipe_aktivitas == 'pause_tes') {
                                                        $tipeClass = 'warning';
                                                    } elseif ($item->tipe_aktivitas == 'lanjut_tes') {
                                                        $tipeClass = 'success';
                                                    } elseif ($item->tipe_aktivitas == 'selesai_tes') {
                                                        $tipeClass = 'dark';
                                                    }
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $tipeClass }}">{{ ucfirst(str_replace('_', ' ', $item->tipe_aktivitas)) }}</span>
                                            </td>
                                            <td>{{ $item->soal ? $item->soal->kode_soal : '-' }}<br><small
                                                    class="text-muted">{{ Str::limit($item->soal->pertanyaan ?? '-', 40) }}</small>
                                            </td>
                                            <td class="text-center"><span
                                                    class="badge bg-primary">{{ $item->jawaban ?? '-' }}</span></td>
                                            <td>{{ $item->ip_address ?? '-' }}</td>
                                            <td class="text-center">{{ $item->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.aktivitaspsikotes.show', $item->id) }}"
                                                        class="btn btn-outline-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Hapus aktivitas ini?')) document.getElementById('delete-{{ $item->id }}').submit();"><i
                                                            class="fas fa-trash"></i></button>
                                                    <form id="delete-{{ $item->id }}"
                                                        action="{{ route('admin.aktivitaspsikotes.destroy', $item->id) }}"
                                                        method="POST" class="d-none">@csrf @method('DELETE')</form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <i class="fas fa-history fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data aktivitas</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($aktivitas->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">Menampilkan {{ $aktivitas->firstItem() }} -
                                    {{ $aktivitas->lastItem() }} dari {{ $aktivitas->total() }} hasil</div>
                                <div>{{ $aktivitas->withQueryString()->links('pagination::bootstrap-4') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('.filter-select').forEach(select => {
                select.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('tipe', this.value);
                    window.location.href = url.toString();
                });
            });
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
        </script>
    @endpush
@endsection
