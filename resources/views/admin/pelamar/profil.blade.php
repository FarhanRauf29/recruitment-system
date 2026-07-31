@extends('layouts.admin')

@section('title', 'Detail Profil Pelamar')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-semibold" style="color: #1e293b;">
                    <i class="fas fa-id-card text-primary me-2"></i> Detail Profil Pelamar
                </h1>
                <p class="text-muted small mb-0">Informasi lengkap data diri pelamar</p>
            </div>
            <a href="{{ route('admin.pelamar.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="bg-primary px-4 py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-white bg-opacity-20 p-2">
                        @if ($pelamar->foto_profil && Storage::disk('public')->exists($pelamar->foto_profil))
                            <img src="{{ Storage::url($pelamar->foto_profil) }}" alt="Foto Profil" class="rounded-circle"
                                style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pelamar->nama_lengkap) }}&background=ffffff&color=2563eb&size=60&bold=true"
                                alt="Avatar" class="rounded-circle" style="width: 60px; height: 60px;">
                        @endif
                    </div>
                    <div class="text-white">
                        <h5 class="fw-bold mb-0">{{ $pelamar->nama_lengkap }}</h5>
                        <p class="small mb-0 opacity-75">{{ $pelamar->email }}</p>
                    </div>
                </div>
            </div>

            <div class="border-bottom px-4 pt-3">
                <ul class="nav nav-tabs border-0 gap-2 flex-nowrap overflow-auto pb-1" style="scrollbar-width: thin;">
                    <li class="nav-item"><button class="nav-link active fw-medium rounded-pill px-3 py-1 small"
                            data-bs-toggle="tab" data-bs-target="#profil"><i class="fas fa-user me-1"></i>Profil
                            Singkat</button></li>
                    <li class="nav-item"><button class="nav-link fw-medium rounded-pill px-3 py-1 small"
                            data-bs-toggle="tab" data-bs-target="#pendidikan"><i
                                class="fas fa-graduation-cap me-1"></i>Pendidikan</button></li>
                    <li class="nav-item"><button class="nav-link fw-medium rounded-pill px-3 py-1 small"
                            data-bs-toggle="tab" data-bs-target="#organisasi"><i
                                class="fas fa-users me-1"></i>Organisasi</button></li>
                    <li class="nav-item"><button class="nav-link fw-medium rounded-pill px-3 py-1 small"
                            data-bs-toggle="tab" data-bs-target="#magang"><i
                                class="fas fa-briefcase me-1"></i>Magang/PKL</button></li>
                    <li class="nav-item"><button class="nav-link fw-medium rounded-pill px-3 py-1 small"
                            data-bs-toggle="tab" data-bs-target="#pengalaman"><i class="fas fa-history me-1"></i>Pengalaman
                            Kerja</button></li>
                    <li class="nav-item"><button class="nav-link fw-medium rounded-pill px-3 py-1 small"
                            data-bs-toggle="tab" data-bs-target="#bahasa"><i
                                class="fas fa-language me-1"></i>Bahasa</button></li>
                    <li class="nav-item"><button class="nav-link fw-medium rounded-pill px-3 py-1 small"
                            data-bs-toggle="tab" data-bs-target="#keahlian"><i
                                class="fas fa-code me-1"></i>Keahlian</button></li>
                </ul>
            </div>

            <div class="card-body p-4">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="profil">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-header">
                                        <i class="fas fa-id-card text-primary"></i>
                                        <span>Data Pribadi</span>
                                    </div>
                                    <div class="info-card-body">
                                        <div class="info-row">
                                            <div class="info-label">Nama Depan</div>
                                            <div class="info-value">{{ $pelamar->nama_depan ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Nama Belakang</div>
                                            <div class="info-value">{{ $pelamar->nama_belakang ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Nama Lengkap</div>
                                            <div class="info-value fw-semibold">{{ $pelamar->nama_lengkap ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Jenis Kelamin</div>
                                            <div class="info-value">{{ $pelamar->jenis_kelamin ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Tempat Lahir</div>
                                            <div class="info-value">{{ $pelamar->tempat_lahir ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Tanggal Lahir</div>
                                            <div class="info-value">
                                                {{ $pelamar->tanggal_lahir ? $pelamar->tanggal_lahir->translatedFormat('d F Y') : '-' }}
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Kewarganegaraan</div>
                                            <div class="info-value">{{ $pelamar->kewarganegaraan ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-header">
                                        <i class="fas fa-address-card text-primary"></i>
                                        <span>Informasi Kontak</span>
                                    </div>
                                    <div class="info-card-body">
                                        <div class="info-row">
                                            <div class="info-label">NIK (KTP)</div>
                                            <div class="info-value">{{ $pelamar->nomor_identitas ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Nomor Handphone</div>
                                            <div class="info-value">{{ $pelamar->nomor_handphone ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Nomor WhatsApp</div>
                                            <div class="info-value">{{ $pelamar->nomor_whatsapp ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Email</div>
                                            <div class="info-value">{{ $pelamar->email ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Pendidikan Terakhir</div>
                                            <div class="info-value">{{ $pelamar->pendidikan_terakhir ?? '-' }}</div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Instansi</div>
                                            <div class="info-value">
                                                @if (!empty($pelamar->instansi))
                                                    {{ $pelamar->instansi }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Jurusan</div>
                                            <div class="info-value">
                                                @if (!empty($pelamar->jurusan))
                                                    {{ $pelamar->jurusan }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="info-row">
                                            <div class="info-label">Gaji Diharapkan</div>
                                            <div class="info-value text-primary fw-semibold">
                                                @if (!empty($pelamar->gaji_diharapkan))
                                                    Rp
                                                    {{ number_format((float) preg_replace('/[^0-9]/', '', $pelamar->gaji_diharapkan), 0, ',', '.') }}
                                                @else
                                                    -
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="info-card">
                                    <div class="info-card-header">
                                        <i class="fas fa-map-marker-alt text-primary"></i>
                                        <span>Alamat Domisili</span>
                                    </div>
                                    <div class="info-card-body">
                                        <div class="info-row">
                                            <div class="info-label">Alamat Lengkap</div>
                                            <div class="info-value">{{ $pelamar->alamat_domisili ?? '-' }}</div>
                                        </div>
                                        <div class="row g-2 mt-2">
                                            <div class="col-md-3 col-6">
                                                <div class="address-item">
                                                    <div class="address-label">Provinsi</div>
                                                    <div class="address-value">{{ $pelamar->provinsi ?? '-' }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="address-item">
                                                    <div class="address-label">Kabupaten/Kota</div>
                                                    <div class="address-value">{{ $pelamar->kab_kota ?? '-' }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="address-item">
                                                    <div class="address-label">Kecamatan</div>
                                                    <div class="address-value">{{ $pelamar->kecamatan ?? '-' }}</div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-6">
                                                <div class="address-item">
                                                    <div class="address-label">Desa/Kelurahan</div>
                                                    <div class="address-value">{{ $pelamar->desa_kelurahan ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pendidikan">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Tingkat</th>
                                        <th>Instansi</th>
                                        <th>Jurusan</th>
                                        <th>Tahun</th>
                                        <th>Nilai/IPK</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendidikan as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td><span
                                                    class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">{{ $item->tingkat }}</span>
                                            </td>
                                            <td>{{ $item->nama_instansi ?? '-' }}</td>
                                            <td>
                                                @if (!empty($item->jurusan))
                                                    {{ $item->jurusan }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->tahun_masuk)
                                                    {{ $item->tahun_masuk }} -
                                                @endif
                                                {{ $item->tahun_lulus ?? '-' }}
                                            </td>
                                            <td>{{ $item->nilai ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="fas fa-graduation-cap fa-3x text-muted mb-3 d-block"></i>
                                                <p class="text-muted mb-0">Belum ada data pendidikan</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="organisasi">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Nama Organisasi</th>
                                        <th>Jabatan</th>
                                        <th>Tahun</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($organisasi as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>{{ $item->nama_organisasi }}</td>
                                            <td>{{ $item->jabatan }}</td>
                                            <td>{{ $item->tahun }}</td>
                                            <td>{{ $item->deskripsi ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                                                <p class="text-muted mb-0">Belum ada data organisasi</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="magang">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Perusahaan</th>
                                        <th>Posisi</th>
                                        <th>Durasi</th>
                                        <th>Lokasi</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($magang as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>{{ $item->perusahaan }}</td>
                                            <td>{{ $item->posisi }}</td>
                                            <td>{{ $item->durasi }} bulan</td>
                                            <td>{{ $item->lokasi ?? '-' }}</td>
                                            <td>{{ $item->deskripsi ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="fas fa-briefcase fa-3x text-muted mb-3 d-block"></i>
                                                <p class="text-muted mb-0">Belum ada data magang/PKL</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pengalaman">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Perusahaan</th>
                                        <th>Jabatan</th>
                                        <th>Tahun</th>
                                        <th>Lokasi</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pengalaman as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>{{ $item->perusahaan }}</td>
                                            <td>{{ $item->jabatan }}</td>
                                            <td>{{ $item->tahun_masuk }} -
                                                {{ $item->is_current ? 'Sekarang' : $item->tahun_keluar }}</td>
                                            <td>{{ $item->lokasi ?? '-' }}</td>
                                            <td>{{ $item->deskripsi ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="fas fa-history fa-3x text-muted mb-3 d-block"></i>
                                                <p class="text-muted mb-0">Belum ada data pengalaman kerja</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="bahasa">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Bahasa</th>
                                        <th>Tingkat</th>
                                        <th>Sertifikat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($bahasa as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td>{{ $item->nama_bahasa }}</td>
                                            <td><span
                                                    class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1">{{ $item->tingkat }}</span>
                                            </td>
                                            <td>
                                                @if ($item->sertifikat)
                                                    <a href="{{ Storage::url($item->sertifikat) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">Lihat</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <i class="fas fa-language fa-3x text-muted mb-3 d-block"></i>
                                                <p class="text-muted mb-0">Belum ada data bahasa</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="keahlian">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Keahlian</th>
                                        <th>Tingkat</th>
                                        <th>Sertifikat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($keahlian as $key => $item)
                                        <tr>
                                            <td class="text-center">{{ $key + 1 }}</td>
                                            <td><span class="fw-semibold">{{ $item->nama_keahlian }}</span></td>
                                            <td>
                                                @if ($item->tingkat)
                                                    <span
                                                        class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1">{{ $item->tingkat }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->sertifikat)
                                                    <a href="{{ Storage::url($item->sertifikat) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1">
                                                        <i class="fas fa-download me-1"></i>Lihat
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <i class="fas fa-code fa-3x text-muted mb-3 d-block"></i>
                                                <p class="text-muted mb-0">Belum ada data keahlian</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .rounded-4 {
            border-radius: 16px !important;
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

        .bg-opacity-20 {
            --bs-bg-opacity: 0.2;
        }

        .nav-tabs .nav-link {
            color: #64748b;
            border: none;
            transition: all 0.2s ease;
            background: transparent;
            white-space: nowrap;
        }

        .nav-tabs .nav-link:hover {
            color: #2563eb;
            background: rgba(37, 99, 235, 0.05);
        }

        .nav-tabs .nav-link.active {
            color: #2563eb;
            background: #eff6ff;
        }

        .info-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
        }

        .info-card-header {
            background: #f8fafc;
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 600;
            font-size: 0.85rem;
            color: #1e293b;
        }

        .info-card-header i {
            margin-right: 8px;
        }

        .info-card-body {
            padding: 12px 16px;
        }

        .info-row {
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #f1f3f5;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            width: 140px;
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 500;
        }

        .info-value {
            flex: 1;
            font-size: 0.85rem;
            color: #1e293b;
        }

        .address-item {
            background: #f8fafc;
            padding: 10px 12px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .address-label {
            font-size: 0.7rem;
            color: #64748b;
            display: block;
            margin-bottom: 5px;
        }

        .address-value {
            font-size: 0.8rem;
            color: #1e293b;
            font-weight: 600;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-bordered {
            border: 1px solid #cbd5e1 !important;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #cbd5e1 !important;
            padding: 12px 15px;
            vertical-align: middle;
        }

        .table thead th {
            background-color: #f1f5f9;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
        }

        .table tbody td {
            font-size: 0.8rem;
            color: #334155;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        @media (max-width: 768px) {
            .info-row {
                flex-direction: column;
            }

            .info-label {
                width: 100%;
                margin-bottom: 5px;
            }

            .nav-tabs {
                flex-wrap: nowrap;
                overflow-x: auto;
                padding-bottom: 8px;
            }

            .address-item {
                margin-bottom: 8px;
            }

            .table-bordered th,
            .table-bordered td {
                padding: 8px 10px;
                font-size: 0.7rem;
            }
        }
    </style>
@endsection
