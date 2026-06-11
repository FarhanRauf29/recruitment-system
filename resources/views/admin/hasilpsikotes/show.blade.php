@extends('layouts.admin')
@section('title', 'Detail Hasil Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0 fw-bold">
                    <i class="fas fa-chart-line text-primary me-2"></i>Detail Hasil Psikotes
                </h3>
                <div>
                    <a href="{{ route('admin.hasilpsikotes.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <!-- Informasi Pelamar dan Hasil -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-semibold"><i class="fas fa-user me-2"></i>Informasi Pelamar</h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <th width="35%" class="ps-3 py-2 text-muted fw-normal">Pelamar</th>
                                        <td class="py-2"><strong>{{ $hasil->pelamar->nama_lengkap ?? '-' }}</strong></td>
                                    </tr>
                                    <tr>
                                        <th width="35%" class="ps-3 py-2 text-muted fw-normal">Lowongan</th>
                                        <td class="py-2">{{ $hasil->lowongan->judul_lowongan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th width="35%" class="ps-3 py-2 text-muted fw-normal">Token Sesi</th>
                                        <td class="py-2">
                                            @if ($hasil->sesi)
                                                <code
                                                    class="bg-light px-2 py-1 rounded">{{ Str::limit($hasil->sesi->token_sesi, 15) }}</code>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="35%" class="ps-3 py-2 text-muted fw-normal">Total Soal</th>
                                        <td class="py-2">{{ $hasil->total_soal_ditampilkan }} soal</td>
                                    </tr>
                                    <tr>
                                        <th width="35%" class="ps-3 py-2 text-muted fw-normal">Soal Dijawab</th>
                                        <td class="py-2">{{ $hasil->soal_dijawab }} soal</td>
                                    </tr>
                                    <tr>
                                        <th width="35%" class="ps-3 py-2 text-muted fw-normal">Durasi Pengerjaan</th>
                                        <td class="py-2">
                                            @php
                                                $durasi = $hasil->durasi_pengerjaan_detik ?? 0;
                                                $menit = floor($durasi / 60);
                                                $detik = $durasi % 60;
                                            @endphp
                                            {{ $menit > 0 ? $menit . ' menit ' : '' }}{{ $detik }} detik
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light py-2">
                                <h6 class="mb-0 fw-semibold"><i class="fas fa-chart-simple me-2"></i>Hasil Tes</h6>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-borderless mb-0">
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Nilai Kognitif</th>
                                        <td class="py-2">
                                            @if ($hasil->nilai_kognitif !== null)
                                                <span
                                                    class="fw-semibold">{{ number_format($hasil->nilai_kognitif, 2) }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Nilai Kepribadian</th>
                                        <td class="py-2">
                                            @if ($hasil->nilai_kepribadian !== null)
                                                <span
                                                    class="fw-semibold">{{ number_format($hasil->nilai_kepribadian, 2) }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Nilai Minat Bakat</th>
                                        <td class="py-2">
                                            @if (($hasil->nilai_minat ?? ($hasil->nilai_minat_bakat ?? null)) !== null)
                                                <span
                                                    class="fw-semibold">{{ number_format($hasil->nilai_minat ?? $hasil->nilai_minat_bakat, 2) }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Nilai Akhir</th>
                                        <td class="py-2">
                                            @if ($hasil->nilai_akhir !== null)
                                                <span
                                                    style="color: {{ $hasil->nilai_akhir >= 70 ? '#198754' : '#dc3545' }}; font-weight: 700;">
                                                    {{ number_format($hasil->nilai_akhir, 2) }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Status Kelulusan</th>
                                        <td class="py-2">
                                            <span
                                                style="color: {{ $hasil->lulus ? '#198754' : '#dc3545' }}; font-weight: 600;">
                                                <i
                                                    class="fas {{ $hasil->lulus ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                                {{ $hasil->lulus ? 'Lulus' : 'Tidak Lulus' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Rekomendasi</th>
                                        <td class="py-2">
                                            @if ($hasil->rekomendasi)
                                                @php
                                                    $rekomendasiMap = [
                                                        'sangat_baik' => [
                                                            'label' => 'Sangat Baik',
                                                            'color' => '#198754',
                                                        ],
                                                        'baik' => ['label' => 'Baik', 'color' => '#0d6efd'],
                                                        'cukup' => ['label' => 'Cukup', 'color' => '#ffc107'],
                                                        'kurang' => ['label' => 'Kurang', 'color' => '#fd7e14'],
                                                        'tidak_lulus' => [
                                                            'label' => 'Tidak Lulus',
                                                            'color' => '#dc3545',
                                                        ],
                                                    ];
                                                    $rekom = $rekomendasiMap[$hasil->rekomendasi] ?? [
                                                        'label' => ucfirst(str_replace('_', ' ', $hasil->rekomendasi)),
                                                        'color' => '#6c757d',
                                                    ];
                                                @endphp
                                                <span style="color: {{ $rekom['color'] }}; font-weight: 500;">
                                                    {{ $rekom['label'] }}
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th width="40%" class="ps-3 py-2 text-muted fw-normal">Waktu Selesai</th>
                                        <td class="py-2">
                                            @if ($hasil->waktu_selesai)
                                                {{ \Carbon\Carbon::parse($hasil->waktu_selesai)->timezone('Asia/Jakarta')->format('d F Y H:i:s') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Jawaban -->
                @if ($hasil->sesi && $hasil->sesi->jawabanPsikotes && $hasil->sesi->jawabanPsikotes->count() > 0)
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-semibold mb-0">
                                <i class="fas fa-list-check text-primary me-2"></i>Detail Jawaban
                            </h5>
                            <span class="badge bg-secondary">{{ $hasil->sesi->jawabanPsikotes->count() }} Soal</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered align-middle">
                                <thead style="background-color: #f8f9fa;">
                                    <tr class="text-center">
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 45%;">Soal</th>
                                        <th style="width: 10%;">Jawaban</th>
                                        <th style="width: 15%;">Kategori</th>
                                        <th style="width: 15%;">Status</th>
                                        <th style="width: 10%;">Skor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hasil->sesi->jawabanPsikotes as $jawabanItem)
                                        @php
                                            $soal = $jawabanItem->soalPsikotes;
                                            $jenisSoal = $soal?->jenis ?? 'kognitif';

                                            if ($jenisSoal == 'kognitif') {
                                                $benar =
                                                    $soal &&
                                                    $soal->kunci_jawaban &&
                                                    strtoupper($jawabanItem->jawaban) ==
                                                        strtoupper($soal->kunci_jawaban);
                                                $statusLabel = $benar ? 'Benar' : 'Salah';
                                                $statusClass = $benar ? 'success' : 'danger';
                                                $kategoriLabel = 'Kognitif';
                                                $kategoriClass = 'info';
                                                $skor = $jawabanItem->skor ?? ($benar ? $soal->bobot_nilai ?? 0 : 0);
                                            } elseif ($jenisSoal == 'kepribadian') {
                                                $kategoriLabel = 'Kepribadian';
                                                $kategoriClass = 'warning';
                                                $statusLabel = 'Terisi';
                                                $statusClass = 'secondary';
                                                $skor = $jawabanItem->skor_kepribadian ?? 0;
                                            } elseif ($jenisSoal == 'minat') {
                                                $kategoriLabel = 'Minat Bakat';
                                                $kategoriClass = 'danger';
                                                $statusLabel = 'Terisi';
                                                $statusClass = 'secondary';
                                                $skor = $jawabanItem->skor_minat_bakat ?? 0;
                                            } else {
                                                $kategoriLabel = 'Unknown';
                                                $kategoriClass = 'secondary';
                                                $statusLabel = '-';
                                                $statusClass = 'secondary';
                                                $skor = 0;
                                            }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>{{ Str::limit(strip_tags($soal?->pertanyaan ?? '-'), 100) }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="fw-semibold badge bg-primary">{{ $jawabanItem->jawaban }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-{{ $kategoriClass }} bg-opacity-10 text-{{ $kategoriClass }} border-0">
                                                    {{ $kategoriLabel }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $statusClass }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-semibold">{{ $skor }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot style="background-color: #f8f9fa;">
                                    <tr>
                                        <th colspan="5" class="text-end pe-3">Nilai Akhir:</th>
                                        <th class="text-center">
                                            <span class="fw-bold"
                                                style="color: {{ ($hasil->nilai_akhir ?? 0) >= 70 ? '#198754' : '#dc3545' }}">
                                                {{ number_format($hasil->nilai_akhir ?? 0, 2) }}
                                            </span>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="mt-4 text-center py-5 bg-light rounded-3">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="mb-0 text-muted">Belum ada data jawaban</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .bg-opacity-10 {
            background-color: rgba(var(--bs-primary-rgb), 0.1) !important;
        }

        @media print {

            .btn,
            .btn-group,
            .card-header .badge,
            .bg-light .badge,
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
