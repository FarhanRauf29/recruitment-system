@extends('layouts.admin')

@section('title', 'Buat Penawaran Kerja')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3 px-4">
                        <h4 class="card-title mb-0 fw-semibold">
                            <i class="fas fa-file-signature me-2"></i> Buat Penawaran Kerja
                        </h4>
                        <small class="opacity-75 mt-1 d-block">
                            <i class="fas fa-user me-1"></i> Kandidat: {{ $pelamar->nama_lengkap }} |
                            <i class="fas fa-briefcase me-1 ms-2"></i> Lowongan: {{ $lowongan->judul_lowongan }}
                        </small>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('admin.offering.store', [$lowongan->id, $pelamar->id]) }}" method="POST"
                            id="offeringForm">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-briefcase text-primary me-1"></i> Posisi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="posisi"
                                        class="form-control form-control-lg @error('posisi') is-invalid @enderror"
                                        value="{{ old('posisi', $lowongan->judul_lowongan) }}"
                                        placeholder="Masukkan posisi pekerjaan" required>
                                    @error('posisi')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-money-bill-wave text-primary me-1"></i> Gaji yang Ditawarkan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0 fw-semibold">Rp</span>
                                        <input type="text" name="gaji_display" id="gaji_display"
                                            class="form-control @error('gaji') is-invalid @enderror" placeholder="0"
                                            value="{{ old('gaji') ? number_format(old('gaji'), 0, ',', '.') : '' }}">
                                        <input type="hidden" name="gaji" id="gaji_hidden" value="{{ old('gaji') }}">
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-info-circle me-1"></i> Jika di atas Rp 15.000.000, perlu approval
                                        atasan
                                    </small>
                                    @error('gaji')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-file-contract text-primary me-1"></i> Tipe Kontrak
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="tipe_kontrak"
                                        class="form-select form-select-lg @error('tipe_kontrak') is-invalid @enderror"
                                        required>
                                        <option value="">-- Pilih Tipe Kontrak --</option>
                                        <option value="tetap" {{ old('tipe_kontrak') == 'tetap' ? 'selected' : '' }}>
                                            <i class="fas fa-check-circle"></i> Tetap
                                        </option>
                                        <option value="kontrak" {{ old('tipe_kontrak') == 'kontrak' ? 'selected' : '' }}>
                                            <i class="fas fa-file"></i> Kontrak
                                        </option>
                                        <option value="probation"
                                            {{ old('tipe_kontrak') == 'probation' ? 'selected' : '' }}>
                                            <i class="fas fa-chart-line"></i> Probation (Masa Percobaan)
                                        </option>
                                    </select>
                                    @error('tipe_kontrak')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-calendar-alt text-primary me-1"></i> Tanggal Mulai Kerja
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="tanggal_mulai_kerja"
                                        class="form-control form-control-lg @error('tanggal_mulai_kerja') is-invalid @enderror"
                                        value="{{ old('tanggal_mulai_kerja') }}" min="{{ date('Y-m-d') }}" required>
                                    @error('tanggal_mulai_kerja')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-hourglass-half text-primary me-1"></i> Batas Respon (hari)
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <input type="number" name="batas_respon"
                                            class="form-control @error('batas_respon') is-invalid @enderror"
                                            value="{{ old('batas_respon', 7) }}" min="1" max="30" required>
                                        <span class="input-group-text bg-light">hari</span>
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-clock me-1"></i> Maksimal 30 hari
                                    </small>
                                    @error('batas_respon')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-sticky-note text-primary me-1"></i> Catatan HR
                                    </label>
                                    <textarea name="catatan_hr" class="form-control @error('catatan_hr') is-invalid @enderror" rows="4"
                                        placeholder="Tambahkan catatan internal untuk HR...">{{ old('catatan_hr') }}</textarea>
                                    <small class="text-muted mt-1 d-block">
                                        <i class="fas fa-lock me-1"></i> Catatan ini hanya terlihat oleh internal HR
                                    </small>
                                    @error('catatan_hr')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Penawaran
                                </button>
                                <a href="{{ route('admin.offering.index', $lowongan->id) }}"
                                    class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                    <i class="fas fa-arrow-left me-2"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Format Rupiah otomatis
            const gajiDisplay = document.getElementById('gaji_display');
            const gajiHidden = document.getElementById('gaji_hidden');

            function formatRupiah(angka) {
                let number_string = angka.replace(/[^,\d]/g, '').toString(),
                    split = number_string.split(','),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                if (ribuan) {
                    let separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }

                rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
                return rupiah;
            }

            gajiDisplay.addEventListener('keyup', function(e) {
                let value = this.value.replace(/[^0-9]/g, '');
                if (value !== '') {
                    let formatted = formatRupiah(value);
                    this.value = formatted;
                    gajiHidden.value = value;
                } else {
                    this.value = '';
                    gajiHidden.value = '';
                }
            });

            gajiDisplay.addEventListener('blur', function() {
                let value = this.value.replace(/[^0-9]/g, '');
                if (value !== '' && parseInt(value) > 0) {
                    let formatted = formatRupiah(value);
                    this.value = 'Rp ' + formatted;
                } else {
                    this.value = '';
                }
            });

            gajiDisplay.addEventListener('focus', function() {
                let value = this.value.replace(/[^0-9]/g, '');
                if (value !== '') {
                    this.value = formatRupiah(value);
                } else {
                    this.value = '';
                }
            });

            // Preview nilai sebelum submit
            document.getElementById('offeringForm').addEventListener('submit', function(e) {
                let rawValue = gajiDisplay.value.replace(/[^0-9]/g, '');
                if (rawValue !== '') {
                    gajiHidden.value = rawValue;
                }
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .form-control-lg,
            .form-select-lg {
                border-radius: 10px;
                padding: 12px 16px;
            }

            .input-group-lg .input-group-text {
                border-radius: 10px 0 0 10px;
                padding: 12px 16px;
            }

            .input-group-lg .form-control {
                border-radius: 0 10px 10px 0;
            }

            .btn {
                font-weight: 500;
                transition: all 0.2s ease;
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
        </style>
    @endpush
@endsection
