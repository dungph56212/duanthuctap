@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Chỉnh sửa sản phẩm: {{ $product->name }}</h1>
    <div class="btn-group">
        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info">
            <i class="fas fa-eye me-2"></i>Xem chi tiết
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                  id="short_description" name="short_description" rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả chi tiết</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="6">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" 
                                   id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="barcode" class="form-label">Mã vạch</label>
                            <input type="text" class="form-control @error('barcode') is-invalid @enderror" 
                                   id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}">
                            @error('barcode')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Giá bán</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="price" class="form-label">Giá gốc <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000" required>
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="sale_price" class="form-label">Giá khuyến mãi</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('sale_price') is-invalid @enderror" 
                                       id="sale_price" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" step="1000">
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('sale_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="cost_price" class="form-label">Giá vốn</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('cost_price') is-invalid @enderror" 
                                       id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" min="0" step="1000">
                                <span class="input-group-text">đ</span>
                            </div>
                            @error('cost_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            @if($product->price && $product->cost_price)
                            <div class="mt-4">
                                @php
                                    $profit = $product->price - $product->cost_price;
                                    $margin = $product->price > 0 ? (($profit / $product->price) * 100) : 0;
                                @endphp
                                <small class="text-muted">
                                    Lợi nhuận: <strong>{{ number_format($profit) }}đ</strong> 
                                    ({{ number_format($margin, 1) }}%)
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inventory -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Quản lý kho</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">                            <label for="stock" class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required>
                            @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="min_quantity" class="form-label">Số lượng tối thiểu</label>
                            <input type="number" class="form-control @error('min_quantity') is-invalid @enderror" 
                                   id="min_quantity" name="min_quantity" value="{{ old('min_quantity', $product->min_quantity) }}" min="0">
                            @error('min_quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="weight" class="form-label">Trọng lượng (gram)</label>
                            <input type="number" class="form-control @error('weight') is-invalid @enderror" 
                                   id="weight" name="weight" value="{{ old('weight', $product->weight) }}" min="0" step="0.01">
                            @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <label for="length" class="form-label">Chiều dài (cm)</label>
                            <input type="number" class="form-control @error('length') is-invalid @enderror" 
                                   id="length" name="length" value="{{ old('length', $product->length) }}" min="0" step="0.01">
                            @error('length')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="width" class="form-label">Chiều rộng (cm)</label>
                            <input type="number" class="form-control @error('width') is-invalid @enderror" 
                                   id="width" name="width" value="{{ old('width', $product->width) }}" min="0" step="0.01">
                            @error('width')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="height" class="form-label">Chiều cao (cm)</label>
                            <input type="number" class="form-control @error('height') is-invalid @enderror" 
                                   id="height" name="height" value="{{ old('height', $product->height) }}" min="0" step="0.01">
                            @error('height')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Images -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Hình ảnh sản phẩm</h5>
                </div>
                <div class="card-body">
                    @if($product->image)
                    <div class="mb-3">
                        <label class="form-label">Hình ảnh hiện tại:</label><br>
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                             class="img-thumbnail" style="max-width: 200px;">
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh chính</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Chỉ chấp nhận file: jpeg, png, jpg, gif. Tối đa 2MB.</div>
                    </div>

                    @if($product->gallery && count($product->gallery) > 0)
                    <div class="mb-3">
                        <label class="form-label">Thư viện ảnh hiện tại:</label>
                        <div class="row">
                            @foreach($product->gallery as $index => $image)
                            <div class="col-md-3 mb-2">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image) }}" alt="Gallery {{ $index + 1 }}" 
                                         class="img-thumbnail w-100" style="height: 100px; object-fit: cover;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                            onclick="removeGalleryImage({{ $index }})">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="gallery" class="form-label">Thêm ảnh vào thư viện</label>
                        <input type="file" class="form-control @error('gallery') is-invalid @enderror" 
                               id="gallery" name="gallery[]" accept="image/*" multiple>
                        @error('gallery')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Có thể chọn nhiều ảnh cùng lúc. Tối đa 10 ảnh, mỗi ảnh tối đa 2MB.</div>
                    </div>

                    <!-- Preview area -->
                    <div id="image-preview" class="row mt-3"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Status & Visibility -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Trạng thái & Hiển thị</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Kích hoạt sản phẩm
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1"
                                {{ old('featured', $product->featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="featured">
                                Sản phẩm nổi bật
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="manage_stock" name="manage_stock" value="1"
                                {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}>
                            <label class="form-check-label" for="manage_stock">
                                Quản lý tồn kho
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="in_stock" name="in_stock" value="1"
                                {{ old('in_stock', $product->in_stock) ? 'checked' : '' }}>
                            <label class="form-check-label" for="in_stock">
                                Còn hàng
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">SEO</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Tiêu đề SEO</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                               id="meta_title" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}">
                        @error('meta_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Mô tả SEO</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $product->meta_description) }}</textarea>
                        @error('meta_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                               id="tags" name="tags" value="{{ old('tags', is_array($product->tags) ? implode(', ', $product->tags) : $product->tags) }}">
                        @error('tags')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Phân tách bằng dấu phẩy (,)</div>
                    </div>
                </div>
            </div>

            <!-- System Info -->
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
                            <th>Cập nhật:</th>
                            <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Lượt xem:</th>
                            <td>{{ $product->views ?? 0 }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật sản phẩm
                        </button>
                        <button type="submit" name="save_and_continue" value="1" class="btn btn-outline-primary">
                            <i class="fas fa-save me-2"></i>Lưu và tiếp tục chỉnh sửa
                        </button>
                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info">
                            <i class="fas fa-eye me-2"></i>Xem chi tiết
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function() {
    previewImage(this, 'main-image-preview');
});

document.getElementById('gallery').addEventListener('change', function() {
    previewGallery(this, 'gallery-preview');
});

function previewImage(input, containerId) {
    const container = document.getElementById(containerId) || createPreviewContainer(containerId);
    container.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-thumbnail';
            img.style.maxWidth = '200px';
            img.style.maxHeight = '200px';
            container.appendChild(img);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewGallery(input, containerId) {
    const container = document.getElementById(containerId) || createPreviewContainer(containerId);
    container.innerHTML = '';
    
    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const col = document.createElement('div');
                col.className = 'col-md-3 mb-2';
                
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'img-thumbnail w-100';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                
                col.appendChild(img);
                container.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }
}

function createPreviewContainer(id) {
    const container = document.createElement('div');
    container.id = id;
    container.className = 'row';
    document.getElementById('image-preview').appendChild(container);
    return container;
}

// Remove gallery image
function removeGalleryImage(index) {
    if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
        // Add hidden input to mark for deletion
        const form = document.querySelector('form');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'remove_gallery[]';
        input.value = index;
        form.appendChild(input);
        
        // Hide the image
        event.target.closest('.col-md-3').style.display = 'none';
    }
}

// Calculate profit margin
document.getElementById('price').addEventListener('input', calculateProfit);
document.getElementById('cost_price').addEventListener('input', calculateProfit);

function calculateProfit() {
    const price = parseFloat(document.getElementById('price').value) || 0;
    const costPrice = parseFloat(document.getElementById('cost_price').value) || 0;
    
    if (price > 0 && costPrice > 0) {
        const profit = price - costPrice;
        const margin = ((profit / price) * 100).toFixed(2);
        
        // You can display this somewhere if needed
        console.log(`Lợi nhuận: ${profit.toLocaleString()}đ (${margin}%)`);
    }
}
</script>
@endpush
