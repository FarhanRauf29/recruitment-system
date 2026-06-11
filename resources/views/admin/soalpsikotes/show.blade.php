@extends('layouts.admin')
@section('title', 'Detail Soal Psikotes')
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i> Detail Soal Psikotes</h5>
                        <div>
                            <a href="{{ route('admin.soalpsikotes.index') }}" class="btn btn-light btn-sm"><i
                                    class="fas fa-arrow-left me-1"></i> Kembali</a>
                            <a href="{{ route('admin.soalpsikotes.edit', $soal->id) }}"
                                class="btn btn-warning btn-sm ms-1"><i class="fas fa-edit me-1"></i> Edit</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width:35%">Kode Soal</th>
                                        <td><code>{{ $soal->kode_soal }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Kategori</th>
                                        <td>{{ $soal->kategori->nama_kategori ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jenis</th>
                                        <td>{{ ucfirst($soal->jenis) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Bobot Nilai</th>
                                        <td>{{ $soal->bobot_nilai }} poin</td>
                                    </tr>
                                    <tr>
                                        <th>Waktu per Soal</th>
                                        <td>{{ $soal->waktu_per_soal_detik }} detik</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{{ $soal->aktif ? 'Aktif' : 'Nonaktif' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width:35%">Tingkat Kesulitan</th>
                                        <td>
                                            @php $level = $soal->tingkat_kesulitan; @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="fas fa-star {{ $i <= $level ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                            <span class="ms-2">({{ $level }}/5)</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Kunci Jawaban</th>
                                        <td>{{ $soal->kunci_jawaban ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Jumlah Jawaban</th>
                                        <td>{{ $soal->jawabanPsikotes()->count() }} jawaban</td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat</th>
                                        <td>{{ $soal->created_at->format('d M Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Diperbarui</th>
                                        <td>{{ $soal->updated_at->format('d M Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="fw-bold mb-2">Pertanyaan</label>
                            <div class="bg-light p-3 rounded">{!! nl2br(e($soal->pertanyaan)) !!}</div>
                        </div>

                        <div class="mt-4">
                            <label class="fw-bold mb-2">Opsi Jawaban</label>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="10%">Huruf</th>
                                            <th>Opsi</th>
                                            @if ($soal->kunci_jawaban)
                                                <th width="15%">Kunci</th>
                                            @endif
                                    </thead>
                                    <tbody>
                                        @php $opsiJawaban = is_array($soal->opsi_jawaban) ? $soal->opsi_jawaban : json_decode($soal->opsi_jawaban, true) ?? []; @endphp
                                        @foreach ($opsiJawaban as $huruf => $opsi)
                                            <tr>
                                                <td class="text-center"><strong>{{ $huruf }}</strong></td>
                                                <td>{{ $opsi }}</td>
                                                @if ($soal->kunci_jawaban)
                                                    <td class="text-center">
                                                        @if ($huruf == $soal->kunci_jawaban)
                                                            <i class="fas fa-check-circle text-success"></i> Benar
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($soal->penjelasan)
                            <div class="mt-4">
                                <label class="fw-bold mb-2">Penjelasan</label>
                                <div class="bg-light p-3 rounded">{!! nl2br(e($soal->penjelasan)) !!}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
