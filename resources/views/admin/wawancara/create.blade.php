@extends('layouts.admin')

@section('title', 'Tambah Jadwal Wawancara')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i> Tambah Jadwal Wawancara
                        </h4>
                        <p class="small mb-0 mt-1 opacity-75">Buat jadwal wawancara untuk pelamar yang lolos tahap sebelumnya
                        </p>
                    </div>
                    <div class="card-body">
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong>Terjadi kesalahan!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.wawancara.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Pilih Pelamar & Lowongan <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-user text-primary"></i></span>
                                            <select name="pelamar_tahapan_id"
                                                class="form-select @error('pelamar_tahapan_id') is-invalid @enderror"
                                                required>
                                                <option value="">-- Pilih Pelamar --</option>
                                                @foreach ($pelamarTahapans as $pt)
                                                    <option value="{{ $pt->id }}"
                                                        {{ old('pelamar_tahapan_id') == $pt->id ? 'selected' : '' }}>
                                                        {{ $pt->pelamar->nama_lengkap }} -
                                                        {{ $pt->lowongan->judul_lowongan }}
                                                        ({{ $pt->tahapan->nama_tahapan }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if ($pelamarTahapans->isEmpty())
                                            <div class="alert alert-warning mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Semua pelamar yang lolos ke tahap wawancara sudah memiliki jadwal wawancara.
                                            </div>
                                        @endif
                                        @error('pelamar_tahapan_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal & Jam Wawancara <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-calendar-alt text-primary"></i></span>
                                            <input type="datetime-local" name="jadwal_wawancara"
                                                class="form-control @error('jadwal_wawancara') is-invalid @enderror"
                                                value="{{ old('jadwal_wawancara') }}" required>
                                        </div>
                                        @error('jadwal_wawancara')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jenis Wawancara <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-video text-primary"></i></span>
                                            <select name="jenis_wawancara"
                                                class="form-select @error('jenis_wawancara') is-invalid @enderror"
                                                id="jenisWawancara" required>
                                                <option value="online"
                                                    {{ old('jenis_wawancara') == 'online' ? 'selected' : '' }}>Online
                                                    (Video Call)</option>
                                                <option value="offline"
                                                    {{ old('jenis_wawancara') == 'offline' ? 'selected' : '' }}>Offline
                                                    (Tatap Muka)</option>
                                            </select>
                                        </div>
                                        @error('jenis_wawancara')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12" id="linkContainer"
                                    style="display: {{ old('jenis_wawancara') == 'online' ? 'block' : 'none' }};">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Link Wawancara <span class="text-danger"
                                                id="linkRequired">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-link text-primary"></i></span>
                                            <input type="url" name="link_wawancara"
                                                class="form-control @error('link_wawancara') is-invalid @enderror"
                                                value="{{ old('link_wawancara') }}"
                                                placeholder="https://zoom.us/... atau https://meet.google.com/...">
                                        </div>
                                        <div class="form-text">Masukkan link Zoom, Google Meet, atau platform video call
                                            lainnya</div>
                                        @error('link_wawancara')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12" id="lokasiContainer"
                                    style="display: {{ old('jenis_wawancara') == 'offline' ? 'block' : 'none' }};">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Lokasi Wawancara <span class="text-danger"
                                                id="lokasiRequired">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-building text-primary"></i></span>
                                            <input type="text" name="lokasi_wawancara"
                                                class="form-control @error('lokasi_wawancara') is-invalid @enderror"
                                                value="{{ old('lokasi_wawancara') }}"
                                                placeholder="Nama ruangan atau gedung">
                                        </div>
                                        @error('lokasi_wawancara')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12" id="alamatContainer"
                                    style="display: {{ old('jenis_wawancara') == 'offline' ? 'block' : 'none' }};">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Alamat Lengkap Lokasi</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-map-marker-alt text-primary"></i></span>
                                            <textarea name="alamat_lokasi" class="form-control" rows="2" placeholder="Jl. ...">{{ old('alamat_lokasi') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Pewawancara</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-user-tie text-primary"></i></span>
                                            <input type="text" name="pewawancara" class="form-control"
                                                value="{{ old('pewawancara') }}" placeholder="Nama pewawancara">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Keterangan Tambahan</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-info-circle text-primary"></i></span>
                                            <textarea name="keterangan" class="form-control" rows="2"
                                                placeholder="Persiapan wawancara, dokumen yang perlu dibawa, dll">{{ old('keterangan') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"
                                    {{ $pelamarTahapans->isEmpty() ? 'disabled' : '' }}>
                                    <i class="fas fa-save me-1"></i> Simpan Jadwal
                                </button>
                                <a href="{{ route('admin.wawancara.index') }}" class="btn btn-secondary"><i
                                        class="fas fa-arrow-left me-1"></i> Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('jenisWawancara').addEventListener('change', function() {
            const jenis = this.value;
            const linkContainer = document.getElementById('linkContainer');
            const lokasiContainer = document.getElementById('lokasiContainer');
            const alamatContainer = document.getElementById('alamatContainer');
            const linkRequired = document.getElementById('linkRequired');
            const lokasiRequired = document.getElementById('lokasiRequired');

            if (jenis === 'online') {
                linkContainer.style.display = 'block';
                lokasiContainer.style.display = 'none';
                alamatContainer.style.display = 'none';
                if (linkRequired) linkRequired.style.display = 'inline';
                if (lokasiRequired) lokasiRequired.style.display = 'none';
            } else {
                linkContainer.style.display = 'none';
                lokasiContainer.style.display = 'block';
                alamatContainer.style.display = 'block';
                if (linkRequired) linkRequired.style.display = 'none';
                if (lokasiRequired) lokasiRequired.style.display = 'inline';
            }
        });
        document.getElementById('jenisWawancara').dispatchEvent(new Event('change'));
    </script>
@endsection
