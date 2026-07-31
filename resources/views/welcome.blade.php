<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSUP Career | PT Riau Sakti United Plantation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1B5E3F;
            --primary-dark: #0D3B26;
            --primary-light: #E8F5ED;
            --accent: #D4A11E;
            --accent-light: #E8C96A;
            --accent-dark: #B8860B;
            --white: #FFFFFF;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-600: #6B7280;
            --gray-800: #1F2937;
            --gray-900: #111827;
            --font-heading: 'Cormorant Garamond', serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: var(--font-body);
            color: var(--gray-800);
            background: var(--white);
            overflow-x: hidden;
            line-height: 1.5;
        }

        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 32px;
        }

        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.4s ease;
            padding: 20px 0;
        }

        #navbar.scrolled {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(12px);
            box-shadow: var(--shadow-md);
            padding: 12px 0;
        }

        .nav-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 32px;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
        }

        .logo-mark {
            width: 48px;
            height: 48px;
            background: linear-gradient(145deg, var(--primary), var(--primary-dark));
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .logo-mark::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(212, 161, 30, 0.3) 0%, transparent 70%);
        }

        .logo-mark i {
            font-size: 24px;
            color: var(--accent);
            position: relative;
            z-index: 1;
        }

        .logo-text {
            line-height: 1.2;
        }

        .logo-text .brand {
            font-family: var(--font-heading);
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--white);
            letter-spacing: -0.3px;
            transition: color 0.3s;
        }

        .logo-text .brand span {
            color: var(--accent);
        }

        .logo-text .sub {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.7);
            letter-spacing: 0.5px;
            font-weight: 500;
        }

        #navbar.scrolled .logo-text .brand {
            color: var(--primary);
        }

        #navbar.scrolled .logo-text .sub {
            color: var(--gray-600);
        }

        .nav-links {
            display: flex;
            gap: 8px;
            list-style: none;
        }

        .nav-links a {
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.85);
            border-radius: 40px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .nav-links a:hover {
            color: var(--white);
            background: rgba(255, 255, 255, 0.12);
        }

        .nav-links a.active {
            color: var(--accent-light);
            background: rgba(212, 161, 30, 0.12);
        }

        #navbar.scrolled .nav-links a {
            color: var(--gray-700);
        }

        #navbar.scrolled .nav-links a:hover {
            color: var(--primary);
            background: var(--gray-100);
        }

        #navbar.scrolled .nav-links a.active {
            color: var(--accent);
            background: rgba(212, 161, 30, 0.1);
        }

        .nav-actions {
            display: flex;
            gap: 12px;
        }

        .btn-outline {
            padding: 10px 24px;
            border-radius: 40px;
            font-size: 0.85rem;
            font-weight: 600;
            border: 1.5px solid rgba(255, 255, 255, 0.4);
            background: transparent;
            color: var(--white);
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: var(--white);
        }

        .btn-primary {
            padding: 10px 28px;
            border-radius: 40px;
            font-size: 0.85rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: var(--primary-dark);
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 161, 30, 0.35);
        }

        #navbar.scrolled .btn-outline {
            border-color: var(--gray-300);
            color: var(--gray-700);
        }

        #navbar.scrolled .btn-outline:hover {
            background: var(--gray-100);
        }

        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
        }

        #navbar.scrolled .mobile-toggle {
            color: var(--primary);
        }

        .mobile-nav {
            position: fixed;
            inset: 0;
            background: linear-gradient(145deg, var(--primary), var(--primary-dark));
            z-index: 999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 24px;
            transform: translateX(100%);
            transition: transform 0.4s ease;
        }

        .mobile-nav.open {
            transform: translateX(0);
        }

        .mobile-nav a {
            font-family: var(--font-heading);
            font-size: 1.6rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 12px;
            transition: all 0.2s;
        }

        .mobile-nav a:hover {
            color: var(--accent-light);
            transform: translateX(6px);
        }

        .mobile-nav-close {
            position: absolute;
            top: 24px;
            right: 24px;
            background: none;
            border: none;
            color: var(--white);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 12px;
        }

        .mobile-nav-actions {
            margin-top: 32px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .mobile-nav-actions .btn-outline,
        .mobile-nav-actions .btn-primary {
            width: 220px;
            justify-content: center;
        }

        #hero {
            position: relative;
            width: 100%;
            height: 100vh;
            min-height: 680px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-video-wrap {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .hero-video-wrap iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 177.78vh;
            height: 56.25vw;
            min-width: 100%;
            min-height: 100%;
            border: none;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(27, 94, 63, 0.88) 0%, rgba(13, 59, 38, 0.82) 100%);
            z-index: 1;
        }

        .hero-overlay-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 180px;
            background: linear-gradient(to top, var(--white) 0%, transparent 100%);
            z-index: 2;
        }

        .hero-content {
            position: relative;
            z-index: 3;
            text-align: center;
            color: var(--white);
            max-width: 860px;
            padding: 0 24px;
            animation: heroFade 1s ease-out both;
        }

        @keyframes heroFade {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(212, 161, 30, 0.2);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(212, 161, 30, 0.4);
            color: var(--accent-light);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            padding: 8px 24px;
            border-radius: 100px;
            margin-bottom: 28px;
        }

        .hero-badge span {
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {

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

        .hero-content h1 {
            font-family: var(--font-heading);
            font-size: clamp(2.5rem, 6vw, 4.2rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .hero-content h1 em {
            color: var(--accent-light);
            font-style: normal;
        }

        .hero-content p {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 40px;
            max-width: 560px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-btns {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero-primary {
            padding: 16px 40px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: var(--primary-dark);
            font-weight: 800;
            font-size: 0.95rem;
            border-radius: 60px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(212, 161, 30, 0.4);
        }

        .btn-hero-ghost {
            padding: 15px 38px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            color: var(--white);
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 60px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(8px);
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-hero-ghost:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: var(--white);
            transform: translateY(-3px);
        }

        .hero-scroll {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.7rem;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            animation: bounce 2s infinite;
            cursor: pointer;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateX(-50%) translateY(0);
            }

            50% {
                transform: translateX(-50%) translateY(6px);
            }
        }

        .stats-strip {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 60px 0;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            overflow: hidden;
        }

        .stat-item {
            text-align: center;
            padding: 32px 24px;
            background: rgba(27, 94, 63, 0.9);
            transition: all 0.3s;
        }

        .stat-item:hover {
            background: rgba(27, 94, 63, 1);
            transform: translateY(-4px);
        }

        .stat-item .num {
            font-family: var(--font-heading);
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--accent);
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-item .label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
        }

        section {
            padding: 100px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-eyebrow {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 800;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--accent);
            margin-bottom: 16px;
        }

        .section-header h2 {
            font-family: var(--font-heading);
            font-size: clamp(2rem, 3.5vw, 2.8rem);
            font-weight: 800;
            color: var(--primary);
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .section-header p {
            font-size: 1rem;
            color: var(--gray-600);
            max-width: 600px;
            margin: 0 auto;
        }

        .divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent-light));
            margin: 20px auto 0;
            border-radius: 3px;
        }

        /* ABOUT SECTION - PERBAIKAN UKURAN CARD */
        #about {
            background: var(--gray-50);
        }

        .about-grid {
            display: grid;
            grid-template-columnS: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .about-img-wrap {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            min-height: 400px;
            border: 1px solid var(--gray-200);
            transition: all 0.3s;
        }

        .about-img-wrap:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-xl);
            border-color: transparent;
        }

        .about-img-main {
            width: 100%;
            height: 400px;
            object-fit: cover;
            transition: transform 0.5s;
            position: relative;
        }

        /* GAMBAR PERUSAHAAN - MENGGUNAKAN BACKGROUND IMAGE DARI URL */
        .about-img-main {
            background-image: url('https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&h=600&fit=crop&crop=center');
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: flex-end;
            justify-content: flex-start;
            padding: 30px;
        }

        .about-img-main::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.6) 0%, transparent 50%);
            pointer-events: none;
        }

        .about-img-overlay-text {
            position: relative;
            z-index: 2;
            color: var(--white);
        }

        .about-img-overlay-text h3 {
            font-family: var(--font-heading);
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 4px;
        }

        .about-img-overlay-text p {
            font-size: 0.85rem;
            opacity: 0.9;
        }

        .about-img-wrap:hover .about-img-main {
            transform: scale(1.02);
        }

        .about-badge {
            position: absolute;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            padding: 16px 24px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: var(--shadow-lg);
            z-index: 3;
        }

        .about-badge i {
            font-size: 2rem;
            color: var(--primary-dark);
        }

        .about-badge div span {
            display: block;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary-dark);
            line-height: 1;
        }

        .about-badge div small {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--primary-dark);
            opacity: 0.8;
        }

        .about-text {
            background: var(--white);
            padding: 40px;
            border-radius: 24px;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .about-text:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-xl);
            border-color: transparent;
        }

        .about-text h2 {
            font-family: var(--font-heading);
            font-size: clamp(1.8rem, 3vw, 2.3rem);
            font-weight: 800;
            color: var(--primary);
            line-height: 1.3;
            margin-bottom: 20px;
        }

        .about-text h2 em {
            color: var(--accent);
            font-style: normal;
        }

        .about-text p {
            color: var(--gray-600);
            margin-bottom: 16px;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        .about-points {
            list-style: none;
            margin: 24px 0 32px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .about-points li {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .about-points li i {
            width: 28px;
            height: 28px;
            background: var(--primary-light);
            color: var(--accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .btn-solid {
            padding: 14px 32px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: var(--white);
            font-weight: 700;
            border-radius: 60px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            align-self: flex-start;
        }

        .btn-solid:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .vacancy-filter {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 48px;
        }

        .filter-btn {
            padding: 10px 28px;
            border-radius: 60px;
            font-size: 0.85rem;
            font-weight: 700;
            border: 2px solid var(--gray-200);
            background: transparent;
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.2s;
        }

        .filter-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .filter-btn.active {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
        }

        .vacancy-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
            margin-bottom: 48px;
        }

        .vacancy-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 24px;
            padding: 32px 24px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 280px;
        }

        .vacancy-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            transform: scaleY(0);
            transition: transform 0.3s;
            transform-origin: bottom;
        }

        .vacancy-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-xl);
            border-color: transparent;
        }

        .vacancy-card:hover::before {
            transform: scaleY(1);
        }

        .vacancy-dept {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--gray-100);
            padding: 5px 14px;
            border-radius: 60px;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--gray-600);
            margin-bottom: 16px;
            align-self: flex-start;
        }

        .vacancy-card h3 {
            font-family: var(--font-heading);
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 12px;
        }

        .vacancy-card p {
            font-size: 0.85rem;
            color: var(--gray-600);
            margin-bottom: 20px;
            line-height: 1.6;
            flex-grow: 1;
        }

        .vacancy-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 20px;
        }

        .vacancy-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        .vacancy-meta i {
            color: var(--accent);
            font-size: 0.75rem;
        }

        .vacancy-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid var(--gray-200);
            margin-top: auto;
        }

        .vacancy-deadline {
            font-size: 0.75rem;
            color: var(--gray-500);
        }

        .btn-apply {
            padding: 8px 24px;
            background: var(--primary);
            color: var(--white);
            font-size: 0.8rem;
            font-weight: 700;
            border-radius: 60px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-apply:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .vacancy-cta {
            text-align: center;
        }

        .btn-outline-navy {
            padding: 14px 36px;
            border: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 700;
            border-radius: 60px;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-outline-navy:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateY(-2px);
        }

        #why {
            background: linear-gradient(145deg, var(--primary), var(--primary-dark));
            color: var(--white);
        }

        .why-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            margin-bottom: 60px;
        }

        .why-card {
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 32px 24px;
            transition: all 0.3s;
            text-align: center;
            min-height: 280px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .why-card:hover {
            background: rgba(255, 255, 255, 0.12);
            transform: translateY(-6px);
        }

        .why-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 1.8rem;
            color: var(--primary-dark);
        }

        .why-card h3 {
            font-family: var(--font-heading);
            font-size: 1.1rem;
            font-weight: 800;
            margin-bottom: 12px;
        }

        .why-card p {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.6;
        }

        .event-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 24px;
        }

        .event-card {
            background: var(--gray-50);
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--gray-200);
            transition: all 0.3s;
            min-height: 280px;
            display: flex;
            flex-direction: column;
        }

        .event-card:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-xl);
            border-color: transparent;
        }

        .event-img {
            height: 180px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .event-img i {
            color: rgba(255, 255, 255, 0.3);
        }

        .event-body {
            padding: 24px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .event-tag {
            display: inline-block;
            background: rgba(212, 161, 30, 0.15);
            color: var(--accent);
            padding: 4px 14px;
            border-radius: 60px;
            font-size: 0.7rem;
            font-weight: 700;
            align-self: flex-start;
            margin-bottom: 12px;
        }

        .event-body h3 {
            font-family: var(--font-heading);
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 8px;
        }

        .event-body p {
            color: var(--gray-600);
            font-size: 0.85rem;
            margin-bottom: 16px;
            flex-grow: 1;
        }

        .event-meta {
            display: flex;
            gap: 20px;
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: auto;
        }

        .event-meta span {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .event-meta i {
            color: var(--accent);
        }

        .event-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .event-list-item {
            background: var(--gray-50);
            border-radius: 20px;
            padding: 20px;
            display: flex;
            gap: 16px;
            align-items: center;
            border: 1px solid var(--gray-200);
            transition: all 0.3s;
            min-height: 100px;
        }

        .event-list-item:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
            border-color: transparent;
        }

        .event-date-box {
            background: var(--primary);
            border-radius: 12px;
            padding: 12px 16px;
            text-align: center;
            min-width: 60px;
            flex-shrink: 0;
        }

        .event-date-box .day {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--white);
            line-height: 1;
        }

        .event-date-box .month {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
        }

        .event-list-item h4 {
            font-weight: 800;
            color: var(--primary);
            font-size: 0.95rem;
        }

        .event-list-item p {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: 2px;
        }

        .process-steps {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0;
            position: relative;
        }

        .process-steps::before {
            content: '';
            position: absolute;
            top: 40px;
            left: 10%;
            right: 10%;
            height: 2px;
            background: linear-gradient(90deg, var(--accent), var(--primary), var(--accent));
            z-index: 0;
        }

        .process-step {
            text-align: center;
            padding: 0 16px;
            position: relative;
            z-index: 1;
        }

        .step-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--white);
            border: 3px solid var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-family: var(--font-heading);
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--primary);
            transition: all 0.3s;
        }

        .process-step:hover .step-circle {
            background: var(--accent);
            transform: scale(1.08);
            color: var(--primary-dark);
        }

        .process-step h4 {
            font-family: var(--font-heading);
            font-size: 1rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .process-step p {
            font-size: 0.8rem;
            color: var(--gray-500);
        }

        #cta-banner {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .cta-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 50px;
            flex-wrap: wrap;
            position: relative;
            z-index: 1;
        }

        .cta-text h2 {
            font-family: var(--font-heading);
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 800;
            color: var(--white);
            margin-bottom: 12px;
        }

        .cta-text p {
            color: rgba(255, 255, 255, 0.7);
            max-width: 480px;
        }

        .cta-btns {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .btn-gold {
            padding: 14px 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
            color: var(--primary-dark);
            font-weight: 800;
            border-radius: 60px;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-gold:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(212, 161, 30, 0.4);
        }

        footer {
            background: #0A1C16;
            color: rgba(255, 255, 255, 0.6);
            padding: 70px 0 0;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.2fr;
            gap: 50px;
            padding-bottom: 50px;
        }

        .footer-brand .logo-row {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .footer-brand .logo-row .logo-mark {
            background: linear-gradient(135deg, var(--accent), var(--accent-light));
        }

        .footer-brand .logo-row .logo-mark i {
            color: var(--primary-dark);
        }

        .footer-brand p {
            font-size: 0.85rem;
            line-height: 1.7;
            margin-bottom: 24px;
        }

        .social-row {
            display: flex;
            gap: 12px;
        }

        .social-btn {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, 0.5);
            transition: all 0.2s;
            font-size: 1rem;
            text-decoration: none;
        }

        .social-btn:hover {
            background: var(--accent);
            color: var(--primary-dark);
            transform: translateY(-3px);
        }

        .footer-col h4 {
            font-size: 0.9rem;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 24px;
            position: relative;
            padding-bottom: 12px;
        }

        .footer-col h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 36px;
            height: 2px;
            background: var(--accent);
        }

        .footer-col ul {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-col ul li a {
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-col ul li a:hover {
            color: var(--white);
            padding-left: 4px;
        }

        .footer-contact-item {
            display: flex;
            gap: 12px;
            margin-bottom: 16px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.5);
        }

        .footer-contact-item i {
            color: var(--accent);
            width: 18px;
            margin-top: 2px;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding: 24px 0;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.4);
        }

        .footer-bottom a {
            color: var(--accent-light);
            text-decoration: none;
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .reveal.visible {
            opacity: 1;
            transform: none;
        }

        .reveal-delay-1 {
            transition-delay: 0.1s;
        }

        .reveal-delay-2 {
            transition-delay: 0.2s;
        }

        .reveal-delay-3 {
            transition-delay: 0.3s;
        }

        @media (max-width: 1024px) {
            .vacancy-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .why-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .event-grid {
                grid-template-columns: 1fr;
            }

            .process-steps {
                grid-template-columns: repeat(3, 1fr);
                gap: 30px;
            }

            .process-steps::before {
                display: none;
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {

            .nav-links,
            .nav-actions {
                display: none;
            }

            .mobile-toggle {
                display: block;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .about-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .about-img-main {
                height: 300px;
            }

            .about-text {
                padding: 28px;
                min-height: auto;
            }

            .vacancy-grid {
                grid-template-columns: 1fr;
            }

            .why-grid {
                grid-template-columns: 1fr;
            }

            .event-grid {
                grid-template-columns: 1fr;
            }

            .process-steps {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .cta-inner {
                flex-direction: column;
                text-align: center;
            }

            .hero-btns {
                flex-direction: column;
                align-items: center;
            }

            .about-img-overlay-text h3 {
                font-size: 1.4rem;
            }

            .about-badge {
                bottom: 20px;
                right: 20px;
                padding: 12px 18px;
            }

            .about-badge i {
                font-size: 1.4rem;
            }

            .about-badge div span {
                font-size: 1.2rem;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .process-steps {
                grid-template-columns: 1fr;
            }

            .about-img-main {
                height: 250px;
            }

            .about-text {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <nav id="navbar">
        <div class="container">
            <div class="nav-inner">
                <a href="#hero" class="nav-logo">
                    <div class="logo-mark"><i class="fas fa-leaf"></i></div>
                    <div class="logo-text">
                        <div class="brand">RSUP <span>Career</span></div>
                        <div class="sub">PT Riau Sakti United Plantation</div>
                    </div>
                </a>
                <ul class="nav-links">
                    <li><a href="#hero" class="active">Beranda</a></li>
                    <li><a href="#about">Tentang Kami</a></li>
                    <li><a href="#vacancy">Lowongan</a></li>
                    <li><a href="#event">Acara</a></li>
                    <li><a href="#news">Berita</a></li>
                </ul>
                <div class="nav-actions">
                    <a href="/login"><button class="btn-outline"><i class="fas fa-user"></i> Masuk</button></a>
                    <a href="/register"><button class="btn-primary"><i class="fas fa-user-plus"></i> Daftar</button></a>
                </div>
                <button class="mobile-toggle" onclick="openMenu()"><i class="fas fa-bars"></i></button>
            </div>
        </div>
    </nav>

    <div class="mobile-nav" id="mobileNav">
        <button class="mobile-nav-close" onclick="closeMenu()"><i class="fas fa-times"></i></button>
        <a href="#hero" onclick="closeMenu()">Beranda</a>
        <a href="#about" onclick="closeMenu()">Tentang Kami</a>
        <a href="#vacancy" onclick="closeMenu()">Lowongan</a>
        <a href="#event" onclick="closeMenu()">Acara</a>
        <a href="#news" onclick="closeMenu()">Berita</a>
        <div class="mobile-nav-actions">
            <a href="/login"><button class="btn-outline">Masuk</button></a>
            <a href="/register"><button class="btn-primary">Daftar</button></a>
        </div>
    </div>

    <section id="hero">
        <div class="hero-video-wrap">
            <iframe
                src="https://www.youtube.com/embed/m4ipa_8fzy8?autoplay=1&mute=1&loop=1&playlist=m4ipa_8fzy8&controls=0&showinfo=0&rel=0&playsinline=1"
                allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>
        <div class="hero-overlay"></div>
        <div class="hero-overlay-bottom"></div>
        <div class="hero-content">
            <div class="hero-badge"><span></span> KARIR BERSAMA KAMI</div>
            <h1>Mari Wujudkan <em>Perjalanan Karir</em> Hebat Bersama Kami</h1>
            <p>Bergabunglah dengan perusahaan perkebunan kelapa hibrida terkemuka di Indonesia yang telah berdiri sejak
                1986.</p>
            <div class="hero-btns">
                <a href="#vacancy"><button class="btn-hero-primary"><i class="fas fa-arrow-right"></i> Lihat
                        Lowongan</button></a>
                <a href="#about"><button class="btn-hero-ghost"><i class="fas fa-play-circle"></i> Tentang
                        Kami</button></a>
            </div>
        </div>
        <div class="hero-scroll" onclick="document.getElementById('about').scrollIntoView({behavior:'smooth'})">
            <i class="fas fa-chevron-down"></i>
            <span>Gulir ke bawah</span>
        </div>
    </section>

    <div class="stats-strip">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item reveal">
                    <div class="num">6.000+</div>
                    <div class="label">Karyawan Aktif</div>
                </div>
                <div class="stat-item reveal reveal-delay-1">
                    <div class="num">1986</div>
                    <div class="label">Tahun Berdiri</div>
                </div>
                <div class="stat-item reveal reveal-delay-2">
                    <div class="num">3.000+</div>
                    <div class="label">Km Jaringan Irigasi</div>
                </div>
                <div class="stat-item reveal reveal-delay-3">
                    <div class="num">50+</div>
                    <div class="label">Tahun Pengalaman</div>
                </div>
            </div>
        </div>
    </div>

    <!-- PERBAIKAN: Bagian Tentang Kami dengan gambar perusahaan -->
    <section id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-img-wrap reveal">
                    <div class="about-img-main">
                        <div class="about-img-overlay-text">
                            <h3>Perkebunan Kelapa Hibrida</h3>
                            <p>Terluas di Indonesia</p>
                        </div>
                    </div>
                    <div class="about-badge">
                        <i class="fas fa-crown"></i>
                        <div><span>#1</span><small>Kelapa Hibrida</small></div>
                    </div>
                </div>
                <div class="about-text reveal reveal-delay-2">
                    <span class="section-eyebrow">Tentang Kami</span>
                    <h2>Pelopor Perkebunan Kelapa <em>Hibrida di Indonesia</em></h2>
                    <p>PT Riau Sakti United Plantation didirikan tahun 1986 sebagai perkebunan kelapa hibrida pertama
                        yang berhasil dibudidayakan di lahan gambut di Indonesia. Kami adalah bagian dari Sambu Group,
                        perusahaan kelapa terintegrasi terbesar di Indonesia.</p>
                    <p>Dengan lebih dari 6.000 karyawan dan jaringan irigasi sepanjang 3.000+ km yang diakui sebagai
                        pertama di dunia, kami terus bertumbuh dan menciptakan lapangan kerja berkualitas.</p>
                    <ul class="about-points">
                        <li><i class="fas fa-check"></i> Perkebunan kelapa hibrida terluas di Indonesia</li>
                        <li><i class="fas fa-check"></i> Fasilitas produksi bertaraf internasional</li>
                        <li><i class="fas fa-check"></i> Program pengembangan SDM berkelanjutan</li>
                        <li><i class="fas fa-check"></i> Lingkungan kerja yang aman, nyaman dan inklusif</li>
                    </ul>
                    <button class="btn-solid" onclick="location.href='#about'"><i class="fas fa-arrow-right"></i>
                        Pelajari Lebih Lanjut</button>
                </div>
            </div>
        </div>
    </section>

    <section id="vacancy">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-eyebrow">Lowongan</span>
                <h2>Posisi yang Tersedia</h2>
                <div class="divider"></div>
                <p>Temukan posisi yang sesuai dengan keahlian dan minat Anda.</p>
            </div>
            <div class="vacancy-filter reveal">
                <button class="filter-btn active" onclick="filterVacancy(this, 'all')">Semua Posisi</button>
                @php
                    $categories = ['Perkebunan', 'Industri', 'Teknik', 'Administrasi', 'IT'];
                @endphp
                @foreach ($categories as $cat)
                    <button class="filter-btn"
                        onclick="filterVacancy(this, '{{ strtolower($cat) }}')">{{ $cat }}</button>
                @endforeach
            </div>
            <div class="vacancy-grid">
                @forelse($lowongans as $index => $lowongan)
                    <div class="vacancy-card reveal {{ $index % 3 == 1 ? 'reveal-delay-1' : ($index % 3 == 2 ? 'reveal-delay-2' : '') }}"
                        data-cat="{{ strtolower($lowongan->kategori ?? 'lainnya') }}">
                        <span class="vacancy-dept">
                            <i class="fas {{ $lowongan->icon ?? 'fa-briefcase' }}"></i>
                            {{ $lowongan->kategori ?? 'Umum' }}
                        </span>
                        <h3>{{ $lowongan->judul_lowongan }}</h3>
                        <p>{{ Str::limit($lowongan->deskripsi, 120) }}</p>
                        <div class="vacancy-meta">
                            <span><i class="fas fa-map-marker-alt"></i> {{ $lowongan->lokasi ?? 'Riau' }}</span>
                            <span><i class="fas fa-briefcase"></i> Full-time</span>
                        </div>
                        <div class="vacancy-footer">
                            <span class="vacancy-deadline"><i class="fas fa-clock"></i> Tutup
                                {{ \Carbon\Carbon::parse($lowongan->tanggal_tutup)->translatedFormat('d M Y') }}</span>
                            <a href="{{ route('lowongan.detail', $lowongan->id) }}">
                                <button class="btn-apply">Lamar →</button>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada lowongan yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
            {{-- <div class="vacancy-cta reveal">
                <a href="{{ route('lowongan.list') }}">
                    <button class="btn-outline-navy"><i class="fas fa-th-list"></i> Lihat Semua Lowongan</button>
                </a>
            </div> --}}
        </div>
    </section>

    <section id="why">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-eyebrow" style="color: var(--accent-light);">Mengapa Bergabung</span>
                <h2 style="color: var(--white);">Lingkungan Kerja yang Mendukung Pertumbuhan Anda</h2>
                <div class="divider"></div>
                <p style="color: rgba(255,255,255,0.7);">Kami percaya karyawan adalah aset terbesar. Bergabunglah dan
                    rasakan perbedaannya.</p>
            </div>
            <div class="why-grid">
                <div class="why-card reveal">
                    <div class="why-icon"><i class="fas fa-chart-line"></i></div>
                    <h3>Pengembangan Karir</h3>
                    <p>Sistem pengembangan karir yang terintegrasi dan jelas.</p>
                </div>
                <div class="why-card reveal reveal-delay-1">
                    <div class="why-icon"><i class="fas fa-brain"></i></div>
                    <h3>Pelatihan & Kompetensi</h3>
                    <p>Program peningkatan soft-skill dan kompetensi teknis.</p>
                </div>
                <div class="why-card reveal reveal-delay-2">
                    <div class="why-icon"><i class="fas fa-users"></i></div>
                    <h3>Komunitas Aktif</h3>
                    <p>Bergabung dengan komunitas karyawan yang aktif.</p>
                </div>
                <div class="why-card reveal reveal-delay-3">
                    <div class="why-icon"><i class="fas fa-shield-alt"></i></div>
                    <h3>Fasilitas & Kesejahteraan</h3>
                    <p>Klinik, sekolah, tempat ibadah, dan fasilitas rekreasi.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="event">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-eyebrow">Acara</span>
                <h2>Acara & Kegiatan Terkini</h2>
                <div class="divider"></div>
                <p>Ikuti berbagai program rekrutmen, job fair, dan kegiatan perusahaan.</p>
            </div>
            <div class="event-grid">
                <div class="event-card reveal">
                    <div class="event-img">
                        <i class="fas fa-calendar-alt fa-3x"></i>
                    </div>
                    <div class="event-body">
                        <span class="event-tag">Job Fair</span>
                        <h3>Rekrutmen Besar-besaran RSUP 2026</h3>
                        <p>Membuka ratusan posisi dalam sesi rekrutmen langsung.</p>
                        <div class="event-meta">
                            <span><i class="fas fa-calendar"></i> 15–16 Agustus 2026</span>
                            <span><i class="fas fa-map-marker-alt"></i> Pekanbaru</span>
                        </div>
                    </div>
                </div>
                <div class="event-list">
                    <div class="event-list-item reveal reveal-delay-1">
                        <div class="event-date-box">
                            <div class="day">22</div>
                            <div class="month">Jul</div>
                        </div>
                        <div>
                            <h4>Campus Hiring Universitas Riau</h4>
                            <p>Rekrutmen khusus fresh graduate</p>
                        </div>
                    </div>
                    <div class="event-list-item reveal reveal-delay-2">
                        <div class="event-date-box">
                            <div class="day">05</div>
                            <div class="month">Agt</div>
                        </div>
                        <div>
                            <h4>Webinar Karir: Berkarir di Perkebunan</h4>
                            <p>Sesi online gratis</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="process">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-eyebrow">Proses Rekrutmen</span>
                <h2>5 Langkah Mudah Bergabung</h2>
                <div class="divider"></div>
            </div>
            <div class="process-steps">
                <div class="process-step reveal">
                    <div class="step-circle">1</div>
                    <h4>Registrasi</h4>
                    <p>Buat akun pelamar</p>
                </div>
                <div class="process-step reveal reveal-delay-1">
                    <div class="step-circle">2</div>
                    <h4>Lengkapi Profil</h4>
                    <p>Isi data diri & pengalaman</p>
                </div>
                <div class="process-step reveal reveal-delay-2">
                    <div class="step-circle">3</div>
                    <h4>Ajukan Lamaran</h4>
                    <p>Pilih posisi yang sesuai</p>
                </div>
                <div class="process-step reveal reveal-delay-3">
                    <div class="step-circle">4</div>
                    <h4>Seleksi & Tes</h4>
                    <p>Ikuti tes online</p>
                </div>
                <div class="process-step reveal reveal-delay-4">
                    <div class="step-circle">5</div>
                    <h4>Wawancara</h4>
                    <p>Interview dengan HRD</p>
                </div>
            </div>
        </div>
    </section>

    <section id="news">
        <div class="container">
            <div class="section-header reveal">
                <span class="section-eyebrow">Berita & Artikel</span>
                <h2>Info Karir & Update Perusahaan</h2>
                <div class="divider"></div>
                <p>Tetap update dengan perkembangan terbaru perusahaan.</p>
            </div>
            <div class="news-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
                <div class="news-card" style="background: var(--gray-50); border-radius: 24px; overflow: hidden;">
                    <div class="news-img"
                        style="height: 200px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-star fa-2x" style="color: rgba(255,255,255,0.3);"></i>
                    </div>
                    <div class="news-body" style="padding: 24px;">
                        <div class="news-date" style="font-size: 0.8rem;"><i class="fas fa-calendar"></i> 30
                            September 2025</div>
                        <h3 style="font-family: var(--font-heading);">Ikuti Cara Ini untuk Meraih Karier Impianmu!</h3>
                        <p style="font-size: 0.85rem;">Strategi jitu mempersiapkan diri menghadapi rekrutmen.</p><a
                            href="#" class="btn-read"
                            style="color: var(--accent); text-decoration: none; font-weight: 700;">Baca Selengkapnya
                            →</a>
                    </div>
                </div>
                <div class="news-card" style="background: var(--gray-50); border-radius: 24px; overflow: hidden;">
                    <div class="news-img"
                        style="height: 200px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users fa-2x" style="color: rgba(255,255,255,0.3);"></i>
                    </div>
                    <div class="news-body" style="padding: 24px;">
                        <div class="news-date" style="font-size: 0.8rem;"><i class="fas fa-calendar"></i> 15 Agustus
                            2025</div>
                        <h3 style="font-family: var(--font-heading);">Perkembangan Baru Lingkungan Kerja</h3>
                        <p style="font-size: 0.85rem;">Bagaimana Gen Z membentuk ulang budaya kerja.</p><a
                            href="#" class="btn-read"
                            style="color: var(--accent); text-decoration: none; font-weight: 700;">Baca Selengkapnya
                            →</a>
                    </div>
                </div>
                <div class="news-card" style="background: var(--gray-50); border-radius: 24px; overflow: hidden;">
                    <div class="news-img"
                        style="height: 200px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-handshake fa-2x" style="color: rgba(255,255,255,0.3);"></i>
                    </div>
                    <div class="news-body" style="padding: 24px;">
                        <div class="news-date" style="font-size: 0.8rem;"><i class="fas fa-calendar"></i> 18 Juli
                            2025</div>
                        <h3 style="font-family: var(--font-heading);">Delegasi Tugas: Kunci Efektivitas Tim</h3>
                        <p style="font-size: 0.85rem;">Memahami seni mendelegasikan tanggung jawab.</p><a
                            href="#" class="btn-read"
                            style="color: var(--accent); text-decoration: none; font-weight: 700;">Baca Selengkapnya
                            →</a>
                    </div>
                </div>
            </div>
            <div class="news-cta" style="text-align: center; margin-top: 40px;">
                <button class="btn-outline-navy" onclick="location.href='/news'"><i class="fas fa-newspaper"></i>
                    Lihat Semua Artikel</button>
            </div>
        </div>
    </section>

    <section id="cta-banner">
        <div class="container">
            <div class="cta-inner">
                <div class="cta-text">
                    <h2>Siap Memulai Perjalanan Karir Bersama Kami?</h2>
                    <p>Daftarkan diri Anda sekarang dan bergabunglah dengan lebih dari 6.000 karyawan.</p>
                </div>
                <div class="cta-btns"><a href="/register"><button class="btn-gold"><i class="fas fa-user-plus"></i>
                            Daftar Sekarang</button></a><a href="#vacancy"><button class="btn-outline"
                            style="border-color: rgba(255,255,255,0.5); color: var(--white); background: transparent;"><i
                                class="fas fa-search"></i> Lihat Lowongan</button></a></div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo-row">
                        <div class="logo-mark"><i class="fas fa-leaf"></i></div>
                        <div>
                            <div
                                style="font-family: var(--font-heading); font-size: 1.2rem; font-weight: 800; color: var(--white);">
                                RSUP Career</div>
                            <div style="font-size: 0.7rem; opacity: 0.6;">PT Riau Sakti United Plantation</div>
                        </div>
                    </div>
                    <p>Perusahaan kelapa terintegrasi terbesar di Indonesia. Berkomitmen menciptakan peluang karir
                        terbaik sejak 1986.</p>
                    <div class="social-row"><a href="#" class="social-btn"><i
                                class="fab fa-facebook-f"></i></a><a href="#" class="social-btn"><i
                                class="fab fa-instagram"></i></a><a href="#" class="social-btn"><i
                                class="fab fa-linkedin-in"></i></a><a href="#" class="social-btn"><i
                                class="fab fa-youtube"></i></a></div>
                </div>
                <div class="footer-col">
                    <h4>Navigasi</h4>
                    <ul>
                        <li><a href="#hero"><i class="fas fa-chevron-right"></i> Beranda</a></li>
                        <li><a href="#about"><i class="fas fa-chevron-right"></i> Tentang Kami</a></li>
                        <li><a href="#vacancy"><i class="fas fa-chevron-right"></i> Lowongan</a></li>
                        <li><a href="#event"><i class="fas fa-chevron-right"></i> Acara</a></li>
                        <li><a href="#news"><i class="fas fa-chevron-right"></i> Berita</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Posisi Populer</h4>
                    <ul>
                        @if (isset($lowongans) && $lowongans->count() > 0)
                            @foreach ($lowongans->take(5) as $lowongan)
                                <li><a href="{{ route('lowongan.detail', $lowongan->id) }}"><i
                                            class="fas fa-chevron-right"></i> {{ $lowongan->judul_lowongan }}</a></li>
                            @endforeach
                        @else
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Belum ada lowongan</a></li>
                        @endif
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Kontak Kami</h4>
                    <div class="footer-contact-item"><i class="fas fa-map-marker-alt"></i><span>Indragiri Hilir,
                            Riau</span></div>
                    <div class="footer-contact-item"><i class="fas fa-phone"></i><span>(0761) 1234567</span></div>
                    <div class="footer-contact-item"><i class="fas fa-envelope"></i><span>hrd@riausakti.co.id</span>
                    </div>
                </div>
            </div>
            <div class="footer-bottom"><span>&copy; 2025 PT Riau Sakti United Plantation. All rights
                    reserved.</span><span>Part of <a href="https://www.sambugroup.com" target="_blank">Sambu
                        Group</a></span></div>
        </div>
    </footer>

    <script>
        function openMenu() {
            document.getElementById('mobileNav').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            document.getElementById('mobileNav').classList.remove('open');
            document.body.style.overflow = '';
        }
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 60);
        });
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const href = a.getAttribute('href');
                if (href === '#') return;
                const target = document.querySelector(href);
                if (!target) return;
                e.preventDefault();
                window.scrollTo({
                    top: target.offsetTop - 80,
                    behavior: 'smooth'
                });
                if (document.getElementById('mobileNav').classList.contains('open')) closeMenu();
            });
        });
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, {
            threshold: 0.12
        });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
        const sections = ['hero', 'about', 'vacancy', 'why', 'event', 'news'];
        window.addEventListener('scroll', () => {
            let cur = '';
            sections.forEach(id => {
                const el = document.getElementById(id);
                if (el && window.scrollY >= el.offsetTop - 120) cur = id;
            });
            document.querySelectorAll('.nav-links a').forEach(a => {
                a.classList.toggle('active', a.getAttribute('href') === '#' + cur);
            });
        });

        function filterVacancy(btn, cat) {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.vacancy-card').forEach(card => {
                card.style.display = (cat === 'all' || card.dataset.cat === cat) ? '' : 'none';
            });
        }
    </script>
</body>

</html>
