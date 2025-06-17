@extends('admin.layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Chi tiết sản phẩm: {{ $product->name }}</h1>
    <div class="btn-group">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Chỉnh sửa
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Product Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin sản phẩm</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                 class="img-fluid rounded mb-3">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" 
                                 style="height: 300px;">
                                <i class="fas fa-image fa-5x text-muted"></i>
                            </div>
                        @endif

                        @if($product->gallery && count($product->gallery) > 0)
                        <div class="row">
                            @foreach($product->gallery as $image)
                            <div class="col-3 mb-2">
                                <img src="{{ asset('storage/' . $image) }}" alt="Gallery" 
                                     class="img-fluid rounded" style="height: 60px; object-fit: cover; cursor: pointer;"
                                     onclick="changeMainImage('{{ asset('storage/' . $image) }}')">
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Tên sản phẩm:</th>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th>SKU:</th>
                                <td><code>{{ $product->sku }}</code></td>
                            </tr>
                            @if($product->barcode)
                            <tr>
                                <th>Mã vạch:</th>
                                <td><code>{{ $product->barcode }}</code></td>
                            </tr>
                            @endif
                            <tr>
                                <th>Danh mục:</th>
                                <td>
                                    @if($product->category)
                                        <a href="{{ route('admin.categories.show', $product->category) }}" class="text-decoration-none">
                                            {{ $product->category->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Chưa phân loại</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Giá bán:</th>
                                <td>
                                    <span class="h5 text-primary">{{ number_format($product->price) }}đ</span>
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <br><small class="text-muted">Giá khuyến mãi: 
                                            <span class="text-danger">{{ number_format($product->sale_price) }}đ</span>
                                        </small>
                                    @endif
                                </td>
                            </tr>
                            @if($product->cost_price)
                            <tr>
                                <th>Giá vốn:</th>
                                <td>{{ number_format($product->cost_price) }}đ</td>
                            </tr>
                            @endif
                            <tr>
                                <th>Tồn kho:</th>
                                <td>                                    <span class="badge {{ $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger') }} fs-6">
                                        {{ $product->stock }} sản phẩm
                                    </span>
                                    @if($product->min_quantity)
                                        <br><small class="text-muted">Tối thiểu: {{ $product->min_quantity }}</small>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-danger">Tạm ngưng</span>
                                    @endif
                                    
                                    @if($product->featured)
                                        <span class="badge bg-warning ms-1">Nổi bật</span>
                                    @endif
                                    
                                    @if($product->in_stock)
                                        <span class="badge bg-info ms-1">Còn hàng</span>
                                    @else
                                        <span class="badge bg-secondary ms-1">Hết hàng</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($product->short_description)
                <hr>
                <div>
                    <h6>Mô tả ngắn:</h6>
                    <p class="text-muted">{{ $product->short_description }}</p>
                </div>
                @endif

                @if($product->description)
                <hr>
                <div>
                    <h6>Mô tả chi tiết:</h6>
                    <div class="text-muted">{!! nl2br(e($product->description)) !!}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Specifications -->
        @if($product->weight || $product->length || $product->width || $product->height)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông số kỹ thuật</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($product->weight)
                    <div class="col-md-3">
                        <strong>Trọng lượng:</strong><br>
                        <span class="text-muted">{{ $product->weight }}g</span>
                    </div>
                    @endif
                    @if($product->length)
                    <div class="col-md-3">
                        <strong>Chiều dài:</strong><br>
                        <span class="text-muted">{{ $product->length }}cm</span>
                    </div>
                    @endif
                    @if($product->width)
                    <div class="col-md-3">
                        <strong>Chiều rộng:</strong><br>
                        <span class="text-muted">{{ $product->width }}cm</span>
                    </div>
                    @endif
                    @if($product->height)
                    <div class="col-md-3">
                        <strong>Chiều cao:</strong><br>
                        <span class="text-muted">{{ $product->height }}cm</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- SEO Information -->
        @if($product->meta_title || $product->meta_description || $product->tags)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin SEO</h5>
            </div>
            <div class="card-body">
                @if($product->meta_title)
                <div class="mb-3">
                    <strong>Tiêu đề SEO:</strong><br>
                    <span class="text-muted">{{ $product->meta_title }}</span>
                </div>
                @endif
                
                @if($product->meta_description)
                <div class="mb-3">
                    <strong>Mô tả SEO:</strong><br>
                    <span class="text-muted">{{ $product->meta_description }}</span>
                </div>
                @endif
                
                @if($product->tags)
                <div class="mb-3">
                    <strong>Tags:</strong><br>
                    @if(is_array($product->tags))
                        @foreach($product->tags as $tag)
                            <span class="badge bg-light text-dark me-1">{{ $tag }}</span>
                        @endforeach
                    @else
                        <span class="text-muted">{{ $product->tags }}</span>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Recent Orders -->
        @if($recentOrders && $recentOrders->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Đơn hàng gần đây ({{ $recentOrders->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Số lượng</th>
                                <th>Tổng tiền</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                        #{{ $order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                                <td>{{ $order->orderItems->where('product_id', $product->id)->first()->quantity ?? 0 }}</td>
                                <td>{{ number_format($order->total_amount) }}đ</td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Quick Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thống kê nhanh</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <h4 class="text-primary">{{ $product->views ?? 0 }}</h4>
                        <small class="text-muted">Lượt xem</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success">{{ $totalSold ?? 0 }}</h4>
                        <small class="text-muted">Đã bán</small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6 border-end">
                        <h4 class="text-warning">{{ $product->reviews_count ?? 0 }}</h4>
                        <small class="text-muted">Đánh giá</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $product->wishlists_count ?? 0 }}</h4>
                        <small class="text-muted">Yêu thích</small>
                    </div>
                </div>
                
                @if($product->price && $product->cost_price)
                <hr>
                <div class="text-center">
                    @php
                        $profit = $product->price - $product->cost_price;
                        $margin = $product->price > 0 ? (($profit / $product->price) * 100) : 0;
                    @endphp
                    <h5 class="text-success">{{ number_format($profit) }}đ</h5>
                    <small class="text-muted">Lợi nhuận ({{ number_format($margin, 1) }}%)</small>
                </div>
                @endif
            </div>
        </div>

        <!-- System Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thông tin hệ thống</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>ID:</th>
                        <td>{{ $product->id }}</td>
                    </tr>
                    <tr>
                        <th>Slug:</th>
                        <td><code>{{ $product->slug }}</code></td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật cuối:</th>
                        <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @if($product->deleted_at)
                    <tr>
                        <th>Ngày xóa:</th>
                        <td class="text-danger">{{ $product->deleted_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Thao tác nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa sản phẩm
                    </a>
                    
                    @if($product->is_active)
                    <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-pause me-2"></i>Tạm ngưng bán
                        </button>
                    </form>
                    @else
                    <form action="{{ route('admin.products.toggle-status', $product) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-play me-2"></i>Kích hoạt bán
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.products.create', ['category' => $product->category_id]) }}" class="btn btn-info">
                        <i class="fas fa-plus me-2"></i>Thêm sản phẩm tương tự
                    </a>
                    
                    <button class="btn btn-outline-secondary" onclick="duplicateProduct()">
                        <i class="fas fa-copy me-2"></i>Nhân bản sản phẩm
                    </button>
                    
                    <hr>
                    
                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" 
                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này? Hành động này không thể hoàn tác.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Xóa sản phẩm
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Stock Alert -->
        @if($product->stock <= $product->min_quantity)
        <div class="card border-warning mb-4">
            <div class="card-header bg-warning text-dark">
                <h6 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Cảnh báo tồn kho</h6>
            </div>
            <div class="card-body">
                <p class="mb-2">Sản phẩm sắp hết hàng!</p>
                <p class="mb-0">
                    <strong>Tồn kho hiện tại:</strong> {{ $product->stock }}<br>
                    <strong>Tối thiểu:</strong> {{ $product->min_quantity }}
                </p>
                <hr>
                <a href="{{ route('admin.products.edit', $product) }}#stock" class="btn btn-warning btn-sm">
                    <i class="fas fa-plus me-1"></i>Nhập thêm hàng
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
// Change main product image
function changeMainImage(imageSrc) {
    const mainImage = document.querySelector('.col-md-4 img:first-child');
    if (mainImage) {
        mainImage.src = imageSrc;
    }
}

// Duplicate product
function duplicateProduct() {
    if (confirm('Bạn có muốn tạo một bản sao của sản phẩm này?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.products.duplicate", $product) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Auto-refresh stock info every 30 seconds
setInterval(function() {
    // You can implement AJAX call to refresh stock info here
}, 30000);
</script>
@endpush
