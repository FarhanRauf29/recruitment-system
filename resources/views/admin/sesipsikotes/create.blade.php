@extends('layouts.admin')
@section('title', 'Buat Sesi Psikotes Baru')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-clock me-2"></i> Buat Sesi Psikotes Baru
                        </h4>
                        <p class="small mb-0 mt-1 opacity-75">Untuk semua pelamar yang lolos screening</p>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i> {!! session('success') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i> {!! session('error') !!}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.sesipsikotes.store') }}" method="POST" id="createSesiForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Lowongan <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-briefcase text-primary"></i></span>
                                            <select name="lowongan_id" id="lowongan_id" class="form-select" required>
                                                <option value="">-- Pilih Lowongan --</option>
                                                @foreach ($lowongans as $lowongan)
                                                    <option value="{{ $lowongan->id }}" data-id="{{ $lowongan->id }}">
                                                        {{ $lowongan->judul_lowongan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-text">Pilih lowongan untuk pelamar lolos Screening Berkas</div>
                                        <div id="lowongan-info" class="mt-2"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Waktu Mulai <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-calendar-alt text-primary"></i></span>
                                            <input type="datetime-local" name="waktu_mulai" id="waktu_mulai"
                                                class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Waktu Selesai</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-flag-checkered text-primary"></i></span>
                                            <input type="datetime-local" name="waktu_selesai" id="waktu_selesai"
                                                class="form-control" readonly>
                                        </div>
                                        <div class="form-text">Waktu selesai otomatis dihitung berdasarkan durasi
                                            konfigurasi</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jumlah Pelamar per Sesi <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-users text-primary"></i></span>
                                            <input type="number" name="jumlah_pelamar_per_sesi"
                                                id="jumlah_pelamar_per_sesi" class="form-control" min="1"
                                                max="100" required value="30">
                                        </div>
                                        <div class="form-text">Batas maksimum jumlah pelamar dalam satu sesi (maks. 100)
                                        </div>
                                    </div>

                                    <div class="alert alert-info mt-3">
                                        <h6 class="fw-bold"><i class="fas fa-info-circle me-2"></i> Informasi Penting</h6>
                                        <ul class="mb-0">
                                            <li>Sesi psikotes akan dibuat untuk <strong>semua pelamar</strong> yang lolos
                                                screening pada lowongan terpilih</li>
                                            <li>Setiap pelamar akan mendapatkan <strong>token unik</strong> untuk mengakses
                                                tes</li>
                                            <li>Jika jumlah pelamar melebihi batas per sesi, akan dibuat sesi baru</li>
                                            <li>Pelamar yang tidak mengikuti tes dalam 1 hari setelah batas waktu akan
                                                dianggap gagal</li>
                                            <li><strong>Waktu selesai otomatis</strong> dihitung berdasarkan durasi
                                                konfigurasi</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-calendar-check me-1"></i> Buat Sesi untuk Pelamar
                                </button>
                                <a href="{{ route('admin.sesipsikotes.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali
                                </a>
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
            function getJakartaTime(date) {
                return new Intl.DateTimeFormat('sv-SE', {
                    timeZone: 'Asia/Jakarta',
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                }).format(date).replace(' ', 'T').slice(0, 16);
            }

            const now = new Date();
            document.getElementById('waktu_mulai').value = getJakartaTime(now);

            const lowonganSelect = document.getElementById('lowongan_id');
            const waktuMulaiInput = document.getElementById('waktu_mulai');
            const waktuSelesaiInput = document.getElementById('waktu_selesai');
            const infoDiv = document.getElementById('lowongan-info');
            const submitBtn = document.getElementById('submitBtn');

            function hitungWaktuSelesai() {
                const lowonganId = lowonganSelect.value;
                const waktuMulai = waktuMulaiInput.value;

                if (lowonganId && waktuMulai) {
                    fetch(`/admin/konfigurasipsikotes/get-durasi/${lowonganId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.durasi_menit) {
                                const waktuMulaiDate = new Date(waktuMulai);
                                const waktuSelesaiDate = new Date(waktuMulaiDate.getTime() + data.durasi_menit *
                                    60000);
                                waktuSelesaiInput.value = getJakartaTime(waktuSelesaiDate);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            }

            lowonganSelect.addEventListener('change', function() {
                const lowonganId = this.value;
                if (lowonganId) {
                    fetch(`/admin/sesipsikotes/info-lowongan/${lowonganId}`)
                        .then(response => response.json())
                        .then(data => {
                            let infoHtml = `<div class="alert alert-info">
                            <strong>Info Lowongan:</strong><br>
                            Jumlah pelamar lolos berkas: ${data.jumlah_pelamar}<br>
                            Durasi tes: ${data.durasi_menit} menit<br>
                        </div>`;
                            if (data.jumlah_sesi > 0 && data.jumlah_pelamar <= document.getElementById(
                                    'jumlah_pelamar_per_sesi').value) {
                                infoHtml += `<div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Sesi psikotes untuk lowongan ini sudah ada dan jumlah pelamar tidak melebihi batas.
                            </div>`;
                                submitBtn.disabled = true;
                            } else {
                                submitBtn.disabled = false;
                            }
                            infoDiv.innerHTML = infoHtml;
                            hitungWaktuSelesai();
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                } else {
                    infoDiv.innerHTML = '';
                    submitBtn.disabled = false;
                    waktuSelesaiInput.value = '';
                }
            });

            waktuMulaiInput.addEventListener('change', hitungWaktuSelesai);

            const form = document.getElementById('createSesiForm');
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
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Membuat Sesi...';
            });
        });
    </script>
@endpush
