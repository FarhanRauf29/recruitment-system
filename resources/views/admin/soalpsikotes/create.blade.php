@extends('layouts.admin')
@section('title', 'Tambah Soal Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-plus-circle me-2"></i> Tambah Soal Psikotes
                                </h4>
                                <p class="small mb-0 mt-1 opacity-75">Buat soal psikotes baru</p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <a href="{{ route('admin.soalpsikotes.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.soalpsikotes.store') }}" method="POST" id="soalForm">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kategori <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-tags text-primary"></i></span>
                                        <select name="kategori_id"
                                            class="form-select @error('kategori_id') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }} ({{ ucfirst($kategori->jenis) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('kategori_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kode Soal <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-code text-primary"></i></span>
                                        <input type="text" name="kode_soal"
                                            class="form-control @error('kode_soal') is-invalid @enderror"
                                            value="{{ old('kode_soal') }}" placeholder="Contoh: KOG001" required>
                                    </div>
                                    <small class="text-muted">Kode unik untuk soal psikotes</small>
                                    @error('kode_soal')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Pertanyaan <span
                                            class="text-danger">*</span></label>
                                    <textarea name="pertanyaan" class="form-control @error('pertanyaan') is-invalid @enderror" rows="3" required>{{ old('pertanyaan') }}</textarea>
                                    @error('pertanyaan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-layer-group text-primary"></i></span>
                                        <select name="jenis" class="form-select @error('jenis') is-invalid @enderror"
                                            required>
                                            <option value="">-- Pilih Jenis --</option>
                                            <option value="kognitif" {{ old('jenis') == 'kognitif' ? 'selected' : '' }}>
                                                Kognitif</option>
                                            <option value="kepribadian"
                                                {{ old('jenis') == 'kepribadian' ? 'selected' : '' }}>Kepribadian</option>
                                            <option value="minat" {{ old('jenis') == 'minat' ? 'selected' : '' }}>Minat
                                            </option>
                                        </select>
                                    </div>
                                    @error('jenis')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kunci Jawaban</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-key text-primary"></i></span>
                                        <input type="text" name="kunci_jawaban"
                                            class="form-control @error('kunci_jawaban') is-invalid @enderror"
                                            value="{{ old('kunci_jawaban') }}" placeholder="Contoh: A">
                                    </div>
                                    <small class="text-muted">Untuk soal kognitif, kosongkan untuk kepribadian/minat</small>
                                    @error('kunci_jawaban')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Bobot Nilai <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-weight-hanging text-primary"></i></span>
                                        <input type="number" name="bobot_nilai"
                                            class="form-control @error('bobot_nilai') is-invalid @enderror"
                                            value="{{ old('bobot_nilai', 1) }}" min="1" required>
                                    </div>
                                    @error('bobot_nilai')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tingkat Kesulitan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-chart-line text-primary"></i></span>
                                        <input type="number" name="tingkat_kesulitan"
                                            class="form-control @error('tingkat_kesulitan') is-invalid @enderror"
                                            value="{{ old('tingkat_kesulitan', 1) }}" min="1" max="5"
                                            required>
                                    </div>
                                    @error('tingkat_kesulitan')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Waktu per Soal (detik)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-clock text-primary"></i></span>
                                        <input type="number" name="waktu_per_soal_detik"
                                            class="form-control @error('waktu_per_soal_detik') is-invalid @enderror"
                                            value="{{ old('waktu_per_soal_detik', 60) }}" min="10">
                                    </div>
                                    @error('waktu_per_soal_detik')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Opsi Jawaban <span
                                            class="text-danger">*</span></label>
                                    <div id="opsi-container">
                                        <div class="input-group mb-2">
                                            <span class="input-group-text bg-primary text-white">A</span>
                                            <input type="text" name="opsi_jawaban_text[]" class="form-control"
                                                placeholder="Opsi A" required>
                                        </div>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text bg-primary text-white">B</span>
                                            <input type="text" name="opsi_jawaban_text[]" class="form-control"
                                                placeholder="Opsi B" required>
                                        </div>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text bg-primary text-white">C</span>
                                            <input type="text" name="opsi_jawaban_text[]" class="form-control"
                                                placeholder="Opsi C">
                                        </div>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text bg-primary text-white">D</span>
                                            <input type="text" name="opsi_jawaban_text[]" class="form-control"
                                                placeholder="Opsi D">
                                        </div>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text bg-primary text-white">E</span>
                                            <input type="text" name="opsi_jawaban_text[]" class="form-control"
                                                placeholder="Opsi E">
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                        id="tambah-opsi"><i class="fas fa-plus me-1"></i> Tambah Opsi</button>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Penjelasan</label>
                                    <textarea name="penjelasan" class="form-control" rows="3">{{ old('penjelasan') }}</textarea>
                                    <small class="text-muted">Penjelasan jawaban (opsional)</small>
                                </div>
                                <div class="col-12 d-flex gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>
                                        Simpan</button>
                                    <a href="{{ route('admin.soalpsikotes.index') }}" class="btn btn-secondary"><i
                                            class="fas fa-arrow-left me-1"></i> Batal</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let opsiContainer = document.getElementById('opsi-container');
                let tambahOpsiBtn = document.getElementById('tambah-opsi');
                let huruf = ['F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W',
                    'X', 'Y', 'Z'
                ];
                let hurufIndex = 0;
                tambahOpsiBtn.addEventListener('click', function() {
                    if (hurufIndex < huruf.length) {
                        let div = document.createElement('div');
                        div.className = 'input-group mb-2';
                        div.innerHTML =
                            `<span class="input-group-text bg-secondary text-white">${huruf[hurufIndex]}</span><input type="text" name="opsi_jawaban_text[]" class="form-control" placeholder="Opsi ${huruf[hurufIndex]}"><button type="button" class="btn btn-outline-danger remove-opsi"><i class="fas fa-times"></i></button>`;
                        opsiContainer.appendChild(div);
                        div.querySelector('.remove-opsi').addEventListener('click', function() {
                            div.remove();
                            hurufIndex--;
                        });
                        hurufIndex++;
                    }
                });
                document.querySelectorAll('.remove-opsi').forEach(btn => {
                    btn.addEventListener('click', function() {
                        this.closest('.input-group').remove();
                    });
                });
            });
        </script>
    @endpush
@endsection
