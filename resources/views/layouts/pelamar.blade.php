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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --primary-light: #dbeafe;
            --primary-gradient: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            --primary-bg: #1a56db;
            --primary-bg-dark: #0f3b8c;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
            --info: #3b82f6;
            --info-light: #dbeafe;
            --surface: #ffffff;
            --bg: #f0f4f9;
            --border: #e5e7eb;
            --border-strong: #d1d5db;
            --text: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 14px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.10);
            --radius-sm: 8px;
            --radius: 12px;
            --radius-lg: 16px;
            --nav-h: 68px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-size: 14px;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-strong);
            border-radius: 10px;
        }

        .topnav {
            height: var(--nav-h);
            background: var(--primary-gradient);
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            position: sticky;
            top: 0;
            z-index: 1050;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 20px rgba(26, 86, 219, 0.3);
            transition: var(--transition);
        }

        .topnav.scrolled {
            box-shadow: 0 4px 30px rgba(26, 86, 219, 0.4);
        }

        .topnav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            gap: 12px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
        }

        .brand-logo img {
            width: 68%;
            height: auto;
            filter: brightness(0) invert(1);
        }

        .brand-name {
            font-size: 15px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.3px;
            line-height: 1.2;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
        }

        .brand-sub {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.75);
            font-weight: 400;
            line-height: 1.2;
            margin-top: -1px;
        }

        .nav-divider {
            width: 1px;
            height: 28px;
            background: rgba(255, 255, 255, 0.2);
            flex-shrink: 0;
        }

        .nav-main {
            display: flex;
            align-items: center;
            gap: 2px;
            list-style: none;
            flex: 1;
        }

        .nav-main .nav-link {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            transition: var(--transition);
            white-space: nowrap;
            position: relative;
        }

        .nav-main .nav-link i {
            font-size: 14px;
            width: 18px;
            text-align: center;
            flex-shrink: 0;
            opacity: 0.8;
            transition: var(--transition);
        }

        .nav-main .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .nav-main .nav-link:hover i {
            opacity: 1;
        }

        .nav-main .nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.2);
            font-weight: 600;
        }

        .nav-main .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: #ffffff;
            border-radius: 10px;
        }

        .nav-main .nav-link.active i {
            opacity: 1;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .user-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 3px 12px 3px 3px;
            border-radius: 50px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
        }

        .user-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
            flex-shrink: 0;
            transition: var(--transition);
        }

        .user-btn:hover .user-avatar {
            border-color: rgba(255, 255, 255, 0.8);
        }

        .user-name {
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            line-height: 1.2;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        .user-role {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.2;
        }

        .user-chevron {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.6);
            margin-left: 2px;
            transition: var(--transition);
        }

        .user-btn[aria-expanded="true"] .user-chevron {
            transform: rotate(180deg);
        }

        .dropdown-menu {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 8px;
            min-width: 210px;
            margin-top: 8px !important;
            border: none;
            box-shadow: var(--shadow-lg);
        }

        .dropdown-header-custom {
            padding: 8px 14px 8px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 4px;
        }

        .dropdown-header-custom .dh-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text);
        }

        .dropdown-header-custom .dh-role {
            font-size: 11px;
            color: var(--text-muted);
        }

        .dropdown-menu .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            font-size: 13px;
            color: var(--text-secondary);
            border-radius: var(--radius-sm);
            transition: var(--transition);
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .dropdown-menu .dropdown-item i {
            width: 16px;
            font-size: 13px;
            color: var(--text-muted);
            transition: var(--transition);
        }

        .dropdown-menu .dropdown-item:hover {
            background: var(--primary-light);
            color: var(--primary);
            transform: translateX(4px);
        }

        .dropdown-menu .dropdown-item:hover i {
            color: var(--primary);
        }

        .dropdown-menu .dropdown-item.text-danger:hover {
            background: var(--danger-light);
            color: var(--danger);
        }

        .dropdown-menu .dropdown-item.text-danger:hover i {
            color: var(--danger);
        }

        .dropdown-divider {
            margin: 6px 0;
        }

        .hamburger {
            display: none;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--radius-sm);
            padding: 7px 11px;
            cursor: pointer;
            color: #ffffff;
            transition: var(--transition);
            font-size: 16px;
        }

        .hamburger:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.3);
        }

        .hamburger:active {
            transform: scale(0.95);
        }

        .drawer-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1059;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .drawer-overlay.open {
            display: block;
            opacity: 1;
        }

        .drawer {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 300px;
            max-width: 85%;
            background: var(--surface);
            z-index: 1060;
            padding: 20px 18px 24px;
            overflow-y: auto;
            transform: translateX(-100%);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: var(--shadow-lg);
            border-right: 1px solid var(--border);
        }

        .drawer.open {
            display: block;
            transform: translateX(0);
        }

        .drawer-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 12px;
        }

        .drawer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .drawer-brand .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-sm);
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .drawer-brand .brand-icon img {
            width: 65%;
            filter: brightness(0) invert(1);
        }

        .drawer-brand .brand-text {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .drawer-close {
            background: none;
            border: none;
            font-size: 18px;
            color: var(--text-muted);
            cursor: pointer;
            padding: 6px 10px;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }

        .drawer-close:hover {
            background: var(--bg);
            color: var(--text);
        }

        .drawer .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 11px 14px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-secondary);
            border-radius: var(--radius-sm);
            text-decoration: none;
            transition: var(--transition);
            margin-bottom: 2px;
        }

        .drawer .nav-link i {
            width: 20px;
            font-size: 16px;
            text-align: center;
            color: var(--text-muted);
            transition: var(--transition);
        }

        .drawer .nav-link:hover,
        .drawer .nav-link.active {
            color: var(--primary);
            background: var(--primary-light);
            transform: translateX(4px);
        }

        .drawer .nav-link:hover i,
        .drawer .nav-link.active i {
            color: var(--primary);
        }

        .drawer-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 12px 0;
        }

        .drawer-footer {
            padding-top: 12px;
            border-top: 1px solid var(--border);
            margin-top: 8px;
        }

        .drawer-footer .nav-link {
            color: var(--danger);
        }

        .drawer-footer .nav-link i {
            color: var(--danger);
        }

        .drawer-footer .nav-link:hover {
            background: var(--danger-light);
            color: var(--danger);
        }

        .drawer-footer .nav-link:hover i {
            color: var(--danger);
        }

        main {
            flex: 1;
            padding: 24px 0 48px;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            background: var(--surface);
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 15px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: var(--radius-lg) var(--radius-lg) 0 0 !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header i {
            color: var(--primary);
            font-size: 18px;
        }

        .card-body {
            padding: 20px;
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
            letter-spacing: 0.6px;
            color: var(--text-muted);
            padding: 12px 16px;
            background: #f8fafc;
            border-bottom: 2px solid var(--border);
        }

        .table td {
            padding: 12px 16px;
            vertical-align: middle;
            font-size: 13.5px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-secondary);
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .btn {
            font-weight: 500;
            font-size: 13px;
            border-radius: var(--radius-sm);
            padding: 9px 18px;
            transition: var(--transition);
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:active {
            transform: scale(0.97);
        }

        .btn-primary {
            background: var(--primary-gradient);
            border-color: var(--primary);
            color: #fff;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
            color: #fff;
        }

        .btn-success {
            background: var(--success);
            border-color: var(--success);
            color: #fff;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
            color: #fff;
        }

        .btn-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: #fff;
        }

        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
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
            transform: translateY(-2px);
        }

        .btn-white {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            border-color: rgba(255, 255, 255, 0.3);
            color: #ffffff;
        }

        .btn-white:hover {
            background: rgba(255, 255, 255, 0.3);
            border-color: rgba(255, 255, 255, 0.5);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
        }

        .badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-success {
            background: var(--success-light);
            color: #059669;
        }

        .badge-danger {
            background: var(--danger-light);
            color: #dc2626;
        }

        .badge-warning {
            background: var(--warning-light);
            color: #d97706;
        }

        .badge-info {
            background: var(--info-light);
            color: #2563eb;
        }

        .alert {
            border: none;
            border-radius: var(--radius);
            padding: 14px 18px;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-sm);
            border-left: 4px solid transparent;
        }

        .alert i {
            font-size: 18px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .alert-success {
            background: var(--success-light);
            color: #065f46;
            border-left-color: var(--success);
        }

        .alert-danger {
            background: var(--danger-light);
            color: #991b1b;
            border-left-color: var(--danger);
        }

        .alert-info {
            background: var(--info-light);
            color: #1e40af;
            border-left-color: var(--info);
        }

        .alert-warning {
            background: var(--warning-light);
            color: #78350f;
            border-left-color: var(--warning);
        }

        .pagination {
            gap: 4px;
            margin-top: 20px;
        }

        .page-link {
            border: 1px solid var(--border);
            border-radius: var(--radius-sm) !important;
            padding: 7px 14px;
            font-size: 13px;
            color: var(--text-secondary);
            background: var(--surface);
            transition: var(--transition);
        }

        .page-link:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            transform: translateY(-1px);
        }

        .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: var(--primary);
            color: #fff;
            box-shadow: 0 2px 10px rgba(37, 99, 235, 0.3);
        }

        footer {
            background: var(--primary-gradient);
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            padding: 18px 0;
            margin-top: auto;
        }

        .footer-text {
            text-align: center;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        .form-control,
        .form-select {
            font-size: 13.5px;
            border: 1px solid var(--border-strong);
            border-radius: var(--radius-sm);
            padding: 9px 14px;
            color: var(--text);
            transition: var(--transition);
            background: var(--surface);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        @media (max-width: 992px) {
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

            .brand-sub {
                display: none;
            }
        }

        @media (max-width: 768px) {
            :root {
                --nav-h: 60px;
            }

            main {
                padding: 16px 0 32px;
            }

            .card-body {
                padding: 14px;
            }

            .card-header {
                padding: 12px 14px;
                font-size: 13px;
            }

            .table td,
            .table th {
                padding: 8px 10px;
                font-size: 12px;
            }

            .user-info {
                display: none;
            }

            .brand-name {
                font-size: 13px;
            }

            .brand-logo {
                width: 34px;
                height: 34px;
            }

            .btn {
                font-size: 12px;
                padding: 7px 14px;
            }

            .drawer {
                width: 280px;
                padding: 16px 14px 20px;
            }
        }

        @media (max-width: 480px) {
            :root {
                --nav-h: 56px;
            }

            .brand-name {
                font-size: 11px;
            }

            .brand-logo {
                width: 30px;
                height: 30px;
            }

            .drawer {
                width: 100%;
                max-width: 100%;
                padding: 14px 12px 18px;
            }

            .drawer .nav-link {
                padding: 10px 12px;
                font-size: 13px;
            }

            .card-body {
                padding: 10px;
            }

            .table td,
            .table th {
                padding: 6px 8px;
                font-size: 11px;
            }

            .user-btn {
                padding: 2px 6px 2px 2px;
            }

            .user-avatar {
                width: 28px;
                height: 28px;
            }

            .hamburger {
                padding: 5px 9px;
                font-size: 14px;
            }
        }

        .text-gradient {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-status::before {
            content: '';
            display: inline-block;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }

        .badge-status.badge-success::before {
            background: var(--success);
        }

        .badge-status.badge-danger::before {
            background: var(--danger);
        }

        .badge-status.badge-warning::before {
            background: var(--warning);
        }

        .badge-status.badge-info::before {
            background: var(--info);
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(0.8);
            }
        }

        /* ============================================
           WELCOME HEADER - IMPROVED
           ============================================ */
        .welcome-header {
            background: var(--primary-gradient);
            border-radius: var(--radius-lg);
            padding: 32px 36px;
            color: #ffffff;
            box-shadow: 0 6px 30px rgba(37, 99, 235, 0.30);
            margin-bottom: 28px;
            position: relative;
            overflow: hidden;
        }

        .welcome-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            pointer-events: none;
        }

        .welcome-header::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
            pointer-events: none;
        }

        .welcome-header h1 {
            font-size: 26px;
            font-weight: 800;
            margin-bottom: 6px;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            position: relative;
            z-index: 1;
        }

        .welcome-header h1 .wave {
            display: inline-block;
            animation: wave 2.5s ease-in-out infinite;
        }

        @keyframes wave {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(15deg);
            }

            75% {
                transform: rotate(-10deg);
            }
        }

        .welcome-header p {
            font-size: 14px;
            opacity: 0.92;
            margin-bottom: 0;
            text-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
            position: relative;
            z-index: 1;
            max-width: 80%;
        }

        .welcome-header .alert-reminder {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #ffffff;
            border-radius: var(--radius-sm);
            padding: 12px 18px;
            margin-top: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .welcome-header .alert-reminder i {
            font-size: 20px;
            opacity: 0.9;
            flex-shrink: 0;
        }

        .welcome-header .alert-reminder strong {
            font-weight: 600;
        }

        /* ============================================
           QUICK ACCESS CARDS - IMPROVED
           ============================================ */
        .quick-access-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-top: 8px;
        }

        .quick-card {
            background: var(--surface);
            border-radius: var(--radius-lg);
            padding: 22px 24px 20px;
            border: 1px solid var(--border);
            transition: var(--transition);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            height: 100%;
            position: relative;
            overflow: hidden;
        }

        .quick-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--primary-gradient);
            opacity: 0;
            transition: var(--transition);
        }

        .quick-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .quick-card:hover::before {
            opacity: 1;
        }

        .quick-card .qc-icon {
            width: 52px;
            height: 52px;
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 14px;
            flex-shrink: 0;
        }

        .quick-card .qc-icon.blue {
            background: var(--primary-light);
            color: var(--primary);
        }

        .quick-card .qc-icon.green {
            background: var(--success-light);
            color: var(--success);
        }

        .quick-card .qc-icon.orange {
            background: var(--warning-light);
            color: var(--warning);
        }

        .quick-card .qc-icon.purple {
            background: #ede9fe;
            color: #7c3aed;
        }

        .quick-card .qc-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 4px;
        }

        .quick-card .qc-desc {
            font-size: 13px;
            color: var(--text-secondary);
            line-height: 1.5;
            flex: 1;
            margin-bottom: 12px;
        }

        .quick-card .qc-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--primary);
            transition: var(--transition);
            margin-top: auto;
        }

        .quick-card .qc-action i {
            font-size: 14px;
            transition: var(--transition);
        }

        .quick-card:hover .qc-action {
            gap: 12px;
        }

        .quick-card:hover .qc-action i {
            transform: translateX(4px);
        }

        /* ============================================
           CARD VARIANT - BORDER LEFT
           ============================================ */
        .card-border-left {
            border-left: 4px solid var(--primary);
        }

        .card-border-left.success {
            border-left-color: var(--success);
        }

        .card-border-left.warning {
            border-left-color: var(--warning);
        }

        .card-border-left.danger {
            border-left-color: var(--danger);
        }

        .card-border-left.purple {
            border-left-color: #7c3aed;
        }
    </style>
    @stack('styles')
</head>

<body>
    <header class="topnav" id="topnav">
        <div class="container">
            <div class="topnav-inner">

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

                <ul class="nav-main">
                    <li><a class="nav-link {{ request()->routeIs('pelamar.dashboard') ? 'active' : '' }}"
                            href="{{ route('pelamar.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a class="nav-link {{ request()->routeIs('pelamar.profil.*') ? 'active' : '' }}"
                            href="{{ route('pelamar.profil.index') }}"><i class="fas fa-user"></i> Profil</a></li>
                    <li><a class="nav-link {{ request()->routeIs('pelamar.lowongan.*') ? 'active' : '' }}"
                            href="{{ route('pelamar.lowongan.index') }}"><i class="fas fa-briefcase"></i> Lowongan</a>
                    </li>
                    <li><a class="nav-link {{ request()->routeIs('pelamar.pelamartahapan.*') ? 'active' : '' }}"
                            href="{{ route('pelamar.pelamartahapan.index') }}"><i class="fas fa-list-check"></i>
                            Tahapan</a></li>
                    <li><a class="nav-link {{ request()->routeIs('pelamar.offering.*') ? 'active' : '' }}"
                            href="{{ route('pelamar.offering.index') }}"><i class="fas fa-file-contract"></i>
                            Penawaran</a></li>
                    <li><a class="nav-link {{ request()->routeIs('pelamar.status.seleksi') ? 'active' : '' }}"
                            href="{{ route('pelamar.status.seleksi') }}"><i class="fas fa-chart-line"></i> Status</a>
                    </li>
                </ul>

                <div class="nav-right">
                    <div class="dropdown">
                        <a class="user-btn dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img src="{{ Auth::user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=ffffff&color=2563eb&bold=true&length=2' }}"
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
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-cog"></i>
                                    Pengaturan Akun</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i
                                            class="fas fa-sign-out-alt"></i> Keluar</button>
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

    <div class="drawer-overlay" id="drawerOverlay"></div>

    <div class="drawer" id="drawer">
        <div class="drawer-header">
            <div class="drawer-brand">
                <div class="brand-icon">
                    <img src="{{ asset('images/logorsup.png') }}" alt="Logo">
                </div>
                <span class="brand-text">RSUP Recruitment</span>
            </div>
            <button class="drawer-close" id="drawerClose"><i class="fas fa-times"></i></button>
        </div>

        <a class="nav-link {{ request()->routeIs('pelamar.dashboard') ? 'active' : '' }}"
            href="{{ route('pelamar.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
        <a class="nav-link {{ request()->routeIs('pelamar.profil.*') ? 'active' : '' }}"
            href="{{ route('pelamar.profil.index') }}"><i class="fas fa-user"></i> Profil</a>
        <a class="nav-link {{ request()->routeIs('pelamar.lowongan.*') ? 'active' : '' }}"
            href="{{ route('pelamar.lowongan.index') }}"><i class="fas fa-briefcase"></i> Lowongan</a>
        <a class="nav-link {{ request()->routeIs('pelamar.pelamartahapan.*') ? 'active' : '' }}"
            href="{{ route('pelamar.pelamartahapan.index') }}"><i class="fas fa-list-check"></i> Tahapan</a>
        <a class="nav-link {{ request()->routeIs('pelamar.offering.*') ? 'active' : '' }}"
            href="{{ route('pelamar.offering.index') }}"><i class="fas fa-file-contract"></i> Penawaran</a>
        <a class="nav-link {{ request()->routeIs('pelamar.status.seleksi') ? 'active' : '' }}"
            href="{{ route('pelamar.status.seleksi') }}"><i class="fas fa-chart-line"></i> Status</a>

        <hr class="drawer-divider">

        <div class="drawer-footer">
            <a class="nav-link" href="{{ route('profile.edit') }}"><i class="fas fa-cog"></i> Pengaturan Akun</a>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="nav-link w-100 text-start"
                    style="background:none;border:none;color:var(--danger);padding:11px 14px;font-size:14px;font-weight:500;border-radius:var(--radius-sm);transition:var(--transition);display:flex;align-items:center;gap:14px;width:100%;cursor:pointer;">
                    <i class="fas fa-sign-out-alt" style="width:20px;text-align:center;"></i> Keluar
                </button>
            </form>
        </div>
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
            const drawer = document.getElementById('drawer');
            const overlay = document.getElementById('drawerOverlay');
            const closeBtn = document.getElementById('drawerClose');

            function openDrawer() {
                drawer.classList.add('open');
                overlay.classList.add('open');
                icon.className = 'fas fa-times';
                document.body.style.overflow = 'hidden';
            }

            function closeDrawer() {
                drawer.classList.remove('open');
                overlay.classList.remove('open');
                icon.className = 'fas fa-bars';
                document.body.style.overflow = '';
            }

            if (btn && drawer && overlay) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    drawer.classList.contains('open') ? closeDrawer() : openDrawer();
                });

                closeBtn.addEventListener('click', closeDrawer);
                overlay.addEventListener('click', closeDrawer);

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && drawer.classList.contains('open')) closeDrawer();
                });

                drawer.querySelectorAll('.nav-link').forEach(function(link) {
                    link.addEventListener('click', closeDrawer);
                });
            }

            document.querySelectorAll('.alert').forEach(function(el) {
                setTimeout(function() {
                    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(-10px) scale(0.98)';
                    setTimeout(function() {
                        if (el.parentNode) el.remove();
                    }, 500);
                }, 6000);
            });

            const topnav = document.getElementById('topnav');
            window.addEventListener('scroll', function() {
                topnav.classList.toggle('scrolled', window.scrollY > 20);
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
