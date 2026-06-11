@extends('layouts.admin')
@section('title', 'Edit Konfigurasi Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i> Edit Konfigurasi Psikotes
                        </h4>
                        <p class="small mb-0 mt-1 opacity-75">Perbarui konfigurasi psikotes</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.konfigurasipsikotes.update', $konfigurasi->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Lowongan <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-briefcase text-primary"></i></span>
                                            <select name="lowongan_id"
                                                class="form-select @error('lowongan_id') is-invalid @enderror" required>
                                                <option value="">-- Pilih Lowongan --</option>
                                                @foreach ($lowongans as $lowongan)
                                                    <option value="{{ $lowongan->id }}"
                                                        {{ old('lowongan_id', $konfigurasi->lowongan_id) == $lowongan->id ? 'selected' : '' }}>
                                                        {{ $lowongan->judul_lowongan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('lowongan_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Durasi (Menit) <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-hourglass-half text-primary"></i></span>
                                            <input type="number" name="durasi_menit"
                                                class="form-control @error('durasi_menit') is-invalid @enderror"
                                                value="{{ old('durasi_menit', $konfigurasi->durasi_menit) }}" min="1"
                                                required>
                                        </div>
                                        @error('durasi_menit')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Batas Nilai Kelulusan (%) <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-chart-line text-primary"></i></span>
                                            <input type="number" name="batas_nilai_kelulusan"
                                                class="form-control @error('batas_nilai_kelulusan') is-invalid @enderror"
                                                value="{{ old('batas_nilai_kelulusan', $konfigurasi->batas_nilai_kelulusan) }}"
                                                min="0" max="100" required>
                                        </div>
                                        @error('batas_nilai_kelulusan')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jumlah Soal Kognitif</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-brain text-primary"></i></span>
                                            <input type="number" name="jumlah_soal_kognitif"
                                                class="form-control @error('jumlah_soal_kognitif') is-invalid @enderror"
                                                value="{{ old('jumlah_soal_kognitif', $konfigurasi->jumlah_soal_kognitif) }}"
                                                min="0">
                                        </div>
                                        @error('jumlah_soal_kognitif')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jumlah Soal Kepribadian</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-user-check text-primary"></i></span>
                                            <input type="number" name="jumlah_soal_kepribadian"
                                                class="form-control @error('jumlah_soal_kepribadian') is-invalid @enderror"
                                                value="{{ old('jumlah_soal_kepribadian', $konfigurasi->jumlah_soal_kepribadian) }}"
                                                min="0">
                                        </div>
                                        @error('jumlah_soal_kepribadian')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jumlah Soal Minat</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-heart text-primary"></i></span>
                                            <input type="number" name="jumlah_soal_minat"
                                                class="form-control @error('jumlah_soal_minat') is-invalid @enderror"
                                                value="{{ old('jumlah_soal_minat', $konfigurasi->jumlah_soal_minat) }}"
                                                min="0">
                                        </div>
                                        @error('jumlah_soal_minat')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Total Soal:
                                    {{ $konfigurasi->jumlah_soal_kognitif + $konfigurasi->jumlah_soal_kepribadian + $konfigurasi->jumlah_soal_minat }}
                                    Soal</strong>
                                <p class="mb-0 mt-1 small">Perubahan jumlah soal akan otomatis memperbarui semua sesi
                                    psikotes yang sedang berlangsung.</p>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Kategori Kognitif</label>
                                        <select name="kategori_kognitif[]" class="form-select" multiple size="5">
                                            @foreach ($kategoriKognitif as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ in_array($kategori->id, old('kategori_kognitif', $konfigurasi->kategori_kognitif ?? [])) ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Tahan Ctrl/Cmd untuk memilih banyak</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Kategori Kepribadian</label>
                                        <select name="kategori_kepribadian[]" class="form-select" multiple size="5">
                                            @foreach ($kategoriKepribadian as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ in_array($kategori->id, old('kategori_kepribadian', $konfigurasi->kategori_kepribadian ?? [])) ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Tahan Ctrl/Cmd untuk memilih banyak</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Kategori Minat</label>
                                        <select name="kategori_minat[]" class="form-select" multiple size="5">
                                            @foreach ($kategoriMinat as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ in_array($kategori->id, old('kategori_minat', $konfigurasi->kategori_minat ?? [])) ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Tahan Ctrl/Cmd untuk memilih banyak</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan
                                    Perubahan</button>
                                <a href="{{ route('admin.konfigurasipsikotes.index') }}" class="btn btn-secondary"><i
                                        class="fas fa-arrow-left me-1"></i> Batal</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
