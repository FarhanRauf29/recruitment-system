@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-lg">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-alt me-2"></i>
                            <h4 class="d-inline-block mb-0">Detail Berkas Pelamar</h4>
                        </div>
                        <a href="{{ route('admin.berkas.index') }}" class="btn btn-light">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-card bg-light p-4 rounded-3 h-100">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-user-circle me-2"></i> Informasi Pelamar
                                    </h5>
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar me-3">
                                            <img src="{{ $berkas->pelamar->foto_profil ? asset('storage/' . $berkas->pelamar->foto_profil) : 'https://ui-avatars.com/api/?name=' . urlencode($berkas->pelamar->nama_lengkap ?? 'User') . '&background=3498db&color=fff' }}"
                                                class="rounded-circle" width="60" height="60" alt="Foto Profil">
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $berkas->pelamar->nama_lengkap ?? 'Tidak diketahui' }}
                                            </h5>
                                            <p class="text-muted mb-1"><i class="fas fa-envelope me-1"></i>
                                                {{ $berkas->pelamar->email ?? '-' }}</p>
                                            <p class="text-muted mb-0"><i class="fas fa-phone me-1"></i>
                                                {{ $berkas->pelamar->no_telepon ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="status-card p-4 rounded-3 h-100"
                                    style="background-color: {{ $berkas->status_verifikasi == 'Diterima' ? '#e8f5e9' : ($berkas->status_verifikasi == 'Ditolak' ? '#ffebee' : '#fff8e1') }}">
                                    <h5 class="text-primary mb-3">
                                        <i class="fas fa-clipboard-check me-2"></i> Status Verifikasi
                                    </h5>
                                    <div class="d-flex align-items-center mb-3">
                                        <span
                                            class="badge rounded-pill fs-6 p-2 {{ $berkas->status_verifikasi == 'Diterima' ? 'bg-success' : ($berkas->status_verifikasi == 'Ditolak' ? 'bg-danger' : 'bg-warning') }}">
                                            <i
                                                class="fas {{ $berkas->status_verifikasi == 'Diterima' ? 'fa-check-circle' : ($berkas->status_verifikasi == 'Ditolak' ? 'fa-times-circle' : 'fa-clock') }} me-1"></i>
                                            {{ $berkas->status_verifikasi }}
                                        </span>
                                        <span class="ms-3 text-muted">Terakhir diperbarui:
                                            {{ $berkas->updated_at->format('l, d F Y') }}</span>
                                    </div>
                                    <div class="mb-0">
                                        <h6 class="mb-2">Catatan Admin:</h6>
                                        <div class="p-3 bg-white rounded border">
                                            {{ $berkas->catatan_admin ?? 'Tidak ada catatan' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="document-section">
                            <h5 class="text-primary mb-4"><i class="fas fa-folder-open me-2"></i> Dokumen Lamaran</h5>
                            <div class="row">
                                @php
                                    $documents = [
                                        'cv_file' => ['CV', 'fas fa-file-user', 'bg-info'],
                                        'surat_lamaran_file' => [
                                            'Surat Lamaran',
                                            'fas fa-envelope-open-text',
                                            'bg-primary',
                                        ],
                                        'ijazah_file' => ['Ijazah', 'fas fa-graduation-cap', 'bg-success'],
                                        'transkrip_nilai_file' => ['Transkrip Nilai', 'fas fa-list-alt', 'bg-warning'],
                                        'ktp_file' => ['KTP', 'fas fa-id-card', 'bg-secondary'],
                                        'pas_foto_file' => ['Pas Foto', 'fas fa-camera', 'bg-purple'],
                                        'sertifikat_file' => ['Sertifikat', 'fas fa-certificate', 'bg-danger'],
                                        'skck_file' => ['SKCK', 'fas fa-file-contract', 'bg-dark'],
                                        'surat_keterangan_sehat_file' => ['Surat Sehat', 'fas fa-heartbeat', 'bg-pink'],
                                        'portofolio_file' => ['Portofolio', 'fas fa-briefcase', 'bg-indigo'],
                                        'kartu_keluarga_file' => ['Kartu Keluarga', 'fas fa-users', 'bg-teal'],
                                        'dokumen_lain_file' => ['Dokumen Lain', 'fas fa-file-archive', 'bg-brown'],
                                    ];
                                @endphp
                                @foreach ($documents as $field => [$label, $icon, $color])
                                    <div class="col-md-4 mb-4">
                                        <div class="document-card card h-100 border-0 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start">
                                                    <div class="icon-container rounded-circle {{ $color }} me-3"><i
                                                            class="{{ $icon }} text-white"></i></div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1">{{ $label }}</h6>
                                                        @if ($berkas->$field && Storage::disk('public')->exists($berkas->$field))
                                                            <a href="{{ asset('storage/' . $berkas->$field) }}"
                                                                target="_blank"
                                                                class="btn btn-sm btn-outline-primary mt-2"><i
                                                                    class="fas fa-eye me-1"></i> Lihat Dokumen</a>
                                                            @php
                                                                $fullPath = storage_path(
                                                                    'app/public/' . $berkas->$field,
                                                                );
                                                                $fileSize = file_exists($fullPath)
                                                                    ? round(filesize($fullPath) / 1024) . ' KB'
                                                                    : 'Ukuran tidak diketahui';
                                                            @endphp
                                                            <small class="d-block text-muted mt-1">Ukuran:
                                                                {{ $fileSize }}</small>
                                                        @else
                                                            <span class="badge bg-light text-dark mt-2"><i
                                                                    class="fas fa-times-circle me-1"></i> Belum
                                                                Diupload</span>
                                                        @endif
                                                    </div>
                                                </div>
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
        .avatar img {
            object-fit: cover;
            border: 3px solid #e0e0e0;
        }

        .icon-container {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-purple {
            background-color: #6f42c1 !important;
        }

        .bg-pink {
            background-color: #e83e8c !important;
        }

        .bg-indigo {
            background-color: #6610f2 !important;
        }

        .bg-teal {
            background-color: #20c997 !important;
        }

        .bg-brown {
            background-color: #795548 !important;
        }

        .document-card:hover {
            transform: translateY(-3px);
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1) !important;
        }

        .info-card,
        .status-card {
            height: 100%;
        }
    </style>
@endsection
