@extends('layouts.admin')

@section('title', 'Input Hasil Medical Check Up')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-0">
                                <i class="fas fa-stethoscope me-2"></i> Input Hasil Medical Check Up
                            </h4>
                            <p class="small mb-0 mt-1 opacity-75">Input data hasil pemeriksaan MCU pelamar</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.mcu.show', $mcu->id) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Pelamar:</strong> {{ $mcu->pelamar->nama_lengkap }} &nbsp;|&nbsp;
                            <strong>Tanggal MCU:</strong>
                            {{ Carbon\Carbon::parse($mcu->jadwal_mcu)->translatedFormat('d F Y H:i') }} WIB
                        </div>

                        <!-- PERBAIKAN: Hapus @method('PUT') karena route hanya support POST -->
                        <form action="{{ route('admin.mcu.store_hasil', $mcu->id) }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tinggi Badan (cm)</label>
                                        <input type="number" step="0.1" name="tinggi_badan" class="form-control"
                                            value="{{ old('tinggi_badan', $mcu->tinggi_badan) }}" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Berat Badan (kg)</label>
                                        <input type="number" step="0.1" name="berat_badan" class="form-control"
                                            value="{{ old('berat_badan', $mcu->berat_badan) }}" placeholder="">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">BMI (Indeks Massa Tubuh)</label>
                                        <input type="number" step="0.1" name="bmi" class="form-control"
                                            value="{{ old('bmi', $mcu->bmi) }}" placeholder="" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tekanan Darah</label>
                                        <input type="text" name="tekanan_darah" class="form-control"
                                            value="{{ old('tekanan_darah', $mcu->tekanan_darah) }}" placeholder="   ">
                                        <small class="text-muted">Format: Sistolik/Diastolik (contoh: 120/80)</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Visus Mata</label>
                                        <input type="text" name="visus_mata" class="form-control"
                                            value="{{ old('visus_mata', $mcu->visus_mata) }}" placeholder="">
                                        <small class="text-muted">Contoh: 6/6, 6/9, Normal</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Buta Warna</label>
                                        <select name="buta_warna" class="form-select">
                                            <option value="">-- Pilih --</option>
                                            <option value="Tidak"
                                                {{ old('buta_warna', $mcu->buta_warna) == 'Tidak' ? 'selected' : '' }}>
                                                Tidak</option>
                                            <option value="Parsial"
                                                {{ old('buta_warna', $mcu->buta_warna) == 'Parsial' ? 'selected' : '' }}>
                                                Parsial</option>
                                            <option value="Total"
                                                {{ old('buta_warna', $mcu->buta_warna) == 'Total' ? 'selected' : '' }}>
                                                Total</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Hasil Tes Narkoba</label>
                                        <select name="hasil_narkoba" class="form-select">
                                            <option value="">-- Pilih --</option>
                                            <option value="Positif"
                                                {{ old('hasil_narkoba', $mcu->hasil_narkoba) == 'Positif' ? 'selected' : '' }}>
                                                Positif</option>
                                            <option value="Negatif"
                                                {{ old('hasil_narkoba', $mcu->hasil_narkoba) == 'Negatif' ? 'selected' : '' }}>
                                                Negatif</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Riwayat Penyakit</label>
                                        <input type="text" name="riwayat_penyakit" class="form-control"
                                            value="{{ old('riwayat_penyakit', $mcu->riwayat_penyakit) }}" placeholder="">
                                        <small class="text-muted">Contoh: Jantung, Diabetes, Hipertensi, Asma</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Catatan Tambahan</label>
                                        <textarea name="catatan_admin" class="form-control" rows="2" placeholder="Catatan tambahan dari pemeriksaan">{{ old('catatan_admin', $mcu->catatan_admin) }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Simpan Hasil MCU
                                </button>
                                <a href="{{ route('admin.mcu.show', $mcu->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('input[name="tinggi_badan"]')?.addEventListener('input', hitungBMI);
        document.querySelector('input[name="berat_badan"]')?.addEventListener('input', hitungBMI);

        function hitungBMI() {
            const tinggi = parseFloat(document.querySelector('input[name="tinggi_badan"]')?.value);
            const berat = parseFloat(document.querySelector('input[name="berat_badan"]')?.value);
            const bmiInput = document.querySelector('input[name="bmi"]');

            if (tinggi > 0 && berat > 0 && bmiInput) {
                const tinggiMeter = tinggi / 100;
                const bmi = berat / (tinggiMeter * tinggiMeter);
                bmiInput.value = bmi.toFixed(1);
            }
        }
    </script>
@endsection
