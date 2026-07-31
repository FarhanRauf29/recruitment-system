@extends('layouts.pelamar')

@section('title', 'Profil Saya')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="bg-primary rounded" style="width: 4px; height: 24px;"></div>
                        <h4 class="fw-bold mb-0" style="color: #1e293b;">Profil Saya</h4>
                    </div>
                    <p class="text-muted small ms-2">Lengkapi data diri Anda untuk meningkatkan peluang diterima</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success border-0 bg-success bg-opacity-10 rounded-3 py-3 px-4 mb-4"
                        style="border-left: 3px solid #10b981;">
                        <div class="d-flex gap-3 align-items-center">
                            <i class="fas fa-check-circle text-success fs-5"></i>
                            <p class="mb-0 small">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger border-0 bg-danger bg-opacity-10 rounded-3 py-3 px-4 mb-4"
                        style="border-left: 3px solid #ef4444;">
                        <div class="d-flex gap-3 align-items-center">
                            <i class="fas fa-exclamation-circle text-danger fs-5"></i>
                            <p class="mb-0 small">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                        <ul class="nav nav-tabs border-0 gap-2" id="profileTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-medium rounded-pill px-4" data-bs-toggle="tab"
                                    data-bs-target="#profil" type="button">
                                    <i class="fas fa-user me-2"></i>Profil Singkat
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-medium rounded-pill px-4" data-bs-toggle="tab"
                                    data-bs-target="#pendidikan" type="button">
                                    <i class="fas fa-graduation-cap me-2"></i>Pendidikan
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-medium rounded-pill px-4" data-bs-toggle="tab"
                                    data-bs-target="#organisasi" type="button">
                                    <i class="fas fa-users me-2"></i>Organisasi
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-medium rounded-pill px-4" data-bs-toggle="tab"
                                    data-bs-target="#magang" type="button">
                                    <i class="fas fa-briefcase me-2"></i>Magang/PKL
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-medium rounded-pill px-4" data-bs-toggle="tab"
                                    data-bs-target="#pengalaman" type="button">
                                    <i class="fas fa-history me-2"></i>Pengalaman
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-medium rounded-pill px-4" data-bs-toggle="tab"
                                    data-bs-target="#bahasa" type="button">
                                    <i class="fas fa-language me-2"></i>Bahasa
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-medium rounded-pill px-4" data-bs-toggle="tab"
                                    data-bs-target="#keahlian" type="button">
                                    <i class="fas fa-code me-2"></i>Keahlian
                                </button>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content">
                            <!-- 1. PROFIL SINGKAT -->
                            <div class="tab-pane fade show active" id="profil">
                                <form action="{{ route('pelamar.profil.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nama Depan</label>
                                            <input type="text" name="nama_depan" class="form-control rounded-3"
                                                value="{{ old('nama_depan', $pelamar->nama_depan) }}"
                                                placeholder="Nama depan Anda">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nama Belakang</label>
                                            <input type="text" name="nama_belakang" class="form-control rounded-3"
                                                value="{{ old('nama_belakang', $pelamar->nama_belakang) }}"
                                                placeholder="Nama belakang Anda">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Jenis Kelamin</label>
                                            <select name="jenis_kelamin" class="form-select rounded-3">
                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                @foreach ($jenisKelaminOptions as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ old('jenis_kelamin', $pelamar->jenis_kelamin) == $value ? 'selected' : '' }}>
                                                        {{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Tempat Lahir</label>
                                            <input type="text" name="tempat_lahir" class="form-control rounded-3"
                                                value="{{ old('tempat_lahir', $pelamar->tempat_lahir) }}"
                                                placeholder="Kota tempat lahir">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Tanggal Lahir</label>
                                            <input type="date" name="tanggal_lahir" class="form-control rounded-3"
                                                value="{{ old('tanggal_lahir', $pelamar->tanggal_lahir?->format('Y-m-d')) }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Kewarganegaraan</label>
                                            <select name="kewarganegaraan" class="form-select rounded-3">
                                                @foreach ($kewarganegaraanOptions as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ old('kewarganegaraan', $pelamar->kewarganegaraan) == $value ? 'selected' : '' }}>
                                                        {{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nomor Handphone</label>
                                            <input type="tel" name="nomor_handphone" class="form-control rounded-3"
                                                value="{{ old('nomor_handphone', $pelamar->nomor_handphone) }}"
                                                placeholder="Contoh: 081234567890">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nomor WhatsApp</label>
                                            <input type="tel" name="nomor_whatsapp" class="form-control rounded-3"
                                                value="{{ old('nomor_whatsapp', $pelamar->nomor_whatsapp) }}"
                                                placeholder="Contoh: 081234567890">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">NIK (KTP)</label>
                                            <input type="text" name="nomor_identitas" class="form-control rounded-3"
                                                value="{{ old('nomor_identitas', $pelamar->nomor_identitas) }}"
                                                maxlength="16" placeholder="16 digit angka sesuai KTP">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fw-semibold">Alamat Domisili</label>
                                            <textarea name="alamat_domisili" class="form-control rounded-3" rows="3"
                                                placeholder="Alamat lengkap domisili Anda">{{ old('alamat_domisili', $pelamar->alamat_domisili) }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Provinsi</label>
                                            <select name="provinsi" id="provinsi" class="form-select rounded-3">
                                                <option value="{{ $pelamar->provinsi }}">
                                                    {{ $pelamar->provinsi ?? '-- Pilih Provinsi --' }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Kabupaten/Kota</label>
                                            <select name="kab_kota" id="kab_kota" class="form-select rounded-3"
                                                disabled>
                                                <option value="{{ $pelamar->kab_kota }}">
                                                    {{ $pelamar->kab_kota ?? '-- Pilih Kab/Kota --' }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Kecamatan</label>
                                            <select name="kecamatan" id="kecamatan" class="form-select rounded-3">
                                                <option value="{{ $pelamar->kecamatan }}">
                                                    {{ $pelamar->kecamatan ?? '-- Pilih Kecamatan --' }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Desa/Kelurahan</label>
                                            <select name="desa_kelurahan" id="desa_kelurahan"
                                                class="form-select rounded-3">
                                                <option value="{{ $pelamar->desa_kelurahan }}">
                                                    {{ $pelamar->desa_kelurahan ?? '-- Pilih Desa/Kelurahan --' }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Pendidikan Terakhir</label>
                                            <select name="pendidikan_terakhir" id="pendidikan_terakhir_pelamar"
                                                class="form-select rounded-3">
                                                <option value="">-- Pilih Jenjang --</option>
                                                @foreach ($pendidikanOptions as $value => $label)
                                                    <option value="{{ $value }}"
                                                        {{ old('pendidikan_terakhir', $pelamar->pendidikan_terakhir) == $value ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Nama Instansi</label>
                                            <div class="input-icon">
                                                <i class="fas fa-university text-primary"></i>
                                                <select name="instansi[]" id="instansi_select_pelamar"
                                                    class="form-select rounded-3" style="padding-left: 36px;">
                                                    <option value="">-- Pilih Instansi --</option>
                                                    @foreach ($instansiOptions as $value => $label)
                                                        <option value="{{ $value }}"
                                                            {{ old('instansi', $pelamar->instansi) == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="instansi[]" id="instansi_input_pelamar"
                                                    class="form-control rounded-3"
                                                    style="display: none; padding-left: 36px;"
                                                    placeholder="Masukkan nama instansi"
                                                    value="{{ old('instansi', $pelamar->instansi) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Jurusan</label>
                                            <div class="input-icon">
                                                <i class="fas fa-graduation-cap text-primary"></i>
                                                <select name="jurusan[]" id="jurusan_select_pelamar"
                                                    class="form-select rounded-3" style="padding-left: 36px;">
                                                    <option value="">-- Pilih Jurusan --</option>
                                                    @foreach ($jurusanOptions as $value => $label)
                                                        <option value="{{ $value }}"
                                                            {{ old('jurusan', $pelamar->jurusan) == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="text" name="jurusan[]" id="jurusan_input_pelamar"
                                                    class="form-control rounded-3"
                                                    style="display: none; padding-left: 36px;"
                                                    placeholder="Masukkan nama jurusan"
                                                    value="{{ old('jurusan', $pelamar->jurusan) }}">
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Gaji yang Diharapkan</label>
                                            <div class="input-group">
                                                <span
                                                    class="input-group-text bg-light border-end-0 rounded-start-3">Rp</span>
                                                <input type="text" name="gaji_diharapkan"
                                                    class="form-control border-start-0"
                                                    value="{{ old('gaji_diharapkan', $pelamar->gaji_diharapkan) }}"
                                                    onkeyup="formatRupiah(this)" placeholder="5.000.000">
                                                <span
                                                    class="input-group-text bg-light border-start-0 rounded-end-3">/bulan</span>
                                            </div>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <div class="d-flex gap-3">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary rounded-pill px-4 py-2"
                                            onclick="resetForm()">
                                            <i class="fas fa-undo-alt me-2"></i>Reset
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- 2. PENDIDIKAN -->
                            <div class="tab-pane fade" id="pendidikan">
                                <div class="card border-0 bg-light rounded-3 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i
                                                class="fas fa-plus-circle me-2 text-primary"></i>Tambah Pendidikan</h6>
                                        <form action="{{ route('pelamar.profil.pendidikan.store') }}" method="POST"
                                            onsubmit="return stayOnTab('pendidikan')">
                                            @csrf
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Tingkat</label>
                                                    <select name="tingkat" id="tingkat_select_pendidikan"
                                                        class="form-select rounded-3" required>
                                                        <option value="">-- Pilih --</option>
                                                        <option value="SD">SD</option>
                                                        <option value="SMP">SMP</option>
                                                        <option value="SMA/SMK">SMA/SMK</option>
                                                        <option value="D3">D3</option>
                                                        <option value="D4">D4</option>
                                                        <option value="S1">S1</option>
                                                        <option value="S2">S2</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small text-muted">Nama Instansi</label>
                                                    <select name="nama_instansi[]" id="instansi_select_pendidikan"
                                                        class="form-select rounded-3">
                                                        <option value="">-- Pilih Instansi --</option>
                                                        @foreach ($instansiOptions as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" name="nama_instansi[]"
                                                        id="instansi_input_pendidikan" class="form-control rounded-3"
                                                        style="display: none;" placeholder="Masukkan nama instansi">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Jurusan</label>
                                                    <select name="jurusan[]" id="jurusan_select_pendidikan"
                                                        class="form-select rounded-3">
                                                        <option value="">-- Pilih Jurusan --</option>
                                                        @foreach ($jurusanOptions as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" name="jurusan[]" id="jurusan_input_pendidikan"
                                                        class="form-control rounded-3" style="display: none;"
                                                        placeholder="Masukkan nama jurusan">
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label small text-muted">Masuk</label>
                                                    <input type="text" name="tahun_masuk"
                                                        class="form-control rounded-3" placeholder="Tahun">
                                                </div>
                                                <div class="col-md-1">
                                                    <label class="form-label small text-muted">Lulus</label>
                                                    <input type="text" name="tahun_lulus"
                                                        class="form-control rounded-3" placeholder="Tahun" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Nilai/IPK</label>
                                                    <input type="text" name="nilai" class="form-control rounded-3"
                                                        placeholder="Contoh: 3.50">
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="submit" class="btn btn-primary rounded-3 w-100"><i
                                                            class="fas fa-plus me-1"></i> Tambah</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="px-3 py-2">Tingkat</th>
                                                <th class="px-3 py-2">Instansi</th>
                                                <th class="px-3 py-2">Jurusan</th>
                                                <th class="px-3 py-2">Tahun</th>
                                                <th class="px-3 py-2">Nilai/IPK</th>
                                                <th class="text-center px-3 py-2" style="width: 100px">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($pendidikan as $item)
                                                <tr>
                                                    <td class="px-3 py-2">{{ $item->tingkat }}</td>
                                                    <td class="px-3 py-2">{{ $item->nama_instansi }}</td>
                                                    <td class="px-3 py-2">{{ $item->jurusan ?? '-' }}</td>
                                                    <td class="px-3 py-2">
                                                        {{ $item->tahun_masuk ? $item->tahun_masuk . '-' : '' }}{{ $item->tahun_lulus }}
                                                    </td>
                                                    <td class="px-3 py-2">{{ $item->nilai ?? '-' }}</td>
                                                    <td class="text-center px-3 py-2">
                                                        <div class="d-flex gap-1 justify-content-center">
                                                            <button class="btn btn-sm btn-warning rounded-pill"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editPendidikanModal{{ $item->id }}"
                                                                title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form
                                                                action="{{ route('pelamar.profil.pendidikan.destroy', $item->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return stayOnTab('pendidikan')">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-sm btn-danger rounded-pill"
                                                                    onclick="return confirm('Hapus data ini?')"
                                                                    title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada data
                                                        pendidikan</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @foreach ($pendidikan as $item)
                                    <div class="modal fade" id="editPendidikanModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4">
                                                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit
                                                        Pendidikan</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pelamar.profil.pendidikan.update', $item->id) }}"
                                                    method="POST" onsubmit="return stayOnTab('pendidikan')">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Tingkat</label>
                                                            <select name="tingkat" class="form-select rounded-3" required>
                                                                <option value="SD"
                                                                    {{ $item->tingkat == 'SD' ? 'selected' : '' }}>SD
                                                                </option>
                                                                <option value="SMP"
                                                                    {{ $item->tingkat == 'SMP' ? 'selected' : '' }}>SMP
                                                                </option>
                                                                <option value="SMA/SMK"
                                                                    {{ $item->tingkat == 'SMA/SMK' ? 'selected' : '' }}>
                                                                    SMA/SMK</option>
                                                                <option value="D3"
                                                                    {{ $item->tingkat == 'D3' ? 'selected' : '' }}>D3
                                                                </option>
                                                                <option value="D4"
                                                                    {{ $item->tingkat == 'D4' ? 'selected' : '' }}>D4
                                                                </option>
                                                                <option value="S1"
                                                                    {{ $item->tingkat == 'S1' ? 'selected' : '' }}>S1
                                                                </option>
                                                                <option value="S2"
                                                                    {{ $item->tingkat == 'S2' ? 'selected' : '' }}>S2
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nama Instansi</label>
                                                            @if (in_array($item->tingkat, ['SD', 'SMP', 'SMA/SMK']))
                                                                <input type="text" name="nama_instansi"
                                                                    class="form-control rounded-3"
                                                                    value="{{ $item->nama_instansi }}"
                                                                    placeholder="Masukkan nama instansi">
                                                            @else
                                                                <select name="nama_instansi"
                                                                    class="form-select rounded-3">
                                                                    <option value="">-- Pilih Instansi --</option>
                                                                    @foreach ($instansiOptions as $value => $label)
                                                                        <option value="{{ $value }}"
                                                                            {{ $item->nama_instansi == $value ? 'selected' : '' }}>
                                                                            {{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Jurusan</label>
                                                            @if (in_array($item->tingkat, ['SD', 'SMP', 'SMA/SMK']))
                                                                <input type="text" name="jurusan"
                                                                    class="form-control rounded-3"
                                                                    value="{{ $item->jurusan }}"
                                                                    placeholder="Masukkan nama jurusan (opsional)">
                                                            @else
                                                                <select name="jurusan" class="form-select rounded-3">
                                                                    <option value="">-- Pilih Jurusan --</option>
                                                                    @foreach ($jurusanOptions as $value => $label)
                                                                        <option value="{{ $value }}"
                                                                            {{ $item->jurusan == $value ? 'selected' : '' }}>
                                                                            {{ $label }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @endif
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Tahun Masuk</label>
                                                                <input type="text" name="tahun_masuk"
                                                                    class="form-control rounded-3"
                                                                    value="{{ $item->tahun_masuk }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Tahun Lulus</label>
                                                                <input type="text" name="tahun_lulus"
                                                                    class="form-control rounded-3"
                                                                    value="{{ $item->tahun_lulus }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nilai/IPK</label>
                                                            <input type="text" name="nilai"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->nilai }}"
                                                                placeholder="Contoh: 3.50 atau 85.5">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pb-4 px-4">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary rounded-pill px-4"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit"
                                                            class="btn btn-primary rounded-pill px-4">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- 3. ORGANISASI -->
                            <div class="tab-pane fade" id="organisasi">
                                <div class="card border-0 bg-light rounded-3 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i
                                                class="fas fa-plus-circle me-2 text-primary"></i>Tambah Organisasi</h6>
                                        <form action="{{ route('pelamar.profil.organisasi.store') }}" method="POST"
                                            onsubmit="return stayOnTab('organisasi')">
                                            @csrf
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label small text-muted">Nama Organisasi</label>
                                                    <input type="text" name="nama_organisasi"
                                                        class="form-control rounded-3" placeholder="Nama Organisasi"
                                                        required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small text-muted">Jabatan</label>
                                                    <input type="text" name="jabatan" class="form-control rounded-3"
                                                        placeholder="Jabatan" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Tahun</label>
                                                    <input type="text" name="tahun" class="form-control rounded-3"
                                                        placeholder="Tahun" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-primary rounded-3 w-100"><i
                                                            class="fas fa-plus me-1"></i> Tambah</button>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <textarea name="deskripsi" class="form-control rounded-3" rows="2"
                                                    placeholder="Deskripsi kegiatan (opsional)"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="px-3 py-2">Organisasi</th>
                                                <th class="px-3 py-2">Jabatan</th>
                                                <th class="px-3 py-2">Tahun</th>
                                                <th class="text-center px-3 py-2" style="width: 120px">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($organisasi as $item)
                                                <tr>
                                                    <td class="px-3 py-2">{{ $item->nama_organisasi }}</td>
                                                    <td class="px-3 py-2">{{ $item->jabatan }}</td>
                                                    <td class="px-3 py-2">{{ $item->tahun }}</td>
                                                    <td class="text-center px-3 py-2">
                                                        <div class="d-flex gap-1 justify-content-center">
                                                            <button class="btn btn-sm btn-warning rounded-pill"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editOrganisasiModal{{ $item->id }}"
                                                                title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form
                                                                action="{{ route('pelamar.profil.organisasi.destroy', $item->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return stayOnTab('organisasi')">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-sm btn-danger rounded-pill"
                                                                    onclick="return confirm('Hapus data ini?')"
                                                                    title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data
                                                        organisasi</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @foreach ($organisasi as $item)
                                    <div class="modal fade" id="editOrganisasiModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4">
                                                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit
                                                        Organisasi</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pelamar.profil.organisasi.update', $item->id) }}"
                                                    method="POST" onsubmit="return stayOnTab('organisasi')">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nama Organisasi</label>
                                                            <input type="text" name="nama_organisasi"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->nama_organisasi }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Jabatan</label>
                                                            <input type="text" name="jabatan"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->jabatan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Tahun</label>
                                                            <input type="text" name="tahun"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->tahun }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Deskripsi</label>
                                                            <textarea name="deskripsi" class="form-control rounded-3" rows="3">{{ $item->deskripsi }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pb-4 px-4">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary rounded-pill px-4"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit"
                                                            class="btn btn-primary rounded-pill px-4">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- 4. MAGANG/PKL -->
                            <div class="tab-pane fade" id="magang">
                                <div class="card border-0 bg-light rounded-3 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i
                                                class="fas fa-plus-circle me-2 text-primary"></i>Tambah Magang/PKL</h6>
                                        <form action="{{ route('pelamar.profil.magang.store') }}" method="POST"
                                            onsubmit="return stayOnTab('magang')">
                                            @csrf
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-3">
                                                    <label class="form-label small text-muted">Perusahaan</label>
                                                    <input type="text" name="perusahaan"
                                                        class="form-control rounded-3" placeholder="Nama Perusahaan"
                                                        required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Posisi</label>
                                                    <input type="text" name="posisi" class="form-control rounded-3"
                                                        placeholder="Posisi" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Durasi (bulan)</label>
                                                    <input type="text" name="durasi" class="form-control rounded-3"
                                                        placeholder="Durasi" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Lokasi</label>
                                                    <input type="text" name="lokasi" class="form-control rounded-3"
                                                        placeholder="Lokasi">
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-primary rounded-3 w-100"><i
                                                            class="fas fa-plus me-1"></i> Tambah</button>
                                                </div>
                                            </div>
                                            <div class="row g-2 mt-2">
                                                <div class="col-md-6"><input type="date" name="tanggal_mulai"
                                                        class="form-control rounded-3" placeholder="Tanggal Mulai"></div>
                                                <div class="col-md-6"><input type="date" name="tanggal_selesai"
                                                        class="form-control rounded-3" placeholder="Tanggal Selesai">
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <textarea name="deskripsi" class="form-control rounded-3" rows="2"
                                                    placeholder="Deskripsi pekerjaan (opsional)"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="px-3 py-2">Perusahaan</th>
                                                <th class="px-3 py-2">Posisi</th>
                                                <th class="px-3 py-2">Durasi</th>
                                                <th class="px-3 py-2">Lokasi</th>
                                                <th class="text-center px-3 py-2" style="width: 120px">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($magang as $item)
                                                <tr>
                                                    <td class="px-3 py-2">{{ $item->perusahaan }}</td>
                                                    <td class="px-3 py-2">{{ $item->posisi }}</td>
                                                    <td class="px-3 py-2">{{ $item->durasi }} bulan</td>
                                                    <td class="px-3 py-2">{{ $item->lokasi ?? '-' }}</td>
                                                    <td class="text-center px-3 py-2">
                                                        <div class="d-flex gap-1 justify-content-center">
                                                            <button class="btn btn-sm btn-warning rounded-pill"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editMagangModal{{ $item->id }}"
                                                                title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form
                                                                action="{{ route('pelamar.profil.magang.destroy', $item->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return stayOnTab('magang')">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-sm btn-danger rounded-pill"
                                                                    onclick="return confirm('Hapus data ini?')"
                                                                    title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data
                                                        magang</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @foreach ($magang as $item)
                                    <div class="modal fade" id="editMagangModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4">
                                                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit
                                                        Magang/PKL</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pelamar.profil.magang.update', $item->id) }}"
                                                    method="POST" onsubmit="return stayOnTab('magang')">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Perusahaan</label>
                                                            <input type="text" name="perusahaan"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->perusahaan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Posisi</label>
                                                            <input type="text" name="posisi"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->posisi }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Durasi (bulan)</label>
                                                            <input type="text" name="durasi"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->durasi }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Lokasi</label>
                                                            <input type="text" name="lokasi"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->lokasi }}">
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Tanggal Mulai</label>
                                                                <input type="date" name="tanggal_mulai"
                                                                    class="form-control rounded-3"
                                                                    value="{{ $item->tanggal_mulai }}">
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Tanggal
                                                                    Selesai</label>
                                                                <input type="date" name="tanggal_selesai"
                                                                    class="form-control rounded-3"
                                                                    value="{{ $item->tanggal_selesai }}">
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Deskripsi</label>
                                                            <textarea name="deskripsi" class="form-control rounded-3" rows="3">{{ $item->deskripsi }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pb-4 px-4">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary rounded-pill px-4"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit"
                                                            class="btn btn-primary rounded-pill px-4">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- 5. PENGALAMAN KERJA -->
                            <div class="tab-pane fade" id="pengalaman">
                                <div class="card border-0 bg-light rounded-3 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i
                                                class="fas fa-plus-circle me-2 text-primary"></i>Tambah Pengalaman Kerja
                                        </h6>
                                        <form action="{{ route('pelamar.profil.pengalaman.store') }}" method="POST"
                                            onsubmit="return stayOnTab('pengalaman')">
                                            @csrf
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-3">
                                                    <label class="form-label small text-muted">Perusahaan</label>
                                                    <input type="text" name="perusahaan"
                                                        class="form-control rounded-3" placeholder="Nama Perusahaan"
                                                        required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Jabatan</label>
                                                    <input type="text" name="jabatan" class="form-control rounded-3"
                                                        placeholder="Jabatan" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Lokasi</label>
                                                    <input type="text" name="lokasi" class="form-control rounded-3"
                                                        placeholder="Lokasi">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Tahun Masuk</label>
                                                    <input type="text" name="tahun_masuk"
                                                        class="form-control rounded-3" placeholder="Tahun" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-primary rounded-3 w-100"><i
                                                            class="fas fa-plus me-1"></i> Tambah</button>
                                                </div>
                                            </div>
                                            <div class="row g-2 mt-2">
                                                <div class="col-md-6"><input type="text" name="tahun_keluar"
                                                        class="form-control rounded-3" placeholder="Tahun Keluar"></div>
                                                <div class="col-md-6">
                                                    <div class="form-check"><input type="checkbox" name="is_current"
                                                            class="form-check-input" id="is_current"
                                                            value="1"><label class="form-check-label"
                                                            for="is_current">Masih bekerja di sini</label></div>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <textarea name="deskripsi" class="form-control rounded-3" rows="2"
                                                    placeholder="Deskripsi pekerjaan (opsional)"></textarea>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="px-3 py-2">Perusahaan</th>
                                                <th class="px-3 py-2">Jabatan</th>
                                                <th class="px-3 py-2">Tahun</th>
                                                <th class="px-3 py-2">Lokasi</th>
                                                <th class="text-center px-3 py-2" style="width: 120px">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($pengalaman as $item)
                                                <tr>
                                                    <td class="px-3 py-2">{{ $item->perusahaan }}</td>
                                                    <td class="px-3 py-2">{{ $item->jabatan }}</td>
                                                    <td class="px-3 py-2">{{ $item->tahun_masuk }} -
                                                        {{ $item->is_current ? 'Sekarang' : $item->tahun_keluar }}</td>
                                                    <td class="px-3 py-2">{{ $item->lokasi ?? '-' }}</td>
                                                    <td class="text-center px-3 py-2">
                                                        <div class="d-flex gap-1 justify-content-center">
                                                            <button class="btn btn-sm btn-warning rounded-pill"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editPengalamanModal{{ $item->id }}"
                                                                title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form
                                                                action="{{ route('pelamar.profil.pengalaman.destroy', $item->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return stayOnTab('pengalaman')">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-sm btn-danger rounded-pill"
                                                                    onclick="return confirm('Hapus data ini?')"
                                                                    title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada data
                                                        pengalaman kerja</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @foreach ($pengalaman as $item)
                                    <div class="modal fade" id="editPengalamanModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4">
                                                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit
                                                        Pengalaman Kerja</h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pelamar.profil.pengalaman.update', $item->id) }}"
                                                    method="POST" onsubmit="return stayOnTab('pengalaman')">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Perusahaan</label>
                                                            <input type="text" name="perusahaan"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->perusahaan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Jabatan</label>
                                                            <input type="text" name="jabatan"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->jabatan }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Lokasi</label>
                                                            <input type="text" name="lokasi"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->lokasi }}">
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Tahun Masuk</label>
                                                                <input type="text" name="tahun_masuk"
                                                                    class="form-control rounded-3"
                                                                    value="{{ $item->tahun_masuk }}" required>
                                                            </div>
                                                            <div class="col-md-6 mb-3">
                                                                <label class="form-label fw-semibold">Tahun Keluar</label>
                                                                <input type="text" name="tahun_keluar"
                                                                    class="form-control rounded-3"
                                                                    value="{{ $item->tahun_keluar }}"
                                                                    {{ $item->is_current ? 'disabled' : '' }}>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <div class="form-check">
                                                                <input type="checkbox" name="is_current"
                                                                    class="form-check-input"
                                                                    id="is_current_edit{{ $item->id }}"
                                                                    value="1"
                                                                    {{ $item->is_current ? 'checked' : '' }}
                                                                    onchange="toggleTahunKeluar(this, {{ $item->id }})">
                                                                <label class="form-check-label"
                                                                    for="is_current_edit{{ $item->id }}">
                                                                    Masih bekerja di sini
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Deskripsi</label>
                                                            <textarea name="deskripsi" class="form-control rounded-3" rows="3">{{ $item->deskripsi }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pb-4 px-4">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary rounded-pill px-4"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit"
                                                            class="btn btn-primary rounded-pill px-4">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- 6. BAHASA -->
                            <div class="tab-pane fade" id="bahasa">
                                <div class="card border-0 bg-light rounded-3 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i
                                                class="fas fa-plus-circle me-2 text-primary"></i>Tambah Bahasa</h6>
                                        <form action="{{ route('pelamar.profil.bahasa.store') }}" method="POST"
                                            enctype="multipart/form-data" onsubmit="return stayOnTab('bahasa')">
                                            @csrf
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-3">
                                                    <label class="form-label small text-muted">Nama Bahasa</label>
                                                    <select name="nama_bahasa" class="form-select rounded-3" required>
                                                        <option value="">-- Pilih Bahasa --</option>
                                                        @foreach ($bahasaOptions as $value => $label)
                                                            <option value="{{ $value }}">{{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Tingkat</label>
                                                    <select name="tingkat" class="form-select rounded-3" required>
                                                        <option value="Pasif">Pasif</option>
                                                        <option value="Aktif">Aktif</option>
                                                        <option value="Lancar">Lancar</option>
                                                        <option value="Native">Native</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small text-muted">Sertifikat</label>
                                                    <input type="file" name="sertifikat_file"
                                                        class="form-control rounded-3" accept=".pdf,.jpg,.jpeg,.png">

                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-primary rounded-3 w-100"><i
                                                            class="fas fa-plus me-1"></i> Tambah</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="px-3 py-2">Bahasa</th>
                                                <th class="px-3 py-2">Tingkat</th>
                                                <th class="px-3 py-2">Sertifikat</th>
                                                <th class="text-center px-3 py-2" style="width: 120px">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($bahasa as $item)
                                                <tr>
                                                    <td class="px-3 py-2">{{ $item->nama_bahasa }}</td>
                                                    <td class="px-3 py-2"><span
                                                            class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">{{ $item->tingkat }}</span>
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        @if ($item->sertifikat)
                                                            <a href="{{ Storage::url($item->sertifikat) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-primary rounded-pill"><i
                                                                class="fas fa-download me-1"></i>Lihat</a>@else<span
                                                                class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center px-3 py-2">
                                                        <div class="d-flex gap-1 justify-content-center">
                                                            <button class="btn btn-sm btn-warning rounded-pill"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editBahasaModal{{ $item->id }}"
                                                                title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form
                                                                action="{{ route('pelamar.profil.bahasa.destroy', $item->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return stayOnTab('bahasa')">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-sm btn-danger rounded-pill"
                                                                    onclick="return confirm('Hapus data ini?')"
                                                                    title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada data
                                                        bahasa</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @foreach ($bahasa as $item)
                                    <div class="modal fade" id="editBahasaModal{{ $item->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4">
                                                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Bahasa
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pelamar.profil.bahasa.update', $item->id) }}"
                                                    method="POST" enctype="multipart/form-data"
                                                    onsubmit="return stayOnTab('bahasa')">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nama Bahasa</label>
                                                            <select name="nama_bahasa" class="form-select rounded-3"
                                                                required>
                                                                @foreach ($bahasaOptions as $value => $label)
                                                                    <option value="{{ $value }}"
                                                                        {{ $item->nama_bahasa == $value ? 'selected' : '' }}>
                                                                        {{ $label }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Tingkat</label>
                                                            <select name="tingkat" class="form-select rounded-3" required>
                                                                <option value="Pasif"
                                                                    {{ $item->tingkat == 'Pasif' ? 'selected' : '' }}>
                                                                    Pasif</option>
                                                                <option value="Aktif"
                                                                    {{ $item->tingkat == 'Aktif' ? 'selected' : '' }}>
                                                                    Aktif</option>
                                                                <option value="Lancar"
                                                                    {{ $item->tingkat == 'Lancar' ? 'selected' : '' }}>
                                                                    Lancar</option>
                                                                <option value="Native"
                                                                    {{ $item->tingkat == 'Native' ? 'selected' : '' }}>
                                                                    Native</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Sertifikat</label>
                                                            @if ($item->sertifikat)
                                                                <div class="mb-2">
                                                                    <a href="{{ Storage::url($item->sertifikat) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">Lihat
                                                                        Sertifikat</a>
                                                                </div>
                                                            @endif
                                                            <input type="file" name="sertifikat_file"
                                                                class="form-control rounded-3"
                                                                accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pb-4 px-4">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary rounded-pill px-4"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit"
                                                            class="btn btn-primary rounded-pill px-4">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- 7. KEAHLIAN -->
                            <div class="tab-pane fade" id="keahlian">
                                <div class="card border-0 bg-light rounded-3 mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="fw-bold mb-3"><i
                                                class="fas fa-plus-circle me-2 text-primary"></i>Tambah Keahlian</h6>
                                        <form action="{{ route('pelamar.profil.keahlian.store') }}" method="POST"
                                            enctype="multipart/form-data" onsubmit="return stayOnTab('keahlian')">
                                            @csrf
                                            <div class="row g-2 align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label small text-muted">Nama Keahlian</label>
                                                    <input type="text" name="nama_keahlian"
                                                        class="form-control rounded-3" placeholder="Nama Keahlian"
                                                        required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small text-muted">Tingkat</label>
                                                    <select name="tingkat" class="form-select rounded-3">
                                                        <option value="Dasar">Dasar</option>
                                                        <option value="Menengah">Menengah</option>
                                                        <option value="Mahir">Mahir</option>
                                                        <option value="Lanjut">Lanjut</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small text-muted">Sertifikat</label>
                                                    <input type="file" name="sertifikat_file"
                                                        class="form-control rounded-3" accept=".pdf,.jpg,.jpeg,.png">
                                                </div>
                                                <div class="col-md-3">
                                                    <button type="submit" class="btn btn-primary rounded-3 w-100"><i
                                                            class="fas fa-plus me-1"></i> Tambah</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead class="bg-light">
                                            <tr>
                                                <th class="px-3 py-2">Keahlian</th>
                                                <th class="px-3 py-2">Tingkat</th>
                                                <th class="px-3 py-2">Sertifikat</th>
                                                <th class="text-center px-3 py-2" style="width: 120px">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($keahlian as $item)
                                                <tr>
                                                    <td class="px-3 py-2">{{ $item->nama_keahlian }}</td>
                                                    <td class="px-3 py-2">
                                                        @if ($item->tingkat)
                                                            <span
                                                                class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">{{ $item->tingkat }}</span>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-3 py-2">
                                                        @if ($item->sertifikat)
                                                            <a href="{{ Storage::url($item->sertifikat) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-primary rounded-pill">
                                                                <i class="fas fa-download me-1"></i>Lihat
                                                            </a>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center px-3 py-2">
                                                        <div class="d-flex gap-1 justify-content-center">
                                                            <button class="btn btn-sm btn-warning rounded-pill"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#editKeahlianModal{{ $item->id }}"
                                                                title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form
                                                                action="{{ route('pelamar.profil.keahlian.destroy', $item->id) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return stayOnTab('keahlian')">
                                                                @csrf @method('DELETE')
                                                                <button class="btn btn-sm btn-danger rounded-pill"
                                                                    onclick="return confirm('Hapus keahlian ini?')"
                                                                    title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada
                                                        data
                                                        keahlian</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @foreach ($keahlian as $item)
                                    <div class="modal fade" id="editKeahlianModal{{ $item->id }}"
                                        tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content rounded-4">
                                                <div class="modal-header bg-primary text-white border-0 rounded-top-4">
                                                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit
                                                        Keahlian
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('pelamar.profil.keahlian.update', $item->id) }}"
                                                    method="POST" enctype="multipart/form-data"
                                                    onsubmit="return stayOnTab('keahlian')">
                                                    @csrf @method('PUT')
                                                    <div class="modal-body p-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Nama Keahlian</label>
                                                            <input type="text" name="nama_keahlian"
                                                                class="form-control rounded-3"
                                                                value="{{ $item->nama_keahlian }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Tingkat</label>
                                                            <select name="tingkat" class="form-select rounded-3">
                                                                <option value="Dasar"
                                                                    {{ $item->tingkat == 'Dasar' ? 'selected' : '' }}>
                                                                    Dasar</option>
                                                                <option value="Menengah"
                                                                    {{ $item->tingkat == 'Menengah' ? 'selected' : '' }}>
                                                                    Menengah</option>
                                                                <option value="Mahir"
                                                                    {{ $item->tingkat == 'Mahir' ? 'selected' : '' }}>
                                                                    Mahir</option>
                                                                <option value="Lanjut"
                                                                    {{ $item->tingkat == 'Lanjut' ? 'selected' : '' }}>
                                                                    Lanjut</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Sertifikat</label>
                                                            @if ($item->sertifikat)
                                                                <div class="mb-2">
                                                                    <a href="{{ Storage::url($item->sertifikat) }}"
                                                                        target="_blank"
                                                                        class="btn btn-sm btn-outline-primary">Lihat
                                                                        Sertifikat</a>
                                                                </div>
                                                            @endif
                                                            <input type="file" name="sertifikat_file"
                                                                class="form-control rounded-3"
                                                                accept=".pdf,.jpg,.jpeg,.png">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0 pb-4 px-4">
                                                        <button type="button"
                                                            class="btn btn-outline-secondary rounded-pill px-4"
                                                            data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit"
                                                            class="btn btn-primary rounded-pill px-4">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .rounded-4 {
            border-radius: 20px !important;
        }

        .rounded-3 {
            border-radius: 12px !important;
        }

        .rounded-pill {
            border-radius: 50px !important;
        }

        .shadow-sm {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.03) !important;
        }

        .bg-opacity-10 {
            --bs-bg-opacity: 0.1;
        }

        .nav-tabs .nav-link {
            color: #64748b;
            border: none;
            transition: all 0.2s ease;
        }

        .nav-tabs .nav-link:hover {
            color: #2563eb;
            background: rgba(37, 99, 235, 0.05);
        }

        .nav-tabs .nav-link.active {
            color: #2563eb;
            background: #eff6ff;
        }

        .btn-primary {
            background: #2563eb;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-outline-secondary:hover {
            transform: translateY(-2px);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #86b7fe;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
        }

        .table-bordered {
            border: 1px solid #dee2e6 !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6 !important;
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

        .form-select {
            background-position: right 0.75rem center !important;
        }

        .form-select[data-bs-auto-close="outside"] {
            background-position: right 0.75rem center !important;
        }

        @media (max-width: 768px) {
            .card-header .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 8px;
            }

            .nav-tabs .nav-link {
                white-space: nowrap;
            }

            .row.g-2>[class*="col-"] {
                margin-bottom: 10px;
            }

            .btn-primary.w-100 {
                margin-top: 5px;
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

        function resetForm() {
            if (confirm('Reset semua perubahan yang belum disimpan?')) {
                location.reload();
            }
        }

        function stayOnTab(tabId) {
            localStorage.setItem('activeTab', tabId);
            return true;
        }

        function toggleTahunKeluar(checkbox, id) {
            const tahunKeluar = document.querySelector(`#editPengalamanModal${id} input[name="tahun_keluar"]`);
            if (tahunKeluar) {
                tahunKeluar.disabled = checkbox.checked;
                if (checkbox.checked) {
                    tahunKeluar.value = '';
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedTab = localStorage.getItem('activeTab');
            if (savedTab) {
                const targetTab = document.querySelector(`button[data-bs-target="#${savedTab}"]`);
                if (targetTab) {
                    const bsTab = new bootstrap.Tab(targetTab);
                    bsTab.show();
                }
                localStorage.removeItem('activeTab');
            }

            const tingkatSelectPendidikan = document.getElementById('tingkat_select_pendidikan');
            const instansiSelectPendidikan = document.getElementById('instansi_select_pendidikan');
            const instansiInputPendidikan = document.getElementById('instansi_input_pendidikan');
            const jurusanSelectPendidikan = document.getElementById('jurusan_select_pendidikan');
            const jurusanInputPendidikan = document.getElementById('jurusan_input_pendidikan');

            const tingkatDropdownPendidikan = ['D3', 'D4', 'S1', 'S2'];

            function togglePendidikanFields() {
                const selected = tingkatSelectPendidikan ? tingkatSelectPendidikan.value : '';

                if (tingkatDropdownPendidikan.includes(selected)) {
                    instansiSelectPendidikan.style.display = 'block';
                    instansiInputPendidikan.style.display = 'none';
                    jurusanSelectPendidikan.style.display = 'block';
                    jurusanInputPendidikan.style.display = 'none';

                    instansiSelectPendidikan.setAttribute('required', 'required');
                    instansiInputPendidikan.removeAttribute('required');
                    instansiInputPendidikan.disabled = true;
                    jurusanSelectPendidikan.removeAttribute('required');
                    jurusanInputPendidikan.removeAttribute('required');
                    jurusanInputPendidikan.disabled = true;

                    instansiInputPendidikan.value = '';
                    jurusanInputPendidikan.value = '';
                } else if (selected === 'SD' || selected === 'SMP' || selected === 'SMA/SMK') {
                    instansiSelectPendidikan.style.display = 'none';
                    instansiInputPendidikan.style.display = 'block';
                    jurusanSelectPendidikan.style.display = 'none';
                    jurusanInputPendidikan.style.display = 'block';

                    instansiSelectPendidikan.removeAttribute('required');
                    instansiInputPendidikan.setAttribute('required', 'required');
                    instansiInputPendidikan.disabled = false;
                    jurusanSelectPendidikan.removeAttribute('required');
                    jurusanInputPendidikan.removeAttribute('required');
                    jurusanInputPendidikan.disabled = false;

                    instansiSelectPendidikan.value = '';
                    jurusanSelectPendidikan.value = '';
                } else {
                    instansiSelectPendidikan.style.display = 'block';
                    instansiInputPendidikan.style.display = 'none';
                    jurusanSelectPendidikan.style.display = 'block';
                    jurusanInputPendidikan.style.display = 'none';

                    instansiSelectPendidikan.removeAttribute('required');
                    instansiInputPendidikan.removeAttribute('required');
                    instansiInputPendidikan.disabled = true;
                    jurusanSelectPendidikan.removeAttribute('required');
                    jurusanInputPendidikan.removeAttribute('required');
                    jurusanInputPendidikan.disabled = true;
                }
            }

            if (tingkatSelectPendidikan) {
                tingkatSelectPendidikan.addEventListener('change', togglePendidikanFields);
                togglePendidikanFields();
            }

            const pendidikanTerakhirSelect = document.getElementById('pendidikan_terakhir_pelamar');
            const jurusanSelectPelamar = document.getElementById('jurusan_select_pelamar');
            const jurusanInputPelamar = document.getElementById('jurusan_input_pelamar');
            const instansiSelectPelamar = document.getElementById('instansi_select_pelamar');
            const instansiInputPelamar = document.getElementById('instansi_input_pelamar');

            const tingkatDropdownPelamar = ['Diploma (D3)', 'Diploma (D4)', 'Sarjana (S1)', 'Magister (S2)'];

            function toggleProfilFields() {
                const selected = pendidikanTerakhirSelect ? pendidikanTerakhirSelect.value : '';

                if (tingkatDropdownPelamar.includes(selected)) {
                    instansiSelectPelamar.style.display = 'block';
                    instansiInputPelamar.style.display = 'none';
                    jurusanSelectPelamar.style.display = 'block';
                    jurusanInputPelamar.style.display = 'none';

                    instansiInputPelamar.disabled = true;
                    jurusanInputPelamar.disabled = true;

                    jurusanInputPelamar.value = '';
                    instansiInputPelamar.value = '';
                } else if (selected === 'SD' || selected === 'SMP' || selected === 'SMA/SMK') {
                    instansiSelectPelamar.style.display = 'none';
                    instansiInputPelamar.style.display = 'block';
                    jurusanSelectPelamar.style.display = 'none';
                    jurusanInputPelamar.style.display = 'block';

                    instansiInputPelamar.disabled = false;
                    jurusanInputPelamar.disabled = false;

                    instansiSelectPelamar.value = '';
                    jurusanSelectPelamar.value = '';
                } else {
                    instansiSelectPelamar.style.display = 'block';
                    instansiInputPelamar.style.display = 'none';
                    jurusanSelectPelamar.style.display = 'block';
                    jurusanInputPelamar.style.display = 'none';

                    instansiInputPelamar.disabled = true;
                    jurusanInputPelamar.disabled = true;
                }
            }

            if (pendidikanTerakhirSelect) {
                pendidikanTerakhirSelect.addEventListener('change', toggleProfilFields);
                toggleProfilFields();
            }

            loadProvinsi();
        });

        const savedProvinsi = "{{ $pelamar->provinsi ?? '' }}";
        const savedKabKota = "{{ $pelamar->kab_kota ?? '' }}";
        const savedKecamatan = "{{ $pelamar->kecamatan ?? '' }}";
        const savedDesa = "{{ $pelamar->desa_kelurahan ?? '' }}";
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
    </script>
@endsection
