@extends('layouts.pelamar')

@section('title', 'Jadwal Wawancara Saya')

@section('content')
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-0">
                    <div class="card-header bg-primary text-white py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-chalkboard-user fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Jadwal Wawancara</h5>
                                <small class="opacity-75">Informasi jadwal wawancara Anda</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show border-0 rounded-0 m-3">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show border-0 rounded-0 m-3">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4 py-3" style="min-width:200px;">Lowongan</th>
                                        <th class="px-3 py-3" style="min-width:120px;">Tanggal</th>
                                        <th class="px-3 py-3" style="min-width:100px;">Jam</th>
                                        <th class="px-3 py-3" style="min-width:130px;">Metode</th>
                                        <th class="px-3 py-3 text-center" style="min-width:120px;">Status</th>
                                        <th class="px-4 py-3 text-center" style="min-width:140px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($wawancara as $item)
                                        @php
                                            $statusConfig = [
                                                'dijadwalkan' => ['color' => 'warning', 'text' => 'Dijadwalkan'],
                                                'dikonfirmasi' => ['color' => 'info', 'text' => 'Dikonfirmasi'],
                                                'hadir' => ['color' => 'primary', 'text' => 'Hadir'],
                                                'tidak_hadir' => ['color' => 'danger', 'text' => 'Tidak Hadir'],
                                                'selesai' => ['color' => 'secondary', 'text' => 'Selesai'],
                                                'verifikasi' => ['color' => 'dark', 'text' => 'Verifikasi'],
                                                'lolos' => ['color' => 'success', 'text' => 'Lolos'],
                                                'gagal' => ['color' => 'danger', 'text' => 'Tidak Lolos'],
                                            ];
                                            $config = $statusConfig[$item->status] ?? $statusConfig['dijadwalkan'];
                                        @endphp
                                        <tr class="align-middle">
                                            <td class="px-4 py-3">
                                                <span
                                                    class="fw-semibold">{{ $item->lowongan->judul_lowongan ?? '-' }}</span>
                                            </td>
                                            <td class="px-3 py-3">
                                                {{ Carbon\Carbon::parse($item->jadwal_wawancara)->format('d/m/Y') }}</td>
                                            <td class="px-3 py-3">
                                                {{ Carbon\Carbon::parse($item->jadwal_wawancara)->format('H:i') }}</td>
                                            <td class="px-3 py-3">
                                                <span
                                                    class="badge bg-{{ $item->jenis_wawancara == 'online' ? 'success' : 'warning' }} rounded-pill px-3 py-1">
                                                    <i
                                                        class="fas fa-{{ $item->jenis_wawancara == 'online' ? 'video' : 'building' }} me-1"></i>
                                                    {{ $item->jenis_wawancara == 'online' ? 'Online' : 'Offline' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-3 text-center">
                                                <span
                                                    class="badge bg-{{ $config['color'] }} rounded-pill px-3 py-1">{{ $config['text'] }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('pelamar.wawancara.show', $item->id) }}"
                                                        class="btn btn-outline-primary btn-sm" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i
                                                    class="fas fa-chalkboard-user fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                                <h6 class="text-muted fw-light">Belum Ada Jadwal Wawancara</h6>
                                                <p class="text-muted small">Saat ini belum ada jadwal wawancara untuk Anda
                                                </p>
                                                <a href="{{ route('pelamar.dashboard') }}"
                                                    class="btn btn-primary btn-sm rounded-pill px-4 mt-2">
                                                    <i class="fas fa-home me-1"></i> Kembali ke Dashboard
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table th {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
        }

        .table td {
            font-size: 0.85rem;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn-sm {
            padding: 0.2rem 0.5rem;
            font-size: 0.75rem;
        }

        .badge {
            font-weight: 500;
            font-size: 0.7rem;
        }
    </style>
@endsection
