@extends('layouts.admin')
@section('title', 'Edit Pelamar')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-user-edit me-2"></i> Edit Data Pelamar
                        </h3>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.pelamar.update', $pelamar->id) }}" method="POST"
                            enctype="multipart/form-data" id="pelamarForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <div class="foto-section">
                                        @if ($pelamar->foto_profil && Storage::disk('public')->exists($pelamar->foto_profil))
                                            <img src="{{ Storage::url($pelamar->foto_profil) }}" alt="Foto Profil"
                                                class="profile-img" id="profilePreview">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pelamar->nama_lengkap) }}&background=2563eb&color=fff&size=150"
                                                alt="Foto" class="profile-img" id="profilePreview">
                                        @endif
                                        <div class="mt-3">
                                            <label for="foto_profil" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-camera"></i> Ganti Foto
                                            </label>
                                            <input type="file" name="foto_profil" id="foto_profil" class="d-none"
                                                accept="image/*">
                                            <small class="text-muted d-block mt-2">Max 2MB (JPG, PNG)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <div class="form-section">
                                        <div class="section-header">
                                            <i class="fas fa-id-card"></i>
                                            <h5>Data Diri</h5>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Nama Depan <span class="text-danger">*</span></label>
                                                <div class="input-icon">
                                                    <i class="fas fa-user"></i>
                                                    <input type="text" name="nama_depan" class="form-control"
                                                        value="{{ old('nama_depan', $pelamar->nama_depan) }}" required>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Nama Belakang <span class="text-danger">*</span></label>
                                                <div class="input-icon">
                                                    <i class="fas fa-user"></i>
                                                    <input type="text" name="nama_belakang" class="form-control"
                                                        value="{{ old('nama_belakang', $pelamar->nama_belakang) }}"
                                                        required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <div class="input-icon">
                                                <i class="fas fa-envelope"></i>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ old('email', $pelamar->email) }}" required>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                                <select name="jenis_kelamin" class="form-control" required>
                                                    <option value="">-- Pilih --</option>
                                                    @foreach ($jenisKelaminOptions as $value => $label)
                                                        <option value="{{ $value }}"
                                                            {{ old('jenis_kelamin', $pelamar->jenis_kelamin) == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Tempat Lahir <span class="text-danger">*</span></label>
                                                <div class="input-icon">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    <input type="text" name="tempat_lahir" class="form-control"
                                                        value="{{ old('tempat_lahir', $pelamar->tempat_lahir) }}" required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Tanggal Lahir <span class="text-danger">*</span></label>
                                                <div class="input-icon">
                                                    <i class="fas fa-calendar-alt"></i>
                                                    <input type="date" name="tanggal_lahir" class="form-control"
                                                        value="{{ old('tanggal_lahir', $pelamar->tanggal_lahir?->format('Y-m-d')) }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Kewarganegaraan <span class="text-danger">*</span></label>
                                                <select name="kewarganegaraan" class="form-control" required>
                                                    @foreach ($kewarganegaraanOptions as $value => $label)
                                                        <option value="{{ $value }}"
                                                            {{ old('kewarganegaraan', $pelamar->kewarganegaraan) == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Nomor Handphone <span class="text-danger">*</span></label>
                                                <div class="input-icon">
                                                    <i class="fas fa-phone"></i>
                                                    <input type="tel" name="nomor_handphone" class="form-control"
                                                        value="{{ old('nomor_handphone', $pelamar->nomor_handphone) }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Nomor Whatsapp <span class="text-danger">*</span></label>
                                                <div class="input-icon">
                                                    <i class="fab fa-whatsapp"></i>
                                                    <input type="tel" name="nomor_whatsapp" class="form-control"
                                                        value="{{ old('nomor_whatsapp', $pelamar->nomor_whatsapp) }}"
                                                        required>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>NIK (KTP) <span class="text-danger">*</span></label>
                                            <div class="input-icon">
                                                <i class="fas fa-id-card"></i>
                                                <input type="text" name="nomor_identitas" class="form-control"
                                                    value="{{ old('nomor_identitas', $pelamar->nomor_identitas) }}"
                                                    maxlength="16" onkeypress="return onlyNumber(event)" required>
                                            </div>
                                            <small class="text-muted">16 digit angka (sesuai KTP)</small>
                                        </div>
                                    </div>

                                    <div class="form-section">
                                        <div class="section-header">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <h5>Alamat & Domisili</h5>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat Domisili <span class="text-danger">*</span></label>
                                            <textarea name="alamat_domisili" class="form-control" rows="2" required>{{ old('alamat_domisili', $pelamar->alamat_domisili) }}</textarea>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Provinsi <span class="text-danger">*</span></label>
                                                <select name="provinsi" id="provinsi" class="form-control" required>
                                                    <option value="{{ $pelamar->provinsi }}">{{ $pelamar->provinsi }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Kabupaten/Kota <span class="text-danger">*</span></label>
                                                <select name="kab_kota" id="kab_kota" class="form-control" required
                                                    disabled>
                                                    <option value="{{ $pelamar->kab_kota }}">{{ $pelamar->kab_kota }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Kecamatan</label>
                                                <select name="kecamatan" id="kecamatan" class="form-control">
                                                    <option value="{{ $pelamar->kecamatan }}">{{ $pelamar->kecamatan }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Desa/Kelurahan</label>
                                                <select name="desa_kelurahan" id="desa_kelurahan" class="form-control">
                                                    <option value="{{ $pelamar->desa_kelurahan }}">
                                                        {{ $pelamar->desa_kelurahan }}
                                                    </option>
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
                                                    class="form-control" required>
                                                    @foreach ($pendidikanOptions as $value => $label)
                                                        <option value="{{ $value }}"
                                                            {{ old('pendidikan_terakhir', $pelamar->pendidikan_terakhir) == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Nama Instansi</label>
                                                <div class="input-icon">
                                                    <i class="fas fa-university"></i>
                                                    <select name="instansi" id="instansi_select" class="form-control">
                                                        <option value="">-- Pilih Instansi --</option>
                                                        @foreach ($instansiOptions as $value => $label)
                                                            <option value="{{ $value }}"
                                                                {{ old('instansi', $pelamar->instansi) == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" name="instansi" id="instansi_input"
                                                        class="form-control" style="display: none;"
                                                        placeholder="Masukkan nama instansi"
                                                        value="{{ old('instansi', $pelamar->instansi) }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label>Jurusan</label>
                                                <div class="input-icon">
                                                    <i class="fas fa-book"></i>
                                                    <select name="jurusan" id="jurusan_select" class="form-control">
                                                        <option value="">-- Pilih Jurusan --</option>
                                                        @foreach ($jurusanOptions as $value => $label)
                                                            <option value="{{ $value }}"
                                                                {{ old('jurusan', $pelamar->jurusan) == $value ? 'selected' : '' }}>
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" name="jurusan" id="jurusan_input"
                                                        class="form-control" style="display: none;"
                                                        placeholder="Masukkan nama jurusan"
                                                        value="{{ old('jurusan', $pelamar->jurusan) }}">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label>Gaji Diharapkan <span class="text-danger">*</span></label>
                                                <div class="input-group-prefix">
                                                    <span class="prefix">Rp</span>
                                                    <input type="text" name="gaji_diharapkan" class="form-control"
                                                        value="{{ old('gaji_diharapkan', $pelamar->gaji_diharapkan) }}"
                                                        onkeyup="formatRupiah(this)" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-section">
                                        <div class="section-header">
                                            <i class="fas fa-lock"></i>
                                            <h5>Akun Login</h5>
                                        </div>

                                        <div class="form-group">
                                            <label>Password Baru</label>
                                            <div class="input-group-password">
                                                <input type="password" name="password" id="password"
                                                    class="form-control">
                                                <button type="button" class="btn-toggle"
                                                    onclick="togglePassword('password')">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Konfirmasi Password Baru</label>
                                            <div class="input-group-password">
                                                <input type="password" name="password_confirmation"
                                                    id="password_confirmation" class="form-control">
                                                <button type="button" class="btn-toggle"
                                                    onclick="togglePassword('password_confirmation')">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <a href="{{ route('admin.pelamar.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Pelamar
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
            margin-bottom: 20px;
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

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #2563eb;
        }

        .form-group {
            margin-bottom: 16px;
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
        }

        .input-icon .form-control {
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
            margin-top: 20px;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
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

        @media (max-width: 768px) {
            .col-md-6 {
                width: 100%;
            }

            .form-row .form-group {
                width: 100%;
            }
        }
    </style>

    <script>
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
                } else if (selected === 'SD' || selected === 'SMP' || selected === 'SMA/SMK') {
                    instansiSelect.style.display = 'none';
                    instansiInput.style.display = 'block';
                    jurusanSelect.style.display = 'none';
                    jurusanInput.style.display = 'block';
                } else {
                    instansiSelect.style.display = 'block';
                    instansiInput.style.display = 'none';
                    jurusanSelect.style.display = 'block';
                    jurusanInput.style.display = 'none';
                }
            }

            if (tingkatSelect) {
                tingkatSelect.addEventListener('change', toggleInstansiJurusan);
                toggleInstansiJurusan();
            }

            loadProvinsi();
        });

        const savedProvinsi = "{{ $pelamar->provinsi }}";
        const savedKabKota = "{{ $pelamar->kab_kota }}";
        const savedKecamatan = "{{ $pelamar->kecamatan }}";
        const savedDesa = "{{ $pelamar->desa_kelurahan }}";

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

        document.getElementById('foto_profil')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.size <= 2 * 1024 * 1024) {
                const reader = new FileReader();
                reader.onload = event => document.getElementById('profilePreview').src = event.target.result;
                reader.readAsDataURL(file);
            } else if (file) {
                alert('Ukuran file maksimal 2MB!');
                this.value = '';
            }
        });

        const API_PROVINSI = 'https://ibnux.github.io/data-indonesia/provinsi.json';
        let provinsiData = [];

        async function loadProvinsi() {
            const provSelect = document.getElementById('provinsi');
            provSelect.innerHTML = '<option value="">Memuat Provinsi...</option>';
            try {
                const res = await fetch(API_PROVINSI);
                provinsiData = await res.json();
                provSelect.innerHTML = '<option value="">-- Pilih Provinsi --</option>';
                provinsiData.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.nama;
                    opt.textContent = p.nama;
                    if (savedProvinsi === p.nama) opt.selected = true;
                    provSelect.appendChild(opt);
                });
                if (savedProvinsi) {
                    const selected = provinsiData.find(p => p.nama === savedProvinsi);
                    if (selected) await loadKabKota(selected.id);
                }
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
                    if (savedKabKota === k.nama) opt.selected = true;
                    kabSelect.appendChild(opt);
                });
                kabSelect.disabled = false;
                if (savedKabKota) {
                    const selected = data.find(k => k.nama === savedKabKota);
                    if (selected) await loadKecamatan(selected.id);
                }
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
            kecSelect.innerHTML = '<option value="">Memuat Kecamatan...</option>';
            kecSelect.disabled = true;
            document.getElementById('desa_kelurahan').innerHTML =
                '<option value="">-- Pilih Desa/Kelurahan --</option>';
            document.getElementById('desa_kelurahan').disabled = true;
            try {
                const res = await fetch(`https://ibnux.github.io/data-indonesia/kecamatan/${kabId}.json`);
                const data = await res.json();
                kecSelect.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                data.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k.nama;
                    opt.textContent = k.nama;
                    if (savedKecamatan === k.nama) opt.selected = true;
                    kecSelect.appendChild(opt);
                });
                kecSelect.disabled = false;
                if (savedKecamatan) {
                    const selected = data.find(k => k.nama === savedKecamatan);
                    if (selected) await loadDesa(selected.id);
                }
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
                    if (savedDesa === d.nama) opt.selected = true;
                    desaSelect.appendChild(opt);
                });
                desaSelect.disabled = false;
            } catch (e) {
                desaSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
            }
        }

        function resetWilayah(keepKecDesa = false) {
            document.getElementById('kab_kota').innerHTML = '<option value="">-- Pilih Kab/Kota --</option>';
            document.getElementById('kab_kota').disabled = true;
            if (!keepKecDesa) {
                document.getElementById('kecamatan').innerHTML = '<option value="">-- Pilih Kecamatan --</option>';
                document.getElementById('kecamatan').disabled = true;
                document.getElementById('desa_kelurahan').innerHTML =
                    '<option value="">-- Pilih Desa/Kelurahan --</option>';
                document.getElementById('desa_kelurahan').disabled = true;
            }
        }

        function resetDesa() {
            document.getElementById('desa_kelurahan').innerHTML = '<option value="">-- Pilih Desa/Kelurahan --</option>';
            document.getElementById('desa_kelurahan').disabled = true;
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadProvinsi();
        });
    </script>
@endsection
