<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký Admin - BookStore</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .register-container {
            max-width: 500px;
            width: 100%;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .register-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .register-header {
            padding: 40px 40px 30px;
            text-align: center;
            position: relative;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .logo i {
            font-size: 2.5rem;
            color: white;
        }

        .register-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .register-subtitle {
            color: #718096;
            font-size: 1rem;
            font-weight: 400;
        }

        .register-body {
            padding: 0 40px 40px;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-control {
            background: #f7fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .form-control:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1rem;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            font-size: 1rem;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .form-check {
            margin: 20px 0;
        }

        .form-check-input {
            width: 1.1rem;
            height: 1.1rem;
            border-radius: 6px;
            border: 2px solid #e2e8f0;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-label {
            font-size: 0.85rem;
            color: #4a5568;
            margin-left: 8px;
            line-height: 1.4;
        }

        .btn-register {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-danger {
            background: rgba(248, 113, 113, 0.1);
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }

        .login-link {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .login-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .floating-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite linear;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 20%;
            right: 10%;
            animation-delay: 5s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            bottom: 30%;
            left: 20%;
            animation-delay: 10s;
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            bottom: 10%;
            right: 20%;
            animation-delay: 15s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.7;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 1;
            }
        }

        .is-invalid {
            border-color: #dc2626 !important;
            background: rgba(248, 113, 113, 0.05) !important;
        }

        .invalid-feedback {
            display: block;
            font-size: 0.8rem;
            color: #dc2626;
            margin-top: 5px;
            font-weight: 500;
        }

        .password-strength {
            margin-top: 8px;
            font-size: 0.8rem;
        }

        .strength-indicator {
            display: flex;
            gap: 3px;
            margin-top: 5px;
        }

        .strength-bar {
            height: 3px;
            flex: 1;
            background: #e2e8f0;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-bar.active {
            background: #22c55e;
        }

        .strength-bar.weak {
            background: #ef4444;
        }

        .strength-bar.medium {
            background: #f59e0b;
        }

        .strength-bar.strong {
            background: #22c55e;
        }

        @media (max-width: 576px) {
            .register-header {
                padding: 30px 30px 20px;
            }
            
            .register-body {
                padding: 0 30px 30px;
            }
            
            .register-title {
                font-size: 1.5rem;
            }

            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="register-title">Đăng ký Admin</h1>
                <p class="register-subtitle">Tạo tài khoản quản trị viên mới</p>
            </div>
            
            <div class="register-body">
                @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
                @endif

                @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
                @endif

                <form action="{{ route('admin.register') }}" method="POST">
                    @csrf
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first_name" class="form-label">
                                <i class="fas fa-user me-2"></i>Họ
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" 
                                       name="first_name" 
                                       value="{{ old('first_name') }}" 
                                       placeholder="Nhập họ"
                                       required>
                                <span class="input-icon">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            @error('first_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="last_name" class="form-label">
                                <i class="fas fa-user me-2"></i>Tên
                            </label>
                            <div class="input-group">
                                <input type="text" 
                                       class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" 
                                       name="last_name" 
                                       value="{{ old('last_name') }}" 
                                       placeholder="Nhập tên"
                                       required>
                                <span class="input-icon">
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            @error('last_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email
                        </label>
                        <div class="input-group">
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   placeholder="Nhập email"
                                   required>
                            <span class="input-icon">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone me-2"></i>Số điện thoại
                        </label>
                        <div class="input-group">
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}" 
                                   placeholder="Nhập số điện thoại">
                            <span class="input-icon">
                                <i class="fas fa-phone"></i>
                            </span>
                        </div>
                        @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Mật khẩu
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Nhập mật khẩu"
                                   required
                                   oninput="checkPasswordStrength()">
                            <button type="button" class="password-toggle" onclick="togglePassword('password', 'password-icon')">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        <div class="password-strength" id="password-strength">
                            <div class="strength-indicator">
                                <div class="strength-bar" id="bar1"></div>
                                <div class="strength-bar" id="bar2"></div>
                                <div class="strength-bar" id="bar3"></div>
                                <div class="strength-bar" id="bar4"></div>
                            </div>
                            <div class="strength-text" id="strength-text"></div>
                        </div>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-2"></i>Xác nhận mật khẩu
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="password_confirmation" 
                                   name="password_confirmation" 
                                   placeholder="Nhập lại mật khẩu"
                                   required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', 'confirm-icon')">
                                <i class="fas fa-eye" id="confirm-icon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label" for="terms">
                            Tôi đồng ý với <a href="#" target="_blank">Điều khoản sử dụng</a> và <a href="#" target="_blank">Chính sách bảo mật</a>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="fas fa-user-plus me-2"></i>Đăng ký
                    </button>
                </form>

                <div class="login-link">
                    <p class="text-muted mb-0">
                        Đã có tài khoản? <a href="{{ route('admin.login') }}">Đăng nhập ngay</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthText = document.getElementById('strength-text');
            const bars = [
                document.getElementById('bar1'),
                document.getElementById('bar2'),
                document.getElementById('bar3'),
                document.getElementById('bar4')
            ];

            // Reset bars
            bars.forEach(bar => {
                bar.classList.remove('active', 'weak', 'medium', 'strong');
            });

            if (password.length === 0) {
                strengthText.textContent = '';
                return;
            }

            let strength = 0;
            
            // Check password criteria
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            // Update strength indicator
            if (strength <= 2) {
                bars[0].classList.add('active', 'weak');
                strengthText.textContent = 'Yếu';
                strengthText.style.color = '#ef4444';
            } else if (strength <= 3) {
                bars[0].classList.add('active', 'medium');
                bars[1].classList.add('active', 'medium');
                strengthText.textContent = 'Trung bình';
                strengthText.style.color = '#f59e0b';
            } else if (strength <= 4) {
                bars[0].classList.add('active', 'strong');
                bars[1].classList.add('active', 'strong');
                bars[2].classList.add('active', 'strong');
                strengthText.textContent = 'Mạnh';
                strengthText.style.color = '#22c55e';
            } else {
                bars.forEach(bar => bar.classList.add('active', 'strong'));
                strengthText.textContent = 'Rất mạnh';
                strengthText.style.color = '#22c55e';
            }
        }

        // Auto focus on first name input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('first_name').focus();
        });

        // Add loading state to register button
        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = document.querySelector('.btn-register');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng ký...';
            submitBtn.disabled = true;
        });

        // Real-time password confirmation validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            
            if (confirmation && password !== confirmation) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    </script>
</body>
</html>
