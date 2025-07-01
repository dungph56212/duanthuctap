@extends('client.layouts.app')

@section('title', 'Đơn hàng của tôi')
@section('description', 'Quản lý và theo dõi tất cả đơn hàng của bạn tại BookStore.')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Đơn hàng của tôi</h1>
                    <p class="text-muted mb-0">Quản lý và theo dõi đơn hàng</p>
                </div>
                <a href="{{ route('client.products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>

    <!-- Orders Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('client.orders.index') }}">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">Tất cả</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="from_date" class="form-label">Từ ngày</label>
                                <input type="date" class="form-control" id="from_date" name="from_date" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="to_date" class="form-label">Đến ngày</label>
                                <input type="date" class="form-control" id="to_date" name="to_date" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Lọc
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($orders->count() > 0)
        <!-- Orders List -->
        <div class="row">
            <div class="col-12">
                @foreach($orders as $order)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-0 py-3">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <h6 class="mb-1">
                                        <strong>Đơn hàng #{{ $order->order_number }}</strong>
                                    </h6>
                                    <small class="text-muted">Đặt ngày: {{ $order->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'confirmed' => 'info',
                                            'processing' => 'primary',
                                            'shipped' => 'secondary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusNames = [
                                            'pending' => 'Chờ xử lý',
                                            'confirmed' => 'Đã xác nhận',
                                            'processing' => 'Đang xử lý',
                                            'shipped' => 'Đang giao hàng',
                                            'delivered' => 'Đã giao hàng',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-6">
                                        {{ $statusNames[$order->status] ?? $order->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- Order Items -->
                            <div class="order-items mb-3">
                                @foreach($order->orderItems->take(3) as $item)
                                    <div class="d-flex align-items-center mb-2">                                        <img src="{{ $item->product && $item->product->images && count($item->product->images) > 0 ? Storage::url($item->product->images[0]) : 'https://via.placeholder.com/50x60' }}" 
                                             class="me-3 rounded" 
                                             width="40" height="50" 
                                             style="object-fit: cover;" 
                                             alt="{{ $item->product_name }}">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-0">{{ Str::limit($item->product_name, 50) }}</h6>
                                            <small class="text-muted">
                                                {{ number_format($item->product_price) }}đ × {{ $item->quantity }}
                                            </small>
                                        </div>
                                        <div class="text-end">
                                            <strong>{{ number_format($item->total_price) }}đ</strong>
                                        </div>
                                    </div>
                                @endforeach
                                  @if($order->orderItems->count() > 3)
                                    <div class="text-center">
                                        <small class="text-muted">
                                            Và {{ $order->orderItems->count() - 3 }} sản phẩm khác
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <!-- Order Info -->
                            <div class="row">
                                <div class="col-md-6">                                    <div class="mb-2">

                                    </div>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <div class="mb-2">
                                        <strong>Thanh toán:</strong>
                                        @php
                                            $paymentMethods = [
                                                'cod' => 'Thanh toán khi nhận hàng',
                                                'bank_transfer' => 'Chuyển khoản ngân hàng',
                                                'momo' => 'Ví điện tử MoMo'
                                            ];
                                            $paymentStatusColors = [
                                                'pending' => 'warning',
                                                'paid' => 'success',
                                                'failed' => 'danger'
                                            ];
                                            $paymentStatusNames = [
                                                'pending' => 'Chờ thanh toán',
                                                'paid' => 'Đã thanh toán',
                                                'failed' => 'Thanh toán thất bại'
                                            ];
                                        @endphp
                                        <div class="text-muted">{{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}</div>
                                        <span class="badge bg-{{ $paymentStatusColors[$order->payment_status] ?? 'secondary' }}">
                                            {{ $paymentStatusNames[$order->payment_status] ?? $order->payment_status }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <!-- Order Actions -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="text-danger mb-0">Tổng: {{ number_format($order->total_amount) }}đ</h5>
                                    @if($order->coupon_discount > 0)
                                        <small class="text-success">Đã giảm: {{ number_format($order->coupon_discount) }}đ</small>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('client.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>Xem chi tiết
                                    </a>
                                    
                                    @if($order->status == 'pending' && $order->payment_status == 'pending')
                                        <button class="btn btn-outline-danger btn-sm ms-2" 
                                                onclick="cancelOrder({{ $order->id }})">
                                            <i class="fas fa-times me-1"></i>Hủy đơn
                                        </button>
                                    @endif
                                    
                                    @if($order->status == 'delivered' && !$order->reviewed)
                                        <button class="btn btn-outline-warning btn-sm ms-2" 
                                                onclick="reviewOrder({{ $order->id }})">
                                            <i class="fas fa-star me-1"></i>Đánh giá
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-shopping-bag fa-4x text-muted"></i>
                        </div>
                        <h4 class="mb-3">Chưa có đơn hàng nào</h4>
                        <p class="text-muted mb-4">Bạn chưa có đơn hàng nào. Hãy khám phá các sản phẩm tuyệt vời của chúng tôi!</p>
                        <a href="{{ route('client.products.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-cart me-2"></i>Bắt đầu mua sắm
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        // Show loading
        const cancelBtn = document.querySelector(`[onclick="cancelOrder(${orderId})"]`);
        const originalText = cancelBtn.innerHTML;
        cancelBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Đang hủy...';
        cancelBtn.disabled = true;

        fetch(`/orders/${orderId}/cancel`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showToast(data.message, 'success');
                
                // Reload page after 1 second
                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        location.reload();
                    }
                }, 1000);
            } else {
                showToast(data.message || 'Có lỗi xảy ra, vui lòng thử lại.', 'error');
                // Restore button
                cancelBtn.innerHTML = originalText;
                cancelBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra, vui lòng thử lại.', 'error');
            // Restore button
            cancelBtn.innerHTML = originalText;
            cancelBtn.disabled = false;
        });
    }
}

// Toast notification function
function showToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type === 'info' ? 'primary' : type === 'error' ? 'danger' : 'success'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement, { delay: 5000 });
    toast.show();
    
    // Remove element after hide
    toastElement.addEventListener('hidden.bs.toast', function() {
        toastElement.remove();
    });
}

function reviewOrder(orderId) {
    // Here you can implement the review functionality
    // For example, redirect to review page or open a modal
    window.location.href = `/orders/${orderId}/review`;
}
</script>
@endpush
