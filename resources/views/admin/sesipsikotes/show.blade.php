@extends('layouts.admin')
@section('title', 'Detail Sesi Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i> Detail Sesi Psikotes
                            </h4>
                            <p class="small mb-0 mt-1 opacity-75">Informasi lengkap sesi psikotes</p>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.sesipsikotes.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <a href="{{ route('admin.sesipsikotes.edit', $sesi->id) }}" class="btn btn-warning btn-sm ms-1">
                                <i class="fas fa-edit me-1"></i> Edit Sesi
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-user"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Nama Pelamar</small>
                                        <h6 class="mb-0">{{ $sesi->pelamar->nama_lengkap ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-briefcase"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Lowongan</small>
                                        <h6 class="mb-0">{{ $sesi->lowongan->judul_lowongan ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-clock"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Waktu Mulai</small>
                                        <h6 class="mb-0">
                                            {{ $sesi->waktu_mulai ? $sesi->waktu_mulai->format('d F Y H:i') : '-' }} WIB
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-flag-checkered"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Waktu Selesai</small>
                                        <h6 class="mb-0">
                                            {{ $sesi->waktu_selesai ? $sesi->waktu_selesai->format('d F Y H:i') : '-' }} WIB
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-hourglass-half"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Durasi Tes</small>
                                        <h6 class="mb-0">{{ $sesi->durasi_menit }} menit</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-question-circle"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Jumlah Soal</small>
                                        <h6 class="mb-0">{{ $sesi->jumlah_soal_ditampilkan }} soal</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-tag"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Token Sesi</small>
                                        <h6 class="mb-0">{{ $sesi->token_sesi }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon"><i class="fas fa-chart-line"></i></div>
                                    <div class="info-content">
                                        <small class="text-muted d-block">Status Sesi</small>
                                        <h6 class="mb-0">
                                            <span
                                                class="badge {{ $sesi->status == 'belum_dimulai' ? 'bg-secondary' : ($sesi->status == 'sedang_berjalan' ? 'bg-primary' : ($sesi->status == 'dijeda' ? 'bg-warning' : 'bg-success')) }}">
                                                {{ ucfirst(str_replace('_', ' ', $sesi->status)) }}
                                            </span>
                                            @if ($sesi->selesai)
                                                <span class="badge bg-success ms-1">Selesai</span>
                                            @endif
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="detail-section">
                            <div class="detail-header">
                                <i class="fas fa-list"></i>
                                <h6 class="mb-0">Daftar Soal Terpilih <span
                                        class="badge bg-primary ms-2">{{ count($sesi->soal_terpilih ?? []) }} soal</span>
                                </h6>
                            </div>
                            <div class="detail-body">
                                @if ($sesi->soal_terpilih && count($sesi->soal_terpilih) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Kode Soal</th>
                                                    <th>Pertanyaan</th>
                                                    <th>Kategori</th>
                                                    <th>Jenis</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sesi->soal_terpilih as $index => $soalId)
                                                    @php $soal = \App\Models\SoalPsikotes::with('kategori')->find($soalId); @endphp
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td><code>{{ $soal->kode_soal ?? '-' }}</code></td>
                                                        <td>{{ Str::limit(strip_tags($soal->pertanyaan ?? ''), 60) }}</td>
                                                        <td><span
                                                                class="badge bg-info">{{ $soal->kategori->nama_kategori ?? '-' }}</span>
                                                        </td>
                                                        <td><span
                                                                class="badge {{ $soal->jenis == 'kognitif' ? 'bg-primary' : ($soal->jenis == 'kepribadian' ? 'bg-warning' : 'bg-success') }}">{{ ucfirst($soal->jenis ?? '-') }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4"><i
                                            class="fas fa-question-circle fa-2x mb-2"></i>
                                        <p>Tidak ada soal terpilih</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="detail-section">
                            <div class="detail-header">
                                <i class="fas fa-poll"></i>
                                <h6 class="mb-0">Hasil Psikotes <span
                                        class="badge bg-primary ms-2">{{ $sesi->hasilPsikotes->count() }} hasil</span>
                                </h6>
                            </div>
                            <div class="detail-body">
                                @if ($sesi->hasilPsikotes && $sesi->hasilPsikotes->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Tanggal Tes</th>
                                                    <th>Nilai Total</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sesi->hasilPsikotes as $hasil)
                                                    <tr>
                                                        <td>{{ $hasil->created_at->format('d/m/Y H:i') }} WIB</td>
                                                        <td><span
                                                                class="fw-bold {{ $hasil->nilai_total >= 70 ? 'text-success' : 'text-danger' }}">{{ $hasil->nilai_total }}</span>
                                                        </td>
                                                        <td>
                                                            @if ($hasil->lulus)
                                                            <span class="badge bg-success">Lulus</span>@else<span
                                                                    class="badge bg-danger">Tidak Lulus</span>
                                                            @endif
                                                        </td>
                                                        <td><a href="{{ route('admin.hasilpsikotes.show', $hasil->id) }}"
                                                                class="btn btn-sm btn-outline-info"><i
                                                                    class="fas fa-eye me-1"></i>Detail</a></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4"><i class="fas fa-chart-line fa-2x mb-2"></i>
                                        <p>Belum ada hasil psikotes</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .info-card {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .info-icon {
            width: 45px;
            height: 45px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 1.2rem;
        }

        .info-content {
            flex: 1;
        }

        .detail-section {
            margin-bottom: 25px;
        }

        .detail-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 2px solid #e9ecef;
            margin-bottom: 15px;
        }

        .detail-header i {
            color: #2563eb;
            font-size: 1.1rem;
        }

        .detail-header h6 {
            margin: 0;
            font-weight: 600;
        }

        .detail-body {
            padding: 0 0 0 28px;
        }

        @media (max-width: 768px) {
            .info-card {
                flex-direction: column;
                text-align: center;
            }

            .detail-body {
                padding-left: 0;
            }
        }
    </style>
@endsection
