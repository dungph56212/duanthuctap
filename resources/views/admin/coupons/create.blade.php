@extends('admin.layouts.app')

@section('title', 'Thêm mã giảm giá')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Thêm mã giảm giá mới</h1>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-2"></i>Quay lại
    </a>
</div>

<form action="{{ route('admin.coupons.store') }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="code" class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                       id="code" name="code" value="{{ old('code') }}" required style="text-transform: uppercase;">
                                <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                                    <i class="fas fa-random"></i>
                                </button>
                            </div>
                            @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Mã phải duy nhất và từ 3-20 ký tự</div>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label">Tên mã giảm giá <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Discount Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Cài đặt giảm giá</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="type" class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">-- Chọn loại --</option>
                                <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Số tiền cố định (đ)</option>
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label for="value" class="form-label">Giá trị giảm <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                       id="value" name="value" value="{{ old('value') }}" min="0" step="0.01" required>
                                <span class="input-group-text" id="value-unit">%</span>
                            </div>
                            @error('value')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4" id="max-discount-field" style="display: none;">
                            <label for="max_discount_amount" class="form-label">Giảm tối đa (đ)</label>
                            <input type="number" class="form-control @error('max_discount_amount') is-invalid @enderror" 
                                   id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount') }}" min="0" step="1000">
                            @error('max_discount_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Chỉ áp dụng cho giảm theo %</div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="min_order_amount" class="form-label">Đơn hàng tối thiểu (đ)</label>
                            <input type="number" class="form-control @error('min_order_amount') is-invalid @enderror" 
                                   id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount') }}" min="0" step="1000">
                            @error('min_order_amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Để trống = không giới hạn</div>
                        </div>
                        <div class="col-md-6">
                            <label for="usage_limit" class="form-label">Số lần sử dụng tối đa</label>
                            <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                   id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" min="1">
                            @error('usage_limit')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Để trống = không giới hạn</div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="usage_limit_per_user" class="form-label">Mỗi khách hàng tối đa</label>
                            <input type="number" class="form-control @error('usage_limit_per_user') is-invalid @enderror" 
                                   id="usage_limit_per_user" name="usage_limit_per_user" value="{{ old('usage_limit_per_user') }}" min="1">
                            @error('usage_limit_per_user')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Để trống = không giới hạn</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thời gian áp dụng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="starts_at" class="form-label">Ngày bắt đầu</label>
                            <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" 
                                   id="starts_at" name="starts_at" value="{{ old('starts_at') }}">
                            @error('starts_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Để trống = hiệu lực ngay</div>
                        </div>
                        <div class="col-md-6">
                            <label for="expires_at" class="form-label">Ngày hết hạn</label>
                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                            @error('expires_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Để trống = không hết hạn</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product/Category Restrictions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Điều kiện áp dụng (Tùy chọn)</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="applicable_categories" class="form-label">Áp dụng cho danh mục</label>
                            <select class="form-select @error('applicable_categories') is-invalid @enderror" 
                                    id="applicable_categories" name="applicable_categories[]" multiple>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ in_array($category->id, old('applicable_categories', [])) ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('applicable_categories')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Để trống = áp dụng cho tất cả</div>
                        </div>
                        <div class="col-md-6">
                            <label for="applicable_products" class="form-label">Áp dụng cho sản phẩm</label>
                            <select class="form-select @error('applicable_products') is-invalid @enderror" 
                                    id="applicable_products" name="applicable_products[]" multiple>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ in_array($product->id, old('applicable_products', [])) ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('applicable_products')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Để trống = áp dụng cho tất cả</div>
                        </div>
                    </div>
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
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Kích hoạt mã giảm giá
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1"
                                {{ old('is_public') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">
                                Hiển thị công khai
                            </label>
                        </div>
                        <div class="form-text">Khách hàng có thể tìm thấy mã này mà không cần biết mã</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="auto_apply" name="auto_apply" value="1"
                                {{ old('auto_apply') ? 'checked' : '' }}>
                            <label class="form-check-label" for="auto_apply">
                                Tự động áp dụng
                            </label>
                        </div>
                        <div class="form-text">Tự động áp dụng khi đơn hàng đủ điều kiện</div>
                    </div>
                </div>
            </div>

            <!-- Quick Templates -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Mẫu nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="applyTemplate('new_customer')">
                            Khách hàng mới
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="applyTemplate('bulk_order')">
                            Đơn hàng lớn
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="applyTemplate('weekend')">
                            Cuối tuần
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="applyTemplate('flash_sale')">
                            Flash Sale
                        </button>
                    </div>
                </div>
            </div>

            <!-- Preview -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Xem trước</h6>
                </div>
                <div class="card-body">
                    <div id="coupon-preview" class="border rounded p-3 text-center bg-light">
                        <h6 class="mb-1">Mã giảm giá</h6>
                        <code class="fs-5" id="preview-code">NEWCODE</code>
                        <div class="mt-2">
                            <span class="badge bg-primary" id="preview-value">0%</span>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted" id="preview-description">Mô tả mã giảm giá</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Tạo mã giảm giá
                        </button>
                        <button type="submit" name="save_and_continue" value="1" class="btn btn-outline-primary">
                            <i class="fas fa-save me-2"></i>Lưu và tiếp tục chỉnh sửa
                        </button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Hủy
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
// Generate random coupon code
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    for (let i = 0; i < 8; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('code').value = result;
    updatePreview();
}

// Update discount type UI
document.getElementById('type').addEventListener('change', function() {
    const type = this.value;
    const unit = document.getElementById('value-unit');
    const maxDiscountField = document.getElementById('max-discount-field');
    
    if (type === 'percentage') {
        unit.textContent = '%';
        maxDiscountField.style.display = 'block';
        document.getElementById('value').max = '100';
    } else if (type === 'fixed') {
        unit.textContent = 'đ';
        maxDiscountField.style.display = 'none';
        document.getElementById('value').max = '';
    }
    updatePreview();
});

// Update preview
function updatePreview() {
    const code = document.getElementById('code').value || 'NEWCODE';
    const type = document.getElementById('type').value;
    const value = document.getElementById('value').value || '0';
    const description = document.getElementById('description').value || 'Mô tả mã giảm giá';
    
    document.getElementById('preview-code').textContent = code;
    document.getElementById('preview-description').textContent = description;
    
    let displayValue = value;
    if (type === 'percentage') {
        displayValue = value + '%';
    } else if (type === 'fixed') {
        displayValue = parseInt(value).toLocaleString() + 'đ';
    }
    document.getElementById('preview-value').textContent = displayValue;
}

// Apply predefined templates
function applyTemplate(template) {
    switch(template) {
        case 'new_customer':
            document.getElementById('code').value = 'WELCOME' + Math.floor(Math.random() * 100);
            document.getElementById('name').value = 'Chào mừng khách hàng mới';
            document.getElementById('description').value = 'Giảm giá cho khách hàng đăng ký lần đầu';
            document.getElementById('type').value = 'percentage';
            document.getElementById('value').value = '10';
            document.getElementById('usage_limit_per_user').value = '1';
            break;
            
        case 'bulk_order':
            document.getElementById('code').value = 'BULK' + Math.floor(Math.random() * 100);
            document.getElementById('name').value = 'Giảm giá đơn hàng lớn';
            document.getElementById('description').value = 'Giảm giá cho đơn hàng từ 1,000,000đ';
            document.getElementById('type').value = 'fixed';
            document.getElementById('value').value = '100000';
            document.getElementById('min_order_amount').value = '1000000';
            break;
            
        case 'weekend':
            document.getElementById('code').value = 'WEEKEND' + Math.floor(Math.random() * 100);
            document.getElementById('name').value = 'Khuyến mãi cuối tuần';
            document.getElementById('description').value = 'Giảm giá đặc biệt cuối tuần';
            document.getElementById('type').value = 'percentage';
            document.getElementById('value').value = '15';
            document.getElementById('max_discount_amount').value = '200000';
            break;
            
        case 'flash_sale':
            document.getElementById('code').value = 'FLASH' + Math.floor(Math.random() * 100);
            document.getElementById('name').value = 'Flash Sale';
            document.getElementById('description').value = 'Giảm giá nhanh - số lượng có hạn';
            document.getElementById('type').value = 'percentage';
            document.getElementById('value').value = '25';
            document.getElementById('usage_limit').value = '100';
            document.getElementById('max_discount_amount').value = '500000';
            break;
    }
    
    // Trigger change events
    document.getElementById('type').dispatchEvent(new Event('change'));
    updatePreview();
}

// Auto-update preview when inputs change
document.getElementById('code').addEventListener('input', updatePreview);
document.getElementById('value').addEventListener('input', updatePreview);
document.getElementById('description').addEventListener('input', updatePreview);

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Set default date/time if not set
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    
    if (!document.getElementById('starts_at').value) {
        document.getElementById('starts_at').value = now.toISOString().slice(0, 16);
    }
    
    updatePreview();
});
</script>
@endpush
