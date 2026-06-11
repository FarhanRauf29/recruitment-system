@extends('layouts.pelamar')

@section('title', 'Riwayat Berkas Lamaran')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i> Riwayat Berkas Lamaran</h5>
                            <small class="opacity-75">Status dan riwayat pengajuan berkas Anda</small>
                        </div>
                        <a href="{{ route('pelamar.berkas.create') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
                            <i class="fas fa-plus me-1"></i> Upload Berkas Baru
                        </a>
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

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr class="text-center align-middle">
                                        <th width="20%">Lowongan</th>
                                        <th width="20%">Tanggal Upload</th>
                                        <th width="15%">Status Verifikasi</th>
                                        <th width="25%">Catatan Admin</th>
                                        <th width="20%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($berkas as $item)
                                        <tr class="align-middle">
                                            <td>
                                                <strong>{{ $item->pelamarTahapan->lowongan->judul_lowongan ?? '-' }}</strong><br>
                                            </td>
                                            <td class="text-center">
                                                {{ $item->created_at->format('d F Y') }}<br>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge {{ $item->status_verifikasi == 'Diterima' ? 'bg-success' : ($item->status_verifikasi == 'Ditolak' ? 'bg-danger' : 'bg-warning') }} rounded-pill px-3 py-2">
                                                    {{ $item->status_verifikasi }}
                                                </span>
                                                @if ($item->tanggal_verifikasi && $item->status_verifikasi != 'Pending')
                                                    <div class="small text-muted mt-1">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        {{ $item->tanggal_verifikasi->format('d M Y') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $item->catatan_admin ?? '-' }}</td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('pelamar.berkas.show', $item->id) }}"
                                                        class="btn btn-outline-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    @if ($item->status_verifikasi == 'Pending')
                                                        <a href="{{ route('pelamar.berkas.edit', $item->id) }}"
                                                            class="btn btn-outline-warning" title="Edit"><i
                                                                class="fas fa-edit"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-folder-open fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada riwayat berkas</p>
                                                <a href="{{ route('pelamar.berkas.create') }}"
                                                    class="btn btn-primary btn-sm mt-2"><i
                                                        class="fas fa-cloud-upload-alt me-1"></i> Upload Berkas Sekarang</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($berkas->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $berkas->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
