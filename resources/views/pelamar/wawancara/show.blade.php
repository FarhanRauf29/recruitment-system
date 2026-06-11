@extends('layouts.pelamar')

@section('title', 'Detail Wawancara')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-chalkboard-user text-primary me-2"></i> Detail Wawancara
                        </h5>
                        <a href="{{ route('pelamar.wawancara.index') }}" class="btn btn-outline-secondary btn-sm"><i
                                class="fas fa-arrow-left me-1"></i> Kembali</a>
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
                                <h6 class="mb-0">{{ $wawancara->lowongan->judul_lowongan ?? '-' }}</h6>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <small class="text-muted">Jadwal Wawancara</small>
                                    <h6 class="mb-0 text-primary">
                                        {{ Carbon\Carbon::parse($wawancara->jadwal_wawancara)->format('l, d F Y H:i') }}
                                    </h6>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border rounded p-3">
                                    <small class="text-muted">Status</small>
                                    <div><span
                                            class="badge bg-{{ $wawancara->status_badge }} px-3 py-2">{{ $wawancara->status_text }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="border rounded p-3">
                                <small class="text-muted">Metode Wawancara</small>
                                <h6 class="mb-1">
                                    @if ($wawancara->jenis_wawancara == 'online')
                                        <i class="fas fa-video text-success me-1"></i> Online (Video Call)
                                        @if ($wawancara->link_wawancara)
                                            <div class="mt-2"><a href="{{ $wawancara->link_wawancara }}" target="_blank"
                                                    class="btn btn-sm btn-primary"><i class="fas fa-video me-1"></i> Join
                                                    Interview</a></div>
                                        @endif
                                    @else
                                        <i class="fas fa-building text-warning me-1"></i> Offline (Tatap Muka)
                                        <h6 class="mb-1 mt-2">{{ $wawancara->lokasi_wawancara }}</h6>
                                        <small class="text-muted">{{ $wawancara->alamat_lokasi }}</small>
                                    @endif
                                </h6>
                            </div>
                        </div>

                        @if ($wawancara->pewawancara)
                            <div class="mb-4">
                                <div class="border rounded p-3">
                                    <small class="text-muted">Pewawancara</small>
                                    <h6 class="mb-0">{{ $wawancara->pewawancara }}</h6>
                                </div>
                            </div>
                        @endif

                        @if ($wawancara->keterangan)
                            <div class="alert alert-info mb-4"><i class="fas fa-info-circle me-2"></i>
                                {{ $wawancara->keterangan }}</div>
                        @endif

                        {{-- @if ($wawancara->nilai_total)
                            <div class="mb-4">
                                <div class="border rounded p-3">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-star text-warning me-2"></i> Hasil Penilaian
                                    </h6>
                                    <div class="text-center">
                                        <span class="badge bg-primary rounded-pill px-4 py-2 fs-6">Nilai Total:
                                            {{ $wawancara->nilai_total }}</span>
                                    </div>
                                    @if ($wawancara->catatan_hr)
                                        <div class="mt-3 p-2 bg-info bg-opacity-10 rounded">
                                            <small class="text-muted">Catatan HR:</small>
                                            <p class="mb-0">{{ $wawancara->catatan_hr }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif --}}

                        @if ($wawancara->status == 'dijadwalkan')
                            <div class="alert alert-warning">
                                <i class="fas fa-clock me-2"></i> <strong>Konfirmasi Kehadiran</strong>
                                <p class="mb-2 mt-2">Silakan konfirmasi kehadiran Anda untuk jadwal wawancara ini.</p>
                                <form action="{{ route('pelamar.wawancara.konfirmasi', $wawancara->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm"><i
                                            class="fas fa-check-circle me-1"></i> Konfirmasi Kehadiran</button>
                                </form>
                            </div>
                        @endif

                        <div class="mb-4">
                            <div class="border rounded p-3">
                                <small class="text-muted">Upload Dokumen Pendukung</small>
                                <form action="{{ route('pelamar.wawancara.upload-dokumen', $wawancara->id) }}"
                                    method="POST" enctype="multipart/form-data" class="mt-2">
                                    @csrf
                                    <div class="row g-2">
                                        <div class="col-md-8"><input type="file" name="dokumen_pendukung_file"
                                                class="form-control" accept=".pdf,.jpg,.jpeg,.png" required></div>
                                        <div class="col-md-4"><button type="submit" class="btn btn-primary w-100"><i
                                                    class="fas fa-upload me-1"></i> Upload</button></div>
                                    </div>
                                </form>
                                <small class="text-muted">Format: PDF, JPG, JPEG, PNG (Max 5MB)</small>
                                @if ($wawancara->dokumen_pendukung_file)
                                    <div class="mt-2"><a
                                            href="{{ route('berkas.view', ['id' => $wawancara->id, 'field' => 'dokumen_pendukung_file']) }}"
                                            target="_blank" class="btn btn-sm btn-outline-secondary"><i
                                                class="fas fa-file me-1"></i> Lihat Dokumen Terupload</a></div>
                                @endif
                            </div>
                        </div>

                        @if ($wawancara->catatan_admin)
                            <div class="alert alert-warning"><i class="fas fa-sticky-note me-2"></i> <strong>Catatan
                                    Admin:</strong><br>{{ $wawancara->catatan_admin }}</div>
                        @endif

                        @if ($wawancara->status == 'lolos')
                            <div class="alert alert-success"><i class="fas fa-check-circle me-2"></i>
                                <strong>Selamat!</strong> Anda dinyatakan LOLOS wawancara. Silakan lanjut ke tahap
                                selanjutnya.
                            </div>
                        @endif

                        @if ($wawancara->status == 'gagal')
                            <div class="alert alert-danger"><i class="fas fa-times-circle me-2"></i> <strong>Maaf,</strong>
                                Anda dinyatakan TIDAK LOLOS wawancara.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
