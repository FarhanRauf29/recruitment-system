@extends('layouts.admin')
@section('title', 'Edit Soal Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-edit me-2"></i> Edit Soal Psikotes
                                </h4>
                                <p class="small mb-0 mt-1 opacity-75">Perbarui soal psikotes</p>
                            </div>
                            <div class="mt-2 mt-md-0">
                                <a href="{{ route('admin.soalpsikotes.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.soalpsikotes.update', $soal->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kategori <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-tags text-primary"></i></span>
                                        <select name="kategori_id" class="form-select" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach ($kategoris as $kategori)
                                                <option value="{{ $kategori->id }}"
                                                    {{ old('kategori_id', $soal->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                                    {{ $kategori->nama_kategori }} ({{ ucfirst($kategori->jenis) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kode Soal <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-code text-primary"></i></span>
                                        <input type="text" name="kode_soal" class="form-control"
                                            value="{{ old('kode_soal', $soal->kode_soal) }}" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Pertanyaan <span
                                            class="text-danger">*</span></label>
                                    <textarea name="pertanyaan" class="form-control" rows="3" required>{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-layer-group text-primary"></i></span>
                                        <select name="jenis" class="form-select" required>
                                            <option value="kognitif"
                                                {{ old('jenis', $soal->jenis) == 'kognitif' ? 'selected' : '' }}>Kognitif
                                            </option>
                                            <option value="kepribadian"
                                                {{ old('jenis', $soal->jenis) == 'kepribadian' ? 'selected' : '' }}>
                                                Kepribadian</option>
                                            <option value="minat"
                                                {{ old('jenis', $soal->jenis) == 'minat' ? 'selected' : '' }}>Minat
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Kunci Jawaban</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-key text-primary"></i></span>
                                        <input type="text" name="kunci_jawaban" class="form-control"
                                            value="{{ old('kunci_jawaban', $soal->kunci_jawaban) }}"
                                            placeholder="Contoh: A">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Bobot Nilai <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-weight-hanging text-primary"></i></span>
                                        <input type="number" name="bobot_nilai" class="form-control"
                                            value="{{ old('bobot_nilai', $soal->bobot_nilai) }}" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tingkat Kesulitan <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-chart-line text-primary"></i></span>
                                        <input type="number" name="tingkat_kesulitan" class="form-control"
                                            value="{{ old('tingkat_kesulitan', $soal->tingkat_kesulitan) }}" min="1"
                                            max="5" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Waktu per Soal (detik)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i
                                                class="fas fa-clock text-primary"></i></span>
                                        <input type="number" name="waktu_per_soal_detik" class="form-control"
                                            value="{{ old('waktu_per_soal_detik', $soal->waktu_per_soal_detik) }}"
                                            min="10">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Opsi Jawaban</label>
                                    <div id="opsi-container">
                                        @php
                                            $opsiJawaban = is_array($soal->opsi_jawaban)
                                                ? $soal->opsi_jawaban
                                                : json_decode($soal->opsi_jawaban, true) ?? [];
                                            $hurufBase = ['A', 'B', 'C', 'D', 'E'];
                                        @endphp
                                        @foreach ($hurufBase as $huruf)
                                            <div class="input-group mb-2">
                                                <span
                                                    class="input-group-text bg-primary text-white">{{ $huruf }}</span>
                                                <input type="text" name="opsi_jawaban_text[]" class="form-control"
                                                    placeholder="Opsi {{ $huruf }}"
                                                    value="{{ $opsiJawaban[$huruf] ?? '' }}"
                                                    {{ in_array($huruf, ['A', 'B']) ? 'required' : '' }}>
                                                @if (!in_array($huruf, ['A', 'B']))
                                                    <button type="button" class="btn btn-outline-danger remove-opsi"><i
                                                            class="fas fa-times"></i></button>
                                                @endif
                                            </div>
                                        @endforeach
                                        @foreach ($opsiJawaban as $huruf => $nilai)
                                            @if (!in_array($huruf, $hurufBase))
                                                <div class="input-group mb-2">
                                                    <span
                                                        class="input-group-text bg-secondary text-white">{{ $huruf }}</span>
                                                    <input type="text" name="opsi_jawaban_text[]" class="form-control"
                                                        placeholder="Opsi {{ $huruf }}"
                                                        value="{{ $nilai }}">
                                                    <button type="button" class="btn btn-outline-danger remove-opsi"><i
                                                            class="fas fa-times"></i></button>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                        id="tambah-opsi"><i class="fas fa-plus me-1"></i> Tambah Opsi</button>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Penjelasan</label>
                                    <textarea name="penjelasan" class="form-control" rows="3">{{ old('penjelasan', $soal->penjelasan) }}</textarea>
                                </div>
                                <div class="col-12 d-flex gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>
                                        Simpan Perubahan</button>
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
                let existingHuruf = [];
                document.querySelectorAll('#opsi-container .input-group .input-group-text').forEach(el => {
                    existingHuruf.push(el.innerText.trim());
                });
                let nextHuruf = huruf.filter(h => !existingHuruf.includes(h));
                hurufIndex = 0;
                tambahOpsiBtn.addEventListener('click', function() {
                    if (hurufIndex < nextHuruf.length) {
                        let div = document.createElement('div');
                        div.className = 'input-group mb-2';
                        div.innerHTML =
                            `<span class="input-group-text bg-secondary text-white">${nextHuruf[hurufIndex]}</span><input type="text" name="opsi_jawaban_text[]" class="form-control" placeholder="Opsi ${nextHuruf[hurufIndex]}"><button type="button" class="btn btn-outline-danger remove-opsi"><i class="fas fa-times"></i></button>`;
                        opsiContainer.appendChild(div);
                        div.querySelector('.remove-opsi').addEventListener('click', function() {
                            div.remove();
                        });
                        hurufIndex++;
                    } else if (hurufIndex < huruf.length) {
                        let div = document.createElement('div');
                        div.className = 'input-group mb-2';
                        div.innerHTML =
                            `<span class="input-group-text bg-secondary text-white">${huruf[hurufIndex]}</span><input type="text" name="opsi_jawaban_text[]" class="form-control" placeholder="Opsi ${huruf[hurufIndex]}"><button type="button" class="btn btn-outline-danger remove-opsi"><i class="fas fa-times"></i></button>`;
                        opsiContainer.appendChild(div);
                        div.querySelector('.remove-opsi').addEventListener('click', function() {
                            div.remove();
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
