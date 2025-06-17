@extends('client.layouts.app')

@section('title', 'Đăng ký')
@section('description', 'Tạo tài khoản BookStore để trải nghiệm mua sắm sách tuyệt vời với nhiều ưu đãi hấp dẫn.')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <!-- Logo & Title -->
                    <div class="text-center mb-4">
                        <h1 class="h3 mb-3">Đăng ký tài khoản</h1>
                        <p class="text-muted">Tham gia cộng đồng yêu sách BookStore!</p>
                    </div>

                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Có lỗi xảy ra:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Register Form -->
                    <form method="POST" action="{{ route('client.register') }}" id="register-form">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Nhập họ và tên"
                                           required 
                                           autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="Nhập email"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="Nhập số điện thoại (tùy chọn)">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Nhập mật khẩu"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                                        <i class="fas fa-eye" id="password-icon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">Ít nhất 6 ký tự</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Nhập lại mật khẩu"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" id="toggle-password-confirm">
                                        <i class="fas fa-eye" id="password-confirm-icon"></i>
                                    </button>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Password Strength Indicator -->
                        <div class="mb-3">
                            <div class="password-strength">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" id="password-strength-bar" role="progressbar" style="width: 0%"></div>
                                </div>
                                <small class="text-muted" id="password-strength-text">Độ mạnh mật khẩu</small>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror" 
                                       type="checkbox" 
                                       id="terms" 
                                       name="terms" 
                                       {{ old('terms') ? 'checked' : '' }}
                                       required>
                                <label class="form-check-label" for="terms">
                                    Tôi đồng ý với 
                                    <a href="{{ route('client.terms') }}" target="_blank" class="text-decoration-none">
                                        Điều khoản sử dụng
                                    </a> 
                                    và 
                                    <a href="{{ route('client.privacy') }}" target="_blank" class="text-decoration-none">
                                        Chính sách bảo mật
                                    </a>
                                    <span class="text-danger">*</span>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Newsletter Subscription -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="newsletter" 
                                       name="newsletter" 
                                       {{ old('newsletter') ? 'checked' : '' }}>
                                <label class="form-check-label" for="newsletter">
                                    Đăng ký nhận tin khuyến mãi và sách mới qua email
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-user-plus me-2"></i>Đăng ký
                        </button>

                        <div class="text-center">
                            <span class="text-muted">Đã có tài khoản? </span>
                            <a href="{{ route('client.login') }}" class="text-decoration-none fw-bold">
                                Đăng nhập ngay
                            </a>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="divider my-4">
                        <span class="divider-text">hoặc</span>
                    </div>

                    <!-- Social Register (Optional) -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-danger" disabled>
                            <i class="fab fa-google me-2"></i>Đăng ký bằng Google
                            <small class="d-block text-muted">(Sắp ra mắt)</small>
                        </button>
                        <button type="button" class="btn btn-outline-primary" disabled>
                            <i class="fab fa-facebook-f me-2"></i>Đăng ký bằng Facebook
                            <small class="d-block text-muted">(Sắp ra mắt)</small>
                        </button>
                    </div>

                    <!-- Back to Home -->
                    <div class="text-center mt-4">
                        <a href="{{ route('client.home') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-2"></i>Quay về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.divider {
    position: relative;
    text-align: center;
    border-top: 1px solid #e9ecef;
}

.divider-text {
    background: white;
    padding: 0 1rem;
    color: #6c757d;
    font-size: 0.9rem;
    position: absolute;
    top: -0.6rem;
    left: 50%;
    transform: translateX(-50%);
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #e9ecef;
}

.btn:disabled {
    cursor: not-allowed;
}

.card {
    border-radius: 15px;
}

.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
}

.password-strength {
    margin-top: 0.5rem;
}

.progress {
    border-radius: 10px;
}

.progress-bar {
    transition: all 0.3s ease;
    border-radius: 10px;
}

@media (max-width: 576px) {
    .card-body {
        padding: 2rem !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const passwordIcon = document.getElementById('password-icon');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        if (type === 'password') {
            passwordIcon.classList.remove('fa-eye-slash');
            passwordIcon.classList.add('fa-eye');
        } else {
            passwordIcon.classList.remove('fa-eye');
            passwordIcon.classList.add('fa-eye-slash');
        }
    });

    // Toggle password confirmation visibility
    const togglePasswordConfirm = document.getElementById('toggle-password-confirm');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const passwordConfirmIcon = document.getElementById('password-confirm-icon');

    togglePasswordConfirm.addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        
        if (type === 'password') {
            passwordConfirmIcon.classList.remove('fa-eye-slash');
            passwordConfirmIcon.classList.add('fa-eye');
        } else {
            passwordConfirmIcon.classList.remove('fa-eye');
            passwordConfirmIcon.classList.add('fa-eye-slash');
        }
    });

    // Password strength checker
    const passwordStrengthBar = document.getElementById('password-strength-bar');
    const passwordStrengthText = document.getElementById('password-strength-text');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);
        
        passwordStrengthBar.style.width = strength.score + '%';
        passwordStrengthBar.className = 'progress-bar ' + strength.class;
        passwordStrengthText.textContent = strength.text;
    });

    function checkPasswordStrength(password) {
        let score = 0;
        let feedback = '';
        
        if (password.length >= 6) score += 20;
        if (password.length >= 8) score += 10;
        if (/[a-z]/.test(password)) score += 15;
        if (/[A-Z]/.test(password)) score += 15;
        if (/[0-9]/.test(password)) score += 15;
        if (/[^A-Za-z0-9]/.test(password)) score += 25;
        
        if (score < 30) {
            feedback = 'Mật khẩu yếu';
            return { score: score, class: 'bg-danger', text: feedback };
        } else if (score < 60) {
            feedback = 'Mật khẩu trung bình';
            return { score: score, class: 'bg-warning', text: feedback };
        } else if (score < 80) {
            feedback = 'Mật khẩu tốt';
            return { score: score, class: 'bg-info', text: feedback };
        } else {
            feedback = 'Mật khẩu mạnh';
            return { score: score, class: 'bg-success', text: feedback };
        }
    }

    // Password confirmation check
    passwordConfirmInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;
        
        if (password !== confirmPassword && confirmPassword.length > 0) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (confirmPassword.length > 0) {
            this.classList.add('is-valid');
            this.classList.remove('is-invalid');
        } else {
            this.classList.remove('is-invalid', 'is-valid');
        }
    });

    // Form validation
    const form = document.getElementById('register-form');
    form.addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;
        const terms = document.getElementById('terms').checked;

        if (!name || !email || !password || !passwordConfirm || !terms) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc');
            return false;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Email không hợp lệ');
            return false;
        }

        // Password validation
        if (password.length < 6) {
            e.preventDefault();
            alert('Mật khẩu phải có ít nhất 6 ký tự');
            return false;
        }

        if (password !== passwordConfirm) {
            e.preventDefault();
            alert('Mật khẩu xác nhận không khớp');
            return false;
        }

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng ký...';
        submitBtn.disabled = true;
    });

    // Auto dismiss alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 5000);
    });
});
</script>
@endpush
