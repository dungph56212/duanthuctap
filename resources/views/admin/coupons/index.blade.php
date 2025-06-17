@extends('admin.layouts.app')

@section('title', 'Quản lý mã giảm giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Quản lý mã giảm giá</h1>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Thêm mã giảm giá
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Tìm kiếm</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Mã, tên mã giảm giá...">
            </div>
            <div class="col-md-2">
                <label for="type" class="form-label">Loại giảm giá</label>
                <select class="form-select" id="type" name="type">
                    <option value="">Tất cả</option>
                    <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Vô hiệu hóa</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Hết hạn</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">Từ ngày</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">Đến ngày</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search"></i>
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-refresh"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Tổng mã giảm giá</h6>
                        <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tags fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Đang hoạt động</h6>
                        <h3 class="mb-0">{{ $stats['active'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Đã sử dụng</h6>
                        <h3 class="mb-0">{{ $stats['used'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Tiết kiệm</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_discount'] ?? 0) }}đ</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Coupons Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách mã giảm giá</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success btn-sm" onclick="exportCoupons()">
                <i class="fas fa-download me-1"></i>Xuất Excel
            </button>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Thao tác hàng loạt
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="bulkAction('activate')">Kích hoạt</a></li>
                    <li><a class="dropdown-item" href="#" onclick="bulkAction('deactivate')">Vô hiệu hóa</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')">Xóa</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($coupons->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="30">
                            <input type="checkbox" id="select-all" class="form-check-input">
                        </th>
                        <th>Mã giảm giá</th>
                        <th>Tên</th>
                        <th>Loại</th>
                        <th>Giá trị</th>
                        <th>Sử dụng</th>
                        <th>Thời hạn</th>
                        <th>Trạng thái</th>
                        <th width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $coupon)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input coupon-checkbox" value="{{ $coupon->id }}">
                        </td>
                        <td>
                            <code class="fs-6">{{ $coupon->code }}</code>
                            @if($coupon->is_public)
                                <span class="badge bg-info ms-1">Public</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <a href="{{ route('admin.coupons.show', $coupon) }}" class="text-decoration-none fw-medium">
                                    {{ $coupon->name }}
                                </a>
                                @if($coupon->description)
                                    <br><small class="text-muted">{{ Str::limit($coupon->description, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ $coupon->type === 'percentage' ? 'success' : 'primary' }}">
                                {{ $coupon->type === 'percentage' ? 'Phần trăm' : 'Cố định' }}
                            </span>
                        </td>
                        <td>
                            @if($coupon->type === 'percentage')
                                <strong>{{ $coupon->value }}%</strong>
                                @if($coupon->max_discount_amount)
                                    <br><small class="text-muted">Tối đa: {{ number_format($coupon->max_discount_amount) }}đ</small>
                                @endif
                            @else
                                <strong>{{ number_format($coupon->value) }}đ</strong>
                            @endif
                        </td>
                        <td>
                            <div>
                                <strong>{{ $coupon->used_count }}</strong> / 
                                {{ $coupon->usage_limit ?: '∞' }}
                            </div>
                            @if($coupon->usage_limit)
                                @php
                                    $percentage = ($coupon->used_count / $coupon->usage_limit) * 100;
                                @endphp
                                <div class="progress mt-1" style="height: 4px;">
                                    <div class="progress-bar {{ $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success') }}" 
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div>
                                @if($coupon->starts_at)
                                    <small class="text-muted">Từ: {{ $coupon->starts_at->format('d/m/Y') }}</small><br>
                                @endif
                                @if($coupon->expires_at)
                                    <small class="text-muted">Đến: {{ $coupon->expires_at->format('d/m/Y') }}</small>
                                    @if($coupon->expires_at->isPast())
                                        <br><span class="badge bg-danger">Hết hạn</span>
                                    @elseif($coupon->expires_at->diffInDays() <= 7)
                                        <br><span class="badge bg-warning">Sắp hết hạn</span>
                                    @endif
                                @else
                                    <span class="text-muted">Không giới hạn</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            @php
                                $isExpired = $coupon->expires_at && $coupon->expires_at->isPast();
                                $isExhausted = $coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit;
                                $isActive = $coupon->is_active && !$isExpired && !$isExhausted;
                            @endphp
                            
                            @if($isActive)
                                <span class="badge bg-success">Hoạt động</span>
                            @elseif($isExpired)
                                <span class="badge bg-danger">Hết hạn</span>
                            @elseif($isExhausted)
                                <span class="badge bg-warning">Hết lượt</span>
                            @else
                                <span class="badge bg-secondary">Vô hiệu hóa</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.coupons.show', $coupon) }}" class="btn btn-outline-info" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-outline-warning" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" 
                                      style="display: inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa mã giảm giá này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($coupons->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Hiển thị {{ $coupons->firstItem() }} - {{ $coupons->lastItem() }} 
                    trong tổng số {{ $coupons->total() }} mã giảm giá
                </div>
                {{ $coupons->links() }}
            </div>
        </div>
        @endif

        @else
        <div class="text-center py-5">
            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Chưa có mã giảm giá nào</h5>
            <p class="text-muted">Hãy tạo mã giảm giá đầu tiên để thu hút khách hàng.</p>
            <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tạo mã giảm giá đầu tiên
            </a>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
// Select all functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.coupon-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Bulk actions
function bulkAction(action) {
    const selectedIds = Array.from(document.querySelectorAll('.coupon-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedIds.length === 0) {
        alert('Vui lòng chọn ít nhất một mã giảm giá');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'activate':
            confirmMessage = `Bạn có chắc chắn muốn kích hoạt ${selectedIds.length} mã giảm giá đã chọn?`;
            break;
        case 'deactivate':
            confirmMessage = `Bạn có chắc chắn muốn vô hiệu hóa ${selectedIds.length} mã giảm giá đã chọn?`;
            break;
        case 'delete':
            confirmMessage = `Bạn có chắc chắn muốn xóa ${selectedIds.length} mã giảm giá đã chọn? Hành động này không thể hoàn tác.`;
            break;
    }
    
    if (confirm(confirmMessage)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.coupons.bulk-action") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);
        
        selectedIds.forEach(id => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Export function
function exportCoupons() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', '1');
    window.location.href = '{{ route("admin.coupons.index") }}?' + params.toString();
}
</script>
@endpush
