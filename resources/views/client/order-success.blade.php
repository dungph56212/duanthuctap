@extends('client.layouts.app')

@section('title', 'Đặt hàng thành công')
@section('description', 'Cảm ơn bạn đã đặt hàng tại BookStore. Đơn hàng của bạn đã được tiếp nhận và sẽ được xử lý sớm nhất.')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <div class="success-icon mx-auto mb-3">
                            <i class="fas fa-check-circle fa-5x text-success"></i>
                        </div>
                        <h1 class="h2 text-success mb-3">Đặt hàng thành công!</h1>
                        <p class="lead text-muted mb-4">
                            Cảm ơn bạn đã tin tương và mua sắm tại BookStore. 
                            Đơn hàng của bạn đã được tiếp nhận và sẽ được xử lý trong thời gian sớm nhất.
                        </p>
                    </div>

                    <!-- Order Number -->
                    <div class="alert alert-info d-inline-flex align-items-center mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Mã đơn hàng của bạn: #{{ $order->order_number }}</strong>
                    </div>

                    <!-- Quick Actions -->
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('client.orders.show', $order) }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-eye me-2"></i>Xem chi tiết đơn hàng
                        </a>
                        <a href="{{ route('client.products.index') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt me-2 text-primary"></i>
                        Thông tin đơn hàng
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Thông tin giao hàng</h6>                            <div class="bg-light p-3 rounded">
                                @php
                                    $shippingAddr = is_array($order->shipping_address) ? $order->shipping_address : json_decode($order->shipping_address, true);
                                @endphp
                                <div><strong>{{ $shippingAddr['name'] ?? 'N/A' }}</strong></div>
                                <div>{{ $shippingAddr['phone'] ?? 'N/A' }}</div>
                                <div>{{ $shippingAddr['email'] ?? 'N/A' }}</div>
                                <div class="text-muted">
                                    {{ $shippingAddr['address'] ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-2">Phương thức thanh toán</h6>
                            <div class="bg-light p-3 rounded">
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
                                <div><strong>{{ $paymentMethods[$order->payment_method] ?? $order->payment_method }}</strong></div>
                                <span class="badge bg-{{ $paymentStatusColors[$order->payment_status] ?? 'secondary' }}">
                                    {{ $paymentStatusNames[$order->payment_status] ?? $order->payment_status }}
                                </span>
                            </div>
                        </div>
                    </div>                    <!-- Order Items -->
                    <h6 class="text-muted mb-3 mt-4">Sản phẩm đã đặt ({{ $order->orderItems->count() }} sản phẩm)</h6>
                    <div class="order-items">
                        @foreach($order->orderItems as $item)
                            <div class="d-flex align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <img src="{{ $item->product && $item->product->images && count($item->product->images) > 0 ? Storage::url($item->product->images[0]) : 'https://via.placeholder.com/50x60' }}" 
                                     class="me-3 rounded" 
                                     width="40" height="50" 
                                     style="object-fit: cover;" 
                                     alt="{{ $item->product_name }}">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ Str::limit($item->product_name, 40) }}</h6>
                                    <small class="text-muted">
                                        {{ number_format($item->product_price) }}đ × {{ $item->quantity }}
                                    </small>
                                </div>
                                <div class="text-end">
                                    <strong>{{ number_format($item->total_price) }}đ</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Total -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính:</span>
                                    <span>{{ number_format($order->subtotal) }}đ</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Phí vận chuyển:</span>
                                    <span>{{ number_format($order->shipping_amount) }}đ</span>
                                </div>                                @if($order->discount_amount > 0)
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
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-info-circle me-2 text-primary"></i>
                        Những bước tiếp theo
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-envelope fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6>Xác nhận qua email</h6>
                                    <p class="text-muted mb-0">
                                        Chúng tôi đã gửi email xác nhận đơn hàng đến <strong>{{ $shippingAddr['email'] ?? 'N/A' }}</strong>. 
                                        Vui lòng kiểm tra hộp thư của bạn.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-phone fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6>Liên hệ xác nhận</h6>
                                    <p class="text-muted mb-0">
                                        Nhân viên của chúng tôi sẽ liên hệ với bạn qua số điện thoại 
                                        <strong>{{ $shippingAddr['phone'] ?? 'N/A' }}</strong> để xác nhận đơn hàng.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-box fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6>Chuẩn bị hàng</h6>
                                    <p class="text-muted mb-0">
                                        Sau khi xác nhận, chúng tôi sẽ chuẩn bị và đóng gói sản phẩm của bạn một cách cẩn thận.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-truck fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h6>Giao hàng</h6>
                                    <p class="text-muted mb-0">
                                        Hàng sẽ được giao đến địa chỉ của bạn trong vòng 1-3 ngày làm việc 
                                        (tùy theo khu vực).
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($order->payment_method == 'bank_transfer')
                        <div class="alert alert-warning mt-4">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>
                                Hướng dẫn chuyển khoản
                            </h6>
                            <p class="mb-2">Bạn đã chọn phương thức chuyển khoản. Vui lòng chuyển khoản theo thông tin sau:</p>
                            <div class="bg-white p-3 rounded">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Ngân hàng:</strong> Vietcombank<br>
                                        <strong>Số tài khoản:</strong> 1234567890<br>
                                        <strong>Chủ tài khoản:</strong> BOOKSTORE
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Số tiền:</strong> {{ number_format($order->total_amount) }}đ<br>
                                        <strong>Nội dung:</strong> {{ $order->order_number }}<br>
                                        <strong>Chi nhánh:</strong> TP. Hồ Chí Minh
                                    </div>
                                </div>
                            </div>
                            <p class="mb-0 mt-2">
                                <small>Lưu ý: Vui lòng ghi đúng nội dung chuyển khoản để chúng tôi có thể xác nhận thanh toán nhanh chóng.</small>
                            </p>
                        </div>
                    @endif

                    <!-- Support Info -->
                    <div class="mt-4 pt-3 border-top text-center">
                        <h6>Cần hỗ trợ?</h6>
                        <p class="text-muted mb-3">
                            Nếu bạn có bất kỳ thắc mắc nào về đơn hàng, vui lòng liên hệ với chúng tôi:
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="tel:+84901234567" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-phone me-1"></i>(+84) 90 123 4567
                            </a>
                            <a href="mailto:support@bookstore.com" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-1"></i>support@bookstore.com
                            </a>
                            <a href="{{ route('client.contact') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-comment me-1"></i>Gửi tin nhắn
                            </a>
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
.success-icon {
    width: 100px;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(40, 167, 69, 0.1);
    border-radius: 50%;
}

.order-items {
    max-height: 300px;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .d-flex.gap-3 {
        flex-direction: column;
    }
    
    .d-flex.gap-3 .btn {
        margin-bottom: 0.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto redirect to order detail after 10 seconds
setTimeout(function() {
    const countdown = document.createElement('div');
    countdown.className = 'text-center mt-3';
    countdown.innerHTML = '<small class="text-muted">Tự động chuyển đến trang chi tiết đơn hàng sau <span id="countdown">10</span> giây...</small>';
    document.querySelector('.card-body').appendChild(countdown);
    
    let timeLeft = 10;
    const timer = setInterval(function() {
        timeLeft--;
        document.getElementById('countdown').textContent = timeLeft;
        
        if (timeLeft <= 0) {
            clearInterval(timer);
            window.location.href = '{{ route("client.orders.show", $order) }}';
        }
    }, 1000);
}, 5000);
</script>
@endpush
