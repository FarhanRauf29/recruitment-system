@extends('layouts.admin')
@section('title', 'Tahapan Rekrutmen Pelamar')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h3 class="card-title mb-0">
                                <i class="fas fa-list-ol me-2"></i> Tahapan Rekrutmen
                            </h3>
                            <p class="small mb-0 mt-1 opacity-75">
                                Pelamar: {{ $pelamar->nama_lengkap }} | Lowongan: {{ $lowongan->judul_lowongan }}
                            </p>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <a href="{{ route('admin.lowongan.applicants', $lowongan) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @php
                            $previousLolos = true;
                        @endphp

                        @forelse ($tahapanPelamar as $index => $tahapan)
                            @php
                                $statusClass =
                                    [
                                        'belum_dimulai' => 'secondary',
                                        'menunggu' => 'warning',
                                        'diproses' => 'info',
                                        'lolos' => 'success',
                                        'gagal' => 'danger',
                                        'terkunci' => 'secondary',
                                    ][$tahapan->status ?? 'belum_dimulai'] ?? 'secondary';

                                $statusIcon =
                                    [
                                        'belum_dimulai' => 'clock',
                                        'menunggu' => 'clock',
                                        'diproses' => 'spinner fa-pulse',
                                        'lolos' => 'check-circle',
                                        'gagal' => 'times-circle',
                                        'terkunci' => 'lock',
                                    ][$tahapan->status ?? 'belum_dimulai'] ?? 'circle';

                                $isEditable =
                                    $tahapan->status !== 'terkunci' &&
                                    $tahapan->status !== 'gagal' &&
                                    $tahapan->status !== 'lolos';
                                $isPreviousLolos = $previousLolos;

                                if ($tahapan->status === 'lolos') {
                                    $previousLolos = true;
                                } elseif ($tahapan->status === 'gagal') {
                                    $previousLolos = false;
                                }
                            @endphp
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                                        <div>
                                            <h5 class="card-title mb-1">
                                                <i class="fas fa-tasks text-primary me-2"></i>
                                                {{ $tahapan->tahapan->nama_tahapan ?? 'Tahapan ' . ($index + 1) }}
                                                <small class="text-muted">(Urutan:
                                                    {{ $tahapan->tahapan->urutan ?? $index + 1 }})</small>
                                            </h5>
                                            @if (isset($tahapan->tahapan) && $tahapan->tahapan->deskripsi)
                                                <p class="card-text text-muted small mt-1">
                                                    {{ $tahapan->tahapan->deskripsi }}</p>
                                            @endif
                                        </div>
                                        <span class="badge bg-{{ $statusClass }} px-3 py-2">
                                            <i class="fas fa-{{ $statusIcon }} me-1"></i>
                                            {{ $tahapan->status_text ?? 'Belum Dimulai' }}
                                        </span>
                                    </div>

                                    @if ($tahapan->id)
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                @if ($tahapan->tanggal_mulai)
                                                    <small class="text-muted d-block mb-1">
                                                        <i class="fas fa-clock me-1 text-primary"></i>
                                                        <strong>Mulai:</strong>
                                                        {{ \Carbon\Carbon::parse($tahapan->tanggal_mulai)->format('d F Y') }}
                                                    </small>
                                                @endif
                                                @if ($tahapan->tanggal_selesai)
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-flag-checkered me-1 text-primary"></i>
                                                        <strong>Selesai:</strong>
                                                        {{ \Carbon\Carbon::parse($tahapan->tanggal_selesai)->format('d F Y') }}
                                                    </small>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                @if ($tahapan->catatan)
                                                    <div class="alert alert-info py-2 mb-0">
                                                        <i class="fas fa-sticky-note me-1"></i>
                                                        <strong>Catatan:</strong> {{ $tahapan->catatan }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($isEditable && $isPreviousLolos)
                                            <div class="mt-3 pt-3 border-top">
                                                <h6 class="mb-3"><i class="fas fa-edit me-1 text-primary"></i> Ubah Status
                                                    Tahapan</h6>
                                                <form
                                                    action="{{ route('admin.lowongan.applicant.tahapan.update', [$lowongan, $pelamar]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="tahapan_id"
                                                        value="{{ $tahapan->tahapan_id }}">
                                                    <div class="row g-2">
                                                        <div class="col-md-5">
                                                            <select name="status" class="form-select form-select-sm">
                                                                <option value="menunggu"
                                                                    {{ ($tahapan->status ?? '') == 'menunggu' ? 'selected' : '' }}>
                                                                    Menunggu</option>
                                                                <option value="diproses"
                                                                    {{ ($tahapan->status ?? '') == 'diproses' ? 'selected' : '' }}>
                                                                    Diproses</option>
                                                                <option value="lolos"
                                                                    {{ ($tahapan->status ?? '') == 'lolos' ? 'selected' : '' }}>
                                                                    Lolos</option>
                                                                <option value="gagal"
                                                                    {{ ($tahapan->status ?? '') == 'gagal' ? 'selected' : '' }}>
                                                                    Gagal</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <input type="text" name="catatan"
                                                                class="form-control form-control-sm"
                                                                placeholder="Catatan (opsional)"
                                                                value="{{ $tahapan->catatan }}">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                                <i class="fas fa-save me-1"></i> Simpan
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @elseif($tahapan->status === 'terkunci')
                                            <div class="mt-3 pt-3 border-top">
                                                <div class="alert alert-secondary mb-0">
                                                    <i class="fas fa-lock me-1"></i> Tahapan ini terkunci. Selesaikan
                                                    tahapan sebelumnya terlebih dahulu.
                                                </div>
                                            </div>
                                        @elseif($tahapan->status === 'gagal')
                                            <div class="mt-3 pt-3 border-top">
                                                <div class="alert alert-danger mb-0">
                                                    <i class="fas fa-times-circle me-1"></i> Tahapan ini gagal. Proses
                                                    rekrutmen berhenti.
                                                </div>
                                            </div>
                                        @elseif($tahapan->status === 'lolos')
                                            <div class="mt-3 pt-3 border-top">
                                                <div class="alert alert-success mb-0">
                                                    <i class="fas fa-check-circle me-1"></i> Tahapan ini sudah selesai dan
                                                    dinyatakan lolos.
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-secondary mt-3 mb-0">
                                            <i class="fas fa-info-circle me-1"></i> Tahapan ini belum dimulai
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-list fa-4x text-muted mb-3"></i>
                                <h5 class="text-muted">Belum ada tahapan rekrutmen</h5>
                                <p class="text-muted small">Belum ada tahapan rekrutmen untuk pelamar ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
