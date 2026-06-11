@extends('layouts.admin')

@section('title', 'Sistem Pendukung Keputusan')

@section('content')
    <div class="container-fluid py-4 bg-slate-50" style="min-height: 100vh;">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border border-slate-200 rounded-3 overflow-hidden bg-white">

                    <div
                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center flex-wrap py-3 border-0 rounded-0">
                        <div>
                            <h4 class="card-title mb-0 fw-bold d-flex align-items-center gap-2"
                                style="font-size: 1.25rem; letter-spacing: -0.02em;">
                                <i class="fas fa-balance-scale"></i> Sistem Pendukung Keputusan
                            </h4>
                            <p class="small mb-0 mt-1 opacity-75">Analisis seleksi kandidat menggunakan penggabungan metode
                                SAW & TOPSIS</p>
                        </div>
                        <div class="d-flex gap-2 mt-2 mt-md-0">
                            <a href="{{ route('admin.spk.kriteria') }}"
                                class="btn btn-light btn-sm text-primary fw-semibold px-3 py-2 rounded-2 shadow-xs transition-all hover-lift">
                                <i class="fas fa-sliders-h me-1"></i> Kelola Kriteria Bobot
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-4">

                        @if (session('success'))
                            <div
                                class="alert alert-success alert-dismissible fade show
border-start border-4 border-success shadow-sm">
                                role="alert">
                                <div class="d-flex align-items-center text-success">
                                    <i class="fas fa-check-circle me-3 fs-5"></i>
                                    <div class="text-slate-700 small"><strong>Notifikasi Sistem:</strong>
                                        {{ session('success') }}</div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                    style="font-size: 0.75rem;"></button>
                            </div>
                        @endif

                        <div class="card border border-slate-200 shadow-xs mb-4 bg-white rounded-2">
                            <div class="card-body p-3">
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <div class="input-group input-group-sm border border-slate-300 rounded-2 overflow-hidden shadow-xs"
                                        style="width: 280px;">
                                        <span class="input-group-text bg-white border-0 text-slate-400 ps-3"><i
                                                class="fas fa-search" style="font-size: 0.8rem;"></i></span>
                                        <input type="text"
                                            class="form-control border-0 bg-white ps-1 py-2 text-slate-700 text-form-custom focus-none"
                                            id="tableSearch" placeholder="Cari posisi lowongan jabatan...">
                                    </div>
                                    <select
                                        class="form-select form-select-sm border border-slate-300 text-slate-700 fw-medium focus-none rounded-2"
                                        id="statusFilter" style="width: 160px; font-size: 0.85rem; height: 34px;">
                                        <option value="">Semua Status</option>
                                        <option value="AKTIF">Status: Aktif</option>
                                        <option value="NON-AKTIF">Status: Non-Aktif</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card border border-slate-200 shadow-xs bg-white rounded-2 overflow-hidden">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle mb-0 enterprise-table">
                                    <thead
                                        class="bg-slate-100 text-slate-500 text-uppercase tracking-wider fw-bold border-bottom border-slate-200"
                                        style="font-size: 0.68rem;">
                                        <tr>
                                            <th scope="col" class="ps-4 py-2.5" style="width: 35%;">Informasi Lowongan
                                            </th>
                                            <th scope="col" class="py-2.5 px-3" style="width: 32%;">Deskripsi Spesifikasi
                                                Teknis</th>
                                            <th scope="col" class="text-center py-2.5" style="width: 11%;">Pelamar</th>
                                            <th scope="col" class="text-center py-2.5" style="width: 12%;">Status
                                            </th>
                                            <th scope="col" class="text-end pe-4 py-2.5" style="width: 10%;">Aksi
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-slate-700" style="font-size: 0.85rem;">
                                        @forelse ($lowongans as $lowongan)
                                            <tr class="data-row">
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        <div
                                                            class="job-icon-silhouette bg-slate-50 border border-slate-200 text-primary rounded-2 shadow-xs">
                                                            <i class="fas fa-briefcase"></i>
                                                        </div>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold text-slate-900 row-title"
                                                                style="font-size: 0.92rem;">{{ $lowongan->judul_lowongan }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-3 px-3">
                                                    <span class="text-slate-800 description-text">
                                                        {{ Str::limit($lowongan->deskripsi ?? 'Parameter kualifikasi dasar belum dideskripsikan pada formulir rekrutmen ini.', 90) }}
                                                    </span>
                                                </td>
                                                <td class="text-center py-3">
                                                    <span class="fw-semibold text-slate-800 " style="font-size: 1.0 rem;">
                                                        {{ $lowongan->pelamars()->count() }} Pelamar
                                                    </span>
                                                </td>
                                                <td class="text-center py-3">
                                                    @if (strtolower($lowongan->status) == 'buka' || strtolower($lowongan->status) == 'aktif')
                                                        <span class="badge-custom badge-active">
                                                            <span class="dot-core bg-success"></span> AKTIF
                                                        </span>
                                                    @else
                                                        <span class="badge-custom badge-inactive">
                                                            <span class="dot-core bg-secondary"></span> NON-AKTIF
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-end pe-4 py-3">
                                                    <a href="{{ route('admin.spk.penilaian', $lowongan->id) }}"
                                                        class="btn btn-primary btn-sm px-3 py-1.5 font-semibold rounded-2 btn-corporate-primary shadow-xs">
                                                        <i class="fas fa-calculator me-1" style="font-size: 0.75rem;"></i>
                                                        Analisis
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-5 bg-white border-0">
                                                    <div class="py-4">
                                                        <div
                                                            class="empty-icon-silhouette border border-slate-200 bg-slate-50 mx-auto mb-3 shadow-xs">
                                                            <i class="fas fa-inbox text-slate-400"></i>
                                                        </div>
                                                        <h6 class="fw-bold text-slate-900 mb-1" style="font-size: 0.95rem;">
                                                            Database Lowongan Kosong</h6>
                                                        <p class="text-slate-400 small mx-auto mb-4"
                                                            style="max-width: 360px; line-height: 1.5;">Tidak ada entri
                                                            data posisi jabatan aktif dalam pipeline yang terikat pada
                                                            matriks SPK saat ini.</p>
                                                        <a href="{{ route('admin.lowongan.create') }}"
                                                            class="btn btn-primary btn-sm font-semibold px-4 py-2 rounded-2 btn-corporate-primary">
                                                            <i class="fas fa-plus me-2" style="font-size: 0.75rem;"></i>
                                                            Buat Lowongan Baru
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            @if (!$lowongans->isEmpty())
                                <div
                                    class="card-footer bg-slate-50 border-top border-slate-200 p-3 d-flex justify-content-between align-items-center">
                                    <span class="text-slate-400 font-normal" style="font-size: 0.76rem;"><i
                                            class="fas fa-info-circle me-1.5"></i> Seluruh data pemrosesan dihitung
                                        otomatis menggunakan matriks preferensi terpusat.</span>
                                    <div class="text-slate-500 fw-medium" style="font-size: 0.8rem;">Total Baris
                                        Terdaftar: <span class="text-slate-900 fw-bold">{{ $lowongans->count() }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            /* === ENTERPRISE CORE SYSTEM TOKENS === */
            .text-slate-900 {
                color: #0f172a !important;
            }

            .text-slate-700 {
                color: #334155 !important;
            }

            .text-slate-500 {
                color: #64748b !important;
            }

            .text-slate-400 {
                color: #94a3b8 !important;
            }

            .bg-slate-50 {
                background-color: #f8fafc !important;
            }

            .bg-slate-100 {
                background-color: #f1f5f9 !important;
            }

            .border-slate-200 {
                border-color: #e2e8f0 !important;
            }

            .border-slate-300 {
                border-color: #cbd5e1 !important;
            }

            .border-slate-100 {
                border-color: #f1f5f9 !important;
            }

            /* === UTILITY SPECIFICATIONS === */
            .rounded-2 {
                border-radius: 6px !important;
            }

            .rounded-3 {
                border-radius: 8px !important;
            }

            .shadow-xs {
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            }

            .tracking-wider {
                letter-spacing: 0.04em;
            }

            .tracking-tight {
                letter-spacing: -0.02em;
            }

            .text-form-custom {
                font-size: 0.85rem;
            }

            /* Focus Outline Override */
            .focus-none:focus {
                box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.15) !important;
                border-color: #86b7fe !important;
                outline: none;
            }

            /* === HEADER & BUTTON STYLES === */
            .btn-light {
                background-color: #ffffff;
                color: #0d6efd;
                border: 1px solid #e2e8f0;
            }

            .btn-light:hover {
                background-color: #f8fafc;
                border-color: #cbd5e1;
            }

            .btn-white {
                background-color: #ffffff;
            }

            .hover-bg-slate-50:hover {
                background-color: #f8fafc !important;
                border-color: #cbd5e1 !important;
                color: #0f172a !important;
            }

            .btn-corporate-primary {
                background-color: #0d6efd !important;
                border-color: #0d6efd !important;
                transition: all 0.15s ease;
            }

            .btn-corporate-primary:hover {
                background-color: #0b5ed7 !important;
                border-color: #0a58ca !important;
            }

            .hover-lift {
                transition: transform 0.2s;
            }

            .hover-lift:hover {
                transform: translateY(-1px);
            }

            /* === DATA GRID ENTERPRISE VIEW WITH COLUMN LINES === */
            .enterprise-table.table-bordered {
                border: 1px solid #e2e8f0 !important;
            }

            .enterprise-table.table-bordered th,
            .enterprise-table.table-bordered td {
                border: 1px solid #e2e8f0 !important;
                /* Menampilkan garis vertikal antar kolom */
            }

            .enterprise-table tbody tr {
                transition: background-color 0.1s ease;
            }

            .enterprise-table tbody tr:hover {
                background-color: #f8fafc;
            }

            .job-icon-silhouette {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .empty-icon-silhouette {
                width: 52px;
                height: 52px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
            }

            .description-text {
                font-size: 0.84rem;
                line-height: 1.45;
                display: block;
            }

            /* Professional Soft-Pill Status Badges */
            .badge-custom {
                display: inline-flex;
                align-items: center;
                padding: 3px 10px;
                font-size: 0.72rem;
                font-weight: 700;
                border-radius: 4px;
                letter-spacing: 0.3px;
            }

            .badge-active {
                background-color: #f0fdf4;
                color: #166534;
                border: 1px solid #bbf7d0;
            }

            .badge-inactive {
                background-color: #f8fafc;
                color: #475569;
                border: 1px solid #e2e8f0;
            }

            .dot-core {
                width: 6px;
                height: 6px;
                border-radius: 50%;
                display: inline-block;
                margin-right: 6px;
            }
        </style>

        <script>
            // Real-Time Multiplex Client-Side Filtering Mechanism
            document.addEventListener('DOMContentLoaded', function() {
                var searchInput = document.getElementById('tableSearch');
                var statusFilter = document.getElementById('statusFilter');

                function applyConsoleFilters() {
                    var searchVal = searchInput ? searchInput.value.toLowerCase() : '';
                    var statusVal = statusFilter ? statusFilter.value.toUpperCase() : '';
                    var dataRows = document.querySelectorAll('.enterprise-table tbody tr.data-row');

                    dataRows.forEach(function(row) {
                        var titleTarget = row.querySelector('.row-title');
                        var badgeTarget = row.querySelector('.badge-custom');

                        if (titleTarget) {
                            var isTitleMatched = titleTarget.textContent.toLowerCase().includes(searchVal);
                            var isStatusMatched = true;

                            if (statusVal !== '') {
                                var currentStatus = badgeTarget ? badgeTarget.textContent.trim().toUpperCase() :
                                    '';
                                isStatusMatched = currentStatus.includes(statusVal);
                            }

                            row.style.display = (isTitleMatched && isStatusMatched) ? "" : "none";
                        }
                    });
                }

                if (searchInput) searchInput.addEventListener('input', applyConsoleFilters);
                if (statusFilter) statusFilter.addEventListener('change', applyConsoleFilters);
            });
        </script>
    @endpush
@endsection
