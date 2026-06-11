@extends('layouts.pelamar')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Berkas Lamaran</h4>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show"><i
                                    class="fas fa-check-circle me-2"></i> {{ session('success') }}<button type="button"
                                    class="btn-close" data-bs-dismiss="alert"></button></div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2 fs-4"></i>
                                    <div>
                                        <h5 class="alert-heading mb-2">Terjadi kesalahan!</h5>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 fs-4"></i>
                            <div>
                                <strong>Petunjuk Pengeditan:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Anda hanya bisa mengunggah file baru untuk mengganti file yang sudah ada</li>
                                    <li>Format file yang diterima: PDF, JPG, JPEG, atau PNG</li>
                                    <li>Ukuran maksimal setiap file adalah 2MB</li>
                                    <li>Status verifikasi akan direset ke "Pending" setelah perubahan</li>
                                </ul>
                            </div>
                        </div>

                        <form action="{{ route('pelamar.berkas.update', $berkas->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf @method('PUT')

                            <div class="accordion mb-4" id="berkasAccordion">
                                <div class="accordion-item">
                                    <h2 class="accordion-header"><button class="accordion-button" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne"><i
                                                class="fas fa-file-alt text-primary me-2"></i> Dokumen Utama</button></h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        data-bs-parent="#berkasAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="form-label fw-bold">Curriculum
                                                            Vitae (CV)</label>
                                                        @if ($berkas->cv_file && Storage::disk('public')->exists($berkas->cv_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->cv_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->cv_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="cv_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png"><small class="text-muted">Unggah
                                                            file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="form-label fw-bold">Surat Lamaran
                                                            Kerja</label>
                                                        @if ($berkas->surat_lamaran_file && Storage::disk('public')->exists($berkas->surat_lamaran_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->surat_lamaran_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->surat_lamaran_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="surat_lamaran_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png"><small class="text-muted">Unggah
                                                            file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="form-label fw-bold">Ijazah
                                                            Terakhir</label>
                                                        @if ($berkas->ijazah_file && Storage::disk('public')->exists($berkas->ijazah_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->ijazah_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->ijazah_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="ijazah_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png"><small class="text-muted">Unggah
                                                            file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="form-label fw-bold">Transkrip
                                                            Nilai</label>
                                                        @if ($berkas->transkrip_nilai_file && Storage::disk('public')->exists($berkas->transkrip_nilai_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->transkrip_nilai_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->transkrip_nilai_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="transkrip_nilai_file"
                                                            class="form-control" accept=".pdf,.jpg,.jpeg,.png"><small
                                                            class="text-muted">Unggah file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header"><button class="accordion-button collapsed"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"><i
                                                class="fas fa-id-card text-success me-2"></i> Dokumen Identitas</button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        data-bs-parent="#berkasAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="form-label fw-bold">KTP</label>
                                                        @if ($berkas->ktp_file && Storage::disk('public')->exists($berkas->ktp_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->ktp_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->ktp_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="ktp_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Unggah file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="form-label fw-bold">Kartu
                                                            Keluarga</label>
                                                        @if ($berkas->kartu_keluarga_file && Storage::disk('public')->exists($berkas->kartu_keluarga_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->kartu_keluarga_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->kartu_keluarga_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="kartu_keluarga_file"
                                                            class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Unggah file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="form-label fw-bold">Pas Foto
                                                            3x4</label>
                                                        @if ($berkas->pas_foto_file && Storage::disk('public')->exists($berkas->pas_foto_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->pas_foto_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->pas_foto_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="pas_foto_file" class="form-control"
                                                            accept=".jpg,.jpeg,.png">
                                                        <small class="text-muted">Unggah file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item">
                                    <h2 class="accordion-header"><button class="accordion-button collapsed"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree"><i
                                                class="fas fa-certificate text-warning me-2"></i> Dokumen
                                            Pendukung</button></h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        data-bs-parent="#berkasAccordion">
                                        <div class="accordion-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="form-label fw-bold">Sertifikat
                                                            Pendukung</label>
                                                        @if ($berkas->sertifikat_file && Storage::disk('public')->exists($berkas->sertifikat_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->sertifikat_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->sertifikat_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="sertifikat_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Unggah file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="form-label fw-bold">SKCK</label>
                                                        @if ($berkas->skck_file && Storage::disk('public')->exists($berkas->skck_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->skck_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->skck_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="skck_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Unggah file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label class="form-label fw-bold">Surat
                                                            Keterangan Sehat</label>
                                                        @if ($berkas->surat_keterangan_sehat_file && Storage::disk('public')->exists($berkas->surat_keterangan_sehat_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->surat_keterangan_sehat_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->surat_keterangan_sehat_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="surat_keterangan_sehat_file"
                                                            class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Unggah file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group"><label
                                                            class="form-label fw-bold">Portofolio</label>
                                                        @if ($berkas->portofolio_file && Storage::disk('public')->exists($berkas->portofolio_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->portofolio_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->portofolio_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="portofolio_file" class="form-control"
                                                            accept=".pdf,.jpg,.jpeg,.png">
                                                        <small class="text-muted">Unggah file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group"><label class="form-label fw-bold">Dokumen
                                                            Lainnya</label>
                                                        @if ($berkas->dokumen_lain_file && Storage::disk('public')->exists($berkas->dokumen_lain_file))
                                                            <div class="mb-2"><a
                                                                    href="{{ asset('storage/' . $berkas->dokumen_lain_file) }}"
                                                                    target="_blank"
                                                                    class="btn btn-sm btn-outline-primary me-2"><i
                                                                        class="fas fa-eye me-1"></i> Lihat</a><a
                                                                    href="{{ asset('storage/' . $berkas->dokumen_lain_file) }}"
                                                                    download class="btn btn-sm btn-primary"><i
                                                                        class="fas fa-download me-1"></i> Unduh</a></div>
                                                        @else<span class="badge bg-light text-dark mb-2"><i
                                                                    class="fas fa-exclamation-circle me-1"></i> Belum
                                                                diunggah</span>
                                                        @endif
                                                        <input type="file" name="dokumen_lain_file"
                                                            class="form-control" accept=".pdf,.jpg,.jpeg,.png"><small
                                                            class="text-muted">Unggah file baru untuk mengganti</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('pelamar.berkas.index') }}"
                                    class="btn btn-outline-secondary me-md-2"><i class="fas fa-arrow-left me-1"></i>
                                    Kembali</a>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan
                                    Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .accordion-button:not(.collapsed) {
            background-color: rgba(13, 110, 253, 0.1);
            color: var(--bs-primary);
        }

        .form-control:focus {
            border-color: var(--bs-primary);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .bg-light {
            background-color: #f8f9fa !important;
        }
    </style>
@endsection
