<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - RSUP Career | PT Riau Sakti United Plantation</title>
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
        .forgot-container {
            display: flex;
            max-width: 1000px;
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
            content: '❓';
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

        /* Process Box */
        .process-box {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(4px);
            border-radius: 20px;
            padding: 24px;
            margin-top: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .process-box h4 {
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .process-box h4 i {
            color: #D4A11E;
        }

        .process-list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .process-list li {
            font-size: 0.8rem;
            opacity: 0.85;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .step-number {
            width: 24px;
            height: 24px;
            background: rgba(212, 161, 30, 0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
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
            line-height: 1.6;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 28px;
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

        /* Button */
        .btn-submit {
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
            margin-bottom: 24px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(27, 94, 63, 0.3);
            background: linear-gradient(135deg, #1B6B48, #0D4B30);
        }

        .btn-submit:active {
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
            .forgot-container {
                flex-direction: column;
                max-width: 550px;
            }

            .brand-panel {
                padding: 40px 32px;
                text-align: center;
            }

            .process-box {
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

            .process-box {
                padding: 18px;
            }

            .process-list li {
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

    <div class="forgot-container">
        <!-- Left Panel - Branding -->
        <div class="brand-panel">
            <div class="brand-logo">
                <div class="brand-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <h2>RSUP <span>Career</span></h2>
                <p>Lupa kata sandi? Kami akan membantu Anda mengatur ulang password dengan cepat dan aman.</p>
            </div>

            <div class="process-box">
                <h4><i class="fas fa-sync-alt"></i> Proses Reset Password</h4>
                <ul class="process-list">
                    <li><span class="step-number">1</span> Masukkan alamat email Anda</li>
                    <li><span class="step-number">2</span> Kami kirim link reset password ke email</li>
                    <li><span class="step-number">3</span> Klik link dan buat password baru</li>
                    <li><span class="step-number">4</span> Login dengan password baru Anda</li>
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
                <h1>Lupa Password?</h1>
                <p>Masukkan alamat email yang terdaftar, kami akan mengirimkan link untuk mereset password Anda.</p>
            </div>

            <!-- Success Message -->
            @if (session('status'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <!-- Error Message -->
            @if ($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
                @csrf

                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                            autofocus class="form-control" placeholder="nama@perusahaan.com">
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i>
                    Kirim Link Reset Password
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
        // Form validation
        const form = document.getElementById('forgotForm');
        const emailInput = document.getElementById('email');

        if (form) {
            form.addEventListener('submit', function(e) {
                const email = emailInput.value.trim();

                if (!email) {
                    e.preventDefault();
                    alert('Alamat email harus diisi!');
                    return false;
                }

                if (!/\S+@\S+\.\S+/.test(email)) {
                    e.preventDefault();
                    alert('Format email tidak valid! Contoh: nama@domain.com');
                    return false;
                }
            });
        }

        // Focus effects on inputs
        const inputs = document.querySelectorAll('.form-control');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                const icon = this.parentElement.querySelector('i');
                if (icon) icon.style.color = '#D4A11E';
            });
            input.addEventListener('blur', function() {
                const icon = this.parentElement.querySelector('i');
                if (icon) icon.style.color = '#9CA3AF';
            });
        });
    </script>
</body>

</html>
