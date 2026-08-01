<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recruitment System — @yield('title', 'Pelamar Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #1a56db;
            --primary-hover: #1648c0;
            --primary-light: #eef3ff;
            --primary-dark: #1e40af;
            --surface: #ffffff;
            --bg: #f1f5f9;
            --border: #e2e8f0;
            --border-strong: #cbd5e1;
            --text: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --success: #059669;
            --success-bg: #d1fae5;
            --warning: #d97706;
            --warning-bg: #fef3c7;
            --danger: #dc2626;
            --danger-bg: #fee2e2;
            --info: #2563eb;
            --info-bg: #dbeafe;
            --nav-h: 72px;
            --radius-sm: 6px;
            --radius: 10px;
            --radius-lg: 14px;
            --shadow-xs: 0 1px 2px rgba(0, 0, 0, .05);
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, .08), 0 1px 2px rgba(0, 0, 0, .04);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, .08), 0 2px 4px rgba(0, 0, 0, .04);
            --shadow-lg: 0 10px 24px rgba(0, 0, 0, .10), 0 4px 8px rgba(0, 0, 0, .04);
        }

        *,
        *::before,
        *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.55;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 14px;
        }

        /* ── TOP NAVIGATION ─────────────────────────────── */
        .topnav {
            height: var(--nav-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 1040;
            display: flex;
            align-items: center;
            box-shadow: 0 1px 0 var(--border), var(--shadow-xs);
        }

        .topnav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 24px;
        }

        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-logo {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
        }

        .brand-logo img {
            width: 78%;
            height: auto;
        }

        .brand-text {}

        .brand-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -.3px;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 11.5px;
            color: var(--text-muted);
            font-weight: 400;
            line-height: 1.2;
        }

        /* Divider after brand */
        .nav-divider {
            width: 1px;
            height: 28px;
            background: var(--border);
            flex-shrink: 0;
        }

        /* Main nav links */
        .nav-main {
            display: flex;
            align-items: center;
            gap: 1px;
            list-style: none;
            flex: 1;
        }

        .nav-main .nav-link {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 9px 15px;
            border-radius: var(--radius-sm);
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text-secondary);
            text-decoration: none;
            transition: color .18s, background .18s;
            white-space: nowrap;
            line-height: 1;
        }

        .nav-main .nav-link i {
            font-size: 13px;
            width: 15px;
            text-align: center;
            flex-shrink: 0;
            opacity: .75;
        }

        .nav-main .nav-link:hover {
            color: var(--primary);
            background: var(--primary-light);
        }

        .nav-main .nav-link:hover i {
            opacity: 1;
        }

        .nav-main .nav-link.active {
            color: var(--primary);
            background: var(--primary-light);
            font-weight: 600;
        }

        .nav-main .nav-link.active i {
            opacity: 1;
        }

        /* User zone */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px 6px 6px;
            border-radius: 10px;
            background: transparent;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: all .18s;
            text-decoration: none;
        }

        .user-btn:hover {
            background: var(--bg);
            border-color: var(--border-strong);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border);
            flex-shrink: 0;
        }

        .user-info {}

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            max-width: 130px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            line-height: 1.2;
        }

        .user-role {
            font-size: 11px;
            color: var(--text-muted);
            line-height: 1.2;
        }

        .user-chevron {
            font-size: 10px;
            color: var(--text-muted);
            margin-left: 2px;
        }

        /* Dropdown */
        .dropdown-menu {
            border: 1px solid var(--border);
            box-shadow: var(--shadow-lg);
            border-radius: var(--radius-lg);
            padding: 6px;
            min-width: 210px;
            margin-top: 8px !important;
        }

        .dropdown-header-custom {
            padding: 10px 12px 8px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 4px;
        }

        .dropdown-header-custom .dh-name {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--text);
        }

        .dropdown-header-custom .dh-role {
            font-size: 11.5px;
            color: var(--text-muted);
        }

        .dropdown-menu .dropdown-item {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 8px 12px;
            font-size: 13px;
            color: var(--text-secondary);
            border-radius: var(--radius-sm);
            transition: all .18s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-menu .dropdown-item i {
            width: 14px;
            font-size: 12px;
            color: var(--text-muted);
        }

        .dropdown-menu .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
        }

        .dropdown-menu .dropdown-item:hover i {
            color: var(--primary);
        }

        .dropdown-menu .dropdown-item.text-danger:hover {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .dropdown-menu .dropdown-item.text-danger:hover i {
            color: var(--danger);
        }

        /* Hamburger */
        .hamburger {
            display: none;
            background: none;
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 8px 11px;
            cursor: pointer;
            color: var(--text-secondary);
            transition: all .18s;
        }

        .hamburger:hover {
            background: var(--bg);
            color: var(--text);
        }

        /* Mobile drawer */
        .nav-drawer {
            display: none;
        }

        /* ── MAIN CONTENT ─────────────────────────────── */
        main {
            flex: 1;
            padding: 32px 0 56px;
        }

        /* ── SHARED COMPONENTS ────────────────────────── */
        .card {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            background: var(--surface);
            transition: box-shadow .2s;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 16px 22px;
            font-size: 14px;
            font-weight: 600;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important;
        }

        .card-body {
            padding: 22px;
        }

        .table-responsive {
            border-radius: var(--radius);
            overflow-x: auto;
        }

        .table {
            margin-bottom: 0;
            min-width: 580px;
        }

        .table th {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--text-muted);
            padding: 13px 18px;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
        }

        .table td {
            padding: 13px 18px;
            vertical-align: middle;
            font-size: 13.5px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-secondary);
        }

        .table tbody tr {
            transition: background .18s;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* Buttons */
        .btn {
            font-weight: 500;
            font-size: 13px;
            border-radius: var(--radius-sm);
            padding: 9px 18px;
            transition: all .18s;
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #fff;
            box-shadow: 0 4px 10px rgba(26, 86, 219, .2);
        }

        .btn-success {
            background: var(--success);
            border-color: var(--success);
            color: #fff;
        }

        .btn-success:hover {
            background: #047857;
            color: #fff;
        }

        .btn-warning {
            background: var(--warning);
            border-color: var(--warning);
            color: #fff;
        }

        .btn-warning:hover {
            background: #b45309;
            color: #fff;
        }

        .btn-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: #fff;
        }

        .btn-danger:hover {
            background: #b91c1c;
            color: #fff;
        }

        .btn-outline-secondary {
            background: var(--surface);
            border-color: var(--border-strong);
            color: var(--text-secondary);
        }

        .btn-outline-secondary:hover {
            background: var(--bg);
            color: var(--text);
        }

        .btn-sm {
            padding: 6px 13px;
            font-size: 12px;
        }

        /* Badge */
        .badge {
            font-size: 11px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: var(--radius);
            padding: 14px 18px;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 20px;
        }

        .alert i {
            font-size: 15px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .alert-success {
            background: var(--success-bg);
            color: #047857;
            border-left: 3px solid var(--success);
        }

        .alert-danger {
            background: var(--danger-bg);
            color: #b91c1c;
            border-left: 3px solid var(--danger);
        }

        .alert-info {
            background: var(--info-bg);
            color: #1d4ed8;
            border-left: 3px solid var(--info);
        }

        .alert-warning {
            background: var(--warning-bg);
            color: #92400e;
            border-left: 3px solid var(--warning);
        }

        /* Pagination */
        .pagination {
            gap: 3px;
            margin-top: 16px;
        }

        .page-link {
            border: 1px solid var(--border);
            border-radius: var(--radius-sm) !important;
            padding: 7px 13px;
            font-size: 13px;
            color: var(--text-secondary);
            background: var(--surface);
            transition: all .18s;
        }

        .page-link:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        .page-item.active .page-link {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        /* Footer */
        footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: 18px 0;
            margin-top: auto;
        }

        .footer-text {
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
        }

        /* Form controls */
        .form-control,
        .form-select {
            font-size: 13.5px;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
            padding: 9px 13px;
            color: var(--text);
            transition: border-color .18s, box-shadow .18s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 86, 219, .12);
            outline: none;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        /* ── RESPONSIVE ─────────────────────────────────── */
        @media (max-width: 1100px) {
            .nav-main {
                display: none;
            }

            .nav-divider {
                display: none;
            }

            .hamburger {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .nav-drawer.open {
                display: block;
                position: fixed;
                inset: var(--nav-h) 0 0 0;
                background: var(--surface);
                border-top: 1px solid var(--border);
                z-index: 1039;
                padding: 10px 12px 20px;
                overflow-y: auto;
                animation: slideDown .2s ease;
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-8px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .nav-drawer .nav-link {
                display: flex;
                align-items: center;
                gap: 11px;
                padding: 11px 14px;
                font-size: 14px;
                font-weight: 500;
                color: var(--text-secondary);
                border-radius: var(--radius-sm);
                text-decoration: none;
                transition: all .18s;
            }

            .nav-drawer .nav-link i {
                width: 16px;
                font-size: 14px;
                text-align: center;
            }

            .nav-drawer .nav-link:hover,
            .nav-drawer .nav-link.active {
                color: var(--primary);
                background: var(--primary-light);
            }

            .brand-sub {
                display: none;
            }
        }

        @media (max-width: 640px) {
            main {
                padding: 18px 0 36px;
            }

            .card-body {
                padding: 16px;
            }

            .table td,
            .table th {
                padding: 10px 12px;
            }

            .user-info {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <header class="topnav">
        <div class="container">
            <div class="topnav-inner">

                {{-- Brand --}}
                <a class="brand" href="{{ route('pelamar.dashboard') }}">
                    <div class="brand-logo">
                        <img src="{{ asset('images/logorsup.png') }}" alt="Logo">
                    </div>
                    <div class="brand-text">
                        <div class="brand-name">RSUP Recruitment</div>
                        <div class="brand-sub">PT Riau Sakti United Plantation</div>
                    </div>
                </a>

                <div class="nav-divider"></div>

                {{-- Desktop nav --}}
                <ul class="nav-main">
                    <li>
                        <a class="nav-link {{ request()->routeIs('pelamar.dashboard') ? 'active' : '' }}"
                            href="{{ route('pelamar.dashboard') }}">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('pelamar.profil.*') ? 'active' : '' }}"
                            href="{{ route('pelamar.profil.index') }}">
                            <i class="fas fa-user"></i> Profil
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('pelamar.lowongan.*') ? 'active' : '' }}"
                            href="{{ route('pelamar.lowongan.index') }}">
                            <i class="fas fa-briefcase"></i> Lowongan
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('pelamar.pelamartahapan.*') ? 'active' : '' }}"
                            href="{{ route('pelamar.pelamartahapan.index') }}">
                            <i class="fas fa-list-check"></i> Tahapan
                        </a>
                    </li>
                    {{-- <li>
                        <a class="nav-link {{ request()->routeIs('pelamar.spk.hasil') ? 'active' : '' }}"
                            href="{{ route('pelamar.spk.hasil') }}">
                            <i class="fas fa-chart-pie"></i> Hasil SPK
                        </a>
                    </li> --}}
                    <li>
                        <a class="nav-link {{ request()->routeIs('pelamar.offering.*') ? 'active' : '' }}"
                            href="{{ route('pelamar.offering.index') }}">
                            <i class="fas fa-file-contract"></i> Penawaran
                        </a>
                    </li>
                    <li>
                        <a class="nav-link {{ request()->routeIs('pelamar.status.seleksi') ? 'active' : '' }}"
                            href="{{ route('pelamar.status.seleksi') }}">
                            <i class="fas fa-chart-line"></i> Status
                        </a>
                    </li>
                </ul>

                {{-- Right side --}}
                <div class="nav-right">
                    <div class="dropdown">
                        <a class="user-btn dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown"
                            aria-expanded="false" style="text-decoration:none;">
                            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=1a56db&color=fff&bold=true&length=2' }}"
                                alt="{{ Auth::user()->name }}" class="user-avatar">
                            <div class="user-info d-none d-lg-block">
                                <div class="user-name">{{ Auth::user()->name }}</div>
                                <div class="user-role">Pelamar</div>
                            </div>
                            <i class="fas fa-chevron-down user-chevron d-none d-lg-block"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <div class="dropdown-header-custom">
                                    <div class="dh-name">{{ Auth::user()->name }}</div>
                                    <div class="dh-role">Pelamar · Akun Aktif</div>
                                </div>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-cog"></i> Pengaturan Akun
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider my-1">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu">
                        <i class="fas fa-bars" id="hamburgerIcon"></i>
                    </button>
                </div>

            </div>
        </div>
    </header>

    {{-- Mobile drawer --}}
    <div class="nav-drawer" id="navDrawer">
        <a class="nav-link {{ request()->routeIs('pelamar.dashboard') ? 'active' : '' }}"
            href="{{ route('pelamar.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
        <a class="nav-link {{ request()->routeIs('pelamar.profil.*') ? 'active' : '' }}"
            href="{{ route('pelamar.profil.index') }}"><i class="fas fa-user"></i> Profil</a>
        <a class="nav-link {{ request()->routeIs('pelamar.lowongan.*') ? 'active' : '' }}"
            href="{{ route('pelamar.lowongan.index') }}"><i class="fas fa-briefcase"></i> Lowongan</a>
        <a class="nav-link {{ request()->routeIs('pelamar.pelamartahapan.*') ? 'active' : '' }}"
            href="{{ route('pelamar.pelamartahapan.index') }}"><i class="fas fa-list-check"></i> Tahapan</a>
        {{-- <a class="nav-link {{ request()->routeIs('pelamar.spk.hasil') ? 'active' : '' }}"
            href="{{ route('pelamar.spk.hasil') }}"><i class="fas fa-chart-pie"></i> Hasil SPK</a> --}}
        <a class="nav-link {{ request()->routeIs('pelamar.offering.*') ? 'active' : '' }}"
            href="{{ route('pelamar.offering.index') }}"><i class="fas fa-file-contract"></i> Penawaran</a>
        <a class="nav-link {{ request()->routeIs('pelamar.status.seleksi') ? 'active' : '' }}"
            href="{{ route('pelamar.status.seleksi') }}"><i class="fas fa-chart-line"></i> Status</a>
        <hr style="border-color:var(--border); margin:10px 0;">
        <a class="nav-link" href="{{ route('profile.edit') }}"><i class="fas fa-cog"></i> Pengaturan Akun</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link w-100 text-start"
                style="background:none;border:none;color:#dc2626;">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>

    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    <footer>
        <div class="container">
            <p class="footer-text">
                &copy; {{ date('Y') }} PT Riau Sakti United Plantation — Recruitment System. All rights reserved.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('hamburgerBtn');
            const icon = document.getElementById('hamburgerIcon');
            const drawer = document.getElementById('navDrawer');

            if (btn && drawer) {
                btn.addEventListener('click', function() {
                    const open = drawer.classList.toggle('open');
                    icon.className = open ? 'fas fa-times' : 'fas fa-bars';
                });
            }

            document.querySelectorAll('.alert').forEach(function(el) {
                setTimeout(function() {
                    el.style.transition = 'opacity .4s, transform .4s';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-6px)';
                    setTimeout(function() {
                        if (el.parentNode) el.remove();
                    }, 400);
                }, 5000);
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
