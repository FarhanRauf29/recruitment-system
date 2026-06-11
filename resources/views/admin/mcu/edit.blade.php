@extends('layouts.admin')

@section('title', 'Edit Jadwal Medical Check Up')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i> Edit Jadwal Medical Check Up
                        </h4>
                        <p class="small mb-0 mt-1 opacity-75">Perbarui informasi jadwal MCU</p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.mcu.update', $mcu->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Tanggal & Jam MCU <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-calendar-alt text-primary"></i></span>
                                            <input type="datetime-local" name="jadwal_mcu" class="form-control"
                                                value="{{ Carbon\Carbon::parse($mcu->jadwal_mcu)->format('Y-m-d\TH:i') }}"
                                                required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Lokasi MCU <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-map-marker-alt text-primary"></i></span>
                                            <input type="text" name="lokasi_mcu" class="form-control"
                                                value="{{ $mcu->lokasi_mcu }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Alamat Lengkap Lokasi</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-address-card text-primary"></i></span>
                                            <textarea name="alamat_lokasi" class="form-control" rows="2">{{ $mcu->alamat_lokasi }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Nama Rumah Sakit</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-hospital text-primary"></i></span>
                                            <input type="text" name="nama_rs" class="form-control"
                                                value="{{ $mcu->nama_rs }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Dokter Pemeriksa</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-user-md text-primary"></i></span>
                                            <input type="text" name="dokter_pemeriksa" class="form-control"
                                                value="{{ $mcu->dokter_pemeriksa }}">
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
                                                    {{ $mcu->status == 'dijadwalkan' ? 'selected' : '' }}>Dijadwalkan
                                                </option>
                                                <option value="dikonfirmasi"
                                                    {{ $mcu->status == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi
                                                </option>
                                                <option value="hadir" {{ $mcu->status == 'hadir' ? 'selected' : '' }}>Hadir
                                                </option>
                                                <option value="tidak_hadir"
                                                    {{ $mcu->status == 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir
                                                </option>
                                                <option value="hasil_diupload"
                                                    {{ $mcu->status == 'hasil_diupload' ? 'selected' : '' }}>Hasil Diupload
                                                </option>
                                                <option value="verifikasi"
                                                    {{ $mcu->status == 'verifikasi' ? 'selected' : '' }}>Verifikasi
                                                </option>
                                                <option value="lolos" {{ $mcu->status == 'lolos' ? 'selected' : '' }}>
                                                    Lolos</option>
                                                <option value="gagal" {{ $mcu->status == 'gagal' ? 'selected' : '' }}>
                                                    Gagal</option>
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
                                            <textarea name="catatan_admin" class="form-control" rows="2">{{ $mcu->catatan_admin }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Keterangan Tambahan</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-white"><i
                                                    class="fas fa-info-circle text-primary"></i></span>
                                            <textarea name="keterangan" class="form-control" rows="2">{{ $mcu->keterangan }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Update
                                    Jadwal</button>
                                <a href="{{ route('admin.mcu.index', $mcu->id) }}" class="btn btn-secondary"><i
                                        class="fas fa-arrow-left me-1"></i> Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
