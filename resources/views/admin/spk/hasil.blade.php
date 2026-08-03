@extends('layouts.admin')

@section('title', 'Hasil Ranking SPK')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-gradient-primary text-white border-0 rounded-top-4 py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-white bg-opacity-20 p-2">
                                    <i class="fas fa-chart-bar fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0 fw-bold">Hasil Ranking Kandidat</h5>
                                    <p class="small mb-0 opacity-75">Lowongan: {{ $lowongan->judul_lowongan }} (Slot:
                                        {{ $lowongan->slot }})</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.spk.penilaian', $lowongan->id) }}"
                                class="btn btn-light btn-sm rounded-pill px-3">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4">
                                {{ session('success') }}<button type="button" class="btn-close"
                                    data-bs-dismiss="alert"></button></div>
                        @endif

                        <ul class="nav nav-pills nav-fill mb-4 gap-2">
                            <li class="nav-item flex-fill">
                                <button class="nav-link active rounded-pill py-2" data-bs-toggle="tab"
                                    data-bs-target="#saw">
                                    <i class="fas fa-calculator me-2"></i> Metode SAW
                                </button>
                            </li>
                            <li class="nav-item flex-fill">
                                <button class="nav-link rounded-pill py-2" data-bs-toggle="tab" data-bs-target="#topsis">
                                    <i class="fas fa-chart-line me-2"></i> Metode TOPSIS
                                </button>
                            </li>
                            <li class="nav-item flex-fill">
                                <button class="nav-link rounded-pill py-2" data-bs-toggle="tab" data-bs-target="#offering">
                                    <i class="fas fa-briefcase me-2"></i> Kandidat Offering
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- TAB SAW -->
                            <div class="tab-pane fade show active" id="saw">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center" style="width: 10%">Ranking</th>
                                                <th style="width: 40%">Nama Pelamar</th>
                                                <th class="text-center" style="width: 15%">Nilai SAW</th>
                                                <th class="text-center" style="width: 20%">Status</th>
                                                <th class="text-center" style="width: 15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($hasilSAW as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge bg-{{ $item->ranking <= $lowongan->slot ? 'success' : ($item->ranking <= $lowongan->slot * 2 ? 'warning' : 'secondary') }} rounded-pill fs-6 px-3 py-2">
                                                            #{{ $item->ranking }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                                <i class="fas fa-user text-primary"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">{{ $item->pelamar->nama_lengkap }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="fw-bold fs-5 text-primary">{{ number_format($item->nilai_saw, 4) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($item->status_rekomendasi == 'rekomendasi')
                                                            <span class="badge bg-success rounded-pill px-3 py-2">
                                                                <i class="fas fa-check-circle me-1"></i> Rekomendasi
                                                            </span>
                                                        @elseif ($item->status_rekomendasi == 'cadangan')
                                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                                <i class="fas fa-exclamation-circle me-1"></i> Cadangan
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                                                <i class="fas fa-times-circle me-1"></i> Tidak Rekomendasi
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <a href="{{ route('admin.spk.detail-saw', [$lowongan->id, $item->pelamar_id]) }}"
                                                                class="btn btn-sm btn-outline-info rounded-pill px-3">
                                                                <i class="fas fa-eye me-1"></i> Detail
                                                            </a>
                                                            @if ($item->status_rekomendasi == 'rekomendasi' || $item->status_rekomendasi == 'cadangan')
                                                                <form
                                                                    action="{{ route('admin.spk.approve', [$lowongan->id, $item->pelamar_id]) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="metode" value="saw">
                                                                    <input type="hidden" name="catatan"
                                                                        value="Lolos berdasarkan perhitungan SAW (Ranking {{ $item->ranking }} - {{ ucfirst($item->status_rekomendasi) }})">
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-success rounded-pill px-3"
                                                                        onclick="return confirm('Approve kandidat ini ke tahap offering?')">
                                                                        <i class="fas fa-check-circle me-1"></i> Approve
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <i class="fas fa-chart-line fa-3x text-muted mb-3 d-block"></i>
                                                        <h5 class="text-muted">Belum Ada Hasil SAW</h5>
                                                        <p class="text-muted mb-0">Silakan hitung SAW terlebih dahulu</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if ($hasilSAW->isNotEmpty())
                                    <div class="d-flex justify-content-end mt-3">
                                        <a href="{{ route('admin.spk.export', [$lowongan->id, 'saw']) }}"
                                            class="btn btn-sm btn-success rounded-pill px-4">
                                            <i class="fas fa-download me-2"></i> Export Excel
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- TAB TOPSIS -->
                            <div class="tab-pane fade" id="topsis">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="text-center" style="width: 10%">Ranking</th>
                                                <th style="width: 40%">Nama Pelamar</th>
                                                <th class="text-center" style="width: 15%">Nilai TOPSIS</th>
                                                <th class="text-center" style="width: 20%">Status</th>
                                                <th class="text-center" style="width: 15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($hasilTOPSIS as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge bg-{{ $item->ranking <= $lowongan->slot ? 'success' : ($item->ranking <= $lowongan->slot * 2 ? 'warning' : 'secondary') }} rounded-pill fs-6 px-3 py-2">
                                                            #{{ $item->ranking }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                                <i class="fas fa-user text-primary"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">
                                                                    {{ $item->pelamar->nama_lengkap }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="fw-bold fs-5 text-primary">{{ number_format($item->nilai_topsis, 4) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($item->status_rekomendasi == 'rekomendasi')
                                                            <span class="badge bg-success rounded-pill px-3 py-2">
                                                                <i class="fas fa-check-circle me-1"></i> Rekomendasi
                                                            </span>
                                                        @elseif ($item->status_rekomendasi == 'cadangan')
                                                            <span
                                                                class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                                <i class="fas fa-exclamation-circle me-1"></i> Cadangan
                                                            </span>
                                                        @else
                                                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                                                <i class="fas fa-times-circle me-1"></i> Tidak Rekomendasi
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex gap-2 justify-content-center">
                                                            <a href="{{ route('admin.spk.detail-topsis', [$lowongan->id, $item->pelamar_id]) }}"
                                                                class="btn btn-sm btn-outline-info rounded-pill px-3">
                                                                <i class="fas fa-eye me-1"></i> Detail
                                                            </a>
                                                            @if ($item->status_rekomendasi == 'rekomendasi' || $item->status_rekomendasi == 'cadangan')
                                                                <form
                                                                    action="{{ route('admin.spk.approve', [$lowongan->id, $item->pelamar_id]) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    <input type="hidden" name="metode" value="topsis">
                                                                    <input type="hidden" name="catatan"
                                                                        value="Lolos berdasarkan perhitungan TOPSIS (Ranking {{ $item->ranking }} - {{ ucfirst($item->status_rekomendasi) }})">
                                                                    <button type="submit"
                                                                        class="btn btn-sm btn-success rounded-pill px-3"
                                                                        onclick="return confirm('Approve kandidat ini ke tahap offering?')">
                                                                        <i class="fas fa-check-circle me-1"></i> Approve
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <i class="fas fa-chart-line fa-3x text-muted mb-3 d-block"></i>
                                                        <h5 class="text-muted">Belum Ada Hasil TOPSIS</h5>
                                                        <p class="text-muted mb-0">Silakan hitung TOPSIS terlebih dahulu
                                                        </p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if ($hasilTOPSIS->isNotEmpty())
                                    <div class="d-flex justify-content-end mt-3">
                                        <a href="{{ route('admin.spk.export', [$lowongan->id, 'topsis']) }}"
                                            class="btn btn-sm btn-success rounded-pill px-4">
                                            <i class="fas fa-download me-2"></i> Export Excel
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- TAB OFFERING -->
                            <div class="tab-pane fade" id="offering">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Nama Pelamar</th>
                                                <th class="text-center">Nilai Akhir</th>
                                                <th class="text-center">Metode</th>
                                                <th class="text-center">Tanggal Approval</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $offeringKandidat = \App\Models\PelamarTahapan::with('pelamar')->where('lowongan_id', $lowongan->id)->where('spk_status', 'approved')->get(); @endphp
                                            @forelse($offeringKandidat as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <div class="rounded-circle bg-primary bg-opacity-10 p-2">
                                                                <i class="fas fa-user text-primary"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-semibold">
                                                                    {{ $item->pelamar->nama_lengkap }}</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center fw-bold text-primary">
                                                        {{ number_format($item->nilai_akhir_spk, 4) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="badge bg-info rounded-pill px-3 py-2">{{ strtoupper($item->metode_terbaik) }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($item->tanggal_approval)->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success rounded-pill px-3 py-2">
                                                            <i class="fas fa-check-circle me-1"></i> Approved Offering
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <i class="fas fa-briefcase fa-3x text-muted mb-3 d-block"></i>
                                                        <h5 class="text-muted">Belum Ada Kandidat Offering</h5>
                                                        <p class="text-muted mb-0">Belum ada kandidat yang di-approve ke
                                                            tahap offering</p>
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
        </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
        }

        .bg-opacity-20 {
            --bs-bg-opacity: 0.2;
        }

        .nav-pills .nav-link {
            background: #f8f9fa;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
        }

        .nav-pills .nav-link:hover:not(.active) {
            background: #e9ecef;
            transform: translateY(-2px);
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .rounded-pill {
            border-radius: 50rem !important;
        }
    </style>
@endsection
