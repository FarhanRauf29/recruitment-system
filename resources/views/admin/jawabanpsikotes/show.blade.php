@extends('layouts.admin')
@section('title', 'Detail Jawaban Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0 fw-bold">
                    <i class="fas fa-reply text-primary me-2"></i>Detail Jawaban Psikotes
                </h3>
                <div>
                    <a href="{{ route('admin.jawabanpsikotes.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <!-- Informasi Jawaban -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-semibold"><i class="fas fa-user me-2"></i>Informasi Pelamar</h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Pelamar</th>
                                        <td class="py-2"><strong>{{ $jawaban->pelamar->nama_lengkap ?? '-' }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Token Sesi</th>
                                        <td class="py-2">
                                            @if ($jawaban->sesiPsikotes)
                                                <code
                                                    class="bg-light px-2 py-1 rounded">{{ Str::limit($jawaban->sesiPsikotes->token_sesi, 15) }}</code>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Waktu Jawab</th>
                                        <td class="py-2">
                                            @if ($jawaban->waktu_jawab)
                                                {{ \Carbon\Carbon::parse($jawaban->waktu_jawab)->timezone('Asia/Jakarta')->format('d F Y H:i:s') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Waktu Menjawab (detik)
                                        </th>
                                        <td class="py-2">
                                            {{ $jawaban->waktu_menjawab_detik ?? '-' }} detik
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Tanggal Dibuat</th>
                                        <td class="py-2">
                                            {{ \Carbon\Carbon::parse($jawaban->created_at)->timezone('Asia/Jakarta')->format('d F Y H:i:s') }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-simple me-2"></i>Hasil Penilaian</h6>
                            </div>
                            <div class="card-body p-0">
                                @php
                                    $soal = $jawaban->soalPsikotes;
                                    $jenisSoal = $soal?->jenis ?? 'kognitif';
                                    $isKognitif = $jenisSoal === 'kognitif';
                                    $isKepribadian = $jenisSoal === 'kepribadian';
                                    $isMinat = $jenisSoal === 'minat';
                                @endphp
                                <table class="table table-borderless mb-0">
                                    @if ($isKognitif)
                                        <tr>
                                            <th width="40%" class="ps-3 py-2 text-muted fw-normal">Status Jawaban</th>
                                            <td class="py-2">
                                                @if ($jawaban->benar == 1)
                                                    <span style="color: #059669; font-weight: 600;">
                                                        <i class="fas fa-check-circle me-1"></i>Benar
                                                    </span>
                                                @elseif ($jawaban->benar === 0)
                                                    <span style="color: #dc2626; font-weight: 600;">
                                                        <i class="fas fa-times-circle me-1"></i>Salah
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th width="40%" class="ps-3 py-2 text-muted fw-normal">Skor</th>
                                            <td class="py-2">
                                                <span
                                                    class="fw-semibold">{{ $jawaban->skor ?? ($soal->bobot_nilai ?? 0) }}</span>
                                            </td>
                                        </tr>
                                    @elseif ($isKepribadian)
                                        <tr>
                                            <th width="40%" class="ps-3 py-2 text-muted fw-normal">Skor Kepribadian</th>
                                            <td class="py-2">
                                                <span class="fw-semibold">{{ $jawaban->skor_kepribadian ?? 0 }}</span>
                                                <span class="text-muted small"> (Max 5 per soal)</span>
                                            </td>
                                        </tr>
                                    @elseif ($isMinat)
                                        <tr>
                                            <th width="40%" class="ps-3 py-2 text-muted fw-normal">Skor Minat Bakat</th>
                                            <td class="py-2">
                                                <span class="fw-semibold">{{ $jawaban->skor_minat_bakat ?? 0 }}</span>
                                                <span class="text-muted small"> (Max 5 per soal)</span>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Jenis Soal</th>
                                        <td class="py-2">
                                            @if ($isKognitif)
                                                <span class="badge bg-info">Kognitif</span>
                                            @elseif ($isKepribadian)
                                                <span class="badge bg-warning">Kepribadian</span>
                                            @elseif ($isMinat)
                                                <span class="badge bg-danger">Minat Bakat</span>
                                            @else
                                                <span class="badge bg-secondary">Unknown</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Jawaban Pelamar</th>
                                        <td class="py-2">
                                            <span class="badge bg-primary fs-6 px-3 py-2">{{ $jawaban->jawaban }}</span>
                                        </td>
                                    </tr>
                                    @if ($soal && $soal->kunci_jawaban && $isKognitif)
                                        <tr>
                                            <th width="40%" class="ps-3 py-2 text-muted fw-normal">Kunci Jawaban</th>
                                            <td class="py-2">
                                                <span
                                                    class="badge bg-secondary fs-6 px-3 py-2">{{ $soal->kunci_jawaban }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Soal -->
                @if ($jawaban->soalPsikotes)
                    <div class="mt-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="fas fa-file-alt text-primary me-2"></i>
                            <h5 class="mb-0 fw-semibold">Detail Soal</h5>
                        </div>
                        <div class="card border shadow-sm">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th width="15%" class="text-muted fw-normal">Kode Soal</th>
                                                <td width="35%">{{ $jawaban->soalPsikotes->kode_soal }}</td>
                                                <th width="15%" class="text-muted fw-normal">Kategori</th>
                                                <td width="35%">
                                                    {{ $jawaban->soalPsikotes->kategori->nama_kategori ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Tingkat Kesulitan</th>
                                                <td>
                                                    @php $tingkat = $jawaban->soalPsikotes->tingkat_kesulitan ?? 1; @endphp
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $tingkat ? 'text-warning' : 'text-muted' }}"
                                                            style="font-size: 12px;"></i>
                                                    @endfor
                                                    <span class="ms-2 text-muted">({{ $tingkat }}/5)</span>
                                                </td>
                                                <th class="text-muted fw-normal">Bobot Nilai</th>
                                                <td>{{ $jawaban->soalPsikotes->bobot_nilai ?? 1 }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-muted fw-normal">Waktu per Soal</th>
                                                <td>{{ $jawaban->soalPsikotes->waktu_per_soal_detik ?? 60 }} detik</td>
                                                <th class="text-muted fw-normal">Status Aktif</th>
                                                <td>
                                                    @if ($jawaban->soalPsikotes->aktif)
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-danger">Nonaktif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <h6 class="fw-semibold mb-2">Pertanyaan:</h6>
                                    <div class="border p-3 bg-light rounded">
                                        {!! nl2br(e($jawaban->soalPsikotes->pertanyaan)) !!}
                                    </div>
                                </div>

                                @if ($jawaban->soalPsikotes->penjelasan)
                                    <div class="mt-3">
                                        <h6 class="fw-semibold mb-2">Penjelasan:</h6>
                                        <div class="border p-3 bg-info bg-opacity-10 rounded">
                                            {!! nl2br(e($jawaban->soalPsikotes->penjelasan)) !!}
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <h6 class="fw-semibold mb-2">Opsi Jawaban:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="text-center" width="10%">Huruf</th>
                                                    <th>Opsi</th>
                                                    <th class="text-center" width="15%">Bobot Skor</th>
                                                    <th class="text-center" width="15%">Jawaban Pelamar</th>
                                                    @if ($jawaban->soalPsikotes->kunci_jawaban && $isKognitif)
                                                        <th class="text-center" width="15%">Kunci Jawaban</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $opsiJawaban = is_array($jawaban->soalPsikotes->opsi_jawaban)
                                                        ? $jawaban->soalPsikotes->opsi_jawaban
                                                        : json_decode($jawaban->soalPsikotes->opsi_jawaban, true);
                                                    $opsiJawaban = $opsiJawaban ?? [];
                                                    $bobotOpsi = $jawaban->soalPsikotes->bobot_opsi ?? [];
                                                @endphp

                                                @foreach ($opsiJawaban as $huruf => $opsi)
                                                    <tr class="{{ $huruf == $jawaban->jawaban ? 'table-success' : '' }}">
                                                        <td class="text-center"><strong>{{ $huruf }}</strong></td>
                                                        <td>{{ $opsi }}</td>
                                                        <td class="text-center">
                                                            @if ($bobotOpsi && isset($bobotOpsi[$huruf]))
                                                                <span
                                                                    class="badge bg-secondary">{{ $bobotOpsi[$huruf] }}</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            @if ($huruf == $jawaban->jawaban)
                                                                <i class="fas fa-check-circle text-success fs-5"></i>
                                                            @endif
                                                        </td>
                                                        @if ($jawaban->soalPsikotes->kunci_jawaban && $isKognitif)
                                                            <td class="text-center">
                                                                @if ($huruf == $jawaban->soalPsikotes->kunci_jawaban)
                                                                    <i class="fas fa-key text-primary fs-5"></i>
                                                                @endif
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tombol Aksi -->
                <div class="mt-4 d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.jawabanpsikotes.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="button" class="btn btn-danger"
                        onclick="if(confirm('Hapus jawaban ini? Data akan dihapus permanen.')) document.getElementById('delete-form').submit();">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                    <form id="delete-form" action="{{ route('admin.jawabanpsikotes.destroy', $jawaban->id) }}"
                        method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-opacity-10 {
            background-color: rgba(var(--bs-info-rgb), 0.1) !important;
        }

        .table-success {
            background-color: #d1e7dd !important;
        }

        @media print {

            .btn,
            .btn-group,
            .card-header .badge,
            .d-flex.justify-content-end {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush
