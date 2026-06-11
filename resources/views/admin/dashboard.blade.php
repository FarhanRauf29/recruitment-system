@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    @php
        use App\Models\Pelamar;
        use App\Models\Lowongan;
        use App\Models\Berkas;
        use App\Models\SoalPsikotes;
        use App\Models\HasilPsikotes;
        use App\Models\PelamarTahapan;
        use App\Models\RiwayatPendidikan;
        use App\Models\RiwayatOrganisasi;
        use App\Models\RiwayatMagang;
        use App\Models\RiwayatPengalaman;
        use App\Models\RiwayatBahasa;
        use App\Models\RiwayatKeahlian;
        use Carbon\Carbon;

        $months = [];
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->isoFormat('MMMM');
            $trendData[] = Pelamar::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        $totalPelamar = Pelamar::count();
        $totalPelamarLaki = Pelamar::where('jenis_kelamin', 'Laki-laki')->count();
        $totalPelamarPerempuan = Pelamar::where('jenis_kelamin', 'Perempuan')->count();
        $totalLowonganAktif = Lowongan::where('status', 'buka')->count();
        $totalLowonganTutup = Lowongan::where('status', 'tutup')->count();
        $totalPelamarBaruHariIni = Pelamar::whereDate('created_at', Carbon::today())->count();
        $totalPelamarBulanIni = Pelamar::whereMonth('created_at', Carbon::now()->month)->count();
        $totalBerkas = Berkas::count();
        $totalSoal = SoalPsikotes::count();

        $statusPending = Berkas::where('status_verifikasi', 'Pending')->count();
        $statusDiterima = Berkas::where('status_verifikasi', 'Diterima')->count();
        $statusDitolak = Berkas::where('status_verifikasi', 'Ditolak')->count();
        $persentaseVerifikasi =
            $totalBerkas > 0 ? round((($statusDiterima + $statusDitolak) / $totalBerkas) * 100, 1) : 0;

        $range1 = HasilPsikotes::whereBetween('nilai_akhir', [0, 50])->count();
        $range2 = HasilPsikotes::whereBetween('nilai_akhir', [51, 70])->count();
        $range3 = HasilPsikotes::whereBetween('nilai_akhir', [71, 85])->count();
        $range4 = HasilPsikotes::whereBetween('nilai_akhir', [86, 100])->count();

        $catKognitif = SoalPsikotes::where('jenis', 'kognitif')->count();
        $catKepribadian = SoalPsikotes::where('jenis', 'kepribadian')->count();
        $catMinat = SoalPsikotes::where('jenis', 'minat')->count();

        $totalPendidikan = RiwayatPendidikan::count();
        $totalOrganisasi = RiwayatOrganisasi::count();
        $totalMagang = RiwayatMagang::count();
        $totalPengalaman = RiwayatPengalaman::count();
        $totalBahasa = RiwayatBahasa::count();
        $totalKeahlian = RiwayatKeahlian::count();
        $totalProfilTerisi = Pelamar::whereNotNull('nama_depan')->whereNotNull('nama_belakang')->count();
        $persentaseProfil = $totalPelamar > 0 ? round(($totalProfilTerisi / $totalPelamar) * 100, 1) : 0;

        $totalSesiPsikotes = \App\Models\SesiPsikotes::count();
        $totalSesiSelesai = \App\Models\SesiPsikotes::where('selesai', true)->count();

        $tahapanSelesai = PelamarTahapan::where('status', 'lolos')->count();
        $tahapanProses = PelamarTahapan::where('status', 'diproses')->count();
        $tahapanMenunggu = PelamarTahapan::where('status', 'menunggu')->count();

        $monthlyGrowth = [];
        for ($i = 0; $i < count($trendData); $i++) {
            if ($i == 0) {
                $monthlyGrowth[] = 0;
            } else {
                $previous = $trendData[$i - 1];
                $current = $trendData[$i];
                if ($previous > 0) {
                    $growth = (($current - $previous) / $previous) * 100;
                    $monthlyGrowth[] = round($growth, 1);
                } else {
                    $monthlyGrowth[] = $current > 0 ? 100 : 0;
                }
            }
        }
    @endphp

    <div class="container-fluid px-3 px-md-4 py-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4 p-lg-5 welcome-card">
                        <div
                            class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                            <div>
                                <h3 class="mb-2 fw-bold text-white">Selamat Datang, {{ auth()->user()->name }} 👋</h3>
                                <p class="text-white mb-0">Pantau perkembangan rekrutmen dan kelola semua data pelamar
                                    dari satu dashboard.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards Row -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-users fa-2x text-primary"></i>
                            </div>
                            <span class="badge bg-success-subtle text-success">
                                <i class="fas fa-arrow-up me-1"></i>+{{ $totalPelamarBaruHariIni }}
                            </span>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Total Pelamar</div>
                            <h2 class="mb-0 fw-bold stat-number">{{ number_format($totalPelamar) }}</h2>
                            <small class="text-muted mt-2 d-block">+{{ $totalPelamarBulanIni }} bulan ini</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-success bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-briefcase fa-2x text-success"></i>
                            </div>
                            <span class="badge bg-success-subtle text-success">
                                <i class="fas fa-check-circle me-1"></i>Aktif
                            </span>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Lowongan Aktif</div>
                            <h2 class="mb-0 fw-bold stat-number">{{ number_format($totalLowonganAktif) }}</h2>
                            <small class="text-muted mt-2 d-block">{{ $totalLowonganTutup }} ditutup</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-warning bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-tasks fa-2x text-warning"></i>
                            </div>
                            <span class="badge bg-warning-subtle text-warning">
                                {{ $statusPending }} pending
                            </span>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Verifikasi Berkas</div>
                            <h2 class="mb-0 fw-bold stat-number">{{ $persentaseVerifikasi }}%</h2>
                            <small class="text-muted mt-2 d-block">{{ $totalBerkas }} total berkas</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 h-100 stat-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="bg-info bg-opacity-10 p-3 rounded-3">
                                <i class="fas fa-brain fa-2x text-info"></i>
                            </div>
                            <span class="badge bg-info-subtle text-info">
                                <i class="fas fa-layer-group me-1"></i>Bank Soal
                            </span>
                        </div>
                        <div>
                            <div class="text-muted small text-uppercase fw-semibold mb-1">Total Soal</div>
                            <h2 class="mb-0 fw-bold stat-number">{{ number_format($totalSoal) }}</h2>
                            <small class="text-muted mt-2 d-block">{{ $totalSesiPsikotes }} sesi aktif</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 1 -->
        <div class="row g-3 mb-4">
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h5 class="mb-1 fw-bold"><i class="fas fa-chart-line text-primary me-2"></i>Tren Pendaftaran
                                    Pelamar</h5>
                                <p class="text-muted small mb-0">Statistik pendaftaran 6 bulan terakhir</p>
                            </div>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary active"
                                    onclick="changeChartType('line')">
                                    <i class="fas fa-chart-line me-1"></i> Line
                                </button>
                                <button type="button" class="btn btn-outline-primary" onclick="changeChartType('bar')">
                                    <i class="fas fa-chart-bar me-1"></i> Bar
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-wrapper">
                            <canvas id="trendChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-1 fw-bold"><i class="fas fa-chart-pie text-primary me-2"></i>Status Verifikasi Berkas
                        </h5>
                        <p class="text-muted small mb-0">Distribusi status verifikasi dokumen pelamar</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-wrapper">
                            <canvas id="statusChart"></canvas>
                        </div>
                        <div class="row text-center mt-4 g-2">
                            <div class="col-4">
                                <div class="p-3 border rounded-3 bg-light status-badge">
                                    <div class="fw-bold text-warning fs-4">{{ $statusPending }}</div>
                                    <small class="text-muted">Pending</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 border rounded-3 bg-light status-badge">
                                    <div class="fw-bold text-success fs-4">{{ $statusDiterima }}</div>
                                    <small class="text-muted">Diterima</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 border rounded-3 bg-light status-badge">
                                    <div class="fw-bold text-danger fs-4">{{ $statusDitolak }}</div>
                                    <small class="text-muted">Ditolak</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row 2 -->
        <div class="row g-3 mb-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-1 fw-bold"><i class="fas fa-chart-bar text-primary me-2"></i>Distribusi Nilai
                            Psikotes</h5>
                        <p class="text-muted small mb-0">Sebaran nilai akhir peserta psikotes</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="chart-wrapper">
                            <canvas id="scoreChart"></canvas>
                        </div>
                        <div class="mt-4">
                            <div class="progress" style="height: 10px; border-radius: 10px;">
                                @php $totalRange = $range1 + $range2 + $range3 + $range4; @endphp
                                <div class="progress-bar bg-danger"
                                    style="width: {{ $totalRange > 0 ? ($range1 / $totalRange) * 100 : 0 }}%; border-radius: 10px 0 0 10px;">
                                </div>
                                <div class="progress-bar bg-warning"
                                    style="width: {{ $totalRange > 0 ? ($range2 / $totalRange) * 100 : 0 }}%;"></div>
                                <div class="progress-bar bg-info"
                                    style="width: {{ $totalRange > 0 ? ($range3 / $totalRange) * 100 : 0 }}%;"></div>
                                <div class="progress-bar bg-success"
                                    style="width: {{ $totalRange > 0 ? ($range4 / $totalRange) * 100 : 0 }}%; border-radius: 0 10px 10px 0;">
                                </div>
                            </div>
                            <div class="row small text-center mt-3 g-2">
                                <div class="col-3">
                                    <div class="score-label">
                                        <span class="text-danger">●</span> 0-50 <span
                                            class="fw-bold ms-1">{{ $range1 }}</span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="score-label">
                                        <span class="text-warning">●</span> 51-70 <span
                                            class="fw-bold ms-1">{{ $range2 }}</span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="score-label">
                                        <span class="text-info">●</span> 71-85 <span
                                            class="fw-bold ms-1">{{ $range3 }}</span>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="score-label">
                                        <span class="text-success">●</span> 86-100 <span
                                            class="fw-bold ms-1">{{ $range4 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-1 fw-bold"><i class="fas fa-database text-primary me-2"></i>Komposisi Bank Soal</h5>
                        <p class="text-muted small mb-0">Distribusi soal psikotes per kategori</p>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center g-4">
                            <div class="col-md-5">
                                <div class="chart-wrapper-small">
                                    <canvas id="categoryChart"></canvas>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="list-group list-group-flush">
                                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                        <span><i class="fas fa-circle text-primary me-2"
                                                style="font-size: 0.75rem;"></i>Kognitif</span>
                                        <span class="fw-bold">{{ $catKognitif }}
                                            <span
                                                class="text-muted fw-normal">({{ $totalSoal > 0 ? round(($catKognitif / $totalSoal) * 100) : 0 }}%)</span>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                                        <span><i class="fas fa-circle text-success me-2"
                                                style="font-size: 0.75rem;"></i>Kepribadian</span>
                                        <span class="fw-bold">{{ $catKepribadian }}
                                            <span
                                                class="text-muted fw-normal">({{ $totalSoal > 0 ? round(($catKepribadian / $totalSoal) * 100) : 0 }}%)</span>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center py-3">
                                        <span><i class="fas fa-circle text-info me-2"
                                                style="font-size: 0.75rem;"></i>Minat</span>
                                        <span class="fw-bold">{{ $catMinat }}
                                            <span
                                                class="text-muted fw-normal">({{ $totalSoal > 0 ? round(($catMinat / $totalSoal) * 100) : 0 }}%)</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Row -->
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-venus-mars text-primary me-2"></i>Statistik Gender
                            Pelamar</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><i class="fas fa-mars text-primary me-2"></i>Laki-laki</span>
                                <span class="fw-bold">{{ number_format($totalPelamarLaki) }}
                                    <span
                                        class="text-muted fw-normal">({{ $totalPelamar > 0 ? round(($totalPelamarLaki / $totalPelamar) * 100) : 0 }}%)</span>
                                </span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-primary"
                                    style="width: {{ $totalPelamar > 0 ? ($totalPelamarLaki / $totalPelamar) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><i class="fas fa-venus text-danger me-2"></i>Perempuan</span>
                                <span class="fw-bold">{{ number_format($totalPelamarPerempuan) }}
                                    <span
                                        class="text-muted fw-normal">({{ $totalPelamar > 0 ? round(($totalPelamarPerempuan / $totalPelamar) * 100) : 0 }}%)</span>
                                </span>
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-danger"
                                    style="width: {{ $totalPelamar > 0 ? ($totalPelamarPerempuan / $totalPelamar) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between pt-3 border-top">
                            <span><i class="fas fa-user-plus text-success me-2"></i>Pendaftar Bulan Ini</span>
                            <span class="fw-bold fs-5">{{ number_format($totalPelamarBulanIni) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-clipboard-list text-primary me-2"></i>Kelengkapan Profil
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="position-relative d-inline-block">
                                <div class="profile-chart-wrapper">
                                    <canvas id="profileDonutChart"></canvas>
                                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                                        <h3 class="fw-bold mb-0 text-primary">{{ $persentaseProfil }}%</h3>
                                        <small class="text-muted">Terisi</small>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-3">{{ $totalProfilTerisi }} dari {{ $totalPelamar }}
                                pelamar</small>
                        </div>
                        <div class="row small text-center g-2">
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-light stat-item"><span
                                        class="fw-bold text-primary d-block">{{ $totalPendidikan }}</span><span
                                        class="text-muted">Pendidikan</span></div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-light stat-item"><span
                                        class="fw-bold text-success d-block">{{ $totalOrganisasi }}</span><span
                                        class="text-muted">Organisasi</span></div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-light stat-item"><span
                                        class="fw-bold text-info d-block">{{ $totalMagang }}</span><span
                                        class="text-muted">Magang</span></div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-light stat-item"><span
                                        class="fw-bold text-warning d-block">{{ $totalPengalaman }}</span><span
                                        class="text-muted">Pengalaman</span></div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-light stat-item"><span
                                        class="fw-bold text-danger d-block">{{ $totalBahasa }}</span><span
                                        class="text-muted">Bahasa</span></div>
                            </div>
                            <div class="col-4">
                                <div class="p-2 rounded-3 bg-light stat-item"><span
                                        class="fw-bold text-secondary d-block">{{ $totalKeahlian }}</span><span
                                        class="text-muted">Keahlian</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-tasks text-primary me-2"></i>Progress Seleksi</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fas fa-hourglass-half text-warning me-2"></i>Menunggu</span>
                                <span class="fw-bold">{{ number_format($tahapanMenunggu) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span><i class="fas fa-spinner fa-spin text-info me-2"></i>Diproses</span>
                                <span class="fw-bold">{{ number_format($tahapanProses) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span><i class="fas fa-check-circle text-success me-2"></i>Lolos</span>
                                <span class="fw-bold">{{ number_format($tahapanSelesai) }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                @php $totalTahapan = $tahapanMenunggu + $tahapanProses + $tahapanSelesai; @endphp
                                <div class="progress-bar bg-warning"
                                    style="width: {{ $totalTahapan > 0 ? ($tahapanMenunggu / $totalTahapan) * 100 : 0 }}%">
                                </div>
                                <div class="progress-bar bg-info"
                                    style="width: {{ $totalTahapan > 0 ? ($tahapanProses / $totalTahapan) * 100 : 0 }}%">
                                </div>
                                <div class="progress-bar bg-success"
                                    style="width: {{ $totalTahapan > 0 ? ($tahapanSelesai / $totalTahapan) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                        <div class="pt-3 border-top">
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-brain text-primary me-2"></i>Sesi Psikotes</span>
                                <div class="text-end">
                                    <span class="fw-bold">{{ number_format($totalSesiPsikotes) }}</span>
                                    <small class="text-muted"> sesi</small>
                                    <div><small class="text-success">{{ $totalSesiSelesai }} selesai</small></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Applicants Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div
                        class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h5 class="mb-1 fw-bold"><i class="fas fa-clock text-primary me-2"></i>Pelamar Terbaru</h5>
                            <p class="text-muted small mb-0">5 pendaftar terakhir yang bergabung</p>
                        </div>
                        <a href="{{ route('admin.pelamar.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Nama Pelamar</th>
                                    <th class="d-none d-md-table-cell">Email</th>
                                    <th class="d-none d-lg-table-cell">No. Handphone</th>
                                    <th class="d-none d-xl-table-cell">Pendidikan</th>
                                    <th>Tanggal Daftar</th>
                                    <th class="text-center pe-4">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(Pelamar::orderBy('created_at', 'desc')->take(5)->get() as $key => $item)
                                    <tr>
                                        <td class="ps-4">{{ $key + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($item->foto_profil)
                                                    <img src="{{ Storage::url($item->foto_profil) }}"
                                                        class="rounded-circle me-2" width="36" height="36"
                                                        style="object-fit: cover;">
                                                @else
                                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-2"
                                                        style="width:36px;height:36px;">
                                                        <i class="fas fa-user text-primary"></i>
                                                    </div>
                                                @endif
                                                <span class="fw-medium">{{ Str::limit($item->nama_lengkap, 25) }}</span>
                                            </div>
                                        </td>
                                        <td class="d-none d-md-table-cell">{{ Str::limit($item->email, 30) }}</td>
                                        <td class="d-none d-lg-table-cell">{{ $item->nomor_handphone ?? '-' }}</td>
                                        <td class="d-none d-xl-table-cell"><span
                                                class="badge bg-secondary rounded-pill">{{ $item->pendidikan_terakhir ?? '-' }}</span>
                                        </td>
                                        <td class="text-nowrap">{{ $item->created_at->translatedFormat('d M Y') }}</td>
                                        <td class="text-center pe-4">
                                            <a href="{{ route('admin.pelamar.show', $item->id) }}"
                                                class="btn btn-sm btn-outline-info rounded-circle" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3 d-block text-muted"></i>
                                            Belum ada data pelamar
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

    <style>
        /* Welcome Card */
        .welcome-card {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        }

        /* Stat Card */
        .stat-card {
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
        }

        .stat-number {
            font-size: 2rem;
            line-height: 1.2;
        }

        /* Chart Wrapper */
        .chart-wrapper {
            position: relative;
            width: 100%;
            min-height: 300px;
        }

        .chart-wrapper canvas {
            max-height: 300px;
            width: 100% !important;
            height: auto !important;
        }

        .chart-wrapper-small {
            position: relative;
            width: 100%;
            min-height: 220px;
        }

        .chart-wrapper-small canvas {
            max-height: 220px;
            width: 100% !important;
            height: auto !important;
        }

        .profile-chart-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto;
        }

        .profile-chart-wrapper canvas {
            width: 100% !important;
            height: 100% !important;
        }

        /* Status Badge */
        .status-badge {
            transition: all 0.2s ease;
        }

        .status-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Score Label */
        .score-label {
            font-size: 0.75rem;
        }

        /* Stat Item */
        .stat-item {
            transition: all 0.2s ease;
        }

        .stat-item:hover {
            transform: translateY(-2px);
            background-color: #f1f5f9 !important;
        }

        /* Table Styles */
        .table-hover tbody tr:hover {
            background-color: rgba(37, 99, 235, 0.05);
            transition: background-color 0.2s ease;
        }

        /* Responsive Breakpoints */
        @media (max-width: 768px) {
            .chart-wrapper {
                min-height: 240px;
            }

            .chart-wrapper canvas {
                max-height: 240px;
            }

            .chart-wrapper-small {
                min-height: 200px;
            }

            .chart-wrapper-small canvas {
                max-height: 200px;
            }

            .profile-chart-wrapper {
                width: 120px;
                height: 120px;
            }

            .stat-number {
                font-size: 1.5rem;
            }

            .score-label {
                font-size: 0.65rem;
            }
        }

        @media (min-width: 768px) and (max-width: 1200px) {
            .chart-wrapper {
                min-height: 280px;
            }

            .chart-wrapper canvas {
                max-height: 280px;
            }
        }

        @media (min-width: 1200px) {
            .chart-wrapper {
                min-height: 340px;
            }

            .chart-wrapper canvas {
                max-height: 340px;
            }

            .score-label {
                font-size: 0.85rem;
            }
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        <script>
            let trendChart;
            let charts = {};

            function initializeCharts() {
                // Trend Chart
                const trendCtx = document.getElementById('trendChart').getContext('2d');
                trendChart = new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: @json($months),
                        datasets: [{
                            label: 'Jumlah Pelamar',
                            data: @json($trendData),
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            borderColor: '#2563eb',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 5,
                            pointHoverRadius: 7
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleFont: {
                                    size: 13,
                                    weight: '600'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                padding: 12,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#e2e8f0'
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah Pelamar',
                                    font: {
                                        size: 12,
                                        weight: '600'
                                    }
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });

                // Status Chart
                charts.status = new Chart(document.getElementById('statusChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'Diterima', 'Ditolak'],
                        datasets: [{
                            data: [{{ $statusPending }}, {{ $statusDiterima }}, {{ $statusDitolak }}],
                            backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                            borderWidth: 0,
                            cutout: '70%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    },
                                    padding: 15
                                }
                            }
                        }
                    }
                });

                // Score Chart
                charts.score = new Chart(document.getElementById('scoreChart'), {
                    type: 'bar',
                    data: {
                        labels: ['0-50', '51-70', '71-85', '86-100'],
                        datasets: [{
                            label: 'Jumlah Peserta',
                            data: [{{ $range1 }}, {{ $range2 }}, {{ $range3 }},
                                {{ $range4 }}
                            ],
                            backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'],
                            borderRadius: 8,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    precision: 0,
                                    font: {
                                        size: 11
                                    }
                                },
                                grid: {
                                    color: '#e2e8f0'
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });

                // Category Chart
                charts.category = new Chart(document.getElementById('categoryChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Kognitif', 'Kepribadian', 'Minat'],
                        datasets: [{
                            data: [{{ $catKognitif }}, {{ $catKepribadian }}, {{ $catMinat }}],
                            backgroundColor: ['#2563eb', '#10b981', '#06b6d4'],
                            borderWidth: 0,
                            cutout: '65%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 11,
                                        weight: '500'
                                    },
                                    padding: 15
                                }
                            }
                        }
                    }
                });

                // Profile Donut Chart
                const profileCanvas = document.getElementById('profileDonutChart');
                if (profileCanvas) {
                    const profileCtx = profileCanvas.getContext('2d');
                    profileCanvas.width = 140;
                    profileCanvas.height = 140;
                    charts.profile = new Chart(profileCtx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: [{{ $persentaseProfil }}, 100 - {{ $persentaseProfil }}],
                                backgroundColor: ['#2563eb', '#e2e8f0'],
                                borderWidth: 0,
                                cutout: '80%'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                tooltip: {
                                    enabled: false
                                },
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
            }

            function changeChartType(type) {
                if (trendChart) trendChart.destroy();
                const ctx = document.getElementById('trendChart').getContext('2d');
                const isBar = type === 'bar';

                // Update button states
                document.querySelectorAll('.btn-group .btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                event.target.closest('.btn').classList.add('active');

                trendChart = new Chart(ctx, {
                    type: type,
                    data: {
                        labels: @json($months),
                        datasets: [{
                            label: 'Jumlah Pelamar',
                            data: @json($trendData),
                            backgroundColor: isBar ? 'rgba(37, 99, 235, 0.8)' : 'rgba(37, 99, 235, 0.1)',
                            borderColor: '#2563eb',
                            borderWidth: isBar ? 0 : 3,
                            tension: 0.4,
                            fill: !isBar,
                            pointBackgroundColor: '#2563eb',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: isBar ? 0 : 5,
                            pointHoverRadius: isBar ? 0 : 7,
                            barPercentage: 0.7,
                            categoryPercentage: 0.8,
                            borderRadius: isBar ? 8 : 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    boxWidth: 12,
                                    font: {
                                        size: 12,
                                        weight: '500'
                                    }
                                }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleFont: {
                                    size: 13,
                                    weight: '600'
                                },
                                bodyFont: {
                                    size: 12
                                },
                                padding: 12,
                                cornerRadius: 8
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: '#e2e8f0'
                                },
                                title: {
                                    display: true,
                                    text: 'Jumlah Pelamar',
                                    font: {
                                        size: 12,
                                        weight: '600'
                                    }
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                }
                            }
                        }
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                initializeCharts();

                // Resize handler untuk semua chart
                let resizeTimeout;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(function() {
                        if (trendChart) trendChart.resize();
                        Object.values(charts).forEach(chart => {
                            if (chart && typeof chart.resize === 'function') {
                                chart.resize();
                            }
                        });
                    }, 250);
                });
            });
        </script>
    @endpush
@endsection
