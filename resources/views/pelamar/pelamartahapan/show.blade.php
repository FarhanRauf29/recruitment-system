@extends('layouts.pelamar')

@section('title', 'Detail Tahapan')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('pelamar.dashboard') }}"
                                class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pelamar.pelamartahapan.index') }}"
                                class="text-decoration-none">Tahapan Saya</a></li>
                        <li class="breadcrumb-item active">{{ $tahapan->tahapan->nama_tahapan ?? 'Detail' }}</li>
                    </ol>
                </nav>

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div
                        class="card-header bg-gradient-primary text-white border-0 py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="mb-0 fw-bold">{{ $tahapan->tahapan->nama_tahapan ?? 'Tahapan' }}</h4>
                            <p class="mb-0 opacity-75 small">{{ $tahapan->lowongan->judul_lowongan ?? 'Lowongan' }}</p>
                        </div>
                        <a href="{{ route('pelamar.pelamartahapan.index') }}" class="btn btn-light rounded-pill px-4">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>

                    <div class="card-body p-4">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3 border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-lg me-2 text-success"></i>
                                    <span>{{ session('success') }}</span>
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle fa-lg me-2 text-danger"></i>
                                    <span>{{ session('error') }}</span>
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                                </div>
                            </div>
                        @endif

                        <div class="row g-4">
                            <div class="col-lg-4">
                                <div class="card border-0 shadow-sm rounded-3">
                                    <div class="card-body text-center p-4">
                                        @php
                                            $statusConfig = [
                                                'menunggu' => [
                                                    'color' => 'warning',
                                                    'icon' => 'clock',
                                                    'text' => 'Menunggu',
                                                ],
                                                'diproses' => [
                                                    'color' => 'primary',
                                                    'icon' => 'spinner fa-pulse',
                                                    'text' => 'Diproses',
                                                ],
                                                'lolos' => [
                                                    'color' => 'success',
                                                    'icon' => 'check-circle',
                                                    'text' => 'Lolos',
                                                ],
                                                'gagal' => [
                                                    'color' => 'danger',
                                                    'icon' => 'times-circle',
                                                    'text' => 'Gagal',
                                                ],
                                            ];
                                            $config = $statusConfig[$tahapan->status] ?? $statusConfig['menunggu'];
                                        @endphp
                                        <div class="rounded-circle d-inline-flex p-4 mb-3"
                                            style="background: rgba(var(--bs-{{ $config['color'] }}-rgb), 0.1);">
                                            <i class="fas fa-{{ $config['icon'] }} fa-3x text-{{ $config['color'] }}"></i>
                                        </div>
                                        <h4 class="mb-2 fw-bold text-{{ $config['color'] }}">{{ $config['text'] }}</h4>
                                        @if ($tahapan->tanggal_mulai)
                                            <p class="mb-1 small"><i class="fas fa-calendar text-muted me-2"></i> Mulai:
                                                {{ \Carbon\Carbon::parse($tahapan->tanggal_mulai)->format('d M Y H:i') }}
                                            </p>
                                        @endif
                                        @if ($tahapan->tanggal_selesai)
                                            <p class="mb-0 small"><i class="fas fa-flag-checkered text-muted me-2"></i>
                                                Selesai:
                                                {{ \Carbon\Carbon::parse($tahapan->tanggal_selesai)->format('d M Y H:i') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm rounded-3 mt-4">
                                    <div class="card-header bg-transparent border-0 pt-3 px-4">
                                        <h6 class="fw-bold mb-0"><i class="fas fa-chart-line text-primary me-2"></i>
                                            Progress Tahapan</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="list-group list-group-flush">
                                            @foreach ($allTahapan as $item)
                                                @php
                                                    $itemConfig =
                                                        $statusConfig[$item->status] ?? $statusConfig['menunggu'];
                                                    $isActive = $item->id == $tahapan->id;
                                                @endphp
                                                <div
                                                    class="list-group-item border-0 py-3 {{ $isActive ? 'bg-primary bg-opacity-5' : '' }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center gap-3">
                                                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                                                style="width: 40px; height: 40px; background: rgba(var(--bs-{{ $itemConfig['color'] }}-rgb), 0.1);">
                                                                <i
                                                                    class="fas fa-{{ $itemConfig['icon'] }} text-{{ $itemConfig['color'] }}"></i>
                                                            </div>
                                                            <div>
                                                                <div class="fw-bold {{ $isActive ? 'text-primary' : '' }}">
                                                                    {{ $item->tahapan->nama_tahapan ?? 'Tahapan' }}</div>
                                                                <small
                                                                    class="text-muted">{{ ucfirst($item->status) }}</small>
                                                            </div>
                                                        </div>
                                                        @if ($item->status == 'diproses' && $item->id != $tahapan->id)
                                                            <a href="{{ route('pelamar.pelamartahapan.show', $item->id) }}"
                                                                class="btn btn-sm btn-outline-primary rounded-pill"><i
                                                                    class="fas fa-eye me-1"></i> Detail</a>
                                                        @elseif($item->id == $tahapan->id)
                                                            <span class="badge bg-primary rounded-pill">Aktif</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8">
                                @if ($tahapan->tahapan->nama_tahapan == 'Screening Berkas')
                                    <div class="card border-0 shadow-sm rounded-3">
                                        <div class="card-header bg-transparent border-0 pt-3 px-4">
                                            <h6 class="fw-bold mb-0"><i
                                                    class="fas fa-cloud-upload-alt text-primary me-2"></i> Upload Berkas
                                                Lamaran</h6>
                                        </div>
                                        <div class="card-body text-center p-4">
                                            <div class="rounded-circle d-inline-flex p-4 mb-3"
                                                style="background: rgba(13,110,253,0.1);">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                                            </div>
                                            <h5 class="fw-bold mb-2">Upload Berkas Lamaran</h5>
                                            <p class="text-muted mb-4">Lengkapi dokumen persyaratan seperti CV, Ijazah,
                                                Transkrip Nilai, dan dokumen pendukung lainnya.</p>
                                            <a href="{{ route('pelamar.berkas.create') }}"
                                                class="btn btn-primary rounded-pill px-5 py-2">
                                                <i class="fas fa-cloud-upload-alt me-2"></i>Upload Berkas
                                            </a>
                                        </div>
                                    </div>
                                @elseif($tahapan->tahapan->nama_tahapan == 'Psikotes')
                                    <div class="card border-0 shadow-sm rounded-3">
                                        <div class="card-header bg-transparent border-0 pt-3 px-4">
                                            <h6 class="fw-bold mb-0"><i class="fas fa-brain text-primary me-2"></i> Psikotes
                                                Online</h6>
                                        </div>
                                        <div class="card-body text-center p-4">
                                            <div class="rounded-circle d-inline-flex p-4 mb-3"
                                                style="background: rgba(13,110,253,0.1);">
                                                <i class="fas fa-brain fa-3x text-primary"></i>
                                            </div>
                                            <h5 class="fw-bold mb-2">Tes Psikotes Online</h5>
                                            <p class="text-muted mb-4">Tes psikotes akan dilaksanakan sesuai jadwal yang
                                                telah ditentukan.</p>
                                            <div class="alert alert-warning rounded-3 d-inline-flex mb-3">
                                                <i class="fas fa-clock me-2"></i> Pastikan Anda siap sebelum memulai tes
                                            </div>
                                            <a href="{{ route('pelamar.psikotes.token') }}"
                                                class="btn btn-primary rounded-pill px-5 py-2">
                                                <i class="fas fa-play me-2"></i>Mulai Psikotes
                                            </a>
                                        </div>
                                    </div>

                                    @if (isset($hasilPsikotes) && $hasilPsikotes->count() > 0)
                                        <div class="card border-0 shadow-sm rounded-3 mt-4">
                                            <div class="card-header bg-transparent border-0 pt-3 px-4">
                                                <h6 class="fw-bold mb-0"><i class="fas fa-chart-line text-primary me-2"></i>
                                                    Hasil Psikotes</h6>
                                            </div>
                                            <div class="card-body p-4">
                                                @foreach ($hasilPsikotes as $hasil)
                                                    <div class="border rounded-3 p-3 mb-3">
                                                        <div
                                                            class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                            <div>
                                                                <span
                                                                    class="badge bg-{{ $hasil->lulus ? 'success' : 'danger' }} rounded-pill">{{ $hasil->lulus ? 'Lulus' : 'Tidak Lulus' }}</span>
                                                                <h6 class="mt-2 mb-1">
                                                                    {{ \Carbon\Carbon::parse($hasil->created_at)->format('d F Y') }}
                                                                </h6>
                                                                <p class="small text-muted mb-0">Nilai:
                                                                    {{ $hasil->nilai_total }}</p>
                                                            </div>
                                                            <a href="{{ route('pelamar.psikotes.hasil', $tahapan->id) }}"
                                                                class="btn btn-outline-primary rounded-pill px-4">
                                                                <i class="fas fa-eye me-1"></i> Lihat Detail Hasil
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @elseif($tahapan->tahapan->nama_tahapan == 'Medical Check Up')
                                    <div class="card border-0 shadow-sm rounded-3">
                                        <div class="card-header bg-transparent border-0 pt-3 px-4">
                                            <h6 class="fw-bold mb-0"><i class="fas fa-heartbeat text-primary me-2"></i>
                                                Medical Check Up</h6>
                                        </div>
                                        <div class="card-body text-center p-4">
                                            <div class="rounded-circle d-inline-flex p-4 mb-3"
                                                style="background: rgba(13,110,253,0.1);">
                                                <i class="fas fa-heartbeat fa-3x text-primary"></i>
                                            </div>
                                            <h5 class="fw-bold mb-2">Medical Check Up</h5>
                                            <p class="text-muted mb-4">Informasi jadwal Medical Check Up akan
                                                diinformasikan lebih lanjut.</p>
                                            <div class="alert alert-info rounded-3 d-inline-flex">
                                                <i class="fas fa-envelope me-2"></i> Silakan cek email Anda secara berkala.
                                            </div>
                                        </div>
                                    </div>

                                    @if (isset($mcu) && $mcu->count() > 0)
                                        <div class="card border-0 shadow-sm rounded-3 mt-4">
                                            <div class="card-header bg-transparent border-0 pt-3 px-4">
                                                <h6 class="fw-bold mb-0"><i class="fas fa-clock text-primary me-2"></i>
                                                    Informasi MCU</h6>
                                            </div>
                                            <div class="card-body p-4">
                                                @foreach ($mcu as $mcuItem)
                                                    <div class="border rounded-3 p-3 mb-3">
                                                        <div
                                                            class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                            <div>
                                                                <span
                                                                    class="badge bg-{{ $mcuItem->status_badge }} rounded-pill">{{ $mcuItem->status_text }}</span>
                                                                <h6 class="mt-2 mb-1">
                                                                    {{ \Carbon\Carbon::parse($mcuItem->jadwal_mcu)->format('d F Y') }}
                                                                </h6>
                                                                <p class="small text-muted mb-0">
                                                                    {{ \Carbon\Carbon::parse($mcuItem->jadwal_mcu)->format('H:i') }}
                                                                    WIB</p>
                                                                <p class="small text-muted">{{ $mcuItem->lokasi_mcu }}</p>
                                                            </div>
                                                            <a href="{{ route('pelamar.mcu.show', $mcuItem->id) }}"
                                                                class="btn btn-outline-primary rounded-pill px-4">
                                                                <i class="fas fa-eye me-1"></i> Lihat Detail MCU
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @elseif($tahapan->tahapan->nama_tahapan == 'Wawancara')
                                    <div class="card border-0 shadow-sm rounded-3">
                                        <div class="card-header bg-transparent border-0 pt-3 px-4">
                                            <h6 class="fw-bold mb-0"><i class="fas fa-users text-primary me-2"></i> Jadwal
                                                Wawancara</h6>
                                        </div>
                                        <div class="card-body text-center p-4">
                                            <div class="rounded-circle d-inline-flex p-4 mb-3"
                                                style="background: rgba(13,110,253,0.1);">
                                                <i class="fas fa-users fa-3x text-primary"></i>
                                            </div>
                                            <h5 class="fw-bold mb-2">Jadwal Wawancara</h5>
                                            <p class="text-muted mb-4">Tim HRD akan menghubungi Anda untuk menjadwalkan
                                                wawancara.</p>
                                            <div class="alert alert-warning rounded-3 d-inline-flex">
                                                <i class="fas fa-phone me-2"></i> Pastikan nomor telepon Anda aktif.
                                            </div>
                                        </div>
                                    </div>

                                    @if (isset($wawancara) && $wawancara->count() > 0)
                                        <div class="card border-0 shadow-sm rounded-3 mt-4">
                                            <div class="card-header bg-transparent border-0 pt-3 px-4">
                                                <h6 class="fw-bold mb-0"><i class="fas fa-clock text-primary me-2"></i>
                                                    Jadwal Wawancara</h6>
                                            </div>
                                            <div class="card-body p-4">
                                                @foreach ($wawancara as $wawancaraItem)
                                                    <div class="border rounded-3 p-3 mb-3">
                                                        <div
                                                            class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                            <div>
                                                                <span
                                                                    class="badge bg-{{ $wawancaraItem->status_badge }} rounded-pill">{{ $wawancaraItem->status_text }}</span>
                                                                <h6 class="mt-2 mb-1">
                                                                    {{ \Carbon\Carbon::parse($wawancaraItem->jadwal_wawancara)->format('d F Y') }}
                                                                </h6>
                                                                <p class="small text-muted mb-0">
                                                                    {{ \Carbon\Carbon::parse($wawancaraItem->jadwal_wawancara)->format('H:i') }}
                                                                    WIB</p>
                                                                <p class="small text-muted mt-1">
                                                                    {{ $wawancaraItem->jenis_wawancara == 'online' ? 'Online (Video Call)' : 'Offline (Tatap Muka)' }}
                                                                </p>
                                                            </div>
                                                            <a href="{{ route('pelamar.wawancara.show', $wawancaraItem->id) }}"
                                                                class="btn btn-outline-primary rounded-pill px-4">
                                                                <i class="fas fa-eye me-1"></i> Lihat Detail Wawancara
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if ($tahapan->berkas->count() > 0)
                                    <div class="card border-0 shadow-sm rounded-3 mt-4">
                                        <div class="card-header bg-transparent border-0 pt-3 px-4">
                                            <h6 class="fw-bold mb-0"><i class="fas fa-folder-open text-primary me-2"></i>
                                                Berkas Terupload</h6>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="list-group list-group-flush">
                                                @foreach ($tahapan->berkas as $berkasItem)
                                                    <a href="{{ route('file.view', ['model' => 'berkas', 'id' => $berkasItem->id, 'field' => 'dokumen_lain_file']) }}"
                                                        target="_blank" class="list-group-item list-group-item-action">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <i class="fas fa-file me-2"></i>
                                                                {{ basename($berkasItem->dokumen_lain_file) }}
                                                            </div>
                                                            <small class="text-muted">
                                                                <i class="fas fa-calendar me-1"></i>
                                                                {{ \Carbon\Carbon::parse($berkasItem->created_at)->format('d/m/Y') }}
                                                            </small>
                                                        </div>
                                                        @if ($berkasItem->status_verifikasi)
                                                            <small
                                                                class="text-{{ $berkasItem->status_verifikasi == 'Diterima' ? 'success' : 'warning' }}">
                                                                Status: {{ $berkasItem->status_verifikasi }}
                                                            </small>
                                                        @endif
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if ($tahapan->catatan)
                                    <div class="card border-0 shadow-sm rounded-3 mt-4">
                                        <div class="card-header bg-transparent border-0 pt-3 px-4">
                                            <h6 class="fw-bold mb-0"><i class="fas fa-sticky-note text-primary me-2"></i>
                                                Catatan Admin</h6>
                                        </div>
                                        <div class="card-body p-4">
                                            <p class="mb-0">{{ $tahapan->catatan }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between gap-3">
                                    @if ($prevTahapan)
                                        <a href="{{ route('pelamar.pelamartahapan.show', $prevTahapan->id) }}"
                                            class="btn btn-outline-secondary rounded-pill px-4">
                                            <i class="fas fa-arrow-left me-2"></i>
                                            {{ $prevTahapan->tahapan->nama_tahapan ?? 'Previous' }}
                                        </a>
                                    @else
                                        <div></div>
                                    @endif
                                    @if ($nextTahapan)
                                        <a href="{{ route('pelamar.pelamartahapan.show', $nextTahapan->id) }}"
                                            class="btn btn-primary rounded-pill px-4">
                                            {{ $nextTahapan->tahapan->nama_tahapan ?? 'Next' }} <i
                                                class="fas fa-arrow-right ms-2"></i>
                                        </a>
                                    @endif
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

        .bg-opacity-5 {
            --bs-bg-opacity: 0.05;
        }

        .rounded-4 {
            border-radius: 1rem !important;
        }

        .rounded-3 {
            border-radius: 0.75rem !important;
        }
    </style>
@endsection
