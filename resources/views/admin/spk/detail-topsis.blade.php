@extends('layouts.admin')

@section('title', 'Detail Perhitungan TOPSIS')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-white bg-opacity-20 p-2">
                                    <i class="fas fa-chart-line fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Detail Perhitungan TOPSIS</h5>
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
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="border rounded-3 p-3 text-center bg-success bg-opacity-10">
                                    <small class="text-muted d-block">Nilai TOPSIS</small>
                                    <h3 class="mb-0 text-success fw-bold">{{ number_format($hasil->nilai_topsis, 4) }}</h3>
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
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">Kode</th>
                                        <th>Kriteria</th>
                                        <th class="text-center">Atribut</th>
                                        <th class="text-center">Bobot (%)</th>
                                        <th class="text-center">Nilai Mentah</th>
                                        <th class="text-center">Normalisasi</th>
                                        <th class="text-center">Nilai Terbobot</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $detailKriteria = $hasil->detail_perhitungan['detail_kriteria'] ?? [];
                                    @endphp
                                    @foreach ($detailKriteria as $kode => $detail)
                                        <tr>
                                            <td class="text-center"><span
                                                    class="badge bg-primary">{{ strtoupper($kode) }}</span></td>
                                            <td>{{ $detail['nama_kriteria'] }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge bg-{{ $detail['atribut'] == 'benefit' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($detail['atribut']) }}
                                                </span>
                                            </td>
                                            <td class="text-center">{{ $detail['bobot'] }}%</td>
                                            <td class="text-center">{{ $detail['nilai_mentah'] }}</td>
                                            <td class="text-center">{{ number_format($detail['nilai_normalisasi'], 4) }}
                                            </td>
                                            <td class="text-center fw-bold text-primary">
                                                {{ number_format($detail['nilai_terbobot'], 4) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <h6 class="fw-bold mb-3"><i class="fas fa-star me-2 text-primary"></i> Solusi Ideal</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">Kode</th>
                                        <th>Kriteria</th>
                                        <th class="text-center">Solusi Ideal Positif (A⁺)</th>
                                        <th class="text-center">Solusi Ideal Negatif (A⁻)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $solusiPositif = $hasil->detail_perhitungan['solusi_ideal_positif'] ?? [];
                                        $solusiNegatif = $hasil->detail_perhitungan['solusi_ideal_negatif'] ?? [];
                                    @endphp
                                    @foreach ($detailKriteria as $kode => $detail)
                                        <tr>
                                            <td class="text-center"><span
                                                    class="badge bg-primary">{{ strtoupper($kode) }}</span></td>
                                            <td>{{ $detail['nama_kriteria'] }}</td>
                                            <td class="text-center fw-bold text-success">
                                                {{ number_format($solusiPositif[$kode] ?? 0, 4) }}</td>
                                            <td class="text-center fw-bold text-danger">
                                                {{ number_format($solusiNegatif[$kode] ?? 0, 4) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info rounded-3 mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Catatan:</strong> Solusi ideal dihitung dari semua kandidat. A⁺ adalah nilai maksimum,
                            A⁻ adalah nilai minimum dari setiap kriteria.
                        </div>

                        <h6 class="fw-bold mb-3"><i class="fas fa-chart-line me-2 text-primary"></i> Jarak Solusi Ideal</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">Parameter</th>
                                        <th class="text-center">Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">Jarak Positif (S⁺)</td>
                                        <td class="text-center fw-bold text-primary">
                                            {{ number_format($hasil->detail_perhitungan['jarak_positif'] ?? 0, 4) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Jarak Negatif (S⁻)</td>
                                        <td class="text-center fw-bold text-primary">
                                            {{ number_format($hasil->detail_perhitungan['jarak_negatif'] ?? 0, 4) }}</td>
                                    </tr>
                                    <tr class="table-success">
                                        <td class="fw-bold">Nilai Preferensi (V)</td>
                                        <td class="text-center fw-bold fs-5">{{ number_format($hasil->nilai_topsis, 4) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info rounded-3">
                            <i class="fas fa-calculator me-2"></i>
                            <strong>Rumus Perhitungan TOPSIS:</strong><br>
                            <code>V = S⁻ / (S⁺ + S⁻)</code><br>
                            V = {{ number_format($hasil->detail_perhitungan['jarak_negatif'] ?? 0, 4) }} /
                            ({{ number_format($hasil->detail_perhitungan['jarak_positif'] ?? 0, 4) }} +
                            {{ number_format($hasil->detail_perhitungan['jarak_negatif'] ?? 0, 4) }})<br>
                            = <strong class="text-primary">{{ number_format($hasil->nilai_topsis, 4) }}</strong>
                        </div>

                        <div class="alert alert-secondary rounded-3">
                            <i class="fas fa-question-circle me-2"></i>
                            <strong>Interpretasi Nilai TOPSIS:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Semakin mendekati <strong class="text-success">1 (satu)</strong>, semakin baik
                                    (mendekati solusi ideal positif)</li>
                                <li>Semakin mendekati <strong class="text-danger">0 (nol)</strong>, semakin buruk (mendekati
                                    solusi ideal negatif)</li>
                                <li>Nilai <strong>{{ number_format($hasil->nilai_topsis, 4) }}</strong> menunjukkan
                                    kandidat ini berada dalam posisi
                                    <strong>{{ round($hasil->nilai_topsis * 100) }}%</strong> mendekati solusi ideal
                                    positif
                                </li>
                            </ul>
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
