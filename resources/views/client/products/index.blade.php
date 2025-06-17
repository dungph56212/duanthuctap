@extends('client.layouts.app')

@section('title', 'Tất cả sản phẩm')
@section('description', 'Khám phá bộ sưu tập sách đa dạng với hàng ngàn đầu sách từ nhiều thể loại khác nhau')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 mb-1">Tất cả sản phẩm</h1>
                    <p class="text-muted mb-0">{{ $products->total() }} sản phẩm được tìm thấy</p>
                </div>
                
                <!-- View Toggle -->
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-primary active" id="grid-view">
                        <i class="fas fa-th"></i>
                    </button>
                    <button type="button" class="btn btn-outline-primary" id="list-view">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('client.products.index') }}" id="filter-form">
                        <div class="row g-3">
                            <!-- Search -->
                            <div class="col-md-4">
                                <label for="search" class="form-label">Tìm kiếm</label>
                                <div class="input-group">
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Tên sách, tác giả...">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Category -->
                            <div class="col-md-2">
                                <label for="category" class="form-label">Danh mục</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Tất cả</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ $category->products_count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Price Range -->
                            <div class="col-md-2">
                                <label for="min_price" class="form-label">Giá từ</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="min_price" 
                                       name="min_price" 
                                       value="{{ request('min_price') }}" 
                                       placeholder="0" 
                                       min="0">
                            </div>
                            
                            <div class="col-md-2">
                                <label for="max_price" class="form-label">Đến</label>
                                <input type="number" 
                                       class="form-control" 
                                       id="max_price" 
                                       name="max_price" 
                                       value="{{ request('max_price') }}" 
                                       placeholder="1000000" 
                                       min="0">
                            </div>
                            
                            <!-- Sort -->
                            <div class="col-md-2">
                                <label for="sort" class="form-label">Sắp xếp</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Giá cao đến thấp</option>
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Bán chạy</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Filter Actions -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i>Lọc
                                </button>
                                <a href="{{ route('client.products.index') }}" class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i>Xóa bộ lọc
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row" id="products-container">
        @if($products->count() > 0)
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4 product-item">
                    <div class="card product-card h-100 shadow-sm">
                        <div class="position-relative">
                            <!-- Product Image -->
                            @if($product->images && count($product->images) > 0)
                                <img src="{{ Storage::url($product->images[0]) }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}"
                                     style="height: 280px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/300x400?text=No+Image" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}"
                                     style="height: 280px; object-fit: cover;">
                            @endif
                            
                            <!-- Sale Badge -->
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                    -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                                </span>
                            @endif
                            
                            <!-- Featured Badge -->
                            @if($product->is_featured)
                                <span class="badge bg-warning position-absolute top-0 end-0 m-2">
                                    <i class="fas fa-star"></i> Nổi bật
                                </span>
                            @endif
                              <!-- Quick Actions -->
                            <div class="product-actions position-absolute top-50 start-50 translate-middle opacity-0">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('client.products.show', $product) }}" 
                                       class="btn btn-primary btn-sm rounded-circle"
                                       title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @auth
                                        <button class="btn btn-success btn-sm rounded-circle" 
                                                onclick="toggleWishlist({{ $product->id }}, this)"
                                                title="Thêm vào yêu thích">
                                            <i class="fas fa-heart"></i>
                                        </button>
                                    @else
                                        <a href="{{ route('client.auth.login') }}" 
                                           class="btn btn-success btn-sm rounded-circle"
                                           title="Đăng nhập để yêu thích">
                                            <i class="fas fa-heart"></i>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <!-- Category -->
                            <span class="badge bg-primary mb-2 align-self-start">{{ $product->category->name }}</span>
                            
                            <!-- Product Title -->
                            <h6 class="card-title mb-2">
                                <a href="{{ route('client.products.show', $product) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ Str::limit($product->name, 60) }}
                                </a>
                            </h6>
                            
                            <!-- Author -->
                            @if($product->author)
                                <small class="text-muted mb-2">{{ $product->author }}</small>
                            @endif
                            
                            <!-- Price -->
                            <div class="price-section mb-3 mt-auto">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <div class="d-flex align-items-center">
                                        <span class="price text-danger fw-bold">{{ number_format($product->sale_price) }}đ</span>
                                        <small class="old-price text-muted text-decoration-line-through ms-2">{{ number_format($product->price) }}đ</small>
                                    </div>
                                @else
                                    <span class="price text-danger fw-bold">{{ number_format($product->price) }}đ</span>
                                @endif
                            </div>
                            
                            <!-- Stock Status -->
                            <div class="stock-status mb-3">
                                @if($product->stock > 0)
                                    <small class="text-success">
                                        <i class="fas fa-check-circle"></i> Còn {{ $product->stock }} cuốn
                                    </small>
                                @else
                                    <small class="text-danger">
                                        <i class="fas fa-times-circle"></i> Hết hàng
                                    </small>
                                @endif
                            </div>
                            
                            <!-- Actions -->
                            <div class="d-grid gap-2">
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
            @endforeach
        @else
            <!-- No Products Found -->
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h3>Không tìm thấy sản phẩm</h3>
                    <p class="text-muted">Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                    <a href="{{ route('client.products.index') }}" class="btn btn-primary">
                        <i class="fas fa-refresh me-1"></i>Xem tất cả sản phẩm
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
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
    border: none;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
}

.product-card:hover .product-actions {
    opacity: 1 !important;
    transition: opacity 0.3s ease;
}

.product-actions {
    transition: opacity 0.3s ease;
}

.price {
    font-size: 1.1rem;
}

.old-price {
    font-size: 0.9rem;
}

.product-item.list-view {
    margin-bottom: 20px;
}

.product-item.list-view .card {
    flex-direction: row;
}

.product-item.list-view .card-img-top {
    width: 200px;
    height: 150px;
    object-fit: cover;
}

.product-item.list-view .card-body {
    flex: 1;
}

@media (max-width: 768px) {
    .product-item.list-view .card {
        flex-direction: column;
    }
    
    .product-item.list-view .card-img-top {
        width: 100%;
        height: 200px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Auto submit form when filters change
$(document).ready(function() {
    $('#category, #sort').on('change', function() {
        $('#filter-form').submit();
    });
    
    // View toggle
    $('#grid-view').click(function() {
        $(this).addClass('active');
        $('#list-view').removeClass('active');
        $('.product-item').removeClass('list-view');
        $('.product-item').removeClass('col-12').addClass('col-lg-3 col-md-4 col-sm-6');
    });
    
    $('#list-view').click(function() {
        $(this).addClass('active');
        $('#grid-view').removeClass('active');
        $('.product-item').addClass('list-view');
        $('.product-item').removeClass('col-lg-3 col-md-4 col-sm-6').addClass('col-12');
    });
});

// Toggle wishlist function
function toggleWishlist(productId, button) {
    if (typeof $ === 'undefined') {
        alert('Trang chưa tải đầy đủ, vui lòng thử lại!');
        return;
    }
    
    const icon = button.querySelector('i');
    const isAdded = button.classList.contains('btn-danger');
    
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
                button.classList.remove('btn-danger');
                button.classList.add('btn-success');
                button.title = 'Thêm vào yêu thích';
            } else {
                // Add to wishlist
                button.classList.remove('btn-success');
                button.classList.add('btn-danger');
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
