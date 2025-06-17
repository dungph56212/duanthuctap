@extends('client.layouts.app')

@section('title', 'Thông tin cá nhân')
@section('description', 'Quản lý thông tin cá nhân và cài đặt tài khoản BookStore của bạn.')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">                    <div class="avatar mb-3">
                        <img src="{{ userAvatarUrl($user->avatar, 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=007bff&color=fff&size=200') }}" 
                             class="rounded-circle" 
                             width="100" height="100" 
                             alt="{{ $user->name }}"
                             id="avatar-preview">
                    </div>
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="text-muted mb-0">{{ $user->email }}</p>
                    @if($user->email_verified_at)
                        <span class="badge bg-success mt-2">
                            <i class="fas fa-check-circle me-1"></i>Đã xác thực
                        </span>
                    @else
                        <span class="badge bg-warning mt-2">
                            <i class="fas fa-exclamation-circle me-1"></i>Chưa xác thực
                        </span>
                    @endif
                </div>
            </div>

            <!-- Navigation Menu -->
            <div class="list-group list-group-flush mt-3">
                <a href="{{ route('client.profile') }}" class="list-group-item list-group-item-action active">
                    <i class="fas fa-user me-3"></i>Thông tin cá nhân
                </a>
                <a href="{{ route('client.orders.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-shopping-bag me-3"></i>Đơn hàng của tôi
                </a>
                <a href="{{ route('client.wishlist') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-heart me-3"></i>Sản phẩm yêu thích
                </a>                <a href="{{ route('client.addresses.index') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-map-marker-alt me-3"></i>Sổ địa chỉ
                </a>
                <a href="{{ route('client.reviews') }}" class="list-group-item list-group-item-action">
                    <i class="fas fa-star me-3"></i>Đánh giá của tôi
                </a>
                <a href="{{ route('client.logout') }}" class="list-group-item list-group-item-action text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-3"></i>Đăng xuất
                </a>
            </div>

            <form id="logout-form" action="{{ route('client.logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Profile Update Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2 text-primary"></i>
                        Cập nhật thông tin cá nhân
                    </h4>
                </div>
                <div class="card-body">
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
                    @endif                    <form method="POST" action="{{ route('client.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Avatar Upload -->
                        <div class="mb-4">
                            <label class="form-label">Ảnh đại diện</label>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <img src="{{ userAvatarUrl($user->avatar, 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=007bff&color=fff&size=200') }}" 
                                         class="rounded-circle" 
                                         width="60" height="60" 
                                         alt="{{ $user->name }}"
                                         id="avatar-preview-form">
                                </div>
                                <div class="flex-grow-1">
                                    <input type="file" 
                                           class="form-control @error('avatar') is-invalid @enderror" 
                                           id="avatar" 
                                           name="avatar" 
                                           accept="image/*"
                                           onchange="previewAvatar(this)">
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Chọn file ảnh (JPEG, PNG, JPG, GIF) tối đa 2MB</small>
                                </div>
                                @if($user->avatar)
                                <div class="ms-2">
                                    <a href="{{ route('client.profile.removeAvatar') }}" 
                                       class="btn btn-outline-danger btn-sm"
                                       onclick="return confirm('Bạn có chắc muốn xóa ảnh đại diện?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">Ngày sinh</label>
                                <input type="date" 
                                       class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" 
                                       name="birth_date" 
                                       value="{{ old('birth_date', $user->birth_date) }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Giới tính</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">Chọn giới tính</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Change Password Section -->
                        <h5 class="mb-3">Đổi mật khẩu</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" 
                                       class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" 
                                       name="current_password" 
                                       placeholder="Nhập mật khẩu hiện tại">
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Nhập mật khẩu mới">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Để trống nếu không muốn đổi</small>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                                <input type="password" 
                                       class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="Nhập lại mật khẩu mới">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    Tài khoản được tạo: {{ $user->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Statistics -->
            <div class="row mt-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="text-primary mb-2">
                                <i class="fas fa-shopping-bag fa-2x"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->orders()->count() }}</h4>
                            <small class="text-muted">Đơn hàng</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="text-success mb-2">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->orders()->where('status', 'delivered')->count() }}</h4>
                            <small class="text-muted">Đã giao</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="text-danger mb-2">
                                <i class="fas fa-heart fa-2x"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->wishlists()->count() }}</h4>
                            <small class="text-muted">Yêu thích</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-0 shadow-sm text-center">
                        <div class="card-body">
                            <div class="text-warning mb-2">
                                <i class="fas fa-star fa-2x"></i>
                            </div>
                            <h4 class="mb-1">{{ $user->reviews()->count() }}</h4>
                            <small class="text-muted">Đánh giá</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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

    // Password change validation
    const currentPassword = document.getElementById('current_password');
    const newPassword = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');

    function validatePasswordChange() {
        if (newPassword.value || confirmPassword.value) {
            currentPassword.required = true;
            if (newPassword.value !== confirmPassword.value) {
                confirmPassword.setCustomValidity('Mật khẩu xác nhận không khớp');
            } else {
                confirmPassword.setCustomValidity('');
            }
        } else {
            currentPassword.required = false;
            confirmPassword.setCustomValidity('');
        }
    }    newPassword.addEventListener('input', validatePasswordChange);
    confirmPassword.addEventListener('input', validatePasswordChange);
});

// Preview avatar function
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
            document.getElementById('avatar-preview-form').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
