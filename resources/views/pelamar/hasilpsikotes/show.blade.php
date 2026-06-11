@extends('layouts.pelamar')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i> Detail Hasil Tes Psikotes
                        </h3>
                        <a href="{{ route('pelamar.hasilpsikotes.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Lowongan</th>
                                        <td>{{ $hasil->lowongan->judul_lowongan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Tes</th>
                                        <td>{{ $hasil->waktu_selesai ? \Carbon\Carbon::parse($hasil->waktu_selesai)->format('d F Y H:i') : '-' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Durasi Pengerjaan</th>
                                        <td>{{ $hasil->durasi_pengerjaan_detik ?? '-' }} detik</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="40%">Status Kelulusan</th>
                                        <td>
                                            <span class="badge bg-{{ $hasil->lulus ? 'success' : 'danger' }}">
                                                {{ $hasil->lulus ? 'Lulus' : 'Tidak Lulus' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Rekomendasi</th>
                                        <td>
                                            <span
                                                class="badge bg-{{ $hasil->rekomendasi == 'sangat_baik'
                                                    ? 'success'
                                                    : ($hasil->rekomendasi == 'baik'
                                                        ? 'primary'
                                                        : ($hasil->rekomendasi == 'cukup'
                                                            ? 'warning'
                                                            : ($hasil->rekomendasi == 'kurang'
                                                                ? 'danger'
                                                                : 'secondary'))) }}">
                                                {{ ucfirst(str_replace('_', ' ', $hasil->rekomendasi ?? '-')) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Nilai Kelulusan</th>
                                        <td>{{ $hasil->konfigurasi->batas_nilai_kelulusan ?? '-' }}%</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div
                                    class="card text-center {{ $hasil->nilai_kognitif >= 70 ? 'border-success' : 'border-danger' }}">
                                    <div class="card-body">
                                        <h5 class="card-title">Nilai Kognitif</h5>
                                        <h2
                                            class="card-text {{ $hasil->nilai_kognitif >= 70 ? 'text-success' : 'text-danger' }}">
                                            {{ $hasil->nilai_kognitif ?? '-' }}
                                        </h2>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                Soal: {{ $hasil->soal_kognitif_ditampilkan ?? '-' }}<br>
                                                Benar: {{ $hasil->soal_kognitif_benar ?? '-' }}
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div
                                    class="card text-center {{ $hasil->nilai_kepribadian >= 70 ? 'border-success' : 'border-danger' }}">
                                    <div class="card-body">
                                        <h5 class="card-title">Nilai Kepribadian</h5>
                                        <h2
                                            class="card-text {{ $hasil->nilai_kepribadian >= 70 ? 'text-success' : 'text-danger' }}">
                                            {{ $hasil->nilai_kepribadian ?? '-' }}
                                        </h2>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                Soal: {{ $hasil->soal_kepribadian_ditampilkan ?? '-' }}
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div
                                    class="card text-center {{ $hasil->nilai_akhir >= 70 ? 'border-success' : 'border-danger' }}">
                                    <div class="card-body">
                                        <h5 class="card-title">Nilai Akhir</h5>
                                        <h2
                                            class="card-text {{ $hasil->nilai_akhir >= 70 ? 'text-success' : 'text-danger' }}">
                                            {{ $hasil->nilai_akhir ?? '-' }}
                                        </h2>
                                        <p class="card-text">
                                            <small class="text-muted">
                                                Total Soal: {{ $hasil->total_soal_ditampilkan ?? '-' }}<br>
                                                Dijawab: {{ $hasil->soal_dijawab ?? '-' }}
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($hasil->keterangan)
                            <div class="mt-4">
                                <h5>Keterangan</h5>
                                <div class="border p-3 bg-light rounded">
                                    {{ $hasil->keterangan }}
                                </div>
                            </div>
                        @endif

                        @if ($hasil->sesi && $hasil->sesi->jawabanPsikotes->count() > 0)
                            <div class="mt-4">
                                <h5>Detail Jawaban</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>#</th>
                                                <th>Soal</th>
                                                <th>Jawaban</th>
                                                <th>Benar</th>
                                                <th>Skor</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($hasil->sesi->jawabanPsikotes as $jawaban)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ Str::limit($jawaban->soal->pertanyaan ?? '-', 50) }}</td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $jawaban->jawaban }}</span>
                                                    </td>
                                                    <td>
                                                        @if ($jawaban->benar === true)
                                                            <span class="badge bg-success">Benar</span>
                                                        @elseif($jawaban->benar === false)
                                                            <span class="badge bg-danger">Salah</span>
                                                        @else
                                                            <span class="badge bg-secondary">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($jawaban->skor_kepribadian)
                                                            <span
                                                                class="badge bg-info">{{ $jawaban->skor_kepribadian }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
