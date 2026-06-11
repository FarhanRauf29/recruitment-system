@extends('layouts.admin')
@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i> Edit Sesi Psikotes
                        </h3>
                        <p class="small mb-0">Token: {{ $sesi->token_sesi }}</p>
                    </div>
                    <div class="card-body">
                        {{-- Notifikasi --}}
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i> {!! session('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i> {!! session('error') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        <form action="{{ route('admin.sesipsikotes.update', $sesi->id) }}" method="POST" id="editSesiForm">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label for="lowongan_id" class="form-label fw-bold">Lowongan</label>
                                    <select name="lowongan_id" id="lowongan_id" class="form-control" required>
                                        <option value="">-- Pilih Lowongan --</option>
                                        @foreach ($lowongans as $lowongan)
                                            <option value="{{ $lowongan->id }}"
                                                {{ $sesi->lowongan_id == $lowongan->id ? 'selected' : '' }}
                                                data-durasi="{{ $lowongan->konfigurasiPsikotes->durasi_menit ?? 0 }}">
                                                {{ $lowongan->judul_lowongan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="pelamar_id" class="form-label fw-bold">Pelamar</label>
                                    <input type="text" class="form-control"
                                        value="{{ $sesi->pelamar->nama_lengkap ?? '-' }}" readonly>
                                    <input type="hidden" name="pelamar_id" value="{{ $sesi->pelamar_id }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="waktu_mulai" class="form-label fw-bold">Waktu Mulai <span
                                            class="text-danger">*</span></label>
                                    <input type="datetime-local" name="waktu_mulai" id="waktu_mulai" class="form-control"
                                        required value="{{ $sesi->waktu_mulai->format('Y-m-d\TH:i') }}">
                                    <div class="form-text">Waktu mulai tes psikotes</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="waktu_selesai" class="form-label fw-bold">Waktu Selesai</label>
                                    <input type="datetime-local" name="waktu_selesai" id="waktu_selesai"
                                        class="form-control" readonly
                                        value="{{ $sesi->waktu_selesai->format('Y-m-d\TH:i') }}">
                                    <div class="form-text">Waktu selesai otomatis berdasarkan durasi konfigurasi</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Durasi Konfigurasi</label>
                                    <input type="text" class="form-control" id="durasi_konfigurasi"
                                        value="{{ $sesi->konfigurasi->durasi_menit ?? 0 }} menit" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Jumlah Soal</label>
                                    <input type="text" class="form-control"
                                        value="{{ $sesi->jumlah_soal_ditampilkan }} soal" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Status</label>
                                    <input type="text" class="form-control"
                                        value="{{ ucfirst(str_replace('_', ' ', $sesi->status)) }}{{ $sesi->selesai ? ' (Selesai)' : '' }}"
                                        readonly>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i>Perhatian</h6>
                                        <p class="mb-0">
                                            Mengedit sesi psikotes akan memperbarui waktu dan mengacak ulang soal
                                            berdasarkan konfigurasi terbaru.
                                            Waktu selesai otomatis dihitung dari waktu mulai + durasi konfigurasi.
                                            Pastikan perubahan sudah benar sebelum menyimpan.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <a href="{{ route('admin.sesipsikotes.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Kembali
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-1"></i> Perbarui Sesi
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lowonganSelect = document.getElementById('lowongan_id');
            const waktuMulaiInput = document.getElementById('waktu_mulai');
            const waktuSelesaiInput = document.getElementById('waktu_selesai');
            const durasiKonfigurasiInput = document.getElementById('durasi_konfigurasi');
            const submitBtn = document.getElementById('submitBtn');

            // Fungsi untuk menghitung waktu selesai otomatis
            function hitungWaktuSelesai() {
                const selectedOption = lowonganSelect.options[lowonganSelect.selectedIndex];
                const durasiMenit = selectedOption.getAttribute('data-durasi') || 0;
                const waktuMulai = waktuMulaiInput.value;

                if (waktuMulai && durasiMenit > 0) {
                    const waktuMulaiDate = new Date(waktuMulai);
                    const waktuSelesaiDate = new Date(waktuMulaiDate.getTime() + (durasiMenit * 60000));

                    // Format ke YYYY-MM-DDTHH:mm
                    const formattedSelesai = waktuSelesaiDate.toISOString().slice(0, 16);
                    waktuSelesaiInput.value = formattedSelesai;

                    // Update tampilan durasi konfigurasi
                    durasiKonfigurasiInput.value = durasiMenit + ' menit';
                } else {
                    waktuSelesaiInput.value = '';
                }
            }

            // Event listeners
            lowonganSelect.addEventListener('change', function() {
                hitungWaktuSelesai();
            });

            waktuMulaiInput.addEventListener('change', hitungWaktuSelesai);

            // Hitung waktu selesai saat pertama kali load
            hitungWaktuSelesai();

            // Form submission dengan loading state
            const form = document.getElementById('editSesiForm');
            form.addEventListener('submit', function(e) {
                const waktuMulaiInput = document.getElementById('waktu_mulai').value;

                if (waktuMulaiInput) {
                    const dateMulai = new Date(waktuMulaiInput);
                    const jakartaMulai = new Date(dateMulai.toLocaleString('en-US', {
                        timeZone: 'Asia/Jakarta'
                    }));
                    const formattedMulai = jakartaMulai.toISOString().slice(0, 16);
                    document.getElementById('waktu_mulai').value = formattedMulai;
                }

                // Submit form
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memperbarui...';
            });
        });
    </script>
@endpush
