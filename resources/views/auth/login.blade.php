<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - RSUP Career | PT Riau Sakti United Plantation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@600;700&display=swap"
        rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(145deg, #E8F5ED 0%, #C8E6D9 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Background Decoration */
        .bg-coconut {
            position: absolute;
            opacity: 0.08;
            pointer-events: none;
        }

        .coconut-1 {
            top: 50px;
            left: 50px;
            font-size: 120px;
            animation: float 8s ease-in-out infinite;
        }

        .coconut-2 {
            bottom: 50px;
            right: 50px;
            font-size: 100px;
            animation: float 6s ease-in-out infinite reverse;
        }

        .coconut-3 {
            top: 30%;
            right: 15%;
            font-size: 80px;
            animation: float 7s ease-in-out infinite 1s;
        }

        .leaf {
            position: absolute;
            opacity: 0.06;
            font-size: 180px;
            bottom: -60px;
            left: -60px;
            transform: rotate(45deg);
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(5deg);
            }
        }

        /* Main Container */
        .login-container {
            display: flex;
            max-width: 1200px;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            position: relative;
            z-index: 10;
            backdrop-filter: blur(2px);
        }

        /* Left Panel - Branding */
        .brand-panel {
            flex: 1.2;
            background: linear-gradient(145deg, #1B5E3F 0%, #0D3B26 100%);
            color: white;
            padding: 60px 48px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .brand-panel::before {
            content: '🌴';
            position: absolute;
            font-size: 300px;
            bottom: -80px;
            right: -80px;
            opacity: 0.1;
            pointer-events: none;
        }

        .brand-panel::after {
            content: '🥥';
            position: absolute;
            font-size: 150px;
            top: -50px;
            left: -50px;
            opacity: 0.08;
            pointer-events: none;
        }

        .brand-logo {
            margin-bottom: 40px;
        }

        .brand-icon {
            width: 70px;
            height: 70px;
            background: rgba(212, 161, 30, 0.2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
        }

        .brand-icon i {
            font-size: 36px;
            color: #D4A11E;
        }

        .brand-panel h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .brand-panel h2 span {
            color: #D4A11E;
        }

        .brand-panel p {
            font-size: 1rem;
            line-height: 1.6;
            opacity: 0.85;
            margin-bottom: 40px;
            max-width: 320px;
        }

        /* Features List */
        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }

        .feature-item:hover {
            background: rgba(255, 255, 255, 0.15);
            transform: translateX(8px);
        }

        .feature-icon {
            width: 44px;
            height: 44px;
            background: rgba(212, 161, 30, 0.25);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .feature-icon i {
            font-size: 20px;
            color: #D4A11E;
        }

        .feature-text h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .feature-text p {
            font-size: 0.8rem;
            opacity: 0.7;
            margin: 0;
        }

        /* Right Panel - Form */
        .form-panel {
            flex: 1;
            padding: 60px 48px;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .company-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 32px;
            padding-bottom: 20px;
            border-bottom: 2px solid #E8F5ED;
        }

        .company-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(145deg, #1B5E3F, #0D3B26);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .company-icon i {
            font-size: 24px;
            color: #D4A11E;
        }

        .company-info h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: #1B5E3F;
        }

        .company-info p {
            font-size: 0.7rem;
            color: #6B7280;
        }

        .form-header {
            margin-bottom: 36px;
        }

        .form-header h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #1B5E3F;
            margin-bottom: 8px;
        }

        .form-header p {
            color: #6B7280;
            font-size: 0.9rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 0.85rem;
            color: #37474F;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 18px;
            transition: color 0.2s;
        }

        .input-wrapper .toggle-password {
            left: auto;
            right: 16px;
            cursor: pointer;
            z-index: 2;
        }

        .input-wrapper .toggle-password:hover {
            color: #D4A11E;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #E5E7EB;
            border-radius: 14px;
            font-size: 0.9rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: all 0.2s ease;
            background: #FAFAFA;
        }

        .form-control:focus {
            border-color: #D4A11E;
            outline: none;
            background: white;
            box-shadow: 0 0 0 4px rgba(212, 161, 30, 0.1);
        }

        .form-control:focus+i {
            color: #D4A11E;
        }

        /* Checkbox & Forgot */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 0.85rem;
            color: #6B7280;
        }

        .checkbox-label input {
            width: 18px;
            height: 18px;
            accent-color: #D4A11E;
            cursor: pointer;
        }

        .forgot-link {
            color: #D4A11E;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #B8860B;
            text-decoration: underline;
        }

        /* Button */
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #1B5E3F, #0D3B26);
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 24px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(27, 94, 63, 0.3);
            background: linear-gradient(135deg, #1B6B48, #0D4B30);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* Register Link */
        .register-section {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
        }

        .register-section p {
            color: #6B7280;
            font-size: 0.85rem;
        }

        .register-link {
            color: #D4A11E;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }

        .register-link:hover {
            color: #B8860B;
            text-decoration: underline;
        }

        /* Alert Messages */
        .alert-success {
            background: #E8F5ED;
            border-left: 4px solid #1B5E3F;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.85rem;
            color: #1B5E3F;
            animation: slideDown 0.4s ease;
        }

        .alert-success i {
            font-size: 18px;
        }

        .alert-error {
            background: #FFEBEE;
            border-left: 4px solid #F44336;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.85rem;
            color: #C62828;
            animation: slideDown 0.4s ease;
        }

        .alert-error i {
            font-size: 18px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive */
        @media (max-width: 992px) {
            .login-container {
                flex-direction: column;
                max-width: 500px;
            }

            .brand-panel {
                padding: 40px 32px;
                text-align: center;
            }

            .brand-panel p {
                max-width: 100%;
            }

            .feature-list {
                align-items: center;
            }

            .feature-item {
                width: 100%;
                max-width: 350px;
            }

            .form-panel {
                padding: 48px 32px;
            }
        }

        @media (max-width: 480px) {
            .brand-panel {
                padding: 32px 24px;
            }

            .form-panel {
                padding: 36px 24px;
            }

            .form-header h1 {
                font-size: 1.6rem;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <!-- Decorative Elements -->
    <div class="bg-coconut coconut-1">🌴</div>
    <div class="bg-coconut coconut-2">🥥</div>
    <div class="bg-coconut coconut-3">🌿</div>
    <div class="leaf">🌴</div>

    <div class="login-container">
        <!-- Left Panel - Branding -->
        <div class="brand-panel">
            <div class="brand-logo">
                <div class="brand-icon">
                    <i class="fas fa-leaf"></i>
                </div>
                <h2>RSUP <span>Career</span></h2>
                <p>Sistem Rekrutmen Online PT Riau Sakti United Plantation</p>
            </div>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Lowongan Terbaru</h4>
                        <p>Akses ke berbagai posisi menarik</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Pantau Status</h4>
                        <p>Lacak perkembangan lamaran Anda</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Notifikasi Real-time</h4>
                        <p>Dapatkan info terbaru proses rekrutmen</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Lamaran Online</h4>
                        <p>Proses lamaran cepat dan mudah</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Form -->
        <div class="form-panel">
            <div class="company-badge">
                <div class="company-icon">
                    <i class="fas fa-tree"></i>
                </div>
                <div class="company-info">
                    <h3>PT Riau Sakti United Plantation</h3>
                    <p>Perkebunan Kelapa Hibrida Terbesar di Indonesia</p>
                </div>
            </div>

            <div class="form-header">
                <h1>Selamat Datang Kembali</h1>
                <p>Masuk untuk melanjutkan proses rekrutmen Anda</p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autofocus class="form-control" placeholder="Silahkan Masukkan Alamat Email ">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" name="password" required class="form-control"
                            placeholder="Silahkan Masukkan Kata Sandi ">
                        <i class="fas fa-eye-slash toggle-password" id="togglePassword" style="cursor: pointer;"></i>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" id="remember_me">
                        <span>Ingat saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Lupa kata sandi?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-arrow-right-to-bracket"></i>
                    Masuk ke Akun
                </button>

                <div class="register-section">
                    <p>Belum punya akun? <a href="{{ route('register') }}" class="register-link">Daftar Sekarang</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        // Floating label effect on focus
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.querySelector('i:not(.toggle-password)').style.color = '#D4A11E';
            });
            input.addEventListener('blur', function() {
                this.parentElement.querySelector('i:not(.toggle-password)').style.color = '#9CA3AF';
            });
        });
    </script>
</body>

</html>
