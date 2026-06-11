@extends('layouts.pelamar')

@section('title', 'Upload Berkas Lamaran')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-cloud-upload-alt me-2"></i> Upload Berkas Lamaran</h5>
                            <small class="opacity-75">Pilih lowongan dan upload dokumen persyaratan</small>
                        </div>
                        <a href="{{ route('pelamar.berkas.index') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if ($availableLowongans->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h4 class="text-muted">Tidak Ada Lowongan yang Memerlukan Upload Berkas</h4>
                                <p class="text-muted">Anda hanya dapat mengupload berkas untuk lowongan yang sedang dalam
                                    tahap Screening Berkas.</p>
                                <a href="{{ route('pelamar.lowongan.index') }}" class="btn btn-primary"><i
                                        class="fas fa-briefcase me-1"></i> Lihat Lowongan Tersedia</a>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Petunjuk Pengisian:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Pilih lowongan yang ingin Anda upload berkasnya</li>
                                    <li>Setiap lowongan hanya dapat diupload sekali</li>
                                    <li>Pastikan semua dokumen dalam format PDF, JPG, JPEG, atau PNG</li>
                                    <li>Ukuran maksimal setiap file adalah 5MB</li>
                                    <li>Setelah upload, admin akan memverifikasi berkas Anda</li>
                                </ul>
                            </div>

                            <form action="{{ route('pelamar.berkas.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Pilih Lowongan <span
                                            class="text-danger">*</span></label>
                                    <select name="pelamar_tahapan_id"
                                        class="form-select @error('pelamar_tahapan_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Lowongan --</option>
                                        @foreach ($availableLowongans as $tahapan)
                                            <option value="{{ $tahapan->id }}"
                                                {{ $lowonganId == $tahapan->lowongan_id ? 'selected' : '' }}>
                                                {{ $tahapan->lowongan->judul_lowongan }} -
                                                {{ $tahapan->tahapan->nama_tahapan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pelamar_tahapan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="accordion mb-4" id="berkasAccordion">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#dokumenUtama">
                                                <i class="fas fa-file-alt text-primary me-2"></i> Dokumen Utama
                                            </button>
                                        </h2>
                                        <div id="dokumenUtama" class="accordion-collapse collapse show"
                                            data-bs-parent="#berkasAccordion">
                                            <div class="accordion-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Curriculum Vitae (CV)</label>
                                                        <input type="file" name="cv_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: PDF/JPG/JPEG/PNG (Max 5MB)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Surat Lamaran Kerja</label>
                                                        <input type="file" name="surat_lamaran_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: PDF/JPG/JPEG/PNG (Max 5MB)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Ijazah Terakhir</label>
                                                        <input type="file" name="ijazah_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: PDF/JPG/JPEG/PNG (Max 5MB)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Transkrip Nilai</label>
                                                        <input type="file" name="transkrip_nilai_file"
                                                            class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: PDF/JPG/JPEG/PNG (Max 5MB)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#dokumenIdentitas">
                                                <i class="fas fa-id-card text-success me-2"></i> Dokumen Identitas
                                            </button>
                                        </h2>
                                        <div id="dokumenIdentitas" class="accordion-collapse collapse"
                                            data-bs-parent="#berkasAccordion">
                                            <div class="accordion-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">KTP</label>
                                                        <input type="file" name="ktp_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: PDF/JPG/JPEG/PNG (Max 5MB)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Kartu Keluarga</label>
                                                        <input type="file" name="kartu_keluarga_file"
                                                            class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: PDF/JPG/JPEG/PNG (Max
                                                            5MB)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Pas Foto 3x4</label>
                                                        <input type="file" name="pas_foto_file" class="form-control"
                                                            accept=".jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: JPG/JPEG/PNG (Max 5MB)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#dokumenPendukung">
                                                <i class="fas fa-certificate text-warning me-2"></i> Dokumen Pendukung
                                            </button>
                                        </h2>
                                        <div id="dokumenPendukung" class="accordion-collapse collapse"
                                            data-bs-parent="#berkasAccordion">
                                            <div class="accordion-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Sertifikat Pendukung</label>
                                                        <input type="file" name="sertifikat_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: PDF/JPG/JPEG/PNG (Max
                                                            5MB)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">SKCK</label>
                                                        <input type="file" name="skck_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: PDF/JPG/JPEG/PNG (Max
                                                            5MB)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Surat Keterangan Sehat</label>
                                                        <input type="file" name="surat_keterangan_sehat_file"
                                                            class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: PDF/JPG/JPEG/PNG (Max
                                                            5MB)</small>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Portofolio</label>
                                                        <input type="file" name="portofolio_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: PDF/JPG/JPEG/PNG (Max
                                                            5MB)</small>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label fw-bold">Dokumen Lainnya</label>
                                                        <input type="file" name="dokumen_lain_file"
                                                            class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Format: PDF/JPG/JPEG/PNG (Max
                                                            5MB)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('pelamar.berkas.index') }}" class="btn btn-outline-secondary"><i
                                            class="fas fa-times me-1"></i> Batal</a>
                                    <button type="submit" class="btn btn-primary"><i
                                            class="fas fa-cloud-upload-alt me-1"></i> Upload Berkas</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .accordion-button:not(.collapsed) {
            background-color: rgba(13, 110, 253, 0.05);
            color: var(--bs-primary);
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(13, 110, 253, 0.25);
        }
    </style>
@endsection
