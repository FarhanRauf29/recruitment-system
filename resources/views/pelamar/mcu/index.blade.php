@extends('layouts.pelamar')

@section('title', 'Medical Check Up Saya')

@section('content')
    <div class="container-fluid px-0">
        <div class="row g-0">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-0">
                    <div class="card-header bg-primary text-white py-3 px-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-heartbeat fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Medical Check Up</h5>
                                <small class="opacity-75">Informasi jadwal dan hasil pemeriksaan kesehatan Anda</small>
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
                                        <th class="px-3 py-3" style="min-width:150px;">Lokasi</th>
                                        <th class="px-3 py-3 text-center" style="min-width:80px;">Skor</th>
                                        <th class="px-3 py-3 text-center" style="min-width:120px;">Status</th>
                                        <th class="px-4 py-3 text-center" style="min-width:180px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mcu as $item)
                                        @php
                                            $statusConfig = [
                                                'dijadwalkan' => ['color' => 'warning', 'text' => 'Dijadwalkan'],
                                                'dikonfirmasi' => ['color' => 'info', 'text' => 'Dikonfirmasi'],
                                                'hadir' => ['color' => 'primary', 'text' => 'Hadir'],
                                                'tidak_hadir' => ['color' => 'danger', 'text' => 'Tidak Hadir'],
                                                'verifikasi' => ['color' => 'secondary', 'text' => 'Verifikasi'],
                                                'lolos' => ['color' => 'success', 'text' => 'Lolos MCU'],
                                                'gagal' => ['color' => 'danger', 'text' => 'Tidak Lolos'],
                                            ];
                                            $config = $statusConfig[$item->status] ?? $statusConfig['dijadwalkan'];
                                            $canDownloadPanggilan = in_array($item->status, [
                                                'dijadwalkan',
                                                'dikonfirmasi',
                                            ]);
                                            $canDownloadHasil = !is_null($item->kondisi_kesehatan);
                                        @endphp
                                        <tr class="align-middle">
                                            <td class="px-4 py-3">
                                                <span
                                                    class="fw-semibold">{{ $item->lowongan->judul_lowongan ?? '-' }}</span>
                                            </td>
                                            <td class="px-3 py-3">
                                                {{ Carbon\Carbon::parse($item->jadwal_mcu)->format('d/m/Y') }}</td>
                                            <td class="px-3 py-3">
                                                {{ Carbon\Carbon::parse($item->jadwal_mcu)->format('H:i') }}</td>
                                            <td class="px-3 py-3">
                                                <span
                                                    class="text-muted small">{{ \Str::limit($item->lokasi_mcu, 20) }}</span>
                                            </td>
                                            <td class="px-3 py-3 text-center">
                                                @if ($item->nilai_mcu)
                                                    @php
                                                        $skorTampil = $item->nilai_mcu;
                                                        if ($skorTampil > 100) {
                                                            $skorTampil = $skorTampil / 100;
                                                        }
                                                    @endphp
                                                    <span
                                                        class="fw-bold text-primary">{{ number_format($skorTampil, 2, ',', '.') }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="px-3 py-3 text-center">
                                                <span
                                                    class="badge bg-{{ $config['color'] }} rounded-pill px-3 py-1">{{ $config['text'] }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <div class="d-flex justify-content-center gap-1">
                                                    <a href="{{ route('pelamar.mcu.show', $item->id) }}"
                                                        class="btn btn-outline-primary btn-sm" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if ($canDownloadPanggilan)
                                                        <a href="{{ route('pelamar.mcu.download_surat', $item->id) }}"
                                                            class="btn btn-outline-success btn-sm" title="Surat Panggilan">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    @endif
                                                    @if ($canDownloadHasil)
                                                        <a href="{{ route('pelamar.mcu.download_surat_hasil', $item->id) }}"
                                                            class="btn btn-outline-info btn-sm" title="Hasil MCU">
                                                            <i class="fas fa-file-pdf"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="fas fa-heartbeat fa-3x text-muted opacity-25 mb-3 d-block"></i>
                                                <h6 class="text-muted fw-light">Belum Ada Jadwal MCU</h6>
                                                <p class="text-muted small">Saat ini belum ada jadwal Medical Check Up untuk
                                                    Anda</p>
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
