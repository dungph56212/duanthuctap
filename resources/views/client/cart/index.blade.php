@extends('client.layouts.app')

@section('title', 'Giỏ hàng')
@section('description', 'Xem và quản lý các sản phẩm trong giỏ hàng của bạn tại BookStore.')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-3">Giỏ hàng của bạn</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item active">Giỏ hàng</li>
                </ol>
            </nav>
        </div>
    </div>

    @if($cartItems->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2 text-primary"></i>
                            Sản phẩm trong giỏ ({{ $cartItems->count() }} sản phẩm)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($cartItems as $item)
                            <div class="cart-item border-bottom p-3" data-item-id="{{ $item->id ?? $item->product->id }}">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                                    <div class="col-md-2 col-3 mb-2 mb-md-0">
                                        <img src="{{ $item->product->images && count($item->product->images) > 0 ? Storage::url($item->product->images[0]) : 'https://via.placeholder.com/80x100' }}" 
                                             class="img-fluid rounded" 
                                             alt="{{ $item->product->name }}"
                                             style="height: 80px; object-fit: cover;">
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="col-md-4 col-9 mb-2 mb-md-0">
                                        <h6 class="mb-1">
                                            <a href="{{ route('client.products.show', $item->product) }}" 
                                               class="text-decoration-none">
                                                {{ $item->product->name }}
                                            </a>
                                        </h6>
                                        @if($item->product->author)
                                            <small class="text-muted d-block">Tác giả: {{ $item->product->author }}</small>
                                        @endif
                                        @if($item->product->sku)
                                            <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                        @endif
                                    </div>
                                    
                                    <!-- Price -->
                                    <div class="col-md-2 col-4 mb-2 mb-md-0 text-center">
                                        @php
                                            $price = isset($item->price) ? $item->price : $item->product->price;
                                        @endphp
                                        <div class="price">
                                            <strong class="text-primary">{{ number_format($price) }}đ</strong>
                                        </div>
                                        @if($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                            <small class="text-muted text-decoration-line-through">
                                                {{ number_format($item->product->price) }}đ
                                            </small>
                                        @endif
                                    </div>
                                    
                                    <!-- Quantity -->
                                    <div class="col-md-2 col-4 mb-2 mb-md-0">
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="updateQuantity({{ $item->id ?? $item->product->id }}, {{ $item->quantity - 1 }})">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="form-control text-center" 
                                                   value="{{ $item->quantity }}" 
                                                   min="1" 
                                                   max="{{ $item->product->stock }}"
                                                   onchange="updateQuantity({{ $item->id ?? $item->product->id }}, this.value)">
                                            <button class="btn btn-outline-secondary" type="button" 
                                                    onclick="updateQuantity({{ $item->id ?? $item->product->id }}, {{ $item->quantity + 1 }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Còn {{ $item->product->stock }} cuốn</small>
                                    </div>
                                    
                                    <!-- Subtotal & Remove -->
                                    <div class="col-md-2 col-4 text-end">
                                        <div class="mb-2">
                                            <strong class="text-danger">
                                                {{ number_format($price * $item->quantity) }}đ
                                            </strong>
                                        </div>
                                        <button class="btn btn-outline-danger btn-sm" 
                                                onclick="removeFromCart({{ $item->id ?? $item->product->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Cart Actions -->
                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('client.products.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                    <button class="btn btn-outline-danger" onclick="clearCart()">
                        <i class="fas fa-trash me-2"></i>Xóa toàn bộ giỏ hàng
                    </button>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-calculator me-2 text-primary"></i>
                            Tổng kết đơn hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span id="cart-subtotal">{{ number_format($total) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <span>30,000đ</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Tổng cộng:</strong>
                            <strong class="text-danger h5" id="cart-total">
                                {{ number_format($total + 30000) }}đ
                            </strong>
                        </div>

                        <!-- Coupon -->
                        <div class="mb-3">
                            <label for="coupon" class="form-label">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="coupon" placeholder="Nhập mã giảm giá">
                                <button class="btn btn-outline-primary" type="button">Áp dụng</button>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        <div class="d-grid">
                            <a href="{{ route('client.checkout') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-credit-card me-2"></i>Thanh toán
                            </a>
                        </div>

                        <!-- Security Info -->
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i>
                                Giao dịch được bảo mật SSL
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-shopping-cart fa-5x text-muted"></i>
                        </div>
                        <h3 class="mb-3">Giỏ hàng trống</h3>
                        <p class="text-muted mb-4">
                            Bạn chưa có sản phẩm nào trong giỏ hàng. 
                            Hãy khám phá các cuốn sách tuyệt vời của chúng tôi!
                        </p>
                        <a href="{{ route('client.products.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Bắt đầu mua sắm
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
function updateQuantity(itemId, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(itemId);
        return;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: `/cart/${itemId}`,
        method: 'PUT',
        data: {
            quantity: newQuantity,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                location.reload(); // Reload để cập nhật tổng tiền
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: response.message
                });
            }
        },
        error: function(xhr) {
            let message = 'Có lỗi xảy ra, vui lòng thử lại!';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: message
            });
        }
    });
}

function removeFromCart(itemId) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: `/cart/${itemId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã xóa!',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                        updateCartCount();
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra, vui lòng thử lại!'
                    });
                }
            });
        }
    });
}

function clearCart() {
    Swal.fire({
        title: 'Xác nhận xóa toàn bộ',
        text: 'Bạn có chắc muốn xóa toàn bộ giỏ hàng?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Xóa toàn bộ',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/cart',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Đã xóa!',
                            text: 'Giỏ hàng đã được xóa toàn bộ',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                        updateCartCount();
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi!',
                        text: 'Có lỗi xảy ra, vui lòng thử lại!'
                    });
                }
            });
        }
    });
}
</script>
@endpush

@push('styles')
<style>
.cart-item:hover {
    background-color: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.cart-item .btn {
    border-radius: 6px;
}

.input-group-sm .form-control {
    max-width: 70px;
}

@media (max-width: 768px) {
    .cart-item .row > div {
        margin-bottom: 1rem;
    }
    
    .cart-item .input-group {
        max-width: 120px;
    }
}
</style>
@endpush
