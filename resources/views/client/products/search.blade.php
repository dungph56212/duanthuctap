@extends('client.layouts.app')

@section('title', 'Kết quả tìm kiếm: ' . $search)
@section('description', 'Kết quả tìm kiếm sách cho từ khóa: ' . $search)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('client.products.index') }}">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tìm kiếm</li>
        </ol>
    </nav>

    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h2 mb-1">Kết quả tìm kiếm</h1>
            <p class="text-muted mb-0">
                Tìm thấy <strong>{{ $products->total() }}</strong> sản phẩm cho từ khóa: 
                <strong>"{{ $search }}"</strong>
            </p>
        </div>
    </div>

    <!-- Search Box -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('client.products.search') }}" method="GET" class="d-flex">
                        <input type="text" class="form-control me-2" name="q" 
                               value="{{ $search }}" placeholder="Tìm kiếm sách...">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Tìm kiếm
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row" id="products-container">
        @forelse($products as $product)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 product-item">
                <div class="card h-100 product-card">
                    <!-- Product Image -->
                    <div class="position-relative">
                        <a href="{{ route('client.products.show', $product->id) }}">
                            <img src="{{ productImageUrl($product->images[0] ?? '') }}" 
                                 class="card-img-top" 
                                 alt="{{ $product->name }}"
                                 style="height: 300px; object-fit: cover;">
                        </a>
                        
                        <!-- Badges -->
                        @if($product->sale_price && $product->sale_price < $product->price)
                            @php
                                $discount = round((($product->price - $product->sale_price) / $product->price) * 100);
                            @endphp
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">-{{ $discount }}%</span>
                        @endif
                        
                        @if($product->is_featured)
                            <span class="badge bg-warning position-absolute top-0 end-0 m-2">Nổi bật</span>
                        @endif

                        <!-- Quick Actions -->
                        <div class="position-absolute bottom-0 end-0 m-2">
                            <div class="btn-group-vertical">
                                <button class="btn btn-sm btn-light rounded-circle mb-1" 
                                        onclick="addToWishlist({{ $product->id }})"
                                        title="Thêm vào yêu thích">
                                    <i class="fas fa-heart"></i>
                                </button>
                                <button class="btn btn-sm btn-light rounded-circle" 
                                        onclick="quickView({{ $product->id }})"
                                        title="Xem nhanh">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Info -->
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="{{ route('client.products.show', $product->id) }}" 
                               class="text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                        </h5>
                        
                        @if($product->author)
                            <p class="text-muted small mb-2">
                                <i class="fas fa-user-edit"></i> {{ $product->author }}
                            </p>
                        @endif

                        @if($product->category)
                            <p class="text-muted small mb-2">
                                <i class="fas fa-tag"></i> 
                                <a href="{{ route('client.products.category', $product->category) }}" 
                                   class="text-muted text-decoration-none">
                                    {{ $product->category->name }}
                                </a>
                            </p>
                        @endif

                        <p class="card-text flex-grow-1">
                            {{ Str::limit($product->short_description ?: $product->description, 100) }}
                        </p>

                        <!-- Rating -->
                        @if($product->reviews_avg_rating)
                            <div class="mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="text-warning me-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($product->reviews_avg_rating))
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $product->reviews_avg_rating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <small class="text-muted">({{ $product->reviews_count ?? 0 }})</small>
                                </div>
                            </div>
                        @endif

                        <!-- Price -->
                        <div class="mb-3">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="h5 text-danger mb-0">{{ number_format($product->sale_price) }}đ</span>
                                <span class="text-muted text-decoration-line-through ms-2">{{ number_format($product->price) }}đ</span>
                            @else
                                <span class="h5 text-primary mb-0">{{ number_format($product->price) }}đ</span>
                            @endif
                        </div>

                        <!-- Stock Status -->
                        @if($product->stock > 0)
                            <small class="text-success mb-2">
                                <i class="fas fa-check-circle"></i> Còn hàng
                                @if($product->stock <= 5)
                                    (Chỉ còn {{ $product->stock }} cuốn)
                                @endif
                            </small>
                        @else
                            <small class="text-danger mb-2">
                                <i class="fas fa-times-circle"></i> Hết hàng
                            </small>
                        @endif

                        <!-- Add to Cart Button -->
                        <button class="btn btn-primary mt-auto" 
                                onclick="addToCart({{ $product->id }})"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-shopping-cart"></i>
                            {{ $product->stock > 0 ? 'Thêm vào giỏ' : 'Hết hàng' }}
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <img src="{{ asset('images/no-search-results.svg') }}" alt="Không tìm thấy" style="width: 200px; opacity: 0.5;">
                    <h4 class="mt-3">Không tìm thấy kết quả</h4>
                    <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp với từ khóa "{{ $search }}"</p>
                    <div class="mt-3">
                        <a href="{{ route('client.products.index') }}" class="btn btn-primary me-2">
                            <i class="fas fa-th"></i> Xem tất cả sản phẩm
                        </a>
                        <button class="btn btn-outline-secondary" onclick="document.querySelector('input[name=q]').focus()">
                            <i class="fas fa-search"></i> Tìm kiếm khác
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Search results pagination">
                    {{ $products->appends(['q' => $search])->links() }}
                </nav>
            </div>
        </div>
    @endif

    <!-- Search Suggestions -->
    @if($products->isEmpty())
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Gợi ý tìm kiếm</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Thể loại phổ biến:</h6>
                                <ul class="list-unstyled">
                                    <li><a href="{{ route('client.products.search') }}?q=tiểu thuyết">Tiểu thuyết</a></li>
                                    <li><a href="{{ route('client.products.search') }}?q=kỹ năng sống">Kỹ năng sống</a></li>
                                    <li><a href="{{ route('client.products.search') }}?q=kinh tế">Kinh tế</a></li>
                                    <li><a href="{{ route('client.products.search') }}?q=văn học">Văn học</a></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Tác giả nổi tiếng:</h6>
                                <ul class="list-unstyled">
                                    <li><a href="{{ route('client.products.search') }}?q=Nguyễn Nhật Ánh">Nguyễn Nhật Ánh</a></li>
                                    <li><a href="{{ route('client.products.search') }}?q=Paulo Coelho">Paulo Coelho</a></li>
                                    <li><a href="{{ route('client.products.search') }}?q=Napoleon Hill">Napoleon Hill</a></li>
                                    <li><a href="{{ route('client.products.search') }}?q=Dale Carnegie">Dale Carnegie</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .product-card .btn-group-vertical {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .product-card:hover .btn-group-vertical {
        opacity: 1;
    }
    
    .search-highlight {
        background-color: yellow;
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script>
// Add to cart function
function addToCart(productId) {
    fetch('/cart', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: 'Sản phẩm đã được thêm vào giỏ hàng',
                showConfirmButton: false,
                timer: 1500
            });
            
            // Update cart count
            updateCartCount();
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: data.message || 'Có lỗi xảy ra'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng'
        });
    });
}

// Add to wishlist function
function addToWishlist(productId) {
    Swal.fire({
        icon: 'info',
        title: 'Thông báo',
        text: 'Tính năng yêu thích đang được phát triển'
    });
}

// Quick view function
function quickView(productId) {
    Swal.fire({
        icon: 'info',
        title: 'Thông báo',
        text: 'Tính năng xem nhanh đang được phát triển'
    });
}

// Update cart count
function updateCartCount() {
    fetch('/cart/count')
        .then(response => response.json())
        .then(data => {
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.count;
            }
        })
        .catch(error => console.error('Error updating cart count:', error));
}
</script>
@endpush
