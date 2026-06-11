@extends('layouts.pelamar')

@section('title', 'Detail Medical Check Up')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-heartbeat text-primary me-2"></i> Detail Medical Check Up
                        </h5>
                        <div>
                            <a href="{{ route('pelamar.mcu.download-surat', $mcu->id) }}" class="btn btn-sm btn-success me-2">
                                <i class="fas fa-download me-1"></i> Download Surat MCU
                            </a>
                            <a href="{{ route('pelamar.mcu.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="mb-4">
                            <div class="border rounded p-3 bg-light">
                                <small class="text-muted">Lowongan</small>
                                <h6 class="mb-0">{{ $mcu->lowongan->judul_lowongan ?? '-' }}</h6>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <small class="text-muted">Jadwal MCU</small>
                                    <h6 class="mb-0 text-primary">
                                        {{ Carbon\Carbon::parse($mcu->jadwal_mcu)->format('l, d F Y H:i') }}</h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <small class="text-muted">Status</small>
                                    <div><span
                                            class="badge bg-{{ $mcu->status_badge }} px-3 py-2">{{ $mcu->status_text }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="border rounded p-3">
                                <small class="text-muted">Lokasi MCU</small>
                                <h6 class="mb-1">{{ $mcu->lokasi_mcu }}</h6>
                                <small class="text-muted">{{ $mcu->alamat_lokasi }}</small>
                            </div>
                        </div>

                        @if ($mcu->dokter_pemeriksa || $mcu->nama_rs)
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <small class="text-muted">Dokter Pemeriksa</small>
                                        <h6 class="mb-0">{{ $mcu->dokter_pemeriksa ?? '-' }}</h6>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3">
                                        <small class="text-muted">Rumah Sakit</small>
                                        <h6 class="mb-0">{{ $mcu->nama_rs ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($mcu->keterangan)
                            <div class="alert alert-info mb-4"><i class="fas fa-info-circle me-2"></i>
                                {{ $mcu->keterangan }}</div>
                        @endif

                        @if ($mcu->status == 'dijadwalkan')
                            <div class="alert alert-warning">
                                <i class="fas fa-clock me-2"></i> <strong>Konfirmasi Kehadiran</strong>
                                <p class="mb-2 mt-2">Silakan konfirmasi kehadiran Anda untuk jadwal MCU ini.</p>
                                <form action="{{ route('pelamar.mcu.konfirmasi', $mcu->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm"><i
                                            class="fas fa-check-circle me-1"></i> Konfirmasi Kehadiran</button>
                                </form>
                            </div>
                        @endif

                        @if ($mcu->hasil_mcu_file)
                            <div class="mb-4">
                                <div class="border rounded p-3">
                                    <small class="text-muted">Hasil MCU</small>
                                    <div class="mt-2">
                                        <a href="{{ route('file.view', ['model' => 'mcu', 'id' => $mcu->id, 'field' => 'hasil_mcu_file']) }}"
                                            target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-pdf me-1"></i> Lihat Hasil MCU
                                        </a>
                                    </div>
                                    @if ($mcu->hasil_mcu_keterangan)
                                        <small class="text-muted d-block mt-2">Keterangan:
                                            {{ $mcu->hasil_mcu_keterangan }}</small>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="mb-4">
                            <div class="border rounded p-3">
                                <small class="text-muted">Upload Dokumen Pendukung</small>
                                <form action="{{ route('pelamar.mcu.upload-dokumen', $mcu->id) }}" method="POST"
                                    enctype="multipart/form-data" class="mt-2">
                                    @csrf
                                    <div class="row g-2">
                                        <div class="col-md-8"><input type="file" name="dokumen_pendukung_file"
                                                class="form-control" accept=".pdf,.jpg,.jpeg,.png" required></div>
                                        <div class="col-md-4"><button type="submit" class="btn btn-primary w-100"><i
                                                    class="fas fa-upload me-1"></i> Upload</button></div>
                                    </div>
                                </form>
                                <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Max 5MB)</small>
                                @if ($mcu->dokumen_pendukung_file)
                                    <div class="mt-2">
                                        <a href="{{ route('file.view', ['model' => 'mcu', 'id' => $mcu->id, 'field' => 'dokumen_pendukung_file']) }}"
                                            target="_blank" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-file me-1"></i> Lihat Dokumen Terupload
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($mcu->surat_mcu_file)
                            <div class="mb-4">
                                <div class="border rounded p-3">
                                    <small class="text-muted">Surat MCU</small>
                                    <div class="mt-2">
                                        <a href="{{ route('pelamar.mcu.download-surat', $mcu->id) }}"
                                            class="btn btn-sm btn-success">
                                            <i class="fas fa-download me-1"></i> Download Surat MCU
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($mcu->catatan_admin)
                            <div class="alert alert-warning">
                                <i class="fas fa-sticky-note me-2"></i> <strong>Catatan
                                    Admin:</strong><br>{{ $mcu->catatan_admin }}
                            </div>
                        @endif

                        @if ($mcu->status == 'lolos')
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i> <strong>Selamat!</strong> Anda dinyatakan LOLOS
                                Medical Check Up. Silakan lanjut ke tahap selanjutnya.
                            </div>
                        @endif

                        @if ($mcu->status == 'gagal')
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle me-2"></i> <strong>Maaf,</strong> Anda dinyatakan TIDAK LOLOS
                                Medical Check Up.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
