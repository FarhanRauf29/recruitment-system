@extends('layouts.admin')
@section('title', 'Tambah Pelamar')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-user-plus me-2"></i> Tambah Pelamar Baru
                        </h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.pelamar.store') }}" method="POST" enctype="multipart/form-data"
                            id="pelamarForm">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-section">
                                        <div class="section-header">
                                            <i class="fas fa-id-card"></i>
                                            <h5>Data Diri</h5>
                                        </div>

                                        <div class="form-group">
                                            <label>Nama Depan <span class="text-danger">*</span></label>
                                            <div class="input-icon">
                                                <i class="fas fa-user"></i>
                                                <input type="text" name="nama_depan"
                                                    class="form-control @error('nama_depan') is-invalid @enderror"
                                                    value="{{ old('nama_depan') }}" required>
                                            </div>
                                            <small class="text-muted">Nama harus sesuai dengan KTP</small>
                                            @error('nama_depan')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Nama Belakang <span class="text-danger">*</span></label>
                                            <div class="input-icon">
                                                <i class="fas fa-user"></i>
                                                <input type="text" name="nama_belakang"
                                                    class="form-control @error('nama_belakang') is-invalid @enderror"
                                                    value="{{ old('nama_belakang') }}" required>
                                            </div>
                                            @error('nama_belakang')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <div class="input-icon">
                                                <i class="fas fa-envelope"></i>
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    value="{{ old('email') }}" required>
                                            </div>
                                            @error('email')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                                <select name="jenis_kelamin"
                                                    class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="Laki-laki"
                                                        {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                                        Laki-laki</option>
                                                    <option value="Perempuan"
                                                        {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                                        Perempuan</option>
                                                </select>
                                                @error('jenis_kelamin')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Tempat Lahir <span class="text-danger">*</span></label>
                                                <div class="input-icon">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <input type="text" name="tempat_lahir"
                                                        class="form-control @error('tempat_lahir') is-invalid @enderror"
                                                        value="{{ old('tempat_lahir') }}" required>
                                                </div>
                                                @error('tempat_lahir')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Tanggal Lahir <span class="text-danger">*</span></label>
                                                <div class="input-icon">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <input type="date" name="tanggal_lahir"
                                                        class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                                        value="{{ old('tanggal_lahir') }}" required>
                                                </div>
                                                @error('tanggal_lahir')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Kewarganegaraan <span class="text-danger">*</span></label>
                                                <select name="kewarganegaraan"
                                                    class="form-control @error('kewarganegaraan') is-invalid @enderror"
                                                    required>
                                                    <option value="">-- Pilih --</option>
                                                    <option value="WNI"
                                                        {{ old('kewarganegaraan') == 'WNI' ? 'selected' : '' }}>WNI
                                                    </option>
                                                    <option value="WNA"
                                                        {{ old('kewarganegaraan') == 'WNA' ? 'selected' : '' }}>WNA
                                                    </option>
                                                </select>
                                                @error('kewarganegaraan')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Nomor Handphone <span class="text-danger">*</span></label>
                                                <div class="input-icon">
                                                    <i class="fas fa-phone"></i>
                                                    <input type="text" name="nomor_handphone"
                                                        class="form-control @error('nomor_handphone') is-invalid @enderror"
                                                        value="{{ old('nomor_handphone') }}"
                                                        onkeypress="return onlyNumber(event)"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                        maxlength="13" minlength="11" required>
                                                </div>
                                                <small class="text-muted">Minimal 11 digit & maksimal 13 digit</small>
                                                @error('nomor_handphone')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Nomor Whatsapp <span class="text-danger">*</span></label>
                                                <div class="input-icon">
                                                    <i class="fab fa-whatsapp"></i>
                                                    <input type="text" name="nomor_whatsapp"
                                                        class="form-control @error('nomor_whatsapp') is-invalid @enderror"
                                                        value="{{ old('nomor_whatsapp') }}"
                                                        onkeypress="return onlyNumber(event)"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                        maxlength="13" minlength="11" required>
                                                </div>
                                                <small class="text-muted">Minimal 11 digit & maksimal 13 digit</small>
                                                @error('nomor_whatsapp')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>NIK (KTP) <span class="text-danger">*</span></label>
                                            <div class="input-icon">
                                                <i class="fas fa-id-card"></i>
                                                <input type="text" name="nomor_identitas"
                                                    class="form-control @error('nomor_identitas') is-invalid @enderror"
                                                    value="{{ old('nomor_identitas') }}" maxlength="16"
                                                    onkeypress="return onlyNumber(event)"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                            </div>
                                            <small class="text-muted">16 digit angka (Sesuai KTP)</small>
                                            @error('nomor_identitas')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-section">
                                        <div class="section-header">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <h5>Alamat & Domisili</h5>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat Domisili <span class="text-danger">*</span></label>
                                            <textarea name="alamat_domisili" class="form-control @error('alamat_domisili') is-invalid @enderror" rows="2"
                                                placeholder=" " required>{{ old('alamat_domisili') }}</textarea>
                                            @error('alamat_domisili')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Provinsi <span class="text-danger">*</span></label>
                                                <select name="provinsi" id="provinsi" class="form-control" required>
                                                    <option value="">-- Pilih Provinsi --</option>
                                                </select>
                                                @error('provinsi')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Kabupaten/Kota <span class="text-danger">*</span></label>
                                                <select name="kab_kota" id="kab_kota" class="form-control" required
                                                    disabled>
                                                    <option value="">-- Pilih Kab/Kota --</option>
                                                </select>
                                                @error('kab_kota')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Kecamatan</label>
                                                <select name="kecamatan" id="kecamatan" class="form-control">
                                                    <option value="">-- Pilih Kecamatan --</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Desa/Kelurahan</label>
                                                <select name="desa_kelurahan" id="desa_kelurahan" class="form-control">
                                                    <option value="">-- Pilih Desa/Kelurahan --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-section">
                                        <div class="section-header">
                                            <i class="fas fa-graduation-cap"></i>
                                            <h5>Pendidikan & Pekerjaan</h5>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Pendidikan Terakhir <span class="text-danger">*</span></label>
                                                <select name="pendidikan_terakhir" id="pendidikan_terakhir"
                                                    class="form-control @error('pendidikan_terakhir') is-invalid @enderror"
                                                    required>
                                                    <option value="">-- Pilih Jenjang --</option>
                                                    <option value="SD">SD</option>
                                                    <option value="SMP">SMP</option>
                                                    <option value="SMA/SMK">SMA/SMK</option>
                                                    <option value="Diploma (D3)">Diploma (D3)</option>
                                                    <option value="Diploma (D4)">Diploma (D4)</option>
                                                    <option value="Sarjana (S1)">Sarjana (S1)</option>
                                                    <option value="Magister (S2)">Magister (S2)</option>
                                                </select>
                                                @error('pendidikan_terakhir')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Nama Instansi</label>
                                                <div class="input-icon">
                                                    <i class="fas fa-university"></i>
                                                    <select name="instansi" id="instansi_select" class="form-control">
                                                        <option value="">-- Pilih Instansi --</option>
                                                        @foreach ($instansiOptions as $value => $label)
                                                            <option value="{{ $value }}"
                                                                {{ old('instansi') == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" name="instansi" id="instansi_input"
                                                        class="form-control" style="display: none;"
                                                        placeholder="Masukkan nama instansi"
                                                        value="{{ old('instansi') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Jurusan</label>
                                                <div class="input-icon">
                                                    <i class="fas fa-graduation-cap"></i>
                                                    <select name="jurusan" id="jurusan_select" class="form-control">
                                                        <option value="">-- Pilih Jurusan --</option>
                                                        @foreach ($jurusanOptions as $value => $label)
                                                            <option value="{{ $value }}"
                                                                {{ old('jurusan') == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" name="jurusan" id="jurusan_input"
                                                        class="form-control" style="display: none;"
                                                        placeholder="Masukkan nama jurusan" value="{{ old('jurusan') }}">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Gaji Diharapkan <span class="text-danger">*</span></label>
                                                <div class="input-group-prefix">
                                                    <span class="prefix">Rp</span>
                                                    <input type="text" name="gaji_diharapkan"
                                                        class="form-control @error('gaji_diharapkan') is-invalid @enderror"
                                                        value="{{ old('gaji_diharapkan') }}" onkeyup="formatRupiah(this)"
                                                        placeholder="5.000.000" required>
                                                </div>
                                                @error('gaji_diharapkan')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-section">
                                        <div class="section-header">
                                            <i class="fas fa-lock"></i>
                                            <h5>Akun Login</h5>
                                        </div>

                                        <div class="form-group">
                                            <label>Password</label>
                                            <div class="input-group-password">
                                                <input type="password" name="password" id="password"
                                                    class="form-control">
                                                <button type="button" class="btn-toggle"
                                                    onclick="togglePassword('password')">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">Kosongkan untuk menggunakan password default:
                                                12345678</small>
                                            @error('password')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Konfirmasi Password</label>
                                            <div class="input-group-password">
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" class="form-control">
                                                <button type="button" class="btn-toggle"
                                                    onclick="togglePassword('password_confirmation')">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Foto Profil</label>
                                            <input type="file" name="foto_profil" class="form-control"
                                                accept="image/*">
                                            <small class="text-muted">Format: JPG, PNG, maks 2MB</small>
                                            @error('foto_profil')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <a href="{{ route('admin.pelamar.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Pelamar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 24px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }

        .section-header i {
            font-size: 1.2rem;
            color: #2563eb;
        }

        .section-header h5 {
            margin: 0;
            font-weight: 600;
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 6px;
            font-size: 0.85rem;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
            z-index: 1;
        }

        .input-icon .form-control,
        .input-icon .form-select {
            padding-left: 36px;
        }

        .input-group-prefix {
            display: flex;
        }

        .input-group-prefix .prefix {
            background: #e9ecef;
            border: 1px solid #dee2e6;
            border-right: none;
            border-radius: 6px 0 0 6px;
            padding: 8px 12px;
        }

        .input-group-prefix .form-control {
            border-radius: 0 6px 6px 0;
        }

        .input-group-password {
            display: flex;
        }

        .input-group-password .form-control {
            border-radius: 6px 0 0 6px;
            flex: 1;
        }

        .btn-toggle {
            background: #e9ecef;
            border: 1px solid #dee2e6;
            border-left: none;
            border-radius: 0 6px 6px 0;
            padding: 0 12px;
        }

        .form-actions {
            margin-top: 24px;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn {
            padding: 8px 20px;
            border-radius: 6px;
        }

        .text-muted {
            font-size: 0.7rem;
            color: #6c757d;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -8px;
        }

        .form-row .form-group {
            padding: 0 8px;
        }

        .col-md-6 {
            width: 50%;
        }

        .form-section {
            overflow: visible !important;
        }

        .card-body {
            overflow: visible !important;
        }

        .card {
            overflow: visible !important;
        }

        .container-fluid,
        .row,
        .col-12,
        .col-md-6 {
            overflow: visible !important;
        }

        .form-select {
            background-position: right 0.75rem center !important;
        }

        .input-icon .form-select {
            background-position: right 0.75rem center !important;
        }

        @media (max-width: 768px) {
            .col-md-6 {
                width: 100%;
            }
        }
    </style>

    <script>
        function formatRupiah(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            if (value) {
                value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                input.value = value;
            }
        }

        function onlyNumber(event) {
            const charCode = event.which ? event.which : event.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const btn = field.nextElementSibling;
            const icon = btn.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const tingkatSelect = document.getElementById('pendidikan_terakhir');
            const instansiSelect = document.getElementById('instansi_select');
            const instansiInput = document.getElementById('instansi_input');
            const jurusanSelect = document.getElementById('jurusan_select');
            const jurusanInput = document.getElementById('jurusan_input');

            const tingkatDropdown = ['Diploma (D3)', 'Diploma (D4)', 'Sarjana (S1)', 'Magister (S2)'];

            function toggleInstansiJurusan() {
                const selected = tingkatSelect ? tingkatSelect.value : '';

                if (tingkatDropdown.includes(selected)) {
                    instansiSelect.style.display = 'block';
                    instansiInput.style.display = 'none';
                    jurusanSelect.style.display = 'block';
                    jurusanInput.style.display = 'none';
                    instansiSelect.name = 'instansi';
                    instansiInput.name = 'instansi';
                    jurusanSelect.name = 'jurusan';
                    jurusanInput.name = 'jurusan';
                } else if (selected === 'SD' || selected === 'SMP' || selected === 'SMA/SMK') {
                    instansiSelect.style.display = 'none';
                    instansiInput.style.display = 'block';
                    jurusanSelect.style.display = 'none';
                    jurusanInput.style.display = 'block';
                    instansiSelect.name = 'instansi';
                    instansiInput.name = 'instansi';
                    jurusanSelect.name = 'jurusan';
                    jurusanInput.name = 'jurusan';
                } else {
                    instansiSelect.style.display = 'block';
                    instansiInput.style.display = 'none';
                    jurusanSelect.style.display = 'block';
                    jurusanInput.style.display = 'none';
                    instansiSelect.name = 'instansi';
                    instansiInput.name = 'instansi';
                    jurusanSelect.name = 'jurusan';
                    jurusanInput.name = 'jurusan';
                }
            }

            if (tingkatSelect) {
                tingkatSelect.addEventListener('change', toggleInstansiJurusan);
                toggleInstansiJurusan();
            }

            loadProvinsi();
        });

        const API_PROVINSI = 'https://ibnux.github.io/data-indonesia/provinsi.json';
        let provinsiData = [];

        async function loadProvinsi() {
            const provSelect = document.getElementById('provinsi');
            if (!provSelect) return;
            provSelect.innerHTML = '<option value="">Memuat Provinsi...</option>';
            try {
                const res = await fetch(API_PROVINSI);
                provinsiData = await res.json();
                provSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
                provinsiData.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.nama;
                    opt.textContent = p.nama;
                    provSelect.appendChild(opt);
                });
                provSelect.onchange = () => {
                    const selected = provinsiData.find(p => p.nama === provSelect.value);
                    if (selected) loadKabKota(selected.id);
                    else resetWilayah();
                };
            } catch (e) {
                provSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
            }
        }

        async function loadKabKota(provId) {
            const kabSelect = document.getElementById('kab_kota');
            if (!kabSelect) return;
            kabSelect.innerHTML = '<option value="">Memuat Kab/Kota...</option>';
            kabSelect.disabled = true;
            resetWilayah(true);
            try {
                const res = await fetch(`https://ibnux.github.io/data-indonesia/kabupaten/${provId}.json`);
                const data = await res.json();
                kabSelect.innerHTML = '<option value="">-- Pilih Kab/Kota --</option>';
                data.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k.nama;
                    opt.textContent = k.nama;
                    kabSelect.appendChild(opt);
                });
                kabSelect.disabled = false;
                kabSelect.onchange = () => {
                    const selected = data.find(k => k.nama === kabSelect.value);
                    if (selected) loadKecamatan(selected.id);
                    else resetWilayah(true);
                };
            } catch (e) {
                kabSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
            }
        }

        async function loadKecamatan(kabId) {
            const kecSelect = document.getElementById('kecamatan');
            if (!kecSelect) return;
            kecSelect.innerHTML = '<option value="">Memuat Kecamatan...</option>';
            kecSelect.disabled = true;
            const desaSelect = document.getElementById('desa_kelurahan');
            if (desaSelect) {
                desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                desaSelect.disabled = true;
            }
            try {
                const res = await fetch(`https://ibnux.github.io/data-indonesia/kecamatan/${kabId}.json`);
                const data = await res.json();
                kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                data.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k.nama;
                    opt.textContent = k.nama;
                    kecSelect.appendChild(opt);
                });
                kecSelect.disabled = false;
                kecSelect.onchange = () => {
                    const selected = data.find(k => k.nama === kecSelect.value);
                    if (selected) loadDesa(selected.id);
                    else resetDesa();
                };
            } catch (e) {
                kecSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
            }
        }

        async function loadDesa(kecId) {
            const desaSelect = document.getElementById('desa_kelurahan');
            if (!desaSelect) return;
            desaSelect.innerHTML = '<option value="">Memuat Desa/Kelurahan...</option>';
            desaSelect.disabled = true;
            try {
                const res = await fetch(`https://ibnux.github.io/data-indonesia/kelurahan/${kecId}.json`);
                const data = await res.json();
                desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                data.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = d.nama;
                    opt.textContent = d.nama;
                    desaSelect.appendChild(opt);
                });
                desaSelect.disabled = false;
            } catch (e) {
                desaSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
            }
        }

        function resetWilayah(keepKecDesa = false) {
            const kabSelect = document.getElementById('kab_kota');
            if (kabSelect) {
                kabSelect.innerHTML = '<option value="">-- Pilih Kab/Kota --</option>';
                kabSelect.disabled = true;
            }
            if (!keepKecDesa) {
                const kecSelect = document.getElementById('kecamatan');
                const desaSelect = document.getElementById('desa_kelurahan');
                if (kecSelect) {
                    kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                    kecSelect.disabled = true;
                }
                if (desaSelect) {
                    desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                    desaSelect.disabled = true;
                }
            }
        }

        function resetDesa() {
            const desaSelect = document.getElementById('desa_kelurahan');
            if (desaSelect) {
                desaSelect.innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                desaSelect.disabled = true;
            }
        }
    </script>
@endsection
