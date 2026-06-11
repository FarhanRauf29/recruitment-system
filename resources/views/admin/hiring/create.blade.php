@extends('layouts.admin')

@section('title', 'Konfirmasi Hiring')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                    <div class="card-header bg-primary text-white py-3 px-4">
                        <h4 class="card-title mb-0 fw-semibold">
                            <i class="fas fa-user-check me-2"></i> Konfirmasi Hiring
                        </h4>
                        <small class="opacity-75 mt-1 d-block">
                            <i class="fas fa-user me-1"></i> Kandidat: {{ $offering->pelamar->nama_lengkap }} |
                            <i class="fas fa-briefcase me-1 ms-2"></i> Lowongan: {{ $lowongan->judul_lowongan }}
                        </small>
                    </div>

                    <div class="card-body p-4">
                        <div class="alert alert-info bg-light border-start border-4 border-info mb-4 py-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-file-signature text-info me-3 fs-5"></i>
                                <div>
                                    <span class="text-muted small d-block">Detail Penawaran</span>
                                    <strong class="fs-6">Posisi: {{ $offering->posisi }} | Gaji: Rp
                                        {{ number_format($offering->gaji, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.hiring.store', [$lowongan->id, $offering->id]) }}" method="POST">
                            @csrf

                            @if ($offering->tipe_kontrak == 'probation')
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="start_probation" class="form-check-input"
                                            id="startProbation" value="1" checked>
                                        <label class="form-check-label fw-semibold" for="startProbation">
                                            <i class="fas fa-chart-line text-warning me-1"></i>
                                            Mulai masa probation ({{ $offering->masa_probation ?? 3 }} bulan)
                                        </label>
                                    </div>
                                    <div class="form-text text-muted small ms-4">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Centang untuk memulai masa probation, kosongkan jika langsung tetap
                                    </div>
                                </div>
                            @endif

                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-sticky-note text-primary me-1"></i>
                                    Catatan Internal HR
                                </label>
                                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="4"
                                    placeholder="Tambahkan catatan internal untuk proses hiring...">{{ old('catatan') }}</textarea>
                                <div class="form-text text-muted mt-1">
                                    <i class="fas fa-lock me-1"></i>
                                    Catatan hanya terlihat oleh internal HR
                                </div>
                                @error('catatan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr class="my-4">

                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">
                                    <i class="fas fa-check-circle me-2"></i> Konfirmasi Hiring
                                </button>
                                <a href="{{ route('admin.hiring.index', $lowongan->id) }}"
                                    class="btn btn-outline-secondary px-4 py-2 rounded-pill">
                                    <i class="fas fa-arrow-left me-2"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .form-control:focus,
            .form-select:focus {
                border-color: #86b7fe;
                box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15);
            }

            .btn {
                transition: all 0.2s ease;
            }

            .btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
        </style>
    @endpush
@endsection
