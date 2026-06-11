@extends('layouts.admin')

@section('title', 'Manajemen Kriteria SPK')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">
                            <i class="fas fa-weight-hanging me-2"></i> Manajemen Kriteria SPK
                        </h4>
                        <p class="small mb-0 mt-1 opacity-75">Tentukan bobot dan atribut kriteria penilaian seleksi kandidat
                        </p>
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

                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" id="tableSearch" class="form-control"
                                        placeholder="Cari kriteria...">
                                    <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select id="statusFilter" class="form-select">
                                    <option value="">-- Semua Status --</option>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Nonaktif">Nonaktif</option>
                                </select>
                            </div>
                            <div class="col-md-5 text-end">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modalTambah">
                                    <i class="fas fa-plus me-1"></i> Tambah Kriteria
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 10%">Kode</th>
                                        <th style="width: 30%">Nama Kriteria</th>
                                        <th class="text-center" style="width: 10%">Bobot (%)</th>
                                        <th class="text-center" style="width: 15%">Atribut</th>
                                        <th class="text-center" style="width: 10%">Status</th>
                                        <th class="text-center" style="width: 25%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kriterias as $kriteria)
                                        <tr class="data-row">
                                            <td class="text-center">
                                                <span class="fw-bold text-primary">{{ $kriteria->kode_kriteria }}</span>
                                            </td>
                                            <td>
                                                <span class="fw-semibold">{{ $kriteria->nama_kriteria }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold">{{ $kriteria->bobot }}%</span>
                                            </td>
                                            <td class="text-center">
                                                @if ($kriteria->atribut == 'benefit')
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-arrow-up me-1"></i> Benefit
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning">
                                                        <i class="fas fa-arrow-down me-1"></i> Cost
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($kriteria->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-secondary">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modalEdit{{ $kriteria->id }}">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm"
                                                        onclick="if(confirm('Yakin ingin menghapus kriteria ini?')) document.getElementById('delete-{{ $kriteria->id }}').submit();">
                                                        <i class="fas fa-trash me-1"></i> Hapus
                                                    </button>
                                                    <form id="delete-{{ $kriteria->id }}"
                                                        action="{{ route('admin.spk.kriteria.delete', $kriteria->id) }}"
                                                        method="POST" class="d-none">@csrf @method('DELETE')</form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <i class="fas fa-weight-hanging fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data kriteria</p>
                                                <button type="button" class="btn btn-primary btn-sm mt-2"
                                                    data-bs-toggle="modal" data-bs-target="#modalTambah">
                                                    <i class="fas fa-plus me-1"></i> Tambah Kriteria
                                                </button>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if (!$kriterias->isEmpty())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1 text-info"></i>
                                    Total Bobot: <strong>{{ $kriterias->sum('bobot') }}%</strong>
                                    @if ($kriterias->sum('bobot') != 100)
                                        <span class="text-danger ms-2">(Bobot harus 100% untuk perhitungan yang
                                            valid)</span>
                                    @endif
                                </div>
                                <div class="text-muted small">
                                    Total Kriteria: <strong>{{ $kriterias->count() }}</strong>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kriteria -->
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i> Tambah Kriteria Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.spk.kriteria.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Kode Kriteria <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                    <input type="text" name="kode_kriteria" class="form-control"
                                        placeholder="Contoh: C1" required>
                                </div>
                                <small class="text-muted">Kode unik untuk identifikasi kriteria</small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Nama Kriteria <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                    <input type="text" name="nama_kriteria" class="form-control"
                                        placeholder="Contoh: Seleksi Administrasi" required>
                                </div>
                                <small class="text-muted">Nama lengkap kriteria yang akan dinilai</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Bobot (%) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                    <input type="number" name="bobot" class="form-control" step="0.01"
                                        min="0" max="100" placeholder="25" required>
                                </div>
                                <small class="text-muted">Nilai bobot kriteria (total harus 100%)</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Atribut <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-chart-line"></i></span>
                                    <select name="atribut" class="form-select" required>
                                        <option value="benefit">Benefit (Semakin Besar Semakin Baik)</option>
                                        <option value="cost">Cost (Semakin Kecil Semakin Baik)</option>
                                    </select>
                                </div>
                                <small class="text-muted">Jenis pengaruh terhadap penilaian akhir</small>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Status</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_active"
                                            id="statusActive" value="1" checked>
                                        <label class="form-check-label" for="statusActive">
                                            <span class="badge bg-success">Aktif</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_active"
                                            id="statusInactive" value="0">
                                        <label class="form-check-label" for="statusInactive">
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">Keterangan</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Deskripsi lengkap kriteria..."></textarea>
                                </div>
                                <small class="text-muted">Informasi tambahan tentang kriteria ini</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Kriteria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Kriteria -->
    @foreach ($kriterias as $kriteria)
        <div class="modal fade" id="modalEdit{{ $kriteria->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-edit me-2"></i> Edit Kriteria
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.spk.kriteria.update', $kriteria->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Kode Kriteria</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        <input type="text" class="form-control"
                                            value="{{ $kriteria->kode_kriteria }}" readonly disabled>
                                    </div>
                                    <small class="text-muted">Kode kriteria tidak dapat diubah</small>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Nama Kriteria <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                        <input type="text" name="nama_kriteria" class="form-control"
                                            value="{{ $kriteria->nama_kriteria }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Bobot (%) <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                        <input type="number" name="bobot" class="form-control" step="0.01"
                                            min="0" max="100" value="{{ $kriteria->bobot }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">Atribut <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-chart-line"></i></span>
                                        <select name="atribut" class="form-select">
                                            <option value="benefit"
                                                {{ $kriteria->atribut == 'benefit' ? 'selected' : '' }}>Benefit (Semakin
                                                Besar Semakin Baik)</option>
                                            <option value="cost" {{ $kriteria->atribut == 'cost' ? 'selected' : '' }}>
                                                Cost (Semakin Kecil Semakin Baik)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_active"
                                                id="editStatusActive{{ $kriteria->id }}" value="1"
                                                {{ $kriteria->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="editStatusActive{{ $kriteria->id }}">
                                                <span class="badge bg-success">Aktif</span>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="is_active"
                                                id="editStatusInactive{{ $kriteria->id }}" value="0"
                                                {{ !$kriteria->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" for="editStatusInactive{{ $kriteria->id }}">
                                                <span class="badge bg-secondary">Nonaktif</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-semibold">Keterangan</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-file-alt"></i></span>
                                        <textarea name="keterangan" class="form-control" rows="3">{{ $kriteria->keterangan }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update Kriteria</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var searchInput = document.getElementById('tableSearch');
            var statusFilter = document.getElementById('statusFilter');

            function applyFilters() {
                var searchText = searchInput ? searchInput.value.toLowerCase() : '';
                var statusValue = statusFilter ? statusFilter.value.toLowerCase() : '';
                var rows = document.querySelectorAll('.table tbody tr.data-row');

                rows.forEach(function(row) {
                    var namaKriteria = row.querySelector('td:nth-child(2)')?.innerText.toLowerCase() || '';
                    var statusKriteria = row.querySelector('td:nth-child(5) .badge')?.innerText
                        .toLowerCase() || '';

                    var matchSearch = namaKriteria.includes(searchText);
                    var matchStatus = statusValue === '' || statusKriteria === statusValue;

                    row.style.display = (matchSearch && matchStatus) ? '' : 'none';
                });
            }

            if (searchInput) searchInput.addEventListener('input', applyFilters);
            if (statusFilter) statusFilter.addEventListener('change', applyFilters);
        });
    </script>
@endsection
