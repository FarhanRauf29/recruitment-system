<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recruitment System - @yield('title', 'Admin Panel')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #3b82f6;
            --secondary-color: #1e293b;
            --accent-color: #ef4444;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 80px;
            --topbar-height: 70px;
            --bg-color: #f8fafc;
            --text-color: #0f172a;
            --text-secondary: #64748b;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            --topbar-bg: #ffffff;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] {
            --bg-color: #0f172a;
            --text-color: #f1f5f9;
            --text-secondary: #94a3b8;
            --card-bg: #1e293b;
            --border-color: #334155;
            --sidebar-bg: linear-gradient(180deg, #0f172a 0%, #020617 100%);
            --topbar-bg: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            overflow-x: hidden;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            overflow-x: hidden;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        /* --- Top Navigation Bar --- */
        .top-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: var(--topbar-height);
            background: var(--topbar-bg);
            box-shadow: var(--shadow-sm);
            z-index: 1030;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--border-color);
        }

        .top-navbar.compact {
            left: var(--sidebar-collapsed-width);
        }

        .top-navbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        /* Hanya tombol mobile yang tersisa */
        .sidebar-toggle-mobile {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-color);
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: none;
        }

        .sidebar-toggle-mobile:hover {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        /* Hilangkan button sidebar-toggle desktop */
        .sidebar-toggle {
            display: none;
        }

        .page-title {
            font-weight: 600;
            color: var(--text-color);
            font-size: 1.125rem;
            margin-bottom: 0;
        }

        .top-navbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        /* --- Theme Toggle --- */
        .theme-toggle {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 1.125rem;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .theme-toggle:hover {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        /* --- Time Display --- */
        .time-display {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            padding: 0 16px;
        }

        .current-time {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-color);
        }

        .current-date {
            font-size: 0.7rem;
            color: var(--text-secondary);
        }

        /* --- Profile Dropdown --- */
        .profile-dropdown {
            position: relative;
        }

        .profile-dropdown-toggle {
            display: flex;
            align-items: center;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 10px;
            transition: all 0.3s;
            border: 1px solid transparent;
            background: none;
        }

        .profile-dropdown-toggle:hover {
            background-color: rgba(37, 99, 235, 0.05);
            border-color: var(--border-color);
        }

        .profile-dropdown-toggle img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            margin-right: 8px;
        }

        .profile-name {
            font-weight: 600;
            color: var(--text-color);
            font-size: 0.875rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 140px;
        }

        .profile-role {
            font-size: 0.7rem;
            color: var(--text-secondary);
            text-transform: capitalize;
        }

        .dropdown-arrow {
            transition: transform 0.3s;
            color: var(--text-secondary);
            font-size: 0.75rem;
        }

        .profile-dropdown-toggle.show .dropdown-arrow {
            transform: rotate(180deg);
        }

        .profile-dropdown-menu {
            position: absolute;
            right: 0;
            top: 100%;
            min-width: 260px;
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
            margin-top: 10px;
            display: none;
            z-index: 1001;
            border: 1px solid var(--border-color);
        }

        .profile-dropdown-menu.show {
            display: block;
            animation: fadeInDown 0.2s ease;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-dropdown-header {
            padding: 14px;
            text-align: center;
            border-bottom: 1px solid var(--border-color);
        }

        .profile-dropdown-header h6 {
            margin-bottom: 4px;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--text-color);
        }

        .profile-dropdown-header small {
            color: var(--text-secondary);
            font-size: 0.75rem;
        }

        .profile-dropdown-item {
            padding: 10px 14px;
            display: flex;
            align-items: center;
            color: var(--text-color);
            text-decoration: none;
            transition: all 0.2s;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .profile-dropdown-item:hover {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary-color);
        }

        .profile-dropdown-item i {
            width: 22px;
            margin-right: 12px;
            text-align: center;
            color: var(--primary-color);
            font-size: 0.875rem;
        }

        .profile-dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: 0.5rem 0;
        }

        /* --- Sidebar --- */
        #sidebar {
            background: var(--sidebar-bg);
            height: 100vh;
            width: var(--sidebar-width);
            position: fixed;
            top: 0;
            left: 0;
            transition: all 0.3s ease;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            z-index: 1020;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        #sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 24px 20px;
            text-align: center;
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            cursor: pointer;
            flex-shrink: 0;
        }

        .sidebar-header:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        #sidebar.collapsed .sidebar-header {
            padding: 16px 12px;
        }

        .logo-container {
            width: 56px;
            height: 56px;
            margin: 0 auto 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 8px;
        }

        #sidebar.collapsed .logo-container {
            width: 40px;
            height: 40px;
            margin-bottom: 6px;
        }

        .logo-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .sidebar-header h4 {
            color: white;
            font-weight: 700;
            margin-bottom: 4px;
            transition: all 0.3s ease;
            font-size: 1.1rem;
        }

        #sidebar.collapsed .sidebar-header h4 {
            font-size: 0.7rem;
        }

        .sidebar-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 0.75rem;
            margin-bottom: 0;
        }

        #sidebar.collapsed .sidebar-header p {
            display: none;
        }

        .status-indicator {
            display: inline-flex;
            align-items: center;
            font-size: 0.75rem;
            margin-top: 8px;
            color: rgba(255, 255, 255, 0.7);
        }

        #sidebar.collapsed .status-indicator {
            font-size: 0.65rem;
        }

        .status-dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #10b981;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 0.5;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.5;
            }
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85);
            padding: 12px 18px;
            margin: 4px 12px;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 14px;
            font-size: 1rem;
        }

        #sidebar.collapsed .nav-link span {
            display: none;
        }

        #sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 14px;
            margin: 4px 8px;
        }

        #sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 1.1rem;
        }

        .sidebar-section {
            padding: 16px 24px 6px;
            color: rgba(255, 255, 255, 0.5);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        #sidebar.collapsed .sidebar-section {
            display: none;
        }

        .sidebar-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
            margin: 10px 18px;
        }

        /* --- Main Content --- */
        main {
            margin-left: var(--sidebar-width);
            padding: 24px;
            width: calc(100% - var(--sidebar-width));
            margin-top: var(--topbar-height);
            transition: all 0.3s ease;
            min-height: calc(100vh - var(--topbar-height));
            background-color: var(--bg-color);
        }

        main.compact {
            margin-left: var(--sidebar-collapsed-width);
            width: calc(100% - var(--sidebar-collapsed-width));
        }

        /* Table Responsive */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            border-radius: 12px;
        }

        .table {
            min-width: 600px;
            width: 100%;
            margin-bottom: 0;
        }

        .table th {
            white-space: nowrap;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.875rem 1.25rem;
            background: #f8fafc;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table td {
            white-space: nowrap;
            font-size: 0.875rem;
            padding: 0.875rem 1.25rem;
            vertical-align: middle;
        }

        /* Card styling */
        .card {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            margin-bottom: 1.5rem;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .card:hover {
            box-shadow: var(--shadow-md);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* Buttons */
        .btn {
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1.25rem;
            font-size: 0.875rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-sm {
            padding: 0.375rem 0.875rem;
            font-size: 0.75rem;
        }

        /* Badges */
        .badge {
            padding: 0.375rem 0.75rem;
            font-weight: 500;
            border-radius: 20px;
            font-size: 0.7rem;
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 0.875rem;
            border: none;
            margin-bottom: 1.5rem;
        }

        /* Pagination */
        .pagination {
            gap: 4px;
        }

        .page-link {
            border: none;
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
            border-radius: 8px !important;
            color: var(--text-secondary);
            background: var(--card-bg);
            border: 1px solid var(--border-color);
        }

        .page-link:hover {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .page-item.active .page-link {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        /* Modal */
        .modal-content {
            border-radius: 14px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
        }

        /* Responsive */
        @media (max-width: 992px) {
            #sidebar {
                transform: translateX(-100%);
                width: var(--sidebar-width);
            }

            #sidebar.show {
                transform: translateX(0);
            }

            .sidebar-toggle-mobile {
                display: flex;
            }

            .top-navbar,
            .top-navbar.compact,
            main,
            main.compact {
                left: 0;
                margin-left: 0;
                width: 100%;
            }

            .table td,
            .table th {
                white-space: nowrap;
            }
        }

        @media (max-width: 768px) {
            .time-display {
                display: none;
            }

            .profile-info {
                display: none;
            }

            .profile-dropdown-toggle img {
                margin-right: 0;
            }

            .profile-dropdown-toggle {
                padding: 8px;
            }

            main {
                padding: 16px;
            }

            .card-body {
                padding: 1.25rem;
            }

            .table td,
            .table th {
                padding: 0.75rem 1rem;
                font-size: 0.8rem;
            }
        }

        @media (max-width: 576px) {
            .top-navbar {
                padding: 0 16px;
                height: 60px;
            }

            .sidebar-toggle-mobile {
                font-size: 1.2rem;
            }

            main {
                padding: 12px;
            }

            .card-header {
                padding: 1rem 1.25rem;
                font-size: 0.95rem;
            }

            .btn {
                padding: 0.375rem 1rem;
                font-size: 0.75rem;
            }
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1019;
        }

        @media (max-width: 992px) {
            .sidebar-overlay.show {
                display: block;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-color);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        #sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
        }

        #sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="d-flex">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <nav id="sidebar" class="d-flex flex-column">
            <div class="sidebar-header" id="sidebarHeader" style="cursor: pointer;">
                <div class="logo-container">
                    <img src="{{ asset('images/logorsup.png') }}" alt="PT Riau Sakti United Plantation"
                        class="company-logo">
                </div>
                <h4>Recruitment System</h4>
                <p>Admin Panel</p>
                <div class="status-indicator">
                    <span class="status-dot"></span>
                    <span>Status: Active</span>
                </div>
            </div>

            <div class="flex-grow-1">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <div class="sidebar-section">Master Data</div>
                    <div class="sidebar-divider"></div>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.pelamar.*') ? 'active' : '' }}"
                            href="{{ route('admin.pelamar.index') }}">
                            <i class="fas fa-users"></i>
                            <span>Data Pelamar</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.tahapanrekrutmen.*') ? 'active' : '' }}"
                            href="{{ route('admin.tahapanrekrutmen.index') }}">
                            <i class="fas fa-stream"></i>
                            <span>Tahapan Rekrutmen</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.lowongan.*') ? 'active' : '' }}"
                            href="{{ route('admin.lowongan.index') }}">
                            <i class="fas fa-briefcase"></i>
                            <span>Lowongan</span>
                        </a>
                    </li>

                    <div class="sidebar-section">Dokumen</div>
                    <div class="sidebar-divider"></div>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.berkas.*') ? 'active' : '' }}"
                            href="{{ route('admin.berkas.index') }}">
                            <i class="fas fa-folder-open"></i>
                            <span>Berkas</span>
                        </a>
                    </li>

                    <div class="sidebar-section">Psikotes</div>
                    <div class="sidebar-divider"></div>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.kategoripsikotes.*') ? 'active' : '' }}"
                            href="{{ route('admin.kategoripsikotes.index') }}">
                            <i class="fas fa-tags"></i>
                            <span>Kategori Psikotes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.konfigurasipsikotes.*') ? 'active' : '' }}"
                            href="{{ route('admin.konfigurasipsikotes.index') }}">
                            <i class="fas fa-cog"></i>
                            <span>Konfigurasi Psikotes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.sesipsikotes.*') ? 'active' : '' }}"
                            href="{{ route('admin.sesipsikotes.index') }}">
                            <i class="fas fa-clock"></i>
                            <span>Sesi Psikotes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.soalpsikotes.*') ? 'active' : '' }}"
                            href="{{ route('admin.soalpsikotes.index') }}">
                            <i class="fas fa-question-circle"></i>
                            <span>Soal Psikotes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.jawabanpsikotes.*') ? 'active' : '' }}"
                            href="{{ route('admin.jawabanpsikotes.index') }}">
                            <i class="fas fa-pen-square"></i>
                            <span>Jawaban Psikotes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.hasilpsikotes.*') ? 'active' : '' }}"
                            href="{{ route('admin.hasilpsikotes.index') }}">
                            <i class="fas fa-poll"></i>
                            <span>Hasil Psikotes</span>
                        </a>
                    </li>

                    <div class="sidebar-section">Medical Check Up</div>
                    <div class="sidebar-divider"></div>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.mcu.*') ? 'active' : '' }}"
                            href="{{ route('admin.mcu.index') }}">
                            <i class="fas fa-heartbeat"></i>
                            <span>Medical Check Up</span>
                        </a>
                    </li>

                    <div class="sidebar-section">Wawancara</div>
                    <div class="sidebar-divider"></div>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.wawancara.*') ? 'active' : '' }}"
                            href="{{ route('admin.wawancara.index') }}">
                            <i class="fas fa-chalkboard-user"></i>
                            <span>Wawancara</span>
                        </a>
                    </li>

                    <div class="sidebar-section">Sistem Pendukung Keputusan</div>
                    <div class="sidebar-divider"></div>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.spk.index') ? 'active' : '' }}"
                            href="{{ route('admin.spk.index') }}">
                            <i class="fas fa-chart-line"></i>
                            <span>SPK (SAW & TOPSIS)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.spk.kriteria') ? 'active' : '' }}"
                            href="{{ route('admin.spk.kriteria') }}">
                            <i class="fas fa-weight-hanging"></i>
                            <span>Kriteria SPK</span>
                        </a>
                    </li>

                    <div class="sidebar-section">Offering & Hiring</div>
                    <div class="sidebar-divider"></div>

                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showLowonganModal('offering')">
                            <i class="fas fa-file-signature"></i>
                            <span>Offering</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showLowonganModal('hiring')">
                            <i class="fas fa-user-check"></i>
                            <span>Hiring</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showLowonganModal('onboarding')">
                            <i class="fas fa-user-plus"></i>
                            <span>Onboarding</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="p-3 text-center text-white-50">
                <small>v1.0.0 © 2026</small>
            </div>
        </nav>

        <div class="top-navbar" id="topNavbar">
            <div class="top-navbar-left">
                <button class="sidebar-toggle-mobile" id="mobileSidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div class="top-navbar-right">
                <button class="theme-toggle" id="themeToggle">
                    <i class="fas fa-moon"></i>
                </button>

                <div class="time-display d-none d-md-flex">
                    <div class="current-time" id="currentTime">00:00:00</div>
                    <div class="current-date" id="currentDate">Loading...</div>
                </div>

                <div class="profile-dropdown">
                    <div class="profile-dropdown-toggle" id="profileDropdownToggle">
                        <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=2563eb&color=fff&length=2' }}"
                            alt="Profile">
                        <div class="profile-info">
                            <span class="profile-name">{{ Auth::user()->name }}</span>
                            <span class="profile-role">{{ Auth::user()->role ?? 'admin' }}</span>
                        </div>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                    <div class="profile-dropdown-menu" id="profileDropdown">
                        <div class="profile-dropdown-header">
                            <h6>{{ Auth::user()->name }}</h6>
                            <small>{{ Auth::user()->email }}</small>
                        </div>
                        <div class="profile-dropdown-divider"></div>
                        <a href="{{ route('profile.edit') }}" class="profile-dropdown-item">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </a>
                        <div class="profile-dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="profile-dropdown-item w-100 text-start">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <main id="mainContent">
            <div class="container-fluid px-0">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Modal Pilih Lowongan -->
    <div class="modal fade" id="lowonganModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fas fa-briefcase me-2"></i> Pilih Lowongan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Lowongan</label>
                        <select id="selectLowongan" class="form-select">
                            <option value="">-- Pilih Lowongan --</option>
                            @foreach (\App\Models\Lowongan::where('status', 'buka')->get() as $lowongan)
                                <option value="{{ $lowongan->id }}">{{ $lowongan->judul_lowongan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnGoToMenu">Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const topNavbar = document.getElementById('topNavbar');
            const mainContent = document.getElementById('mainContent');
            const profileDropdownToggle = document.getElementById('profileDropdownToggle');
            const profileDropdown = document.getElementById('profileDropdown');
            const themeToggle = document.getElementById('themeToggle');
            const sidebarHeader = document.getElementById('sidebarHeader');

            function updateTime() {
                const now = new Date();
                const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'
                ];

                document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID');
                document.getElementById('currentDate').textContent =
                    `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
            }
            updateTime();
            setInterval(updateTime, 1000);

            // Mobile sidebar toggle (slide in/out overlay)
            mobileSidebarToggle.addEventListener('click', () => {
                sidebar.classList.add('show');
                sidebarOverlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            });

            sidebarOverlay.addEventListener('click', () => {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
                document.body.style.overflow = '';
            });

            // Click logo to go to dashboard
            if (sidebarHeader) {
                sidebarHeader.addEventListener('click', function() {
                    window.location.href = '{{ route('admin.dashboard') }}';
                });
            }

            // Profile dropdown
            profileDropdownToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });

            document.addEventListener('click', () => {
                profileDropdown.classList.remove('show');
            });

            // Theme toggle
            themeToggle.addEventListener('click', () => {
                const html = document.documentElement;
                const icon = themeToggle.querySelector('i');

                if (html.getAttribute('data-theme') === 'dark') {
                    html.removeAttribute('data-theme');
                    icon.classList.remove('fa-sun');
                    icon.classList.add('fa-moon');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.setAttribute('data-theme', 'dark');
                    icon.classList.remove('fa-moon');
                    icon.classList.add('fa-sun');
                    localStorage.setItem('theme', 'dark');
                }
            });

            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                const icon = themeToggle.querySelector('i');
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
            }
        });

        let selectedMenu = '';

        function showLowonganModal(menu) {
            selectedMenu = menu;
            const modal = new bootstrap.Modal(document.getElementById('lowonganModal'));
            modal.show();
        }

        document.getElementById('btnGoToMenu').addEventListener('click', function() {
            const lowonganId = document.getElementById('selectLowongan').value;
            if (!lowonganId) {
                alert('Silakan pilih lowongan terlebih dahulu');
                return;
            }

            let url = '';
            if (selectedMenu === 'offering') {
                url = '{{ url('admin/offering') }}/' + lowonganId;
            } else if (selectedMenu === 'hiring') {
                url = '{{ url('admin/hiring') }}/' + lowonganId;
            } else if (selectedMenu === 'onboarding') {
                url = '{{ url('admin/onboarding') }}/' + lowonganId;
            }

            window.location.href = url;
        });
    </script>

    @stack('scripts')
</body>

</html>
