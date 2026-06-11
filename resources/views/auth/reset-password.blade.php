<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - RSUP Career | PT Riau Sakti United Plantation</title>
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
        .reset-container {
            display: flex;
            max-width: 1100px;
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
            flex: 1;
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
            content: '🔐';
            position: absolute;
            font-size: 200px;
            bottom: -60px;
            right: -60px;
            opacity: 0.08;
            pointer-events: none;
        }

        .brand-panel::after {
            content: '🥥';
            position: absolute;
            font-size: 120px;
            top: -40px;
            left: -40px;
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
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .brand-panel h2 span {
            color: #D4A11E;
        }

        .brand-panel p {
            font-size: 0.95rem;
            line-height: 1.6;
            opacity: 0.85;
            margin-bottom: 30px;
        }

        /* Tips Box */
        .tips-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(4px);
            border-radius: 20px;
            padding: 24px;
            margin-top: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .tips-box h4 {
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tips-box h4 i {
            color: #D4A11E;
        }

        .tips-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .tips-list li {
            font-size: 0.8rem;
            opacity: 0.8;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tips-list li i {
            font-size: 12px;
            color: #D4A11E;
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
            margin-bottom: 8px;
        }

        .form-header p {
            color: #6B7280;
            font-size: 0.85rem;
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
            font-size: 16px;
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
            padding: 14px 16px 14px 46px;
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

        /* Password Strength */
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
        .btn-reset {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #1B5E3F, #0D3B26);
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 8px;
            margin-bottom: 24px;
        }

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(27, 94, 63, 0.3);
            background: linear-gradient(135deg, #1B6B48, #0D4B30);
        }

        .btn-reset:active {
            transform: translateY(0);
        }

        /* Back Link */
        .back-link {
            text-align: center;
        }

        .back-link a {
            color: #6B7280;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: color 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .back-link a:hover {
            color: #D4A11E;
        }

        /* Alert Messages */
        .alert-success {
            background: #E8F5ED;
            border-left: 4px solid #1B5E3F;
            padding: 14px 18px;
            border-radius: 14px;
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
            border-radius: 14px;
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

        /* Responsive */
        @media (max-width: 992px) {
            .reset-container {
                flex-direction: column;
                max-width: 550px;
            }

            .brand-panel {
                padding: 40px 32px;
                text-align: center;
            }

            .tips-box {
                text-align: left;
            }

            .form-panel {
                padding: 40px 32px;
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

            .tips-box {
                padding: 18px;
            }

            .tips-list li {
                font-size: 0.75rem;
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

    <div class="reset-container">
        <!-- Left Panel - Branding -->
        <div class="brand-panel">
            <div class="brand-logo">
                <div class="brand-icon">
                    <i class="fas fa-key"></i>
                </div>
                <h2>RSUP <span>Career</span></h2>
                <p>Atur ulang kata sandi Anda untuk kembali mengakses sistem rekrutmen</p>
            </div>

            <div class="tips-box">
                <h4><i class="fas fa-lightbulb"></i> Tips Membuat Password Kuat</h4>
                <ul class="tips-list">
                    <li><i class="fas fa-check-circle"></i> Minimal 8 karakter</li>
                    <li><i class="fas fa-check-circle"></i> Kombinasi huruf besar & kecil</li>
                    <li><i class="fas fa-check-circle"></i> Sertakan angka (0-9)</li>
                    <li><i class="fas fa-check-circle"></i> Gunakan simbol (!@#$%) untuk keamanan ekstra</li>
                    <li><i class="fas fa-check-circle"></i> Hindari informasi pribadi (nama, tgl lahir)</li>
                </ul>
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
                <h1>Buat Password Baru</h1>
                <p>Silakan buat kata sandi baru untuk akun Anda</p>
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

            <form method="POST" action="{{ route('password.store') }}" id="resetForm">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                            required autofocus class="form-control" placeholder="nama@email.com">
                    </div>
                </div>

                <!-- New Password -->
                <div class="form-group">
                    <label for="password">Kata Sandi Baru</label>
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

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock"></i>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                            class="form-control" placeholder="Ketik ulang kata sandi baru">
                        <i class="fas fa-eye-slash toggle-password" id="toggleConfirmPassword"
                            style="cursor: pointer;"></i>
                    </div>
                </div>

                <button type="submit" class="btn-reset">
                    <i class="fas fa-sync-alt"></i>
                    Reset Password
                </button>

                <div class="back-link">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke halaman login
                    </a>
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
        const form = document.getElementById('resetForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;

                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Konfirmasi kata sandi tidak cocok!');
                    return false;
                }

                if (password.length < 8) {
                    e.preventDefault();
                    alert('Kata sandi minimal 8 karakter!');
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
