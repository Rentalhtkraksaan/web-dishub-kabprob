<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Halaman Login Dinas Perhubungan Kabupaten Probolinggo</title>
    <meta name="description" content="Website Resmi Dinas Perhubungan Kabupaten Probolinggo" />
    <meta name="keywords" content="Dinas Perhubungan, dishub, probolinggo, transportasi" />
    <meta property="og:locale" content="id" />
    <meta property="og:type" content="Pemerintahan" />
    <meta property="og:title" content="Login Admin - Dinas Perhubungan Kabupaten Probolinggo" />
    <meta name="theme-color" content="#0f172a">

    <link rel="icon" type="image/png" href="{{ $settings['favicon'] ?? asset('images/logo_dishub.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== LEFT PANEL (Branding) ===== */
        .panel-left {
            width: 520px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            background: linear-gradient(145deg, #0ea5e9 0%, #0369a1 40%, #0c4a6e 100%);
        }
        .panel-left::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800&q=80') center/cover no-repeat;
            opacity: 0.12;
            z-index: 0;
        }
        .panel-left::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
            z-index: 0;
        }

        .brand-content {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem 2.5rem;
            text-align: center;
        }
        .brand-logo {
            width: 120px;
            height: 120px;
            object-fit: contain;
            margin-bottom: 1.8rem;
            filter: drop-shadow(0 8px 24px rgba(0,0,0,0.3));
            animation: floatLogo 4s ease-in-out infinite;
        }
        @keyframes floatLogo {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .brand-title {
            font-size: 1.55rem;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.35;
            margin-bottom: 0.6rem;
            text-shadow: 0 2px 12px rgba(0,0,0,0.25);
        }
        .brand-subtitle {
            font-size: 1.15rem;
            font-weight: 600;
            color: rgba(255,255,255,0.85);
            margin-bottom: 2rem;
        }
        .brand-divider {
            width: 60px;
            height: 3px;
            background: rgba(255,255,255,0.5);
            border-radius: 4px;
            margin: 0 auto 1.5rem;
        }
        .brand-desc {
            font-size: 0.82rem;
            color: rgba(255,255,255,0.7);
            line-height: 1.7;
            max-width: 380px;
        }
        .brand-badge {
            margin-top: 2rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 50px;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.9);
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        .brand-badge i {
            font-size: 0.65rem;
            color: #38bdf8;
        }

        /* ===== RIGHT PANEL (Login Form) ===== */
        .panel-right {
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            position: relative;
        }
        .panel-right::before {
            content: '';
            position: absolute;
            top: -120px;
            right: -120px;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: rgba(14, 165, 233, 0.06);
            z-index: 0;
        }

        .login-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 460px;
            padding: 2.5rem 2.8rem;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .login-header h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 0.4rem;
        }
        .login-header p {
            font-size: 0.82rem;
            color: #64748b;
            font-weight: 500;
        }

        /* Error alert */
        .alert-error {
            padding: 0.85rem 1rem;
            border-radius: 12px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            font-size: 0.78rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
            animation: shakeAlert 0.4s ease;
        }
        @keyframes shakeAlert {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            75% { transform: translateX(6px); }
        }
        .alert-error i { color: #ef4444; font-size: 0.9rem; }

        /* Success alert */
        .alert-success {
            padding: 0.85rem 1rem;
            border-radius: 12px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            font-size: 0.78rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1.5rem;
        }
        .alert-success i { color: #10b981; font-size: 0.9rem; }

        /* Form styles */
        .form-group {
            margin-bottom: 1.3rem;
        }
        .form-label {
            display: block;
            font-size: 0.78rem;
            font-weight: 700;
            color: #334155;
            margin-bottom: 0.45rem;
            letter-spacing: 0.3px;
        }
        .form-label .required-star {
            color: #ef4444;
            margin-left: 2px;
        }
        .input-wrapper {
            position: relative;
        }
        .input-wrapper .input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.85rem;
            transition: color 0.2s;
            pointer-events: none;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 0.9rem 0.75rem 2.6rem;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 500;
            color: #1e293b;
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            outline: none;
            transition: all 0.25s ease;
        }
        .form-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }
        .form-input:focus {
            border-color: #0ea5e9;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
        }
        .toggle-password {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 4px;
            transition: color 0.2s;
        }
        .toggle-password:hover { color: #0ea5e9; }

        /* Remember checkbox */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 0.3rem;
        }
        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #0ea5e9;
            cursor: pointer;
        }
        .remember-row label {
            font-size: 0.78rem;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
        }

        /* Buttons */
        .btn-row {
            display: flex;
            gap: 12px;
            margin-top: 1.5rem;
        }
        .btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0.82rem 1rem;
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.25s ease;
            text-decoration: none;
        }
        .btn-back {
            background: #f1f5f9;
            color: #475569;
            border: 1.5px solid #e2e8f0;
        }
        .btn-back:hover {
            background: #e2e8f0;
            color: #1e293b;
        }
        .btn-login {
            background: linear-gradient(135deg, #0ea5e9 0%, #0369a1 100%);
            color: #ffffff;
            box-shadow: 0 4px 16px rgba(14, 165, 233, 0.35);
        }
        .btn-login:hover {
            box-shadow: 0 6px 24px rgba(14, 165, 233, 0.5);
            transform: translateY(-2px);
        }

        /* Footer */
        .login-footer {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 1.5rem 2rem;
        }
        .login-footer a {
            font-size: 0.72rem;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .login-footer a:hover { color: #0ea5e9; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            body { flex-direction: column; }
            .panel-left {
                width: 100%;
                min-height: auto;
                padding: 0;
            }
            .brand-content {
                padding: 2rem 1.5rem;
            }
            .brand-logo { width: 80px; height: 80px; margin-bottom: 1rem; }
            .brand-title { font-size: 1.15rem; }
            .brand-subtitle { font-size: 0.95rem; }
            .brand-desc { display: none; }
            .brand-badge { display: none; }
            .panel-right { min-height: auto; }
            .login-card { padding: 2rem 1.5rem; }
        }
    </style>
</head>
<body>

    <!-- ===== LEFT PANEL: Branding ===== -->
    <div class="panel-left">
        <div class="brand-content">
            <img src="{{ $settings['logo_frontend'] ?? 'https://diskominfo.probolinggokab.go.id/backend/gambar/logo_frontend.png' }}"
                 alt="Logo DISHUB"
                 class="brand-logo">

            <h2 class="brand-title">Dinas Perhubungan</h2>
            <h3 class="brand-subtitle">Kabupaten Probolinggo</h3>

            <div class="brand-divider"></div>

            <p class="brand-desc">
                Website Resmi Dinas Perhubungan Kabupaten Probolinggo merupakan media informasi elektronik satu pintu
                meliputi penyimpanan dan pengelolaan informasi serta mekanisme penyampaian informasi dari penyelenggara
                pelayanan publik kepada masyarakat.
            </p>
        </div>
    </div>

    <!-- ===== RIGHT PANEL: Login Form ===== -->
    <div class="panel-right">
        <div class="login-card">

            <div class="login-header">
                <h1>Login Admin</h1>
                <p>Masuk ke panel administrasi Dinas Perhubungan</p>
            </div>

            @if(session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" autocomplete="off">
                @csrf

                <!-- Username / Email -->
                <div class="form-group">
                    <label class="form-label">Username / Email <span class="required-star">*</span></label>
                    <div class="input-wrapper">
                        <input type="text"
                               id="email"
                               name="email"
                               class="form-input"
                               placeholder="Masukkan username atau email..."
                               value="{{ old('email') }}"
                               required
                               autofocus>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label class="form-label">Password <span class="required-star">*</span></label>
                    <div class="input-wrapper">
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-input"
                               placeholder="••••••••"
                               required
                               autocomplete="off">
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility()" title="Tampilkan / Sembunyikan Password">
                            <i class="fas fa-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Captcha -->
                <div class="form-group mb-4">
                    <label class="form-label">Masukkan Kode Berikut <span class="required-star">*</span></label>
                    <div class="d-flex align-items-center mb-2" style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px;">
                        <div class="captcha-svg-holder" style="flex-shrink: 0;">
                            {!! $captchaSvg ?? '' !!}
                        </div>
                        <button type="button" class="btn-refresh-captcha" id="btn-refresh-captcha" title="Acak / Refresh Captcha" style="width: 46px; height: 46px; border-radius: 10px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #0284c7; font-size: 1.1rem; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s ease;">
                            <i class="fas fa-rotate" id="refresh-icon"></i>
                        </button>
                    </div>
                    <input type="text"
                           id="captcha"
                           name="captcha"
                           class="form-input text-center"
                           placeholder="Masukkan 5 Karakter Kode..."
                           required
                           maxlength="5"
                           style="text-align: center; letter-spacing: 6px; font-weight: 800; font-size: 1.05rem; text-transform: uppercase;"
                           autocomplete="off">
                </div>

                <!-- Remember Me & Forgot Password Row -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
                    <div class="remember-row" style="margin-bottom: 0;">
                        <input type="checkbox" name="remember" id="remember">
                        <label for="remember">Ingat Saya</label>
                    </div>
                    <button type="button" onclick="openForgotModal()" class="forgot-pass-btn" style="background: none; border: none; color: #0284c7; font-weight: 700; font-size: 0.78rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease;">
                        <i class="fas fa-key" style="font-size: 0.75rem; color: #0284c7;"></i>
                        <span>Lupa Password?</span>
                    </button>
                </div>

                <!-- Buttons -->
                <div class="btn-row">
                    <a href="{{ route('home') }}" class="btn btn-back">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-login">
                        <span>Masuk</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>

        <div class="login-footer">
            <a href="{{ route('home') }}">&copy; {{ date('Y') }} Dinas Perhubungan Kabupaten Probolinggo</a>
        </div>
    </div>

    <!-- Modal Lupa Password (2-Step Validation) -->
    <div id="forgotModal" style="display: {{ $errors->has('forgot_error') ? 'flex' : 'none' }}; position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(5px); align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: #ffffff; border-radius: 24px; max-width: 450px; width: 100%; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4); position: relative; border: 1px solid #cbd5e1;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.8rem;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 40px; height: 40px; border-radius: 12px; background: #e0f2fe; color: #0284c7; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: 800;">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1rem; font-weight: 800; color: #0f172a;">Lupa Password Akun</h3>
                        <p style="font-size: 0.72rem; color: #64748b;">Validasi 2-Langkah untuk memulihkan akses</p>
                    </div>
                </div>
                <button type="button" onclick="closeForgotModal()" style="background: #f1f5f9; border: none; width: 32px; height: 32px; border-radius: 50%; color: #64748b; cursor: pointer; font-size: 0.9rem;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            @if($errors->has('forgot_error'))
                <div class="alert-error" style="margin-bottom: 1.2rem;">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first('forgot_error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.forgot.verify') }}">
                @csrf
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 0.8rem 1rem; margin-bottom: 1.2rem; font-size: 0.75rem; color: #334155; line-height: 1.5;">
                    💡 <strong>Prosedur Pemulihan Akses:</strong>
                    <ol style="margin-left: 1.2rem; margin-top: 4px;">
                        <li><strong>Identitas:</strong> Username / Email Akun</li>
                        <li><strong>Validasi 1:</strong> No. WhatsApp terdaftar</li>
                        <li><strong>Validasi 2:</strong> Kode Referral Keamanan</li>
                    </ol>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label">Username / Email Akun <span class="required-star">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="identity" required class="form-input" placeholder="Contoh: mamad / mamad@gmail.com" value="{{ old('identity') }}">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label">No. WhatsApp (Validasi 1) <span class="required-star">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="whatsapp" required class="form-input" placeholder="Contoh: 081234567890" value="{{ old('whatsapp') }}">
                        <i class="fab fa-whatsapp input-icon" style="color: #25d366;"></i>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label class="form-label">Kode Referral (Validasi 2) <span class="required-star">*</span></label>
                    <div class="input-wrapper">
                        <input type="text" name="referral_code" required class="form-input" placeholder="Contoh: MAMAD2026" value="{{ old('referral_code') }}">
                        <i class="fas fa-key input-icon" style="color: #0284c7;"></i>
                    </div>
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeForgotModal()" class="btn btn-back" style="flex: 0 0 auto; padding: 0.65rem 1rem;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-login" style="padding: 0.65rem 1.2rem;">
                        <span>Verifikasi & Masuk Akun</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <script>
        function openForgotModal() {
            document.getElementById('forgotModal').style.display = 'flex';
        }
        function closeForgotModal() {
            document.getElementById('forgotModal').style.display = 'none';
        }

        function togglePasswordVisibility() {
            var passInput = document.getElementById('password');
            var eyeIcon = document.getElementById('eye-icon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }

        $(document).ready(function() {
            $('#btn-refresh-captcha').click(function() {
                var $icon = $('#refresh-icon');
                $icon.addClass('fa-spin');
                $.ajax({
                    type: 'GET',
                    url: '{{ route("captcha.refresh") }}',
                    success: function(data) {
                        if (data && data.captcha) {
                            $('.captcha-svg-holder').html(data.captcha);
                        }
                    },
                    complete: function() {
                        setTimeout(function() {
                            $icon.removeClass('fa-spin');
                        }, 300);
                    }
                });
            });
        });
    </script>
</body>
</html>
