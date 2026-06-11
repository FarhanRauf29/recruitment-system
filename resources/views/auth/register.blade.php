<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - RSUP Career | PT Riau Sakti United Plantation</title>
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
            justify-content: center;
            align-items: center;
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
        .register-container {
            display: flex;
            max-width: 1200px;
            width: 100%;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            position: relative;
            z-index: 10;
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
            max-height: 90vh;
            overflow-y: auto;
        }

        .form-panel::-webkit-scrollbar {
            width: 6px;
        }

        .form-panel::-webkit-scrollbar-track {
            background: #E5E7EB;
            border-radius: 10px;
        }

        .form-panel::-webkit-scrollbar-thumb {
            background: #D4A11E;
            border-radius: 10px;
        }

        .company-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            padding-bottom: 16px;
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
            margin-bottom: 28px;
        }

        .form-header h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1B5E3F;
            margin-bottom: 6px;
        }

        .form-header p {
            color: #6B7280;
            font-size: 0.85rem;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.8rem;
            color: #37474F;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            font-size: 16px;
            transition: color 0.2s;
        }

        .input-wrapper .toggle-password {
            left: auto;
            right: 14px;
            cursor: pointer;
            z-index: 2;
        }

        .input-wrapper .toggle-password:hover {
            color: #D4A11E;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px 12px 44px;
            border: 2px solid #E5E7EB;
            border-radius: 12px;
            font-size: 0.85rem;
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

        /* Password Strength Indicator */
        .password-strength {
            margin-top: 8px;
            display: flex;
            gap: 6px;
            align-items: center;
            flex-wrap: wrap;
        }

        .strength-bar {
            flex: 1;
            height: 4px;
            background: #E5E7EB;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .strength-bar.active {
            background: #D4A11E;
        }

        .strength-text {
            font-size: 0.7rem;
            color: #6B7280;
        }

        /* Button */
        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #1B5E3F, #0D3B26);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 12px;
            margin-bottom: 20px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(27, 94, 63, 0.3);
            background: linear-gradient(135deg, #1B6B48, #0D4B30);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        /* Login Link */
        .login-section {
            text-align: center;
            padding-top: 16px;
            border-top: 1px solid #E5E7EB;
        }

        .login-section p {
            color: #6B7280;
            font-size: 0.8rem;
        }

        .login-link {
            color: #D4A11E;
            text-decoration: none;
            font-weight: 700;
            transition: color 0.2s;
        }

        .login-link:hover {
            color: #B8860B;
            text-decoration: underline;
        }

        /* Alert Messages */
        .alert-success {
            background: #E8F5ED;
            border-left: 4px solid #1B5E3F;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            color: #1B5E3F;
            animation: slideDown 0.4s ease;
        }

        .alert-success i {
            font-size: 16px;
        }

        .alert-error {
            background: #FFEBEE;
            border-left: 4px solid #F44336;
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.8rem;
            color: #C62828;
            animation: slideDown 0.4s ease;
        }

        .alert-error i {
            font-size: 16px;
        }

        .error-list {
            margin: 0;
            padding-left: 20px;
            font-size: 0.8rem;
        }

        .error-list li {
            margin: 4px 0;
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

        /* Terms Checkbox */
        .terms-group {
            margin: 20px 0;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 0.75rem;
            color: #6B7280;
        }

        .checkbox-label input {
            width: 16px;
            height: 16px;
            accent-color: #D4A11E;
            cursor: pointer;
        }

        .checkbox-label a {
            color: #D4A11E;
            text-decoration: none;
            font-weight: 600;
        }

        .checkbox-label a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .register-container {
                flex-direction: column;
                max-width: 550px;
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
                padding: 40px 32px;
                max-height: none;
            }
        }

        @media (max-width: 480px) {
            .brand-panel {
                padding: 32px 24px;
            }

            .form-panel {
                padding: 32px 24px;
            }

            .form-header h1 {
                font-size: 1.5rem;
            }

            .feature-item {
                padding: 10px 14px;
            }

            .feature-icon {
                width: 38px;
                height: 38px;
            }

            .feature-icon i {
                font-size: 16px;
            }

            .feature-text h4 {
                font-size: 0.85rem;
            }

            .feature-text p {
                font-size: 0.7rem;
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

    <div class="register-container">
        <!-- Left Panel - Branding -->
        <div class="brand-panel">
            <div class="brand-logo">
                <div class="brand-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2>RSUP <span>Career</span></h2>
                <p>Mulai perjalanan karir Anda bersama perusahaan perkebunan kelapa hibrida terbesar di Indonesia</p>
            </div>

            <div class="feature-list">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Peluang Karir</h4>
                        <p>Akses ke berbagai posisi menarik</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Lingkungan Profesional</h4>
                        <p>Bekerja dengan tim terbaik</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Pengembangan Diri</h4>
                        <p>Program pelatihan & sertifikasi</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="feature-text">
                        <h4>Jenjang Karir Jelas</h4>
                        <p>Sistem promosi transparan</p>
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
                <h1>Buat Akun Baru</h1>
                <p>Isi form di bawah untuk memulai pendaftaran</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="error-list">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Success Message -->
            @if (session('status'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user"></i>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                            autofocus class="form-control" placeholder="Silahkan Masukkan Nama Lengkap ">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            class="form-control" placeholder="Silahkan Masukkan Alamat Email ">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" name="password" required class="form-control"
                            placeholder="Minimal 8 karakter">
                        <i class="fas fa-eye-slash toggle-password" id="togglePassword" style="cursor: pointer;"></i>
                    </div>
                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                        <div class="strength-bar"></div>
                        <span class="strength-text" id="strengthText">Kekuatan password</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="form-control" placeholder="Ketik ulang kata sandi Anda">
                        <i class="fas fa-eye-slash toggle-password" id="toggleConfirmPassword"
                            style="cursor: pointer;"></i>
                    </div>
                </div>

                <div class="terms-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="terms" id="terms" required>
                        <span>Saya menyetujui <a href="#">Syarat & Ketentuan</a> dan <a href="#">Kebijakan
                                Privasi</a></span>
                    </label>
                </div>

                <button type="submit" class="btn-register">
                    <i class="fas fa-user-plus"></i>
                    Daftar Sekarang
                </button>

                <div class="login-section">
                    <p>Sudah punya akun? <a href="{{ route('login') }}" class="login-link">Masuk di sini</a></p>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        if (toggleConfirmPassword && confirmPasswordInput) {
            toggleConfirmPassword.addEventListener('click', function() {
                const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPasswordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }

        // Password strength checker
        const passwordField = document.getElementById('password');
        const strengthBars = document.querySelectorAll('.strength-bar');
        const strengthText = document.getElementById('strengthText');

        if (passwordField) {
            passwordField.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                if (password.length >= 8) strength++;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
                if (password.match(/[0-9]/)) strength++;
                if (password.match(/[^a-zA-Z0-9]/)) strength++;

                strengthBars.forEach((bar, index) => {
                    if (index < strength) {
                        bar.classList.add('active');
                    } else {
                        bar.classList.remove('active');
                    }
                });

                if (strength === 0) strengthText.textContent = 'Kekuatan password';
                else if (strength === 1) strengthText.textContent = 'Lemah';
                else if (strength === 2) strengthText.textContent = 'Sedang';
                else if (strength === 3) strengthText.textContent = 'Kuat';
                else strengthText.textContent = 'Sangat Kuat';
            });
        }

        // Form validation
        const form = document.getElementById('registerForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                const terms = document.getElementById('terms');

                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Kata sandi dan konfirmasi kata sandi tidak cocok!');
                    return false;
                }

                if (password.length < 8) {
                    e.preventDefault();
                    alert('Kata sandi minimal 8 karakter!');
                    return false;
                }

                if (!terms.checked) {
                    e.preventDefault();
                    alert('Harap setujui Syarat & Ketentuan dan Kebijakan Privasi');
                    return false;
                }
            });
        }

        // Focus effects on inputs
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                const icon = this.parentElement.querySelector('i:not(.toggle-password)');
                if (icon) icon.style.color = '#D4A11E';
            });
            input.addEventListener('blur', function() {
                const icon = this.parentElement.querySelector('i:not(.toggle-password)');
                if (icon) icon.style.color = '#9CA3AF';
            });
        });
    </script>
</body>

</html>
