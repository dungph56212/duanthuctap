@extends('client.layouts.app')

@section('title', 'Đăng nhập')
@section('description', 'Đăng nhập vào tài khoản BookStore để trải nghiệm mua sắm tuyệt vời.')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <!-- Logo & Title -->
                    <div class="text-center mb-4">
                        <h1 class="h3 mb-3">Đăng nhập</h1>
                        <p class="text-muted">Chào mừng bạn quay trở lại BookStore!</p>
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
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" id="login-form">
                        @csrf
                        
                        <div class="mb-3">
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
                                       placeholder="Nhập email của bạn"
                                       required 
                                       autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
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
                        </div>

                        <div class="mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="remember" 
                                               name="remember" 
                                               {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            Ghi nhớ đăng nhập
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{ route('client.password.request') }}" class="text-decoration-none">
                                        Quên mật khẩu?
                                    </a>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                        </button>

                        <div class="text-center">
                            <span class="text-muted">Chưa có tài khoản? </span>
                            <a href="{{ route('client.register') }}" class="text-decoration-none fw-bold">
                                Đăng ký ngay
                            </a>
                        </div>
                    </form>

                    <!-- Divider -->
                    <div class="divider my-4">
                        <span class="divider-text">hoặc</span>
                    </div>

                    <!-- Social Login (Optional) -->
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-danger" disabled>
                            <i class="fab fa-google me-2"></i>Đăng nhập bằng Google
                            <small class="d-block text-muted">(Sắp ra mắt)</small>
                        </button>
                        <button type="button" class="btn btn-outline-primary" disabled>
                            <i class="fab fa-facebook-f me-2"></i>Đăng nhập bằng Facebook
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

            <!-- Admin Login Link -->
            <div class="text-center mt-3">
                <small class="text-muted">
                    Bạn là quản trị viên? 
                    <a href="{{ route('admin.login') }}" class="text-decoration-none">
                        Đăng nhập admin
                    </a>
                </small>
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

    // Form validation
    const form = document.getElementById('login-form');
    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        if (!email || !password) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin');
            return false;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert('Email không hợp lệ');
            return false;
        }

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang đăng nhập...';
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
