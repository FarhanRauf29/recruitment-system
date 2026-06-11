@extends('layouts.admin')

@section('title', 'Detail Perhitungan SAW')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-white bg-opacity-20 p-2">
                                    <i class="fas fa-calculator fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Detail Perhitungan SAW</h5>
                                    <p class="small mb-0 opacity-75">{{ $hasil->pelamar->nama_lengkap }} -
                                        {{ $hasil->lowongan->judul_lowongan ?? 'Lowongan' }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.spk.hasil', $hasil->lowongan_id) }}"
                                class="btn btn-light btn-sm rounded-pill px-3">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 bg-light">
                                    <small class="text-muted d-block">Nama Pelamar</small>
                                    <h6 class="mb-0 fw-bold">{{ $hasil->pelamar->nama_lengkap }}</h6>
                                    <small class="text-muted">{{ $hasil->pelamar->email }}</small>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded-3 p-3 text-center bg-success bg-opacity-10">
                                    <small class="text-muted d-block">Nilai SAW</small>
                                    <h3 class="mb-0 text-success fw-bold">{{ number_format($hasil->nilai_saw, 4) }}</h3>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded-3 p-3 text-center bg-primary bg-opacity-10">
                                    <small class="text-muted d-block">Ranking</small>
                                    <h3 class="mb-0 text-primary fw-bold">#{{ $hasil->ranking }}</h3>
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3"><i class="fas fa-table me-2 text-primary"></i> Detail Perhitungan per
                            Kriteria</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">Kode</th>
                                        <th>Kriteria</th>
                                        <th class="text-center">Nilai Mentah</th>
                                        <th class="text-center">Bobot (%)</th>
                                        <th class="text-center">Bobot Normalisasi</th>
                                        <th class="text-center">Nilai Terbobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalBobot = \App\Models\Kriteria::where('is_active', true)->sum('bobot'); @endphp
                                    @foreach ($hasil->detail_perhitungan as $kode => $detail)
                                        <tr>
                                            <td class="text-center"><span class="badge bg-info">{{ $kode }}</span>
                                            </td>
                                            <td>{{ \App\Models\Kriteria::where('kode_kriteria', $kode)->first()->nama_kriteria ?? $kode }}
                                            </td>
                                            <td class="text-center fw-semibold">{{ $detail['nilai_mentah'] }}</td>
                                            <td class="text-center">{{ $detail['bobot'] }}%</td>
                                            <td class="text-center">{{ number_format($detail['bobot_normalisasi'], 4) }}
                                            </td>
                                            <td class="text-center fw-bold text-primary">
                                                {{ number_format($detail['nilai_terbobot'], 4) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-primary">
                                        <td colspan="5" class="text-end fw-bold">Total Nilai SAW</td>
                                        <td class="text-center fw-bold fs-5">{{ number_format($hasil->nilai_saw, 4) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info rounded-3 mt-3">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Rumus SAW:</strong> Nilai Akhir = Σ (Nilai Mentah × Bobot Normalisasi)<br>
                            Bobot Normalisasi = Bobot Kriteria / Total Bobot ({{ $totalBobot }}%)
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        }

        .bg-opacity-20 {
            --bs-bg-opacity: 0.2;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .rounded-3 {
            border-radius: 0.75rem !important;
        }
    </style>
@endsection
