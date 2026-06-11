@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Detail Aktivitas Psikotes</h3>
                <div>
                    <a href="{{ route('admin.aktivitaspsikotes.index') }}" class="btn btn-secondary me-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Pelamar</th>
                                <td>
                                    <div>{{ $aktivitas->pelamar->nama_lengkap ?? '-' }}</div>
                                    <small class="text-muted">{{ $aktivitas->pelamar->email ?? '-' }}</small>
                                </td>
                            </tr>
                            <tr>
                                <th>Sesi</th>
                                <td>
                                    @if ($aktivitas->sesi)
                                        <span
                                            class="badge bg-primary">{{ Str::limit($aktivitas->sesi->token_sesi, 15) }}</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tipe Aktivitas</th>
                                <td>
                                    <span
                                        class="badge bg-{{ $aktivitas->tipe_aktivitas == 'mulai_tes'
                                            ? 'primary'
                                            : ($aktivitas->tipe_aktivitas == 'jawab_soal'
                                                ? 'info'
                                                : ($aktivitas->tipe_aktivitas == 'pause_tes'
                                                    ? 'warning'
                                                    : ($aktivitas->tipe_aktivitas == 'lanjut_tes'
                                                        ? 'success'
                                                        : ($aktivitas->tipe_aktivitas == 'selesai_tes'
                                                            ? 'dark'
                                                            : 'secondary')))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $aktivitas->tipe_aktivitas)) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Soal</th>
                                <td>
                                    @if ($aktivitas->soal)
                                        <div>{{ $aktivitas->soal->kode_soal }}</div>
                                        <small class="text-muted">{{ Str::limit($aktivitas->soal->pertanyaan, 50) }}</small>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Jawaban</th>
                                <td>
                                    @if ($aktivitas->jawaban)
                                        <span class="badge bg-primary">{{ $aktivitas->jawaban }}</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>IP Address</th>
                                <td>{{ $aktivitas->ip_address ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>User Agent</th>
                                <td>{{ Str::limit($aktivitas->user_agent, 50) }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>{{ \Carbon\Carbon::parse($aktivitas->created_at)->timezone('Asia/Jakarta')->format('d F Y H:i:s') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Terakhir Diperbarui</th>
                                <td>{{ \Carbon\Carbon::parse($aktivitas->updated_at)->timezone('Asia/Jakarta')->format('d F Y H:i:s') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Metadata --}}
                @if ($aktivitas->metadata)
                    <div class="mt-4">
                        <h5>Metadata Tambahan</h5>
                        <div class="border p-3 bg-light rounded">
                            <pre>{{ json_encode($aktivitas->metadata, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    </div>
                @endif

                {{-- Detail Sesi --}}
                @if ($aktivitas->sesi)
                    <div class="mt-4">
                        <h5>Detail Sesi</h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="40%">Token Sesi</th>
                                                <td>{{ $aktivitas->sesi->token_sesi }}</td>
                                            </tr>
                                            <tr>
                                                <th>Lowongan</th>
                                                <td>{{ $aktivitas->sesi->lowongan->judul_lowongan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $aktivitas->sesi->status == 'belum_dimulai'
                                                            ? 'secondary'
                                                            : ($aktivitas->sesi->status == 'sedang_berjalan'
                                                                ? 'primary'
                                                                : ($aktivitas->sesi->status == 'dijeda'
                                                                    ? 'warning'
                                                                    : 'success')) }}">
                                                        {{ ucfirst(str_replace('_', ' ', $aktivitas->sesi->status)) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <tr>
                                                <th width="40%">Durasi</th>
                                                <td>{{ $aktivitas->sesi->durasi_menit }} menit</td>
                                            </tr>
                                            <tr>
                                                <th>Soal Ditampilkan</th>
                                                <td>{{ $aktivitas->sesi->jumlah_soal_ditampilkan }} soal</td>
                                            </tr>
                                            <tr>
                                                <th>Jawaban Terisi</th>
                                                <td>{{ $aktivitas->sesi->jawabanPsikotes()->count() }} jawaban</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
