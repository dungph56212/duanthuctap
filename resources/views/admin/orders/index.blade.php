@extends('admin.layouts.app')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Quản lý đơn hàng</h1>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-success" onclick="exportOrders()">
            <i class="fas fa-download me-2"></i>Xuất báo cáo
        </button>
        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tạo đơn hàng
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Tìm kiếm</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" placeholder="Mã đơn, tên khách hàng...">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Tất cả</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Đã gửi</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Đã giao</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="payment_status" class="form-label">Thanh toán</label>
                <select class="form-select" id="payment_status" name="payment_status">
                    <option value="">Tất cả</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                    <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Thất bại</option>
                    <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
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
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
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
                        <h6 class="card-title">Tổng đơn hàng</h6>
                        <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
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
                        <h6 class="card-title">Chờ xử lý</h6>
                        <h3 class="mb-0">{{ $stats['pending'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x opacity-75"></i>
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
                        <h6 class="card-title">Đã giao</h6>
                        <h3 class="mb-0">{{ $stats['delivered'] ?? 0 }}</h3>
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
                        <h6 class="card-title">Doanh thu</h6>
                        <h3 class="mb-0">{{ number_format($stats['revenue'] ?? 0) }}đ</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Danh sách đơn hàng</h5>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Thao tác hàng loạt
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="bulkAction('confirm')">Xác nhận đơn hàng</a></li>
                    <li><a class="dropdown-item" href="#" onclick="bulkAction('ship')">Cập nhật đã gửi</a></li>
                    <li><a class="dropdown-item" href="#" onclick="bulkAction('deliver')">Cập nhật đã giao</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('cancel')">Hủy đơn hàng</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($orders->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="30">
                            <input type="checkbox" id="select-all" class="form-check-input">
                        </th>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input order-checkbox" value="{{ $order->id }}">
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-medium">
                                #{{ $order->order_number }}
                            </a>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $order->user->name ?? $order->customer_name }}</strong>
                                <br><small class="text-muted">{{ $order->user->email ?? $order->customer_email }}</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                {{ $order->orderItems->count() }} sản phẩm
                                <br><small class="text-muted">{{ $order->orderItems->sum('quantity') }} món</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ number_format($order->total_amount) }}đ</strong>
                                @if($order->discount_amount > 0)
                                <br><small class="text-success">-{{ number_format($order->discount_amount) }}đ</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-{{ 
                                $order->payment_status === 'paid' ? 'success' : 
                                ($order->payment_status === 'failed' ? 'danger' : 
                                ($order->payment_status === 'refunded' ? 'info' : 'warning')) 
                            }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                            <br><small class="text-muted">{{ ucfirst($order->payment_method) }}</small>
                        </td>
                        <td>
                            <select class="form-select form-select-sm" onchange="updateOrderStatus({{ $order->id }}, this.value)">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Đã gửi</option>
                                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Đã giao</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </td>
                        <td>
                            <div>
                                {{ $order->created_at->format('d/m/Y') }}
                                <br><small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-info" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-outline-warning" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="btn btn-outline-primary" onclick="printOrder({{ $order->id }})" title="In đơn hàng">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Hiển thị {{ $orders->firstItem() }} - {{ $orders->lastItem() }} 
                    trong tổng số {{ $orders->total() }} đơn hàng
                </div>
                {{ $orders->links() }}
            </div>
        </div>
        @endif

        @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">Chưa có đơn hàng nào</h5>
            <p class="text-muted">Đơn hàng sẽ xuất hiện ở đây khi khách hàng đặt mua.</p>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
// Select all functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.order-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Update order status
function updateOrderStatus(orderId, status) {
    if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng này?')) {
        fetch(`/admin/orders/${orderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Cập nhật trạng thái thành công!');
                // Optionally reload the page
                location.reload();
            } else {
                alert('Có lỗi xảy ra khi cập nhật trạng thái!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật trạng thái!');
        });
    }
}

// Bulk actions
function bulkAction(action) {
    const selectedIds = Array.from(document.querySelectorAll('.order-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedIds.length === 0) {
        alert('Vui lòng chọn ít nhất một đơn hàng');
        return;
    }
    
    let confirmMessage = '';
    switch(action) {
        case 'confirm':
            confirmMessage = `Bạn có chắc chắn muốn xác nhận ${selectedIds.length} đơn hàng đã chọn?`;
            break;
        case 'ship':
            confirmMessage = `Bạn có chắc chắn muốn cập nhật ${selectedIds.length} đơn hàng đã gửi?`;
            break;
        case 'deliver':
            confirmMessage = `Bạn có chắc chắn muốn cập nhật ${selectedIds.length} đơn hàng đã giao?`;
            break;
        case 'cancel':
            confirmMessage = `Bạn có chắc chắn muốn hủy ${selectedIds.length} đơn hàng đã chọn?`;
            break;
    }
    
    if (confirm(confirmMessage)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.orders.bulk-action") }}';
        
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

// Print order
function printOrder(orderId) {
    window.open(`/admin/orders/${orderId}/print`, '_blank');
}

// Export orders
function exportOrders() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', '1');
    window.location.href = '{{ route("admin.orders.index") }}?' + params.toString();
}

// Auto-refresh every 2 minutes for new orders
setInterval(function() {
    // Check for new orders via AJAX
    fetch('{{ route("admin.orders.check-new") }}')
        .then(response => response.json())
        .then(data => {
            if (data.newOrders > 0) {
                // Show notification or update counter
                console.log(`${data.newOrders} đơn hàng mới`);
            }
        });
}, 120000);
</script>
@endpush
