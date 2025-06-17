@extends('client.layouts.app')

@section('title', 'Trang chủ')
@section('description', 'Cửa hàng sách trực tuyến hàng đầu Việt Nam với hàng ngàn đầu sách hay trong mọi lĩnh vực')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">Khám phá thế giới tri thức cùng BookStore</h1>
                <p class="lead mb-4">Hàng ngàn đầu sách hay đang chờ bạn khám phá. Từ văn học đến khoa học, từ kinh tế đến nghệ thuật - tất cả đều có tại BookStore.</p>
                <div class="d-flex flex-column flex-md-row gap-3">
                    <a href="{{ route('client.products.index') }}" class="btn btn-secondary btn-lg px-4">
                        <i class="fas fa-book me-2"></i>Khám phá ngay
                    </a>
                    <a href="#featured-products" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-star me-2"></i>Sách nổi bật
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-center">
                    <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                         alt="Books" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Danh mục sản phẩm</h2>
            <p class="text-muted">Tìm sách theo chủ đề yêu thích của bạn</p>
        </div>
        
        <div class="row g-4">
            @foreach($categories->take(6) as $category)
            <div class="col-lg-2 col-md-4 col-6">
                <a href="{{ route('client.products.category', $category) }}" class="text-decoration-none">
                    <div class="category-card h-100">
                        <div class="category-icon">
                            @switch($category->name)
                                @case('Văn học')
                                    <i class="fas fa-feather-alt"></i>
                                    @break
                                @case('Kinh tế')
                                    <i class="fas fa-chart-line"></i>
                                    @break
                                @case('Khoa học')
                                    <i class="fas fa-flask"></i>
                                    @break
                                @case('Lịch sử')
                                    <i class="fas fa-landmark"></i>
                                    @break
                                @case('Thiếu nhi')
                                    <i class="fas fa-child"></i>
                                    @break
                                @default
                                    <i class="fas fa-book"></i>
                            @endswitch
                        </div>
                        <h6 class="mb-2">{{ $category->name }}</h6>
                        <small class="text-muted">{{ $category->products_count }} sách</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('client.products.index') }}" class="btn btn-primary">
                Xem tất cả danh mục <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section id="featured-products" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Sách nổi bật</h2>
            <p class="text-muted">Những cuốn sách được đánh giá cao và yêu thích nhất</p>
        </div>
        
        <div class="row g-4">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-6">                <div class="card product-card h-100">                    <div class="position-relative">
                        <img src="{{ productImageUrl($product->images[0] ?? '') }}" class="card-img-top" alt="{{ $product->name }}">
                        
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                        @endif
                        
                        @if($product->is_featured)
                            <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                                <i class="fas fa-star"></i>
                            </span>
                        @endif
                          <!-- Wishlist Button -->
                        @auth
                            @php
                                $isInWishlist = in_array($product->id, $userWishlistIds ?? []);
                            @endphp
                            <button class="btn btn-light btn-sm position-absolute" 
                                    style="top: 10px; right: {{ $product->is_featured ? '70px' : '10px' }}; border-radius: 50%; width: 40px; height: 40px;"
                                    onclick="toggleWishlist({{ $product->id }}, this)"
                                    title="{{ $isInWishlist ? 'Xóa khỏi yêu thích' : 'Thêm vào yêu thích' }}">
                                <i class="fas fa-heart {{ $isInWishlist ? 'text-danger' : 'text-muted' }}"></i>
                            </button>
                        @endauth
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ Str::limit($product->name, 50) }}</h6>
                        @if($product->author)
                            <small class="text-muted mb-2">Tác giả: {{ $product->author }}</small>
                        @endif
                        <span class="badge bg-primary mb-2 align-self-start">{{ $product->category->name }}</span>
                        
                        <div class="mt-auto">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="price">{{ number_format($product->sale_price) }}đ</span>
                                        <small class="old-price ms-2">{{ number_format($product->price) }}đ</small>
                                    @else
                                        <span class="price">{{ number_format($product->price) }}đ</span>
                                    @endif
                                </div>
                                @if($product->stock > 0)
                                    <small class="text-success"><i class="fas fa-check-circle"></i> Còn hàng</small>
                                @else
                                    <small class="text-danger"><i class="fas fa-times-circle"></i> Hết hàng</small>
                                @endif
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('client.products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Chi tiết
                                </a>
                                @if($product->stock > 0)
                                    <button class="btn btn-primary btn-sm" onclick="addToCart({{ $product->id }})">
                                        <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="fas fa-ban me-1"></i>Hết hàng
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('client.products.index') }}" class="btn btn-primary">
                Xem thêm sách nổi bật <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Latest Products -->
@if($latestProducts->count() > 0)
<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Sách mới nhất</h2>
            <p class="text-muted">Những cuốn sách vừa được cập nhật trên hệ thống</p>
        </div>
        
        <div class="row g-4">
            @foreach($latestProducts->take(8) as $product)
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">                    <div class="position-relative">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ productImageUrl($product->images[0] ?? '') }}" class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <img src="{{ productImageUrl('') }}" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        
                        <span class="badge bg-success position-absolute top-0 start-0 m-2">Mới</span>
                        
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="badge bg-danger position-absolute" style="top: 10px; right: 60px;">
                                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                        @endif
                        
                        <!-- Wishlist Button -->
                        @auth
                            <button class="btn btn-light btn-sm position-absolute" 
                                    style="top: 10px; right: 10px; border-radius: 50%; width: 40px; height: 40px;"
                                    onclick="toggleWishlist({{ $product->id }}, this)"
                                    title="Thêm vào yêu thích">
                                <i class="fas fa-heart text-muted"></i>
                            </button>
                        @endauth
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ Str::limit($product->name, 50) }}</h6>
                        @if($product->author)
                            <small class="text-muted mb-2">Tác giả: {{ $product->author }}</small>
                        @endif
                        <span class="badge bg-primary mb-2 align-self-start">{{ $product->category->name }}</span>
                        
                        <div class="mt-auto">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="price">{{ number_format($product->sale_price) }}đ</span>
                                        <small class="old-price ms-2">{{ number_format($product->price) }}đ</small>
                                    @else
                                        <span class="price">{{ number_format($product->price) }}đ</span>
                                    @endif
                                </div>
                                @if($product->stock > 0)
                                    <small class="text-success"><i class="fas fa-check-circle"></i> Còn hàng</small>
                                @else
                                    <small class="text-danger"><i class="fas fa-times-circle"></i> Hết hàng</small>
                                @endif
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('client.products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Chi tiết
                                </a>
                                @if($product->stock > 0)
                                    <button class="btn btn-primary btn-sm" onclick="addToCart({{ $product->id }})">
                                        <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="fas fa-ban me-1"></i>Hết hàng
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('client.products.index') }}" class="btn btn-primary">
                Xem tất cả sản phẩm <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

<!-- Best Selling Products -->
@if($bestSellingProducts->count() > 0)
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Sách bán chạy</h2>
            <p class="text-muted">Những cuốn sách được độc giả yêu thích và mua nhiều nhất</p>
        </div>
        
        <div class="row g-4">
            @foreach($bestSellingProducts as $product)
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100">                    <div class="position-relative">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ productImageUrl($product->images[0] ?? '') }}" class="card-img-top" alt="{{ $product->name }}">
                        @else
                            <img src="{{ productImageUrl('') }}" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        
                        <span class="badge bg-warning position-absolute top-0 start-0 m-2">
                            <i class="fas fa-fire"></i> Hot
                        </span>
                        
                        @if($product->sale_price && $product->sale_price < $product->price)
                            <span class="badge bg-danger position-absolute" style="top: 10px; right: 60px;">
                                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                        @endif
                        
                        <!-- Wishlist Button -->
                        @auth
                            <button class="btn btn-light btn-sm position-absolute" 
                                    style="top: 10px; right: 10px; border-radius: 50%; width: 40px; height: 40px;"
                                    onclick="toggleWishlist({{ $product->id }}, this)"
                                    title="Thêm vào yêu thích">
                                <i class="fas fa-heart text-muted"></i>
                            </button>
                        @endauth
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ Str::limit($product->name, 50) }}</h6>
                        @if($product->author)
                            <small class="text-muted mb-2">Tác giả: {{ $product->author }}</small>
                        @endif
                        <span class="badge bg-primary mb-2 align-self-start">{{ $product->category->name }}</span>
                        <small class="text-muted mb-2"><i class="fas fa-shopping-cart"></i> Đã bán: {{ $product->sold_count }}</small>
                        
                        <div class="mt-auto">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="price">{{ number_format($product->sale_price) }}đ</span>
                                        <small class="old-price ms-2">{{ number_format($product->price) }}đ</small>
                                    @else
                                        <span class="price">{{ number_format($product->price) }}đ</span>
                                    @endif
                                </div>
                                @if($product->stock > 0)
                                    <small class="text-success"><i class="fas fa-check-circle"></i> Còn hàng</small>
                                @else
                                    <small class="text-danger"><i class="fas fa-times-circle"></i> Hết hàng</small>
                                @endif
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('client.products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Chi tiết
                                </a>
                                @if($product->stock > 0)
                                    <button class="btn btn-primary btn-sm" onclick="addToCart({{ $product->id }})">
                                        <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                                    </button>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="fas fa-ban me-1"></i>Hết hàng
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Features -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mb-3">
                    <i class="fas fa-shipping-fast fa-3x text-warning"></i>
                </div>
                <h5>Giao hàng nhanh</h5>
                <p class="mb-0">Giao hàng trong 1-2 ngày làm việc</p>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mb-3">
                    <i class="fas fa-shield-alt fa-3x text-warning"></i>
                </div>
                <h5>Bảo hành chất lượng</h5>
                <p class="mb-0">Đổi trả miễn phí nếu sách bị lỗi</p>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mb-3">
                    <i class="fas fa-headset fa-3x text-warning"></i>
                </div>
                <h5>Hỗ trợ 24/7</h5>
                <p class="mb-0">Đội ngũ tư vấn nhiệt tình</p>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="mb-3">
                    <i class="fas fa-tags fa-3x text-warning"></i>
                </div>
                <h5>Giá tốt nhất</h5>
                <p class="mb-0">Cam kết giá cạnh tranh nhất thị trường</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Load user wishlist and update UI on page load
$(document).ready(function() {
    @auth
        loadUserWishlist();
    @endauth
});

// Load user wishlist function
function loadUserWishlist() {
    const userWishlistIds = @json($userWishlistIds ?? []);
    
    // Update all wishlist buttons based on user's current wishlist
    userWishlistIds.forEach(function(productId) {
        $(`button[onclick*="toggleWishlist(${productId}"]`).each(function() {
            const button = this;
            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.remove('text-muted');
                icon.classList.add('text-danger');
                button.title = 'Xóa khỏi yêu thích';
            }
        });
    });
}

// Add to cart function
function addToCart(productId, quantity = 1) {
    if (typeof $ === 'undefined') {
        alert('Trang chưa tải đầy đủ, vui lòng thử lại!');
        return;
    }
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.post(`{{ url('/cart/add') }}/${productId}`, {
        quantity: quantity,
        _token: '{{ csrf_token() }}'
    })
    .done(function(response) {
        if (response.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
            });
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: response.message
            });
        }
    })
    .fail(function(xhr) {
        let message = 'Có lỗi xảy ra, vui lòng thử lại!';
        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        }
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: message
        });
    });
}

// Toggle wishlist function
function toggleWishlist(productId, button) {
    if (typeof $ === 'undefined') {
        alert('Trang chưa tải đầy đủ, vui lòng thử lại!');
        return;
    }
    
    const icon = button.querySelector('i');
    const isAdded = icon.classList.contains('text-danger');
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    const url = isAdded ? 
        `{{ url('/wishlist/remove') }}/${productId}` : 
        `{{ url('/wishlist/add') }}/${productId}`;
    
    const requestData = {
        _token: '{{ csrf_token() }}'
    };
    
    // Add _method for DELETE request
    if (isAdded) {
        requestData._method = 'DELETE';
    }
    
    $.ajax({
        url: url,
        method: 'POST', // Always use POST for Laravel
        data: requestData
    })
    .done(function(response) {
        if (response.success) {
            if (isAdded) {
                // Remove from wishlist
                icon.classList.remove('text-danger');
                icon.classList.add('text-muted');
                button.title = 'Thêm vào yêu thích';
            } else {
                // Add to wishlist
                icon.classList.remove('text-muted');
                icon.classList.add('text-danger');
                button.title = 'Xóa khỏi yêu thích';
            }
            
            // Show success message
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
            
            Toast.fire({
                icon: 'success',
                title: response.message
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: response.message
            });
        }
    })
    .fail(function(xhr) {
        let message = 'Có lỗi xảy ra, vui lòng thử lại!';
        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        } else if (xhr.status === 401) {
            message = 'Vui lòng đăng nhập để sử dụng tính năng này!';
        }
        
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: message
        });
    });
}
</script>
@endpush
