@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Chi tiết đơn hàng #{{ $order->order_number }}</h1>
    <div class="btn-group">
        <button class="btn btn-outline-primary" onclick="printOrder()">
            <i class="fas fa-print me-2"></i>In đơn hàng
        </button>
        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Chỉnh sửa
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Sản phẩm đặt mua ({{ $order->orderItems->count() }} sản phẩm)</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product && $item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product_name }}" 
                                                 class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                 style="width: 60px; height: 60px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            @if($item->product)
                                                <a href="{{ route('admin.products.show', $item->product) }}" class="text-decoration-none fw-medium">
                                                    {{ $item->product_name }}
                                                </a>
                                            @else
                                                <span class="fw-medium">{{ $item->product_name }}</span>
                                                <br><small class="text-danger">Sản phẩm đã bị xóa</small>
                                            @endif
                                            @if($item->product_sku)
                                                <br><small class="text-muted">SKU: {{ $item->product_sku }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($item->unit_price) }}đ</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="fw-medium">{{ number_format($item->total_price) }}đ</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Lịch sử đơn hàng</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @php
                        $timeline = [
                            ['status' => 'pending', 'title' => 'Đơn hàng được tạo', 'time' => $order->created_at],
                            ['status' => 'confirmed', 'title' => 'Đơn hàng được xác nhận', 'time' => $order->confirmed_at ?? null],
                            ['status' => 'processing', 'title' => 'Đang chuẩn bị hàng', 'time' => $order->processing_at ?? null],
                            ['status' => 'shipped', 'title' => 'Đã gửi hàng', 'time' => $order->shipped_at ?? null],
                            ['status' => 'delivered', 'title' => 'Đã giao hàng', 'time' => $order->delivered_at ?? null],
                        ];
                        
                        if ($order->status === 'cancelled') {
                            $timeline[] = ['status' => 'cancelled', 'title' => 'Đơn hàng đã hủy', 'time' => $order->cancelled_at ?? $order->updated_at];
                        }
                    @endphp
                    
                    @foreach($timeline as $index => $step)
                        @php
                            $isActive = false;
                            $isCompleted = false;
                            
                            if ($order->status === 'cancelled' && $step['status'] === 'cancelled') {
                                $isActive = true;
                            } elseif ($order->status !== 'cancelled') {
                                $statusOrder = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                                $currentIndex = array_search($order->status, $statusOrder);
                                $stepIndex = array_search($step['status'], $statusOrder);
                                
                                if ($stepIndex !== false && $currentIndex !== false) {
                                    $isCompleted = $stepIndex < $currentIndex;
                                    $isActive = $stepIndex === $currentIndex;
                                }
                            }
                        @endphp
                        
                        <div class="timeline-item {{ $isCompleted ? 'completed' : ($isActive ? 'active' : '') }}">
                            <div class="timeline-marker">
                                <i class="fas {{ $isCompleted ? 'fa-check' : ($isActive ? 'fa-clock' : 'fa-circle') }}"></i>
                            </div>
                            <div class="timeline-content">
                                <h6 class="mb-1">{{ $step['title'] }}</h6>
                                @if($step['time'])
                                    <small class="text-muted">{{ $step['time']->format('d/m/Y H:i') }}</small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($order->notes)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Ghi chú đơn hàng</h5>
            </div>
            <div class="card-body">
                <p class="mb-0">{{ $order->notes }}</p>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Order Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Tóm tắt đơn hàng</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td>Tạm tính:</td>
                        <td class="text-end">{{ number_format($order->subtotal) }}đ</td>
                    </tr>
                    @if($order->shipping_fee > 0)
                    <tr>
                        <td>Phí vận chuyển:</td>
                        <td class="text-end">{{ number_format($order->shipping_fee) }}đ</td>
                    </tr>
                    @endif
                    @if($order->tax_amount > 0)
                    <tr>
                        <td>Thuế:</td>
                        <td class="text-end">{{ number_format($order->tax_amount) }}đ</td>
                    </tr>
                    @endif
                    @if($order->discount_amount > 0)
                    <tr class="text-success">
                        <td>Giảm giá:</td>
                        <td class="text-end">-{{ number_format($order->discount_amount) }}đ</td>
                    </tr>
                    @endif
                    @if($order->coupon_code)
                    <tr class="text-info">
                        <td>Mã giảm giá:</td>
                        <td class="text-end">{{ $order->coupon_code }}</td>
                    </tr>
                    @endif
                    <tr class="table-active">
                        <td><strong>Tổng cộng:</strong></td>
                        <td class="text-end"><strong class="text-primary">{{ number_format($order->total_amount) }}đ</strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin thanh toán</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td>Phương thức:</td>
                        <td class="text-end">
                            <span class="badge bg-info">{{ ucfirst($order->payment_method) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>Trạng thái:</td>
                        <td class="text-end">
                            <span class="badge bg-{{ 
                                $order->payment_status === 'paid' ? 'success' : 
                                ($order->payment_status === 'failed' ? 'danger' : 
                                ($order->payment_status === 'refunded' ? 'info' : 'warning')) 
                            }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                    </tr>
                    @if($order->paid_at)
                    <tr>
                        <td>Thanh toán lúc:</td>
                        <td class="text-end">{{ $order->paid_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($order->transaction_id)
                    <tr>
                        <td>Mã giao dịch:</td>
                        <td class="text-end"><code>{{ $order->transaction_id }}</code></td>
                    </tr>
                    @endif
                </table>
                
                @if($order->payment_status === 'pending')
                <hr>
                <div class="d-grid gap-2">
                    <button class="btn btn-success btn-sm" onclick="markAsPaid()">
                        <i class="fas fa-check me-1"></i>Đánh dấu đã thanh toán
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin khách hàng</h5>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 50px; height: 50px;">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $order->user->name ?? $order->customer_name }}</h6>
                        <small class="text-muted">{{ $order->user->email ?? $order->customer_email }}</small>
                    </div>
                </div>

                @if($order->user)
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <h6 class="text-primary">{{ $order->user->orders->count() }}</h6>
                        <small class="text-muted">Đơn hàng</small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-success">{{ number_format($order->user->orders->sum('total_amount')) }}đ</h6>
                        <small class="text-muted">Tổng chi tiêu</small>
                    </div>
                </div>
                <hr>
                <a href="{{ route('admin.users.show', $order->user) }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-user me-1"></i>Xem thông tin khách hàng
                </a>
                @endif
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Địa chỉ giao hàng</h5>
            </div>
            <div class="card-body">
                <address class="mb-0">
                    <strong>{{ $order->shipping_name }}</strong><br>
                    {{ $order->shipping_address }}<br>
                    @if($order->shipping_address_2)
                        {{ $order->shipping_address_2 }}<br>
                    @endif
                    {{ $order->shipping_city }}, {{ $order->shipping_state }}<br>
                    {{ $order->shipping_zip }}, {{ $order->shipping_country }}<br>
                    @if($order->shipping_phone)
                        <strong>SĐT:</strong> {{ $order->shipping_phone }}
                    @endif
                </address>
                
                @if($order->tracking_number)
                <hr>
                <div>
                    <strong>Mã vận đơn:</strong> <code>{{ $order->tracking_number }}</code>
                    <br><a href="#" class="btn btn-outline-info btn-sm mt-2">
                        <i class="fas fa-truck me-1"></i>Theo dõi đơn hàng
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Actions -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Thao tác đơn hàng</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($order->status === 'pending')
                    <button class="btn btn-success" onclick="updateStatus('confirmed')">
                        <i class="fas fa-check me-2"></i>Xác nhận đơn hàng
                    </button>
                    @endif
                    
                    @if(in_array($order->status, ['confirmed', 'processing']))
                    <button class="btn btn-info" onclick="updateStatus('shipped')">
                        <i class="fas fa-shipping-fast me-2"></i>Cập nhật đã gửi
                    </button>
                    @endif
                    
                    @if($order->status === 'shipped')
                    <button class="btn btn-primary" onclick="updateStatus('delivered')">
                        <i class="fas fa-check-circle me-2"></i>Đánh dấu đã giao
                    </button>
                    @endif
                    
                    <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa đơn hàng
                    </a>
                    
                    <button class="btn btn-outline-primary" onclick="printOrder()">
                        <i class="fas fa-print me-2"></i>In đơn hàng
                    </button>
                    
                    @if(!in_array($order->status, ['delivered', 'cancelled']))
                    <hr>
                    <button class="btn btn-danger" onclick="cancelOrder()">
                        <i class="fas fa-times me-2"></i>Hủy đơn hàng
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding: 0;
}

.timeline-item {
    position: relative;
    padding-left: 50px;
    padding-bottom: 20px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 20px;
    top: 30px;
    height: calc(100% - 10px);
    width: 2px;
    background-color: #dee2e6;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.timeline-item.active .timeline-marker {
    background-color: #0d6efd;
    color: white;
}

.timeline-item.completed .timeline-marker {
    background-color: #198754;
    color: white;
}

.timeline-item.completed::before {
    background-color: #198754;
}
</style>
@endpush

@push('scripts')
<script>
// Update order status
function updateStatus(status) {
    if (confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng?')) {
        fetch(`{{ route('admin.orders.update-status', $order) }}`, {
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
                location.reload();
            } else {
                alert('Có lỗi xảy ra khi cập nhật trạng thái!');
            }
        });
    }
}

// Mark as paid
function markAsPaid() {
    if (confirm('Bạn có chắc chắn đơn hàng này đã được thanh toán?')) {
        fetch(`{{ route('admin.orders.mark-paid', $order) }}`, {
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
                alert('Có lỗi xảy ra khi cập nhật trạng thái thanh toán!');
            }
        });
    }
}

// Cancel order
function cancelOrder() {
    const reason = prompt('Lý do hủy đơn hàng:');
    if (reason && confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        fetch(`{{ route('admin.orders.cancel', $order) }}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra khi hủy đơn hàng!');
            }
        });
    }
}

// Print order
function printOrder() {
    window.open(`{{ route('admin.orders.print', $order) }}`, '_blank');
}
</script>
@endpush
