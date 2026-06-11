@extends('layouts.admin')
@section('title', 'Jawaban Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h5 class="mb-0"><i class="fas fa-reply me-2"></i> Daftar Jawaban Psikotes</h5>
                            <small>Total: {{ $jawabans->total() }} jawaban</small>
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

                        <div class="row g-2 mb-3 align-items-center">
                            <div class="col-md-4">
                                <form action="{{ route('admin.jawabanpsikotes.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama pelamar..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit"><i
                                                class="fas fa-search"></i></button>
                                        @if (request('search'))
                                            <a href="{{ route('admin.jawabanpsikotes.index') }}"
                                                class="btn btn-outline-secondary"><i class="fas fa-times"></i></a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Pelamar</th>
                                        <th>Soal</th>
                                        <th class="text-center">Jawaban</th>
                                        <th class="text-center">Kunci</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Skor</th>
                                        <th class="text-center">Waktu Jawab</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jawabans as $key => $jawaban)
                                        @php
                                            $soal = $jawaban->soalPsikotes;
                                            $isKognitif = $soal && $soal->jenis === 'kognitif';
                                            $isKepribadian = $soal && $soal->jenis === 'kepribadian';
                                            // PERBAIKAN: Gunakan 'minat' bukan 'minat_bakat'
                                            $isMinat = $soal && $soal->jenis === 'minat';
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ $jawabans->firstItem() + $key }}</td>
                                            <td>
                                                <strong>{{ $jawaban->pelamar->nama_lengkap ?? '-' }}</strong>
                                            </td>
                                            <td>{{ Str::limit($soal->pertanyaan ?? '-', 60) }}</td>
                                            <td class="text-center">
                                                <strong>{{ $jawaban->jawaban }}</strong>
                                            </td>
                                            <td class="text-center">
                                                {{ $soal->kunci_jawaban ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                @if ($isKognitif)
                                                    @if ($jawaban->benar == 1)
                                                        <span style="color: #059669; font-weight: 500;">✓ Benar</span>
                                                    @elseif ($jawaban->benar === 0)
                                                        <span style="color: #dc2626; font-weight: 500;">✗ Salah</span>
                                                    @else
                                                        <span style="color: #6b7280;">-</span>
                                                    @endif
                                                @elseif ($isKepribadian)
                                                    <span style="color: #6b7280;">Kepribadian</span>
                                                @elseif ($isMinat)
                                                    <span style="color: #d97706;">Minat</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($isKognitif)
                                                    <span class="fw-semibold">{{ $jawaban->skor ?? 0 }}</span>
                                                @elseif ($isKepribadian)
                                                    <span class="fw-semibold">{{ $jawaban->skor_kepribadian ?? 0 }}</span>
                                                @elseif ($isMinat)
                                                    <span class="fw-semibold">{{ $jawaban->skor_minat_bakat ?? 0 }}</span>
                                                @else
                                                    <span class="fw-semibold">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if ($jawaban->waktu_jawab)
                                                    <div class="d-flex flex-column">
                                                        <span
                                                            class="fw-semibold">{{ $jawaban->waktu_jawab->format('d M Y') }}</span>
                                                        <small
                                                            class="text-muted">{{ $jawaban->waktu_jawab->format('H:i:s') }}</small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.jawabanpsikotes.show', $jawaban->id) }}"
                                                        class="btn btn-outline-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-danger" title="Hapus"
                                                        onclick="if(confirm('Hapus jawaban ini?')) document.getElementById('delete-{{ $jawaban->id }}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    <form id="delete-{{ $jawaban->id }}"
                                                        action="{{ route('admin.jawabanpsikotes.destroy', $jawaban->id) }}"
                                                        method="POST" class="d-none">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-4">
                                                <i class="fas fa-reply fa-2x text-muted mb-2"></i>
                                                <p class="mb-0">Belum ada data jawaban</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($jawabans->hasPages())
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted small">
                                    Menampilkan {{ $jawabans->firstItem() }} - {{ $jawabans->lastItem() }}
                                    dari {{ $jawabans->total() }} hasil
                                </div>
                                <div>{{ $jawabans->withQueryString()->links('pagination::bootstrap-4') }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
