@extends('client.layouts.app')

@section('title', $category->name . ' - Danh mục sách')
@section('description', 'Khám phá bộ sưu tập sách ' . $category->name . ' với nhiều đầu sách hay và chất lượng')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('client.products.index') }}">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">{{ $category->name }}</h1>
                    <p class="text-muted mb-0">{{ $products->total() }} sản phẩm</p>
                    @if($category->description)
                        <p class="text-muted mt-2">{{ $category->description }}</p>
                    @endif
                </div>
                
                <!-- View Toggle -->
                <div class="btn-group" role="group" aria-label="View toggle">
                    <button type="button" class="btn btn-outline-secondary active" id="grid-view">
                        <i class="fas fa-th"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary" id="list-view">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Sort -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <!-- Sort Options -->
                <div class="d-flex align-items-center gap-3">
                    <label for="sort" class="form-label mb-0">Sắp xếp:</label>
                    <select class="form-select" id="sort" style="width: auto;">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Giá thấp → cao</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Giá cao → thấp</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A → Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z → A</option>
                        <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Phổ biến</option>
                    </select>
                </div>

                <!-- Price Filter -->
                <div class="d-flex align-items-center gap-3">
                    <label class="form-label mb-0">Giá:</label>
                    <select class="form-select" id="price-filter" style="width: auto;">
                        <option value="">Tất cả</option>
                        <option value="0-100000" {{ request('price') == '0-100000' ? 'selected' : '' }}>Dưới 100k</option>
                        <option value="100000-200000" {{ request('price') == '100000-200000' ? 'selected' : '' }}>100k - 200k</option>
                        <option value="200000-500000" {{ request('price') == '200000-500000' ? 'selected' : '' }}>200k - 500k</option>
                        <option value="500000-1000000" {{ request('price') == '500000-1000000' ? 'selected' : '' }}>500k - 1M</option>
                        <option value="1000000-" {{ request('price') == '1000000-' ? 'selected' : '' }}>Trên 1M</option>
                    </select>
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
                    <img src="{{ asset('images/no-products.svg') }}" alt="Không có sản phẩm" style="width: 200px; opacity: 0.5;">
                    <h4 class="mt-3">Không có sản phẩm nào</h4>
                    <p class="text-muted">Hiện tại danh mục này chưa có sản phẩm nào.</p>
                    <a href="{{ route('client.products.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Xem tất cả sản phẩm
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Product pagination">
                    {{ $products->links() }}
                </nav>
            </div>
        </div>
    @endif
</div>

<!-- Quick View Modal -->
<div class="modal fade" id="quickViewModal" tabindex="-1" aria-labelledby="quickViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickViewModalLabel">Xem nhanh sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="quickViewContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
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
    
    .list-view .product-item {
        width: 100%;
    }
    
    .list-view .product-card {
        flex-direction: row;
    }
    
    .list-view .product-card img {
        width: 200px;
        height: 150px;
        object-fit: cover;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle
    const gridView = document.getElementById('grid-view');
    const listView = document.getElementById('list-view');
    const container = document.getElementById('products-container');
    
    gridView.addEventListener('click', function() {
        container.classList.remove('list-view');
        gridView.classList.add('active');
        listView.classList.remove('active');
    });
    
    listView.addEventListener('click', function() {
        container.classList.add('list-view');
        listView.classList.add('active');
        gridView.classList.remove('active');
    });
    
    // Sort and filter
    const sortSelect = document.getElementById('sort');
    const priceFilter = document.getElementById('price-filter');
    
    function updateUrl() {
        const url = new URL(window.location);
        const sort = sortSelect.value;
        const price = priceFilter.value;
        
        if (sort) {
            url.searchParams.set('sort', sort);
        } else {
            url.searchParams.delete('sort');
        }
        
        if (price) {
            url.searchParams.set('price', price);
        } else {
            url.searchParams.delete('price');
        }
        
        window.location = url;
    }
    
    sortSelect.addEventListener('change', updateUrl);
    priceFilter.addEventListener('change', updateUrl);
});

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
    // Implementation for wishlist
    Swal.fire({
        icon: 'info',
        title: 'Thông báo',
        text: 'Tính năng yêu thích đang được phát triển'
    });
}

// Quick view function
function quickView(productId) {
    // Implementation for quick view
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
