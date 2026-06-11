@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">Edit Tahapan Pelamar</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.pelamartahapan.update', $pelamartahapan) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="pelamar_id" class="form-label">Pelamar</label>
                                <select class="form-select" id="pelamar_id" name="pelamar_id" required>
                                    <option value="" disabled>-- Pilih Pelamar --</option>
                                    @foreach ($pelamars as $pelamar)
                                        <option value="{{ $pelamar->id }}"
                                            {{ old('pelamar_id', $pelamartahapan->pelamar_id) == $pelamar->id ? 'selected' : '' }}>
                                            {{ $pelamar->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="lowongan_id" class="form-label">Lowongan</label>
                                <select class="form-select" id="lowongan_id" name="lowongan_id" required>
                                    <option value="" disabled>-- Pilih Lowongan --</option>
                                    @foreach ($lowongans as $lowongan)
                                        <option value="{{ $lowongan->id }}"
                                            {{ old('lowongan_id', $pelamartahapan->lowongan_id) == $lowongan->id ? 'selected' : '' }}>
                                            {{ $lowongan->judul_lowongan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="tahapan_id" class="form-label">Tahapan Rekrutmen</label>
                                <select class="form-select" id="tahapan_id" name="tahapan_id" required>
                                    <option value="" disabled>-- Pilih Tahapan --</option>
                                    @foreach ($tahapans as $tahapan)
                                        <option value="{{ $tahapan->id }}"
                                            {{ old('tahapan_id', $pelamartahapan->tahapan_id) == $tahapan->id ? 'selected' : '' }}>
                                            {{ $tahapan->nama_tahapan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    @foreach ($statusOptions as $status)
                                        <option value="{{ $status }}"
                                            {{ old('status', $pelamartahapan->status) == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="3">{{ old('catatan', $pelamartahapan->catatan) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="datetime-local" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                                    value="{{ old('tanggal_mulai', $pelamartahapan->tanggal_mulai ? $pelamartahapan->tanggal_mulai->format('Y-m-d\TH:i') : '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="datetime-local" class="form-control" id="tanggal_selesai"
                                    name="tanggal_selesai"
                                    value="{{ old('tanggal_selesai', $pelamartahapan->tanggal_selesai ? $pelamartahapan->tanggal_selesai->format('Y-m-d\TH:i') : '') }}">
                            </div>

                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                            <a href="{{ route('admin.pelamartahapan.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
