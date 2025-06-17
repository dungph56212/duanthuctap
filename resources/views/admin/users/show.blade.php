@extends('admin.layouts.app')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Chi tiết người dùng: {{ $user->name }}</h1>
    <div class="btn-group">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Chỉnh sửa
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- User Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin cá nhân</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" 
                                 class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                 style="width: 150px; height: 150px;">
                                <span class="text-white" style="font-size: 3rem;">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        
                        <div>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'primary' }} fs-6">
                                {{ ucfirst($user->role) }}
                            </span>
                            @if($user->email_verified_at)
                                <span class="badge bg-success fs-6">Email đã xác thực</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Họ và tên:</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Số điện thoại:</th>
                                <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <th>Ngày sinh:</th>
                                <td>{{ $user->date_of_birth ? $user->date_of_birth->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                            </tr>
                            <tr>
                                <th>Giới tính:</th>
                                <td>
                                    @if($user->gender)
                                        {{ $user->gender === 'male' ? 'Nam' : ($user->gender === 'female' ? 'Nữ' : 'Khác') }}
                                    @else
                                        Chưa cập nhật
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-danger">Vô hiệu hóa</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Đăng nhập cuối:</th>
                                <td>
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->format('d/m/Y H:i') }}
                                        <small class="text-muted">({{ $user->last_login_at->diffForHumans() }})</small>
                                    @else
                                        Chưa đăng nhập
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        @if($user->orders && $user->orders->count() > 0)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Đơn hàng gần đây ({{ $user->orders->count() }} đơn)</h5>
                <a href="{{ route('admin.orders.index', ['user' => $user->id]) }}" class="btn btn-outline-primary btn-sm">
                    Xem tất cả
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Sản phẩm</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->orders->take(5) as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $order->orderItems->count() }} sản phẩm</td>
                                <td>{{ number_format($order->total_amount) }}đ</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $order->status === 'delivered' ? 'success' : 
                                        ($order->status === 'cancelled' ? 'danger' : 'warning') 
                                    }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <!-- Addresses -->
        @if($user->addresses && $user->addresses->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Địa chỉ ({{ $user->addresses->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($user->addresses as $address)
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3">
                            @if($address->is_default)
                                <span class="badge bg-primary mb-2">Mặc định</span>
                            @endif
                            <address class="mb-0">
                                <strong>{{ $address->name }}</strong><br>
                                {{ $address->address }}<br>
                                {{ $address->city }}, {{ $address->state }}<br>
                                {{ $address->zip }}, {{ $address->country }}<br>
                                @if($address->phone)
                                    <strong>SĐT:</strong> {{ $address->phone }}
                                @endif
                            </address>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Quick Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thống kê tổng quan</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <h4 class="text-primary">{{ $user->orders_count ?? 0 }}</h4>
                        <small class="text-muted">Đơn hàng</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ number_format($user->total_spent ?? 0) }}đ</h4>
                        <small class="text-muted">Tổng chi tiêu</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <h4 class="text-info">{{ $user->reviews_count ?? 0 }}</h4>
                        <small class="text-muted">Đánh giá</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $user->wishlists_count ?? 0 }}</h4>
                        <small class="text-muted">Sản phẩm yêu thích</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thông tin tài khoản</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>ID:</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Ngày đăng ký:</th>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật cuối:</th>
                        <td>{{ $user->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Xác thực email:</th>
                        <td>
                            @if($user->email_verified_at)
                                <span class="text-success">
                                    <i class="fas fa-check-circle"></i> 
                                    {{ $user->email_verified_at->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-danger">
                                    <i class="fas fa-times-circle"></i> Chưa xác thực
                                </span>
                            @endif
                        </td>
                    </tr>
                    @if($user->last_login_at)
                    <tr>
                        <th>Đăng nhập cuối:</th>
                        <td>{{ $user->last_login_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>IP cuối:</th>
                        <td>{{ $user->last_login_ip ?? 'N/A' }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thao tác nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin
                    </a>
                    
                    @if(!$user->email_verified_at)
                    <button class="btn btn-success" onclick="verifyEmail()">
                        <i class="fas fa-check me-2"></i>Xác thực email
                    </button>
                    @endif
                    
                    @if($user->is_active)
                    <button class="btn btn-warning" onclick="toggleStatus('deactivate')">
                        <i class="fas fa-ban me-2"></i>Vô hiệu hóa tài khoản
                    </button>
                    @else
                    <button class="btn btn-success" onclick="toggleStatus('activate')">
                        <i class="fas fa-check me-2"></i>Kích hoạt tài khoản
                    </button>
                    @endif
                    
                    <button class="btn btn-info" onclick="resetPassword()">
                        <i class="fas fa-key me-2"></i>Đặt lại mật khẩu
                    </button>
                    
                    <a href="{{ route('admin.orders.create', ['user' => $user->id]) }}" class="btn btn-outline-primary">
                        <i class="fas fa-plus me-2"></i>Tạo đơn hàng
                    </a>
                    
                    <hr>
                    
                    @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Xóa tài khoản
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Hoạt động gần đây</h6>
            </div>
            <div class="card-body">
                <div class="timeline-sm">
                    @if($user->last_login_at)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <small class="text-muted">{{ $user->last_login_at->format('d/m/Y H:i') }}</small>
                            <div>Đăng nhập vào hệ thống</div>
                        </div>
                    </div>
                    @endif
                    
                    @if($user->orders && $user->orders->count() > 0)
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <small class="text-muted">{{ $user->orders->first()->created_at->format('d/m/Y H:i') }}</small>
                            <div>Đặt đơn hàng #{{ $user->orders->first()->order_number }}</div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="timeline-item">
                        <div class="timeline-marker bg-info"></div>
                        <div class="timeline-content">
                            <small class="text-muted">{{ $user->created_at->format('d/m/Y H:i') }}</small>
                            <div>Đăng ký tài khoản</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.timeline-sm {
    position: relative;
    padding-left: 20px;
}

.timeline-sm::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 15px;
}

.timeline-marker {
    position: absolute;
    left: -24px;
    top: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    border: 3px solid white;
}

.timeline-content {
    font-size: 14px;
}
</style>
@endpush

@push('scripts')
<script>
// Toggle user status
function toggleStatus(action) {
    const message = action === 'activate' ? 'kích hoạt' : 'vô hiệu hóa';
    if (confirm(`Bạn có chắc chắn muốn ${message} tài khoản này?`)) {
        fetch(`{{ route('admin.users.toggle-status', $user) }}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ action: action })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra khi cập nhật trạng thái!');
            }
        });
    }
}

// Verify email
function verifyEmail() {
    if (confirm('Bạn có chắc chắn muốn xác thực email cho người dùng này?')) {
        fetch(`{{ route('admin.users.verify-email', $user) }}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra khi xác thực email!');
            }
        });
    }
}

// Reset password
function resetPassword() {
    if (confirm('Bạn có chắc chắn muốn đặt lại mật khẩu cho người dùng này? Mật khẩu mới sẽ được gửi qua email.')) {
        fetch(`{{ route('admin.users.reset-password', $user) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Đã gửi email đặt lại mật khẩu thành công!');
            } else {
                alert('Có lỗi xảy ra khi đặt lại mật khẩu!');
            }
        });
    }
}
</script>
@endpush
