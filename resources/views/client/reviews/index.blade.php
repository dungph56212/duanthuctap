@extends('client.layouts.app')

@section('title', 'Đánh giá của tôi')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Đánh giá của tôi</h2>
                <a href="{{ route('client.products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
                </a>
            </div>

            <!-- Reviews List -->
            @if(auth()->user()->reviews && auth()->user()->reviews->count() > 0)
                <div class="row">
                    @foreach(auth()->user()->reviews as $review)
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex mb-3">
                                    <img src="{{ productImageUrl($review->product->image_url) }}" 
                                         class="rounded me-3" 
                                         width="80" height="80" 
                                         alt="{{ $review->product->name }}"
                                         style="object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $review->product->name }}</h6>
                                        <p class="text-muted mb-2">{{ number_format($review->product->price) }}đ</p>
                                        <div class="mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-muted"></i>
                                                @endif
                                            @endfor
                                            <span class="text-muted ms-2">{{ $review->rating }}/5</span>
                                        </div>
                                    </div>
                                </div>
                                
                                @if($review->comment)
                                <div class="border-start border-primary ps-3 mb-3">
                                    <p class="mb-0">{{ $review->comment }}</p>
                                </div>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center text-muted">
                                    <small>{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                    <a href="{{ route('client.products.show', $review->product) }}" class="btn btn-sm btn-outline-primary">
                                        Xem sản phẩm
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if(auth()->user()->reviews->count() > 12)
                <div class="d-flex justify-content-center">
                    <nav aria-label="Reviews pagination">
                        <!-- Add pagination here if needed -->
                    </nav>
                </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-star fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted mb-3">Bạn chưa có đánh giá nào</h5>
                    <p class="text-muted mb-4">Hãy mua sắm và đánh giá sản phẩm để chia sẻ trải nghiệm của bạn!</p>
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
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-2px);
}
.border-start {
    border-left: 3px solid var(--bs-primary) !important;
}
</style>
@endpush
