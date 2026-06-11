@extends('layouts.admin')

@section('title', 'Penilaian Kandidat SPK')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-white bg-opacity-20 p-2">
                                    <i class="fas fa-clipboard-list fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Penilaian Kandidat</h5>
                                    <p class="small mb-0 opacity-75">Lowongan: {{ $lowongan->judul_lowongan }}</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.spk.index') }}" class="btn btn-light btn-sm rounded-pill px-3">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                                <a href="{{ route('admin.spk.kriteria') }}"
                                    class="btn btn-outline-light btn-sm rounded-pill px-3">
                                    <i class="fas fa-weight-hanging me-1"></i> Kriteria
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
                                {{ session('success') }}<button type="button" class="btn-close"
                                    data-bs-dismiss="alert"></button></div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4">
                                {{ session('error') }}<button type="button" class="btn-close"
                                    data-bs-dismiss="alert"></button></div>
                        @endif

                        <form action="{{ route('admin.spk.penilaian.store', $lowongan->id) }}" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="bg-light">
                                        <tr class="text-center">
                                            <th rowspan="2" style="vertical-align: middle; width: 5%">No</th>
                                            <th rowspan="2" style="vertical-align: middle; width: 20%">Nama Pelamar</th>
                                            @foreach ($kriterias as $kriteria)
                                                <th colspan="2" class="text-center">
                                                    {{ $kriteria->nama_kriteria }}<br><small class="text-muted">Bobot:
                                                        {{ $kriteria->bobot }}% | {{ ucfirst($kriteria->atribut) }}</small>
                                                </th>
                                            @endforeach
                                        </tr>
                                        <tr class="text-center">
                                            @foreach ($kriterias as $kriteria)
                                                <th width="100">Nilai (0-100)</th>
                                                <th width="150">Catatan</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pelamars as $index => $pelamar)
                                            <tr>
                                                <td class="text-center fw-bold">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                            <i class="fas fa-user text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $pelamar->nama_lengkap }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                @foreach ($kriterias as $kriteria)
                                                    <td class="text-center">
                                                        <input type="number"
                                                            name="nilai[{{ $pelamar->id }}][{{ $kriteria->id }}]"
                                                            class="form-control form-control-sm text-center rounded-pill"
                                                            value="{{ $penilaian[$pelamar->id][$kriteria->id] ?? '' }}"
                                                            min="0" max="100" step="0.01">
                                                    </td>
                                                    <td>
                                                        <input type="text"
                                                            name="catatan[{{ $pelamar->id }}][{{ $kriteria->id }}]"
                                                            class="form-control form-control-sm rounded-pill"
                                                            placeholder="Catatan...">
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="{{ count($kriterias) * 2 + 2 }}" class="text-center py-5">
                                                    <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                                    <h5 class="text-muted">Belum Ada Kandidat</h5>
                                                    <p class="text-muted mb-0">Belum ada pelamar yang lolos ke tahap
                                                        wawancara</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if ($pelamars->isNotEmpty())
                                <div class="d-flex justify-content-between flex-wrap gap-3 mt-4 pt-2">
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.spk.sinkronisasi', $lowongan->id) }}"
                                            class="btn btn-secondary rounded-pill px-4"
                                            onclick="return confirm('Sinkronkan nilai dari data tahapan pelamar?')">
                                            <i class="fas fa-sync-alt me-2"></i> Sinkronisasi Nilai
                                        </a>
                                        <a href="{{ route('admin.spk.hitung-saw', $lowongan->id) }}"
                                            class="btn btn-success rounded-pill px-4"
                                            onclick="return confirm('Hitung SAW untuk lowongan ini?')">
                                            <i class="fas fa-calculator me-2"></i> Hitung SAW
                                        </a>
                                        <a href="{{ route('admin.spk.hitung-topsis', $lowongan->id) }}"
                                            class="btn btn-info rounded-pill px-4"
                                            onclick="return confirm('Hitung TOPSIS untuk lowongan ini?')">
                                            <i class="fas fa-chart-line me-2"></i> Hitung TOPSIS
                                        </a>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.spk.hasil', $lowongan->id) }}"
                                            class="btn btn-primary rounded-pill px-4">
                                            <i class="fas fa-chart-bar me-2"></i> Lihat Hasil
                                        </a>
                                        <a href="{{ route('admin.spk.export-penilaian', $lowongan->id) }}"
                                            class="btn btn-success rounded-pill px-4">
                                            <i class="fas fa-file-excel me-2"></i> Ekspor ke Excel
                                        </a>
                                        {{-- <button type="submit" class="btn btn-warning rounded-pill px-4">
                                            <i class="fas fa-save me-2"></i> Simpan Penilaian
                                        </button> --}}
                                    </div>
                                </div>
                            @endif
                        </form>
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

        .rounded-pill {
            border-radius: 50rem !important;
        }
    </style>
@endsection
