@extends('layouts.admin')
@section('title', 'Detail Pelamar')
@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-user-circle me-2"></i> Detail Pelamar
                    </h3>
                    <div>
                        <a href="{{ route('admin.pelamar.edit', $pelamar->id) }}" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.pelamar.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Foto Profil -->
                        <div class="col-md-3 text-center">
                            <div class="foto-card">
                                @if($pelamar->foto_profil)
                                    <img src="{{ Storage::url($pelamar->foto_profil) }}" alt="Foto Profil" class="detail-img">
                                @else
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($pelamar->nama_lengkap) }}&background=2563eb&color=fff&size=180" alt="Foto" class="detail-img">
                                @endif
                                <div class="mt-3">
                                    <span class="badge bg-secondary">{{ $pelamar->pendidikan_terakhir }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi -->
                        <div class="col-md-9">
                            <!-- Data Diri -->
                            <div class="info-section">
                                <div class="section-title">
                                    <i class="fas fa-id-card"></i> Data Diri
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label>Nama Lengkap</label>
                                            <p>{{ $pelamar->nama_lengkap }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Email</label>
                                            <p>{{ $pelamar->email }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Jenis Kelamin</label>
                                            <p>{{ $pelamar->jenis_kelamin }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Tempat Lahir</label>
                                            <p>{{ $pelamar->tempat_lahir }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Tanggal Lahir</label>
                                            <p>{{ $pelamar->tanggal_lahir?->format('d F Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label>NIK (KTP)</label>
                                            <p>{{ $pelamar->nomor_identitas }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Kewarganegaraan</label>
                                            <p>{{ $pelamar->kewarganegaraan }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Nomor Handphone</label>
                                            <p>{{ $pelamar->nomor_handphone }}</p>
                                        </div>
                                        <div class="info-item">
                                            <label>Nomor Whatsapp</label>
                                            <p>{{ $pelamar->nomor_whatsapp }}</p>
                                        </div>
                                       <div class="info-item">
    <label>Gaji Diharapkan</label>
    @php
        $gaji = (int) preg_replace('/[^0-9]/', '', $pelamar->gaji_diharapkan ?? 0);
    @endphp
    <p>Rp {{ number_format($gaji, 0, ',', '.') }}</p>
</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="info-section">
                                <div class="section-title">
                                    <i class="fas fa-map-marker-alt"></i> Alamat & Domisili
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="info-item">
                                            <label>Alamat Domisili</label>
                                            <p>{{ $pelamar->alamat_domisili }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label>Provinsi</label>
                                            <p>{{ $pelamar->provinsi }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label>Kabupaten/Kota</label>
                                            <p>{{ $pelamar->kab_kota }}</p>
                                        </div>
                                    </div>
                                    @if($pelamar->kecamatan)
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label>Kecamatan</label>
                                            <p>{{ $pelamar->kecamatan }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    @if($pelamar->desa_kelurahan)
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label>Desa/Kelurahan</label>
                                            <p>{{ $pelamar->desa_kelurahan }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Pendidikan -->
                            <div class="info-section">
                                <div class="section-title">
                                    <i class="fas fa-graduation-cap"></i> Pendidikan & Pekerjaan
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label>Pendidikan Terakhir</label>
                                            <p>{{ $pelamar->pendidikan_terakhir }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label>Jurusan</label>
                                            <p>{{ $pelamar->jurusan }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Sistem -->
                            <div class="info-section">
                                <div class="section-title">
                                    <i class="fas fa-info-circle"></i> Informasi Sistem
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label>Tanggal Registrasi</label>
                                            <p>{{ $pelamar->created_at?->format('l, d F Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <label>Terakhir Diupdate</label>
                                            <p>{{ $pelamar->updated_at?->format('l, d F Y H:i') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .detail-img {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #2563eb;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .info-section {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 18px;
        margin-bottom: 20px;
    }
    .section-title {
        font-weight: 600;
        font-size: 1rem;
        color: #2563eb;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e9ecef;
    }
    .section-title i {
        margin-right: 8px;
    }
    .info-item {
        margin-bottom: 12px;
    }
    .info-item label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6c757d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        display: block;
    }
    .info-item p {
        font-size: 0.9rem;
        font-weight: 500;
        color: #2c3e50;
        margin: 0;
    }
    .foto-card {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 12px;
        text-align: center;
    }
    .badge {
        padding: 6px 12px;
        font-size: 0.75rem;
    }
    @media (max-width: 768px) {
        .col-md-3, .col-md-9 {
            width: 100%;
        }
        .foto-card {
            margin-bottom: 20px;
        }
    }
</style>
@endsection