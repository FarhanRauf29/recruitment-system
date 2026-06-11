@extends('layouts.admin')
@section('title', 'Soal Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i> Bank Soal Psikotes</h5>
                            <small>Total: {{ $soals->total() }} soal</small>
                        </div>
                        <div class="d-flex gap-2 mt-2 mt-md-0">
                            {{-- <div class="btn-group">
                                <button type="button" class="btn btn-light btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <i class="fas fa-download me-1"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Export ke Excel</a></li>
                                </ul>
                            </div> --}}
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="fas fa-upload me-1"></i> Import Soal
                            </button>
                            <a href="{{ route('admin.soalpsikotes.create') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i> Tambah Soal
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <div class="row g-2 mb-3">
                            <div class="col-md-4">
                                <form action="{{ route('admin.soalpsikotes.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" placeholder="Cari soal..."
                                            value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.soalpsikotes.index') }}"
                                                class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-3">
                                <select name="kategori" class="form-select filter-select" data-filter="kategori">
                                    <option value="">-- Semua Kategori --</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}"
                                            {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="jenis" class="form-select filter-select" data-filter="jenis">
                                    <option value="">-- Semua Jenis --</option>
                                    <option value="kognitif" {{ request('jenis') == 'kognitif' ? 'selected' : '' }}>
                                        Kognitif</option>
                                    <option value="kepribadian" {{ request('jenis') == 'kepribadian' ? 'selected' : '' }}>
                                        Kepribadian</option>
                                    <option value="minat" {{ request('jenis') == 'minat' ? 'selected' : '' }}>Minat
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Kode Soal</th>
                                        <th>Pertanyaan</th>
                                        <th>Kategori</th>
                                        <th>Kesulitan</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($soals as $key => $soal)
                                        <tr>
                                            <td class="text-center">{{ $soals->firstItem() + $key }}</td>
                                            <td><span class="badge bg-secondary">{{ $soal->kode_soal }}</span></td>
                                            <td>{{ Str::limit($soal->pertanyaan, 80) }}<br></td>
                                            <td><span
                                                    class="badge {{ $soal->jenis == 'kognitif' ? 'bg-primary' : ($soal->jenis == 'kepribadian' ? 'bg-warning' : 'bg-success') }}">{{ $soal->kategori->nama_kategori ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @php $level = $soal->tingkat_kesulitan; @endphp
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i
                                                        class="fas fa-star {{ $i <= $level ? 'text-warning' : 'text-muted' }}"></i>
                                                @endfor
                                            </td>
                                            <td><span
                                                    class="badge {{ $soal->aktif ? 'bg-success' : 'bg-secondary' }}">{{ $soal->aktif ? 'Aktif' : 'Nonaktif' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.soalpsikotes.show', $soal->id) }}"
                                                        class="btn btn-outline-info" title="Detail"><i
                                                            class="fas fa-eye"></i></a>
                                                    <a href="{{ route('admin.soalpsikotes.edit', $soal->id) }}"
                                                        class="btn btn-outline-warning" title="Edit"><i
                                                            class="fas fa-edit"></i></a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Hapus soal ini?')) document.getElementById('delete-{{ $soal->id }}').submit();"><i
                                                            class="fas fa-trash"></i></button>
                                                    <form id="delete-{{ $soal->id }}"
                                                        action="{{ route('admin.soalpsikotes.destroy', $soal->id) }}"
                                                        method="POST" class="d-none">@csrf @method('DELETE')</form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-question-circle fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data soal</p>
                                                <a href="{{ route('admin.soalpsikotes.create') }}"
                                                    class="btn btn-primary btn-sm mt-2">Tambah Soal</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($soals->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    Menampilkan {{ $soals->firstItem() }} - {{ $soals->lastItem() }} dari
                                    {{ $soals->total() }} hasil
                                </div>
                                <div>
                                    {{ $soals->withQueryString()->links('pagination::bootstrap-4') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title"><i class="fas fa-file-import me-2 text-primary"></i> Import Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.soalpsikotes.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <strong><i class="fas fa-info-circle me-1"></i> Petunjuk:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Download template Excel</li>
                                <li>Isi data sesuai format</li>
                                <li>File max 5MB (.xlsx, .xls, .csv)</li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pilih File</label>
                            <input type="file" name="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                        </div>
                        <a href="{{ route('admin.soalpsikotes.download-template') }}"
                            class="btn btn-outline-primary btn-sm"><i class="fas fa-download me-1"></i> Download
                            Template</a>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.filter-select').forEach(select => {
            select.addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set(this.getAttribute('data-filter'), this.value);
                window.location.href = url.toString();
            });
        });
    </script>
@endsection
