@extends('layouts.admin')

@section('title', 'Edit Jadwal Wawancara')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i> Edit Jadwal Wawancara
                        </h4>
                        <p class="small mb-0 mt-1 opacity-75">Perbarui informasi jadwal wawancara</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.wawancara.update', $wawancara->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal & Jam Wawancara <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-calendar-alt text-primary"></i></span>
                                            <input type="datetime-local" name="jadwal_wawancara" class="form-control"
                                                value="{{ Carbon\Carbon::parse($wawancara->jadwal_wawancara)->format('Y-m-d\TH:i') }}"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Jenis Wawancara <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-video text-primary"></i></span>
                                            <select name="jenis_wawancara" class="form-select" id="jenisWawancara" required>
                                                <option value="online"
                                                    {{ $wawancara->jenis_wawancara == 'online' ? 'selected' : '' }}>Online
                                                    (Video Call)</option>
                                                <option value="offline"
                                                    {{ $wawancara->jenis_wawancara == 'offline' ? 'selected' : '' }}>Offline
                                                    (Tatap Muka)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" id="linkContainer"
                                    style="{{ $wawancara->jenis_wawancara == 'online' ? 'display:block' : 'display:none' }}">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Link Wawancara</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-link text-primary"></i></span>
                                            <input type="url" name="link_wawancara" class="form-control"
                                                value="{{ $wawancara->link_wawancara }}" placeholder="https://zoom.us/...">
                                        </div>
                                        <div class="form-text">Link untuk wawancara online</div>
                                    </div>
                                </div>

                                <div class="col-12" id="lokasiContainer"
                                    style="{{ $wawancara->jenis_wawancara == 'offline' ? 'display:block' : 'display:none' }}">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Lokasi Wawancara</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-building text-primary"></i></span>
                                            <input type="text" name="lokasi_wawancara" class="form-control"
                                                value="{{ $wawancara->lokasi_wawancara }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12" id="alamatContainer"
                                    style="{{ $wawancara->jenis_wawancara == 'offline' ? 'display:block' : 'display:none' }}">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Alamat Lengkap Lokasi</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-map-marker-alt text-primary"></i></span>
                                            <textarea name="alamat_lokasi" class="form-control" rows="2">{{ $wawancara->alamat_lokasi }}</textarea>
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
                                                value="{{ $wawancara->pewawancara }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Status</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-flag-checkered text-primary"></i></span>
                                            <select name="status" class="form-select">
                                                <option value="dijadwalkan"
                                                    {{ $wawancara->status == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan
                                                </option>
                                                <option value="dikonfirmasi"
                                                    {{ $wawancara->status == 'dikonfirmasi' ? 'selected' : '' }}>
                                                    Dikonfirmasi</option>
                                                <option value="hadir"
                                                    {{ $wawancara->status == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                                <option value="tidak_hadir"
                                                    {{ $wawancara->status == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir
                                                </option>
                                                <option value="selesai"
                                                    {{ $wawancara->status == 'selesai' ? 'selected' : '' }}>Selesai
                                                </option>
                                                <option value="verifikasi"
                                                    {{ $wawancara->status == 'verifikasi' ? 'selected' : '' }}>Verifikasi
                                                </option>
                                                <option value="lolos"
                                                    {{ $wawancara->status == 'lolos' ? 'selected' : '' }}>Lolos</option>
                                                <option value="gagal"
                                                    {{ $wawancara->status == 'gagal' ? 'selected' : '' }}>Gagal</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Catatan Admin</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-sticky-note text-primary"></i></span>
                                            <textarea name="catatan_admin" class="form-control" rows="2">{{ $wawancara->catatan_admin }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Keterangan Tambahan</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-info-circle text-primary"></i></span>
                                            <textarea name="keterangan" class="form-control" rows="2">{{ $wawancara->keterangan }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update
                                    Jadwal</button>
                                <a href="{{ route('admin.wawancara.index', $wawancara->id) }}"
                                    class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
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

            if (jenis === 'online') {
                linkContainer.style.display = 'block';
                lokasiContainer.style.display = 'none';
                alamatContainer.style.display = 'none';
            } else {
                linkContainer.style.display = 'none';
                lokasiContainer.style.display = 'block';
                alamatContainer.style.display = 'block';
            }
        });
    </script>
@endsection
