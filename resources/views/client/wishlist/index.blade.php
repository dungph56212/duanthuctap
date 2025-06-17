@extends('client.layouts.app')

@section('title', 'Danh sách yêu thích')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Danh sách yêu thích</h2>
                <a href="{{ route('client.products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
                </a>
            </div>

            <!-- Wishlist Items -->
            @if(auth()->user()->wishlists && auth()->user()->wishlists->count() > 0)
                <div class="row">
                    @foreach(auth()->user()->wishlists as $wishlist)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="position-relative">
                                <img src="{{ productImageUrl($wishlist->product->image_url) }}" 
                                     class="card-img-top" 
                                     alt="{{ $wishlist->product->name }}"
                                     style="height: 250px; object-fit: cover;">
                                  <!-- Remove from wishlist button -->
                                <form action="{{ route('client.wishlist.remove', $wishlist->product) }}" method="POST" class="position-absolute top-0 end-0 m-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger rounded-circle" 
                                            title="Xóa khỏi yêu thích"
                                            onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>
                                
                                <!-- Stock status -->
                                @if($wishlist->product->stock <= 0)
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-danger">Hết hàng</span>
                                @elseif($wishlist->product->stock <= 10)
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-warning">Sắp hết</span>
                                @endif
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">{{ $wishlist->product->name }}</h6>
                                
                                @if($wishlist->product->author)
                                    <p class="text-muted small mb-2">Tác giả: {{ $wishlist->product->author }}</p>
                                @endif
                                
                                <div class="mb-3">
                                    <span class="h5 text-primary">{{ number_format($wishlist->product->price) }}đ</span>
                                    @if($wishlist->product->original_price && $wishlist->product->original_price > $wishlist->product->price)
                                        <small class="text-muted text-decoration-line-through ms-2">
                                            {{ number_format($wishlist->product->original_price) }}đ
                                        </small>
                                    @endif
                                </div>
                                
                                <!-- Rating -->
                                @if($wishlist->product->reviews && $wishlist->product->reviews->count() > 0)
                                <div class="mb-3">
                                    @php
                                        $avgRating = $wishlist->product->reviews->avg('rating');
                                        $reviewCount = $wishlist->product->reviews->count();
                                    @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $avgRating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-muted"></i>
                                        @endif
                                    @endfor
                                    <span class="text-muted ms-2">({{ $reviewCount }})</span>
                                </div>
                                @endif
                                
                                <div class="mt-auto">
                                    <div class="d-grid gap-2">
                                        @if($wishlist->product->stock > 0)
                                            <form action="{{ route('client.cart.add', $wishlist->product) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                                </button>
                                            </form>
                                        @else
                                            <button class="btn btn-secondary w-100" disabled>
                                                <i class="fas fa-times"></i> Hết hàng
                                            </button>
                                        @endif
                                        
                                        <a href="{{ route('client.products.show', $wishlist->product) }}" 
                                           class="btn btn-outline-primary w-100">
                                            <i class="fas fa-eye"></i> Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <small class="text-muted">
                                    Đã thêm: {{ $wishlist->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Statistics -->
                <div class="alert alert-info">
                    <i class="fas fa-heart"></i>
                    Bạn có <strong>{{ auth()->user()->wishlists->count() }}</strong> sản phẩm trong danh sách yêu thích
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-heart fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted mb-3">Danh sách yêu thích trống</h5>
                    <p class="text-muted mb-4">Hãy thêm những sản phẩm bạn quan tâm vào danh sách yêu thích!</p>
                    <a href="{{ route('client.products.index') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag"></i> Khám phá sản phẩm
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.btn-circle {
    width: 35px;
    height: 35px;
    padding: 0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.position-relative .btn {
    transition: all 0.2s ease;
}

.position-relative:hover .btn {
    transform: scale(1.1);
}

.card-img-top {
    transition: transform 0.3s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}
</style>
@endpush

@push('scripts')
<script>
// Add to cart with animation
document.querySelectorAll('form[action*="cart/add"]').forEach(form => {
    form.addEventListener('submit', function(e) {
        const button = this.querySelector('button');
        const originalText = button.innerHTML;
        
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
        button.disabled = true;
        
        // Re-enable after 2 seconds (or handle with actual response)
        setTimeout(() => {
            button.innerHTML = '<i class="fas fa-check"></i> Đã thêm';
            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 1000);
        }, 1000);
    });
});
</script>
@endpush
