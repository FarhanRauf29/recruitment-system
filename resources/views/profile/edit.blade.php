@extends('layouts.profile')

@section('title', 'Edit Profile')

@section('content')
    <div class="profile-container">
        <div class="profile-card">
            <div class="card-header">
                <div class="header-title">
                    <div class="header-icon">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div>
                        <h1>Edit Profile</h1>
                        <p>Kelola informasi data diri Anda</p>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>

            <div class="card-body">
                <div class="avatar-wrapper">
                    <div class="avatar-container">
                        @if ($pelamar && $pelamar->foto_profil)
                            <img src="{{ Storage::url($pelamar->foto_profil) }}" alt="Foto Profil" class="avatar-img"
                                id="profilePicturePreview">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=2563eb&color=fff&size=120&bold=true"
                                alt="Avatar" class="avatar-img" id="profilePicturePreview">
                        @endif
                        <div class="avatar-overlay" onclick="document.getElementById('profilePictureInput').click()">
                            <i class="fas fa-camera"></i>
                            <span>Ganti Foto</span>
                        </div>
                    </div>
                    <input type="file" id="profilePictureInput" name="foto_profil" accept="image/*"
                        style="display: none;">
                    <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                </div>

                <div class="tabs">
                    <button class="tab-btn active" data-tab="info">
                        <i class="fas fa-user"></i>
                        <span>Profil Singkat</span>
                    </button>
                    <button class="tab-btn" data-tab="password">
                        <i class="fas fa-lock"></i>
                        <span>Ubah Password</span>
                    </button>
                    <button class="tab-btn" data-tab="danger">
                        <i class="fas fa-trash-alt"></i>
                        <span>Hapus Akun</span>
                    </button>
                </div>

                <div class="tab-content">
                    <div class="tab-pane active" id="info-tab">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                            id="profileForm">
                            @csrf
                            @method('patch')

                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-id-card"></i>
                                    <h3 class="section-title">Data Diri</h3>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Nama Depan <span class="required">*</span></label>
                                        <input type="text" name="nama_depan"
                                            class="form-control @error('nama_depan') is-invalid @enderror"
                                            value="{{ old('nama_depan', $pelamar->nama_depan ?? '') }}"
                                            onkeyup="updateNamaLengkap()" required>
                                        <small class="text-muted">Nama harus sesuai dengan KTP</small>
                                        @error('nama_depan')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Nama Belakang <span class="required">*</span></label>
                                        <input type="text" name="nama_belakang"
                                            class="form-control @error('nama_belakang') is-invalid @enderror"
                                            value="{{ old('nama_belakang', $pelamar->nama_belakang ?? '') }}"
                                            onkeyup="updateNamaLengkap()" required>
                                        @error('nama_belakang')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Nama Lengkap <span class="required">*</span></label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                                        value="{{ old('nama_lengkap', $pelamar->nama_lengkap ?? ($user->name ?? '')) }}"
                                        readonly>
                                    <small class="text-muted"><i class="fas fa-info-circle"></i> Nama lengkap akan terisi
                                        otomatis</small>
                                </div>

                                <div class="form-group">
                                    <label>Email <span class="required">*</span></label>
                                    <div class="input-icon">
                                        <i class="fas fa-envelope"></i>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    @error('email')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>NIK (KTP) <span class="required">*</span></label>
                                        <div class="input-icon">
                                            <i class="fas fa-id-card"></i>
                                            <input type="text" name="nomor_identitas"
                                                class="form-control @error('nomor_identitas') is-invalid @enderror"
                                                value="{{ old('nomor_identitas', $pelamar->nomor_identitas ?? '') }}"
                                                maxlength="16" onkeypress="return onlyNumber(event)" required>
                                        </div>
                                        <small class="text-muted">16 digit angka (sesuai KTP)</small>
                                        @error('nomor_identitas')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Kewarganegaraan <span class="required">*</span></label>
                                        <select name="kewarganegaraan"
                                            class="form-control @error('kewarganegaraan') is-invalid @enderror" required>
                                            <option value="">-- Pilih Kewarganegaraan --</option>
                                            @foreach ($kewarganegaraanOptions as $value => $label)
                                                <option value="{{ $value }}"
                                                    {{ old('kewarganegaraan', $pelamar->kewarganegaraan ?? '') == $value ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('kewarganegaraan')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Tempat Lahir <span class="required">*</span></label>
                                        <div class="input-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <input type="text" name="tempat_lahir"
                                                class="form-control @error('tempat_lahir') is-invalid @enderror"
                                                value="{{ old('tempat_lahir', $pelamar->tempat_lahir ?? '') }}" required>
                                        </div>
                                        @error('tempat_lahir')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Tanggal Lahir <span class="required">*</span></label>
                                        <div class="input-icon">
                                            <i class="fas fa-calendar-alt"></i>
                                            <input type="date" name="tanggal_lahir"
                                                class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                                value="{{ old('tanggal_lahir', $pelamar->tanggal_lahir ?? '') }}"
                                                required>
                                        </div>
                                        @error('tanggal_lahir')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Jenis Kelamin <span class="required">*</span></label>
                                        <div class="gender-group">
                                            @foreach ($jenisKelaminOptions as $value => $label)
                                                <label class="gender-option">
                                                    <input type="radio" name="jenis_kelamin"
                                                        value="{{ $value }}"
                                                        {{ old('jenis_kelamin', $pelamar->jenis_kelamin ?? '') == $value ? 'checked' : '' }}
                                                        required>
                                                    <span><i
                                                            class="fas fa-{{ $value == 'Laki-laki' ? 'mars' : 'venus' }}"></i>
                                                        {{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        @error('jenis_kelamin')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Gaji Diharapkan <span class="required">*</span></label>
                                        <div class="input-group-prefix">
                                            <span class="prefix">Rp</span>
                                            <input type="text" name="gaji_diharapkan"
                                                class="form-control @error('gaji_diharapkan') is-invalid @enderror"
                                                value="{{ old('gaji_diharapkan', $pelamar->gaji_diharapkan ?? '') }}"
                                                onkeyup="formatRupiah(this)" placeholder="5.000.000" required>
                                        </div>
                                        @error('gaji_diharapkan')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Nomor Handphone <span class="required">*</span></label>
                                        <div class="input-icon">
                                            <i class="fas fa-phone"></i>
                                            <input type="tel" name="nomor_handphone"
                                                class="form-control @error('nomor_handphone') is-invalid @enderror"
                                                value="{{ old('nomor_handphone', $pelamar->nomor_handphone ?? '') }}"
                                                placeholder="081234567890" required>
                                        </div>
                                        <small class="text-muted">Bukan nomor whatsapp?</small>
                                        @error('nomor_handphone')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Nomor Whatsapp <span class="required">*</span></label>
                                        <div class="input-icon">
                                            <i class="fab fa-whatsapp"></i>
                                            <input type="tel" name="nomor_whatsapp"
                                                class="form-control @error('nomor_whatsapp') is-invalid @enderror"
                                                value="{{ old('nomor_whatsapp', $pelamar->nomor_whatsapp ?? '') }}"
                                                placeholder="081234567890" required>
                                        </div>
                                        @error('nomor_whatsapp')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <h3 class="section-title">Alamat & Domisili</h3>
                                </div>

                                <div class="form-group">
                                    <label>Alamat Domisili <span class="required">*</span></label>
                                    <textarea name="alamat_domisili" class="form-control @error('alamat_domisili') is-invalid @enderror" rows="2"
                                        placeholder="Jl. Contoh No. 123, RT 01/RW 02" required>{{ old('alamat_domisili', $pelamar->alamat_domisili ?? '') }}</textarea>
                                    @error('alamat_domisili')
                                        <div class="error">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Provinsi <span class="required">*</span></label>
                                        <select name="provinsi" id="provinsi" class="form-control" required>
                                            <option value="">-- Pilih Provinsi --</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Kabupaten/Kota <span class="required">*</span></label>
                                        <select name="kab_kota" id="kab_kota" class="form-control" required disabled>
                                            <option value="">-- Pilih Kab/Kota --</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Kecamatan</label>
                                        <select name="kecamatan" id="kecamatan" class="form-control">
                                            <option value="">-- Pilih Kecamatan --</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-6">
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
                                    <h3 class="section-title">Pendidikan & Pekerjaan</h3>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label>Pendidikan Terakhir <span class="required">*</span></label>
                                        <select name="pendidikan_terakhir"
                                            class="form-control @error('pendidikan_terakhir') is-invalid @enderror"
                                            required>
                                            <option value="">-- Pilih Jenjang --</option>
                                            @foreach ($pendidikanOptions as $value => $label)
                                                <option value="{{ $value }}"
                                                    {{ old('pendidikan_terakhir', $pelamar->pendidikan_terakhir ?? '') == $value ? 'selected' : '' }}>
                                                    {{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('pendidikan_terakhir')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-6">
                                        <label>Jurusan <span class="required">*</span></label>
                                        <div class="input-icon">
                                            <i class="fas fa-book"></i>
                                            <input type="text" name="jurusan"
                                                class="form-control @error('jurusan') is-invalid @enderror"
                                                value="{{ old('jurusan', $pelamar->jurusan ?? '') }}"
                                                placeholder="Contoh: Teknik Informatika" required>
                                        </div>
                                        @error('jurusan')
                                            <div class="error">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn-reset" onclick="resetForm()">
                                    <i class="fas fa-undo-alt"></i> Reset
                                </button>
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="password-tab">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('put')

                            <div class="form-group">
                                <label>Password Saat Ini <span class="required">*</span></label>
                                <div class="input-group-password">
                                    <input type="password" name="current_password" id="current_password"
                                        class="form-control" required>
                                    <button type="button" class="btn-toggle"
                                        onclick="togglePassword('current_password')">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Password Baru <span class="required">*</span></label>
                                <div class="input-group-password">
                                    <input type="password" name="password" id="new_password" class="form-control"
                                        required>
                                    <button type="button" class="btn-toggle" onclick="togglePassword('new_password')">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Konfirmasi Password Baru <span class="required">*</span></label>
                                <div class="input-group-password">
                                    <input type="password" name="password_confirmation" id="confirm_password"
                                        class="form-control" required>
                                    <button type="button" class="btn-toggle"
                                        onclick="togglePassword('confirm_password')">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-key"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="danger-tab">
                        <div class="danger-box">
                            <div class="danger-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h3>Hapus Akun Permanen</h3>
                            <p>Setelah akun dihapus, semua data dan riwayat lamaran Anda akan hilang secara permanen.</p>

                            <div class="warning-checklist">
                                <div class="checklist-item">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Semua data pribadi akan terhapus</span>
                                </div>
                                <div class="checklist-item">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Riwayat lamaran pekerjaan hilang</span>
                                </div>
                                <div class="checklist-item">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Email tidak dapat digunakan kembali</span>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('profile.destroy') }}"
                                onsubmit="return confirmDelete()">
                                @csrf
                                @method('delete')
                                <div class="form-group">
                                    <label>Konfirmasi Password <span class="required">*</span></label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn-delete">
                                    <i class="fas fa-trash-alt"></i> Hapus Akun Saya
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .profile-container {
            min-height: 100vh;
            background: #f0f2f5;
            padding: 20px;
        }

        .profile-card {
            max-width: 1100px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-header {
            background: #2563eb;
            color: white;
            padding: 20px 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .header-icon {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .card-header h1 {
            font-size: 1.4rem;
            font-weight: 600;
            margin: 0 0 4px 0;
        }

        .card-header p {
            font-size: 0.8rem;
            opacity: 0.9;
            margin: 0;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.85rem;
            transition: background 0.2s;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .card-body {
            padding: 28px;
        }

        .avatar-wrapper {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 24px;
        }

        .avatar-container {
            position: relative;
            display: inline-block;
        }

        .avatar-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #2563eb;
        }

        .avatar-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #2563eb;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
            color: white;
        }

        .avatar-overlay:hover {
            background: #1d4ed8;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 24px;
            gap: 4px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 10px 20px;
            background: none;
            border: none;
            color: #6b7280;
            font-size: 0.85rem;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tab-btn.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .tab-btn:hover:not(.active) {
            color: #374151;
        }

        .tab-content {
            min-height: 400px;
        }

        .tab-pane {
            display: none;
        }

        .tab-pane.active {
            display: block;
        }

        .form-section {
            margin-bottom: 28px;
            padding: 20px;
            background: #f9fafb;
            border-radius: 10px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-header i {
            font-size: 1.1rem;
            color: #2563eb;
        }

        .section-title {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -8px;
        }

        .form-group {
            padding: 0 8px;
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #374151;
        }

        .required {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.85rem;
            transition: border-color 0.2s;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .form-control:disabled,
        .form-control[readonly] {
            background: #f3f4f6;
            cursor: not-allowed;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 0.85rem;
        }

        .input-icon .form-control {
            padding-left: 32px;
        }

        .error {
            color: #ef4444;
            font-size: 0.7rem;
            margin-top: 4px;
        }

        .text-muted {
            color: #6b7280;
            font-size: 0.7rem;
            display: block;
            margin-top: 4px;
        }

        .gender-group {
            display: flex;
            gap: 12px;
            padding: 6px 0;
        }

        .gender-option {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 6px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            transition: all 0.2s;
            background: white;
        }

        .gender-option input {
            margin-right: 8px;
        }

        .gender-option:has(input:checked) {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .input-group-prefix {
            display: flex;
            align-items: stretch;
        }

        .input-group-prefix .prefix {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-right: none;
            border-radius: 6px 0 0 6px;
            padding: 8px 12px;
            font-size: 0.85rem;
            color: #6b7280;
        }

        .input-group-prefix .form-control {
            border-radius: 0 6px 6px 0;
        }

        .input-group-password {
            display: flex;
            align-items: stretch;
        }

        .input-group-password .form-control {
            border-radius: 6px 0 0 6px;
            flex: 1;
        }

        .btn-toggle {
            background: #f3f4f6;
            border: 1px solid #d1d5db;
            border-left: none;
            border-radius: 0 6px 6px 0;
            padding: 0 12px;
            cursor: pointer;
            color: #6b7280;
        }

        .btn-toggle:hover {
            background: #e5e7eb;
        }

        .form-actions {
            margin-top: 24px;
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-save,
        .btn-reset {
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: opacity 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-save {
            background: #2563eb;
            color: white;
        }

        .btn-save:hover {
            background: #1d4ed8;
        }

        .btn-reset {
            background: #9ca3af;
            color: white;
        }

        .btn-reset:hover {
            background: #6b7280;
        }

        .danger-box {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 24px;
            text-align: center;
        }

        .danger-icon {
            width: 48px;
            height: 48px;
            background: #fee2e2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }

        .danger-icon i {
            font-size: 1.5rem;
            color: #dc2626;
        }

        .danger-box h3 {
            color: #dc2626;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .danger-box p {
            color: #6b7280;
            margin-bottom: 16px;
            font-size: 0.8rem;
        }

        .warning-checklist {
            background: white;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 16px;
            text-align: left;
        }

        .checklist-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 0;
            color: #dc2626;
            font-size: 0.8rem;
        }

        .btn-delete {
            background: #dc2626;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-delete:hover {
            background: #b91c1c;
        }

        @media (max-width: 640px) {
            .profile-container {
                padding: 10px;
            }

            .card-body {
                padding: 16px;
            }

            .card-header {
                padding: 16px;
            }

            .card-header h1 {
                font-size: 1.1rem;
            }

            .form-group.col-6 {
                width: 100%;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-save,
            .btn-reset {
                width: 100%;
                justify-content: center;
            }

            .tab-btn {
                flex: 1;
                justify-content: center;
                padding: 8px;
            }

            .tab-btn span {
                display: none;
            }

            .gender-group {
                flex-direction: column;
            }

            .form-section {
                padding: 16px;
            }
        }

        @media (min-width: 641px) {
            .form-group.col-6 {
                width: 50%;
            }
        }
    </style>

    <script>
        function updateNamaLengkap() {
            const namaDepan = document.getElementById('nama_depan')?.value || '';
            const namaBelakang = document.getElementById('nama_belakang')?.value || '';
            const namaLengkap = (namaDepan + ' ' + namaBelakang).trim();
            const namaLengkapField = document.getElementById('nama_lengkap');
            if (namaLengkapField) namaLengkapField.value = namaLengkap;
        }

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

        function resetForm() {
            if (confirm('Reset semua perubahan yang belum disimpan?')) {
                location.reload();
            }
        }

        function confirmDelete() {
            return confirm('PERINGATAN! Tindakan ini akan menghapus semua data Anda secara permanen. Apakah Anda yakin?');
        }

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                document.getElementById(this.dataset.tab + '-tab').classList.add('active');
            });
        });

        document.getElementById('profilePictureInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.size <= 2 * 1024 * 1024) {
                if (!file.type.match('image.*')) {
                    alert('Hanya file gambar yang diperbolehkan!');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = event => document.getElementById('profilePicturePreview').src = event.target.result;
                reader.readAsDataURL(file);
            } else if (file) {
                alert('Ukuran file maksimal 2MB!');
                this.value = '';
            }
        });

        const API_PROVINSI = 'https://ibnux.github.io/data-indonesia/provinsi.json';
        let provinsiData = [];
        const savedProvinsi = "{{ old('provinsi', $pelamar->provinsi ?? '') }}";
        const savedKabKota = "{{ old('kab_kota', $pelamar->kab_kota ?? '') }}";
        const savedKecamatan = "{{ old('kecamatan', $pelamar->kecamatan ?? '') }}";
        const savedDesa = "{{ old('desa_kelurahan', $pelamar->desa_kelurahan ?? '') }}";

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
                    if (savedDesa === d.nama) opt.selected = true;
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

        document.addEventListener('DOMContentLoaded', function() {
            loadProvinsi();
            updateNamaLengkap();
        });
    </script>
@endsection
