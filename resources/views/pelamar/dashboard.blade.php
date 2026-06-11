@extends('layouts.pelamar')

@section('title', 'Dashboard')

@push('styles')
    <style>
        /* Welcome banner */
        .welcome-banner {
            background: linear-gradient(135deg, #1a56db 0%, #1341b0 100%);
            border-radius: var(--radius-lg);
            padding: 34px 36px;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 24px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(26, 86, 219, .3);
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            right: -80px;
            top: -80px;
            width: 260px;
            height: 260px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .07);
            pointer-events: none;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            right: 100px;
            bottom: -100px;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            pointer-events: none;
        }

        .welcome-greeting {
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -.5px;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .welcome-sub {
            font-size: 14px;
            color: rgba(255, 255, 255, .82);
            max-width: 480px;
            line-height: 1.55;
        }

        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, .15);
            border: 1px solid rgba(255, 255, 255, .22);
            color: #fff;
            font-size: 12.5px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 24px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .badge-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #4ade80;
            box-shadow: 0 0 0 2px rgba(74, 222, 128, .3);
        }

        /* Notice */
        .notice-banner {
            background: var(--warning-bg);
            border: 1px solid #fcd34d;
            border-radius: var(--radius-lg);
            padding: 14px 18px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 28px;
            font-size: 13.5px;
            color: #78350f;
        }

        .notice-banner i {
            font-size: 16px;
            color: var(--warning);
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Section label */
        .section-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .8px;
            margin-bottom: 16px;
        }

        /* Progress tracker */
        .step-track {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 26px 30px;
            margin-bottom: 32px;
            box-shadow: var(--shadow-sm);
        }

        .step-track-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 26px;
        }

        .step-track-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 2px;
        }

        .step-track-sub {
            font-size: 12px;
            color: var(--text-muted);
        }

        .steps-row {
            display: flex;
            align-items: flex-start;
        }

        .step-item {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            position: relative;
        }

        /* Connector line */
        .step-item:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 18px;
            left: calc(50% + 20px);
            right: calc(-50% + 20px);
            height: 2px;
            background: var(--border);
            z-index: 0;
        }

        .step-item.done:not(:last-child)::after {
            background: var(--success);
        }

        .step-item.active:not(:last-child)::after {
            background: linear-gradient(to right, var(--primary) 40%, var(--border) 100%);
        }

        .step-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid var(--border);
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: var(--text-muted);
            position: relative;
            z-index: 1;
            transition: all .2s;
        }

        .step-item.done .step-circle {
            background: var(--success);
            border-color: var(--success);
            color: #fff;
        }

        .step-item.active .step-circle {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            box-shadow: 0 0 0 4px rgba(26, 86, 219, .15);
        }

        .step-label {
            font-size: 11.5px;
            font-weight: 500;
            color: var(--text-muted);
            text-align: center;
            line-height: 1.3;
            max-width: 80px;
        }

        .step-item.done .step-label {
            color: var(--success);
            font-weight: 600;
        }

        .step-item.active .step-label {
            color: var(--primary);
            font-weight: 700;
        }

        /* Action cards grid */
        .action-cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 18px;
        }

        .action-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 26px 24px 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            text-decoration: none;
            transition: box-shadow .2s, transform .2s, border-color .2s;
            position: relative;
            overflow: hidden;
        }

        .action-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
            border-color: transparent;
        }

        .action-card-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .icon-blue {
            background: #dbeafe;
            color: #1a56db;
        }

        .icon-green {
            background: #d1fae5;
            color: #059669;
        }

        .icon-amber {
            background: #fef3c7;
            color: #d97706;
        }

        .icon-rose {
            background: #ffe4e6;
            color: #e11d48;
        }

        .icon-violet {
            background: #ede9fe;
            color: #7c3aed;
        }

        .icon-teal {
            background: #ccfbf1;
            color: #0d9488;
        }

        .action-card-body {
            flex: 1;
        }

        .action-card-label {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 5px;
            line-height: 1.25;
        }

        .action-card-desc {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.55;
        }

        .action-card-cta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
        }

        .action-card-cta i {
            font-size: 11px;
            transition: transform .2s;
        }

        .action-card:hover .action-card-cta i {
            transform: translateX(3px);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .action-cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .action-cards {
                grid-template-columns: 1fr;
            }

            .welcome-banner {
                padding: 24px;
                flex-direction: column;
                align-items: flex-start;
            }

            .welcome-badge {
                display: none;
            }

            .step-label {
                display: none;
            }

            .step-track {
                padding: 20px 16px;
            }
        }
    </style>
@endpush

@section('content')

    {{-- Welcome --}}
    <div class="welcome-banner">
        <div>
            <div class="welcome-greeting">Selamat Datang, {{ Auth::user()->name }} 👋</div>
            <p class="welcome-sub">Lacak proses lamaran Anda, lengkapi dokumen, dan ikuti setiap tahapan seleksi dengan
                mudah.</p>
        </div>
        <div class="welcome-badge">
            <span class="badge-dot"></span>
            Akun Aktif
        </div>
    </div>

    {{-- Notice --}}
    <div class="notice-banner">
        <i class="fas fa-triangle-exclamation"></i>
        <div>
            <strong>Penting:</strong> Pastikan semua dokumen wajib telah diunggah sebelum melanjutkan ke tahap psikotes.
        </div>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>{{ session('error') }}</div>
        </div>
    @endif


    {{-- Quick access --}}
    <div class="section-label">Akses Cepat</div>
    <div class="action-cards">

        <a href="{{ route('pelamar.profil.index') }}" class="action-card">
            <div class="action-card-icon icon-blue">
                <i class="fas fa-user"></i>
            </div>
            <div class="action-card-body">
                <div class="action-card-label">Profil Saya</div>
                <div class="action-card-desc">Lengkapi informasi pribadi, pendidikan, dan pengalaman kerja Anda.</div>
            </div>
            <span class="action-card-cta">Atur Profil <i class="fas fa-arrow-right"></i></span>
        </a>

        <a href="{{ route('pelamar.berkas.create') }}" class="action-card">
            <div class="action-card-icon icon-green">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div class="action-card-body">
                <div class="action-card-label">Unggah Dokumen</div>
                <div class="action-card-desc">Submit CV, ijazah, transkrip, dan dokumen pendukung lainnya.</div>
            </div>
            <span class="action-card-cta">Unggah Sekarang <i class="fas fa-arrow-right"></i></span>
        </a>

        <a href="{{ route('pelamar.pelamartahapan.index') }}" class="action-card">
            <div class="action-card-icon icon-amber">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="action-card-body">
                <div class="action-card-label">Status Tahapan Lamaran</div>
                <div class="action-card-desc">Monitor perkembangan aplikasi Anda di setiap tahapan seleksi.</div>
            </div>
            <span class="action-card-cta">Cek Status <i class="fas fa-arrow-right"></i></span>
        </a>


        <a href="{{ route('pelamar.offering.index') }}" class="action-card">
            <div class="action-card-icon icon-teal">
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="action-card-body">
                <div class="action-card-label">Penawaran Kerja</div>
                <div class="action-card-desc">Review dan tanggapi penawaran pekerjaan yang diberikan.</div>
            </div>
            <span class="action-card-cta">Lihat Tawaran <i class="fas fa-arrow-right"></i></span>
        </a>

    </div>

@endsection
