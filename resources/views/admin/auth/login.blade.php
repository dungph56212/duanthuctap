<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Admin - BookStore</title>
    
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

        .login-container {
            max-width: 450px;
            width: 100%;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .login-header {
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

        .login-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #718096;
            font-size: 1rem;
            font-weight: 400;
        }

        .login-body {
            padding: 0 40px 40px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
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
            padding: 15px 20px;
            font-size: 1rem;
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
            font-size: 1.1rem;
        }

        .form-check {
            margin: 25px 0;
        }

        .form-check-input {
            width: 1.2rem;
            height: 1.2rem;
            border-radius: 6px;
            border: 2px solid #e2e8f0;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-label {
            font-size: 0.9rem;
            color: #4a5568;
            margin-left: 8px;
        }

        .btn-login {
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

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 25px;
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

        .demo-info {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.2);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            margin-top: 25px;
        }

        .demo-info .demo-title {
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .demo-info .demo-text {
            color: #6b7280;
            font-size: 0.85rem;
            font-family: 'Courier New', monospace;
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
            font-size: 1.1rem;
            z-index: 10;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        @media (max-width: 576px) {
            .login-header {
                padding: 30px 30px 20px;
            }
            
            .login-body {
                padding: 0 30px 30px;
            }
            
            .login-title {
                font-size: 1.5rem;
            }
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
            font-size: 0.85rem;
            color: #dc2626;
            margin-top: 5px;
            font-weight: 500;
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

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-book-open"></i>
                </div>
                <h1 class="login-title">BookStore Admin</h1>
                <p class="login-subtitle">Đăng nhập để quản lý hệ thống</p>
            </div>
            
            <div class="login-body">
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

                <form action="{{ route('admin.login') }}" method="POST">
                    @csrf
                    
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
                                   placeholder="Nhập email của bạn"
                                   required>
                            <span class="input-icon">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        @error('email')
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
                                   placeholder="Nhập mật khẩu của bạn"
                                   required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Ghi nhớ đăng nhập
                        </label>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                    </button>
                </form>

                <div class="demo-info">
                    <div class="demo-title">
                        <i class="fas fa-info-circle me-2"></i>Thông tin demo
                    </div>
                    <div class="demo-text">
                        Email: admin@example.com<br>
                        Password: password
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
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

        // Auto focus on email input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });

        // Add loading state to login button
        document.querySelector('form').addEventListener('submit', function() {
            const submitBtn = document.querySelector('.btn-login');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng nhập...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>
