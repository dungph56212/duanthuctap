@extends('client.layouts.app')

@section('title', $product->name)
@section('description', Str::limit(strip_tags($product->description), 160))
@section('keywords', $product->name . ', ' . ($product->author ?? '') . ', ' . $product->category->name . ', sách')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('client.home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('client.products.index') }}">Sản phẩm</a></li>
            <li class="breadcrumb-item"><a href="{{ route('client.products.category', $product->category) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 50) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6 mb-4">            <div class="product-images">
                @if($product->images && count($product->images) > 0)
                    <!-- Main Image -->
                    <div class="main-image mb-3">
                        <img src="{{ productImageUrl($product->images[0] ?? '') }}" 
                             class="img-fluid rounded shadow" 
                             alt="{{ $product->name }}"
                             id="main-product-image"
                             style="width: 100%; height: 500px; object-fit: cover;">
                    </div>
                    
                    @if(count($product->images) > 1)
                        <!-- Thumbnail Images -->
                        <div class="thumbnail-images">
                            <div class="row g-2">
                                @foreach($product->images as $index => $image)
                                    <div class="col-3">
                                        <img src="{{ productImageUrl($image) }}" 
                                             class="img-fluid rounded thumbnail-img {{ $index === 0 ? 'active' : '' }}" 
                                             alt="{{ $product->name }}"
                                             style="height: 100px; object-fit: cover; cursor: pointer; border: 2px solid {{ $index === 0 ? 'var(--primary-color)' : 'transparent' }};"
                                             onclick="changeMainImage('{{ productImageUrl($image) }}', this)">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif                @else
                    <img src="{{ productImageUrl('') }}" 
                         class="img-fluid rounded shadow" 
                         alt="{{ $product->name }}"
                         style="width: 100%; height: 500px; object-fit: cover;">
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-6">
            <div class="product-info">
                <!-- Product Title -->
                <h1 class="h2 mb-3">{{ $product->name }}</h1>
                
                <!-- Author & Publisher -->
                @if($product->author || $product->publisher)
                    <div class="mb-3">
                        @if($product->author)
                            <p class="mb-1"><strong>Tác giả:</strong> {{ $product->author }}</p>
                        @endif
                        @if($product->publisher)
                            <p class="mb-1"><strong>Nhà xuất bản:</strong> {{ $product->publisher }}</p>
                        @endif
                    </div>
                @endif

                <!-- Category -->
                <div class="mb-3">
                    <span class="badge bg-primary fs-6">{{ $product->category->name }}</span>
                    @if($product->is_featured)
                        <span class="badge bg-warning fs-6 ms-2">
                            <i class="fas fa-star"></i> Nổi bật
                        </span>
                    @endif
                </div>

                <!-- Price -->
                <div class="price-section mb-4">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <div class="d-flex align-items-center gap-3">
                            <span class="price h3 text-danger mb-0">{{ number_format($product->sale_price) }}đ</span>
                            <span class="old-price h5 text-muted text-decoration-line-through">{{ number_format($product->price) }}đ</span>
                            <span class="badge bg-danger">
                                -{{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                            </span>
                        </div>
                    @else
                        <span class="price h3 text-danger">{{ number_format($product->price) }}đ</span>
                    @endif
                </div>

                <!-- Stock Status -->
                <div class="stock-status mb-4">
                    @if($product->stock > 0)
                        <div class="alert alert-success d-inline-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            Còn {{ $product->stock }} cuốn trong kho
                        </div>
                    @else
                        <div class="alert alert-danger d-inline-flex align-items-center">
                            <i class="fas fa-times-circle me-2"></i>
                            Tạm hết hàng
                        </div>
                    @endif
                </div>

                <!-- Quantity & Add to Cart -->
                @if($product->stock > 0)
                    <div class="quantity-cart mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="quantity" class="form-label">Số lượng:</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" 
                                           class="form-control text-center" 
                                           id="quantity" 
                                           value="1" 
                                           min="1" 
                                           max="{{ $product->stock }}">
                                    <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>                            <div class="col-md-8">
                                <label class="form-label d-block">&nbsp;</label>
                                <button class="btn btn-primary btn-lg w-100 mb-2" onclick="addToCartWithQuantity({{ $product->id }})">
                                    <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                                </button>
                                @auth
                                    <button class="btn btn-outline-danger w-100" 
                                            onclick="toggleWishlist({{ $product->id }}, this)"
                                            title="Thêm vào yêu thích">
                                        <i class="fas fa-heart me-2"></i>Thêm vào yêu thích
                                    </button>
                                @else
                                    <a href="{{ route('client.auth.login') }}" class="btn btn-outline-danger w-100">
                                        <i class="fas fa-heart me-2"></i>Đăng nhập để yêu thích
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Product Details -->
                <div class="product-details">
                    <h5>Thông tin chi tiết</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                @if($product->sku)
                                    <tr>
                                        <th width="150">Mã sản phẩm:</th>
                                        <td>{{ $product->sku }}</td>
                                    </tr>
                                @endif
                                @if($product->isbn)
                                    <tr>
                                        <th>ISBN:</th>
                                        <td>{{ $product->isbn }}</td>
                                    </tr>
                                @endif
                                @if($product->pages)
                                    <tr>
                                        <th>Số trang:</th>
                                        <td>{{ $product->pages }} trang</td>
                                    </tr>
                                @endif
                                @if($product->publish_year)
                                    <tr>
                                        <th>Năm xuất bản:</th>
                                        <td>{{ $product->publish_year }}</td>
                                    </tr>
                                @endif
                                @if($product->language)
                                    <tr>
                                        <th>Ngôn ngữ:</th>
                                        <td>{{ $product->language === 'vi' ? 'Tiếng Việt' : 'Tiếng Anh' }}</td>
                                    </tr>
                                @endif
                                @if($product->weight)
                                    <tr>
                                        <th>Trọng lượng:</th>
                                        <td>{{ $product->weight }}g</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Social Share -->
                <div class="social-share mt-4">
                    <h6>Chia sẻ:</h6>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}" 
                           class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($product->name) }}" 
                           class="btn btn-outline-info btn-sm" target="_blank">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(request()->fullUrl()) }}&description={{ urlencode($product->name) }}" 
                           class="btn btn-outline-danger btn-sm" target="_blank">
                            <i class="fab fa-pinterest"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    @if($product->description)
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Mô tả sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="product-description">
                            {!! nl2br(e($product->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>    @endif

    <!-- Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Đánh giá sản phẩm</h5>
                        @auth
                            <!-- Wishlist Button -->
                            @php
                                $inWishlist = auth()->user()->wishlists()->where('product_id', $product->id)->exists();
                            @endphp
                            <div class="d-flex gap-2">
                                @if($inWishlist)
                                    <form action="{{ route('client.wishlist.remove', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-heart"></i> Đã yêu thích
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('client.wishlist.add', $product) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="far fa-heart"></i> Yêu thích
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endauth
                    </div>
                </div>
                <div class="card-body">
                    @auth
                        <!-- Review Form -->
                        @php
                            $userReview = auth()->user()->reviews()->where('product_id', $product->id)->first();
                        @endphp
                        
                        @if(!$userReview)
                            <div class="mb-4 p-3 bg-light rounded">
                                <h6>Viết đánh giá của bạn</h6>
                                <form action="{{ route('client.reviews.store', $product) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Đánh giá</label>
                                            <div class="rating-input">
                                                @for($i = 5; $i >= 1; $i--)
                                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" required>
                                                    <label for="star{{ $i }}"><i class="fas fa-star"></i></label>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="comment" class="form-label">Nhận xét</label>
                                            <textarea name="comment" id="comment" class="form-control" rows="3" placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."></textarea>
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-paper-plane"></i> Gửi đánh giá
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <a href="{{ route('client.auth.login') }}">Đăng nhập</a> để viết đánh giá cho sản phẩm này.
                        </div>
                    @endauth

                    <!-- Reviews List -->
                    @if($product->reviews && $product->reviews->count() > 0)
                        <div class="reviews-list">
                            <h6 class="mb-3">Đánh giá từ khách hàng ({{ $product->reviews->count() }})</h6>
                            
                            <!-- Overall Rating -->
                            @php
                                $avgRating = $product->reviews->avg('rating');
                                $ratingCounts = $product->reviews->groupBy('rating')->map->count();
                            @endphp
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <div class="h2 text-warning mb-2">{{ number_format($avgRating, 1) }}</div>
                                        <div class="mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $avgRating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted">{{ $product->reviews->count() }} đánh giá</small>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    @for($i = 5; $i >= 1; $i--)
                                        <div class="d-flex align-items-center mb-1">
                                            <span class="me-2">{{ $i }}</span>
                                            <i class="fas fa-star text-warning me-2"></i>
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar bg-warning" 
                                                     style="width: {{ $product->reviews->count() > 0 ? ($ratingCounts->get($i, 0) / $product->reviews->count()) * 100 : 0 }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ $ratingCounts->get($i, 0) }}</small>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <!-- Individual Reviews -->
                            @foreach($product->reviews->take(5) as $review)
                                <div class="review-item mb-3 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong>{{ $review->user->name }}</strong>
                                            <div class="rating">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <i class="fas fa-star text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-muted"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $review->created_at->format('d/m/Y') }}</small>
                                    </div>
                                    @if($review->comment)
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @endforeach

                            @if($product->reviews->count() > 5)
                                <div class="text-center">
                                    <a href="{{ route('client.reviews') }}" class="btn btn-outline-primary">
                                        Xem tất cả đánh giá
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</p>
                            @auth
                                <p class="text-muted">Hãy là người đầu tiên đánh giá!</p>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h3 class="mb-4">Sản phẩm liên quan</h3>
                <div class="row g-4">
                    @foreach($relatedProducts as $relatedProduct)
                        <div class="col-lg-3 col-md-6">
                            <div class="card product-card h-100">
                                <div class="position-relative">
                                    @if($relatedProduct->images && count($relatedProduct->images) > 0)
                                        <img src="{{ Storage::url($relatedProduct->images[0]) }}" 
                                             class="card-img-top" 
                                             alt="{{ $relatedProduct->name }}"
                                             style="height: 250px; object-fit: cover;">
                                    @else
                                        <img src="https://via.placeholder.com/300x400?text=No+Image" 
                                             class="card-img-top" 
                                             alt="{{ $relatedProduct->name }}">
                                    @endif
                                    
                                    @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                        <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                            -{{ round((($relatedProduct->price - $relatedProduct->sale_price) / $relatedProduct->price) * 100) }}%
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">{{ Str::limit($relatedProduct->name, 50) }}</h6>
                                    @if($relatedProduct->author)
                                        <small class="text-muted mb-2">{{ $relatedProduct->author }}</small>
                                    @endif
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div>
                                                @if($relatedProduct->sale_price && $relatedProduct->sale_price < $relatedProduct->price)
                                                    <span class="price">{{ number_format($relatedProduct->sale_price) }}đ</span>
                                                    <small class="old-price ms-2">{{ number_format($relatedProduct->price) }}đ</small>
                                                @else
                                                    <span class="price">{{ number_format($relatedProduct->price) }}đ</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('client.products.show', $relatedProduct) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i>Chi tiết
                                            </a>
                                            @if($relatedProduct->stock > 0)
                                                <button class="btn btn-primary btn-sm" 
                                                        onclick="addToCart({{ $relatedProduct->id }})">
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
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.thumbnail-img.active {
    border-color: var(--primary-color) !important;
}

.thumbnail-img:hover {
    border-color: var(--secondary-color) !important;
}

.product-description {
    line-height: 1.8;
}

.social-share a:hover {
    transform: translateY(-2px);
}

/* Rating Input Styles */
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input[type="radio"] {
    display: none;
}

.rating-input label {
    cursor: pointer;
    color: #ddd;
    font-size: 1.5rem;
    margin-right: 5px;
    transition: color 0.2s;
}

.rating-input label:hover,
.rating-input label:hover ~ label,
.rating-input input[type="radio"]:checked ~ label {
    color: #ffc107;
}

.review-item {
    transition: box-shadow 0.2s;
}

.review-item:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
    transform: translateY(-2px);
    transition: transform 0.3s ease;
}

@media (max-width: 768px) {
    .main-image img {
        height: 300px !important;
    }
    
    .thumbnail-images .col-3 img {
        height: 60px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
function changeMainImage(imageSrc, thumbnail) {
    // Change main image
    document.getElementById('main-product-image').src = imageSrc;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail-img').forEach(img => {
        img.classList.remove('active');
        img.style.borderColor = 'transparent';
    });
    
    thumbnail.classList.add('active');
    thumbnail.style.borderColor = 'var(--primary-color)';
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.max);
    
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
    }
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.getAttribute('max'));
    
    if (currentValue < maxValue) {
        quantityInput.value = currentValue + 1;
    }
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const minValue = parseInt(quantityInput.getAttribute('min'));
    
    if (currentValue > minValue) {
        quantityInput.value = currentValue - 1;
    }
}

function addToCartWithQuantity(productId) {
    console.log('addToCartWithQuantity called with productId:', productId);
    
    const quantityElement = document.getElementById('quantity');
    if (!quantityElement) {
        console.error('Quantity element not found!');
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Không tìm thấy trường số lượng!'
        });
        return;
    }
    
    const quantity = parseInt(quantityElement.value);
    console.log('Quantity:', quantity);
    
    if (isNaN(quantity) || quantity < 1) {
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Vui lòng nhập số lượng hợp lệ!'
        });
        return;
    }
    
    // Check if jQuery is loaded
    if (typeof $ === 'undefined') {
        console.error('jQuery not loaded!');
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Trang chưa tải đầy đủ, vui lòng thử lại!'
        });
        return;
    }
    
    console.log('Sending AJAX request...');
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    const url = `{{ url('/cart/add') }}/${productId}`;
    console.log('URL:', url);
    
    $.post(url, {
        quantity: quantity,
        _token: '{{ csrf_token() }}'
    }, function(response) {
        console.log('Success response:', response);
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
    }).fail(function(xhr) {
        console.log('AJAX Error:', xhr);
        console.log('Status:', xhr.status);
        console.log('Response Text:', xhr.responseText);
        
        let message = 'Có lỗi xảy ra, vui lòng thử lại!';
        if (xhr.responseJSON && xhr.responseJSON.message) {
            message = xhr.responseJSON.message;
        } else if (xhr.status === 419) {
            message = 'Phiên làm việc đã hết hạn, vui lòng tải lại trang!';
        } else if (xhr.status === 500) {
            message = 'Lỗi server, vui lòng thử lại sau!';
        } else if (xhr.status === 404) {
            message = 'Không tìm thấy trang, vui lòng kiểm tra đường dẫn!';
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
                button.classList.add('btn-outline-danger');
                button.innerHTML = '<i class="fas fa-heart me-2"></i>Thêm vào yêu thích';
                button.title = 'Thêm vào yêu thích';
            } else {
                // Add to wishlist
                button.classList.remove('btn-outline-danger');
                button.classList.add('btn-danger');
                button.innerHTML = '<i class="fas fa-heart me-2"></i>Đã yêu thích';
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

// Update view count when page loads
$(document).ready(function() {
    // Track product view (optional - can be implemented via AJAX)
    console.log('Product viewed: {{ $product->name }}');
});
</script>
@endpush
