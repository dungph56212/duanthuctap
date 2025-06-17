@extends('client.layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->order_number)
@section('description', 'Xem chi tiết đơn hàng #' . $order->order_number . ' tại BookStore.')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('client.orders.index') }}">Đơn hàng của tôi</a></li>
            <li class="breadcrumb-item active">Đơn hàng #{{ $order->order_number }}</li>
        </ol>
    </nav>

    <!-- Order Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h1 class="h3 mb-1">Đơn hàng #{{ $order->order_number }}</h1>
                            <p class="text-muted mb-0">
                                Đặt ngày: {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
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
                            <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }} fs-5 mb-2">
                                {{ $statusNames[$order->status] ?? $order->status }}
                            </span>
                            <div>
                                @if($order->status == 'pending' && $order->payment_status == 'pending')
                                    <button class="btn btn-outline-danger btn-sm" onclick="cancelOrder({{ $order->id }})">
                                        <i class="fas fa-times me-1"></i>Hủy đơn hàng
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Progress -->
    @if($order->status != 'cancelled')
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4">Trạng thái đơn hàng</h5>
                        <div class="order-progress">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="step {{ in_array($order->status, ['pending', 'confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                    <div class="step-icon">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div class="step-title">Đặt hàng</div>
                                    <div class="step-time">{{ $order->created_at->format('d/m H:i') }}</div>
                                </div>
                                <div class="step-line {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}"></div>
                                <div class="step {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                    <div class="step-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div class="step-title">Xác nhận</div>
                                    <div class="step-time">
                                        @if(in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']))
                                            {{ $order->updated_at->format('d/m H:i') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="step-line {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : '' }}"></div>
                                <div class="step {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                    <div class="step-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="step-title">Đóng gói</div>
                                    <div class="step-time">
                                        @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                            {{ $order->updated_at->format('d/m H:i') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="step-line {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : '' }}"></div>
                                <div class="step {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : '' }}">
                                    <div class="step-icon">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                    <div class="step-title">Giao hàng</div>
                                    <div class="step-time">
                                        @if(in_array($order->status, ['shipped', 'delivered']))
                                            {{ $order->updated_at->format('d/m H:i') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="step-line {{ $order->status == 'delivered' ? 'completed' : '' }}"></div>
                                <div class="step {{ $order->status == 'delivered' ? 'completed' : '' }}">
                                    <div class="step-icon">
                                        <i class="fas fa-home"></i>
                                    </div>
                                    <div class="step-title">Nhận hàng</div>
                                    <div class="step-time">
                                        @if($order->status == 'delivered')
                                            {{ $order->updated_at->format('d/m H:i') }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Order Items -->
        <div class="col-lg-8 mb-4">            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-bag me-2 text-primary"></i>
                        Sản phẩm trong đơn hàng ({{ $order->orderItems->count() }} sản phẩm)
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                        <div class="order-item d-flex align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <img src="{{ $item->product && $item->product->images && count($item->product->images) > 0 ? Storage::url($item->product->images[0]) : 'https://via.placeholder.com/80x100' }}" 
                                 class="me-3 rounded" 
                                 width="60" height="80" 
                                 style="object-fit: cover;" 
                                 alt="{{ $item->product_name }}">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    @if($item->product)
                                        <a href="{{ route('client.products.show', $item->product) }}" 
                                           class="text-decoration-none">
                                            {{ $item->product_name }}
                                        </a>
                                    @else
                                        {{ $item->product_name }}
                                    @endif
                                </h6>
                                @if($item->product && $item->product->author)
                                    <p class="text-muted mb-1 small">Tác giả: {{ $item->product->author }}</p>
                                @endif
                                @if($item->product_sku && $item->product_sku !== 'N/A')
                                    <p class="text-muted mb-1 small">SKU: {{ $item->product_sku }}</p>
                                @endif
                                <div class="d-flex align-items-center">
                                    <span class="text-muted me-3">{{ number_format($item->product_price) }}đ × {{ $item->quantity }}</span>
                                    <strong class="text-primary">{{ number_format($item->total_price) }}đ</strong>
                                </div>
                            </div>
                            @if($order->status == 'delivered')
                                <div class="text-end">
                                    <button class="btn btn-outline-warning btn-sm" 
                                            onclick="reviewProduct({{ $item->product->id }})">
                                        <i class="fas fa-star me-1"></i>Đánh giá
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Order Summary & Info -->
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2 text-primary"></i>
                        Tổng kết đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($order->subtotal) }}đ</span>
                    </div>                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <span>{{ number_format($order->shipping_amount) }}đ</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($order->discount_amount) }}đ</span>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <strong class="text-danger h5">{{ number_format($order->total_amount) }}đ</strong>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-shipping-fast me-2 text-primary"></i>
                        Thông tin giao hàng
                    </h5>
                </div>
                <div class="card-body">                    <div class="mb-3">
                        <strong>Người nhận:</strong>
                        <div>{{ is_array($order->shipping_address) ? ($order->shipping_address['name'] ?? 'N/A') : 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Số điện thoại:</strong>
                        <div>{{ is_array($order->shipping_address) ? ($order->shipping_address['phone'] ?? 'N/A') : 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong>
                        <div>{{ is_array($order->shipping_address) ? ($order->shipping_address['email'] ?? 'N/A') : 'N/A' }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Địa chỉ:</strong>
                        <div>{{ is_array($order->shipping_address) ? ($order->shipping_address['address'] ?? 'N/A') : 'N/A' }}</div>
                    </div>
                    @if($order->notes)
                        <div>
                            <strong>Ghi chú:</strong>
                            <div class="text-muted">{{ $order->notes }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2 text-primary"></i>
                        Thông tin thanh toán
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Phương thức:</strong>
                        @php
                            $paymentMethods = [
                                'cod' => 'Thanh toán khi nhận hàng',
                                'bank_transfer' => 'Chuyển khoản ngân hàng',
                                'momo' => 'Ví điện tử MoMo'
                            ];
                        @endphp
                        <div>{{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}</div>
                    </div>
                    <div>
                        <strong>Trạng thái:</strong>
                        @php
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
                        <div>
                            <span class="badge bg-{{ $paymentStatusColors[$order->payment_status] ?? 'secondary' }}">
                                {{ $paymentStatusNames[$order->payment_status] ?? $order->payment_status }}
                            </span>
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
.order-progress {
    margin: 2rem 0;
}

.order-progress .step {
    text-align: center;
    position: relative;
}

.order-progress .step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

.order-progress .step.completed .step-icon {
    background: #28a745;
    color: white;
}

.order-progress .step-title {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.order-progress .step.completed .step-title {
    color: #28a745;
    font-weight: 600;
}

.order-progress .step-time {
    font-size: 0.75rem;
    color: #6c757d;
}

.order-progress .step.completed .step-time {
    color: #28a745;
}

.order-progress .step-line {
    height: 2px;
    background: #e9ecef;
    flex: 1;
    align-self: center;
    margin: 0 1rem;
    transition: all 0.3s ease;
}

.order-progress .step-line.completed {
    background: #28a745;
}

.order-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
    margin: -0.5rem;
    padding: 1rem !important;
}

@media (max-width: 768px) {
    .order-progress .step-line {
        margin: 0 0.5rem;
    }
    
    .order-progress .step-title {
        font-size: 0.8rem;
    }
    
    .order-progress .step-time {
        font-size: 0.7rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        fetch(`/orders/${orderId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại.');
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra, vui lòng thử lại.');
        });
    }
}

function reviewProduct(productId) {
    // Implement product review functionality
    window.location.href = `/products/${productId}/review`;
}
</script>
@endpush
