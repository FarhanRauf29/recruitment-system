@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Detail Tahapan Pelamar</h3>
                        <div>
                            <a href="{{ route('admin.pelamartahapan.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <a href="{{ route('admin.pelamartahapan.edit', $pelamartahapan) }}"
                                class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th style="width: 30%;">Pelamar</th>
                                <td>{{ $pelamartahapan->pelamar->nama_lengkap ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Lowongan</th>
                                <td>{{ $pelamartahapan->lowongan->judul_lowongan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tahapan Rekrutmen</th>
                                <td>{{ $pelamartahapan->tahapanRekrutmen->nama_tahapan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>{{ ucfirst($pelamartahapan->status) }}</td>
                            </tr>
                            <tr>
                                <th>Catatan</th>
                                <td>{{ $pelamartahapan->catatan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Mulai</th>
                                <td>{{ $pelamartahapan->tanggal_mulai ? $pelamartahapan->tanggal_mulai->format('d-m-Y H:i') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Selesai</th>
                                <td>{{ $pelamartahapan->tanggal_selesai ? $pelamartahapan->tanggal_selesai->format('d-m-Y H:i') : '-' }}
                                </td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>{{ $pelamartahapan->created_at->format('l, d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Terakhir Diperbarui</th>
                                <td>{{ $pelamartahapan->updated_at->format('l, d F Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
