@extends('client.layouts.app')

@section('title', 'Thanh toán')
@section('description', 'Hoàn tất đơn hàng của bạn tại BookStore với quy trình thanh toán đơn giản và bảo mật.')

@section('content')
<div class="container py-5">
    <!-- Progress Steps -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="checkout-progress">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="step completed">
                        <div class="step-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="step-title">Giỏ hàng</div>
                    </div>
                    <div class="step-line completed"></div>
                    <div class="step active">
                        <div class="step-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="step-title">Thanh toán</div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step">
                        <div class="step-icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="step-title">Hoàn tất</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('client.checkout.store') }}" method="POST" id="checkout-form">
        @csrf
        <div class="row">
            <!-- Customer Information -->
            <div class="col-lg-8">                <!-- Shipping Information -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-shipping-fast me-2 text-primary"></i>
                            Thông tin giao hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Email Field (standalone) -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', auth()->user()->email ?? '') }}" 
                                       placeholder="your@email.com"
                                       required>
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Enhanced Address Form Component -->
                        @component('components.enhanced-address-form', [
                            'prefix' => 'shipping_',
                            'defaultName' => auth()->user()->name ?? '',
                            'defaultPhone' => auth()->user()->phone ?? '',
                            'existingAddresses' => $addresses ?? collect(),
                            'showSaveOption' => auth()->check()
                        ])
                        @endcomponent

                        <!-- Order Notes -->
                        <div class="mt-4">
                            <label for="notes" class="form-label">Ghi chú đơn hàng</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                <textarea class="form-control" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3" 
                                          placeholder="Ghi chú về đơn hàng, yêu cầu đặc biệt (tùy chọn)">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="quan" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select class="form-select" id="quan" name="quan" required>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                                <input type="hidden" id="ten_quan" name="ten_quan">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phuong" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select class="form-select" id="phuong" name="phuong" required>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                                <input type="hidden" id="ten_phuong" name="ten_phuong">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ cụ thể <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('address') is-invalid @enderror" 
                                   id="address" 
                                   name="address" 
                                   value="{{ old('address') }}" 
                                   placeholder="Số nhà, tên đường, phường/xã, quận/huyện" 
                                   required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú đơn hàng</label>
                            <textarea class="form-control" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Ghi chú về đơn hàng (tùy chọn)">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-credit-card me-2 text-primary"></i>
                            Phương thức thanh toán
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="payment-methods">
                            <div class="form-check mb-3">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="payment_method" 
                                       id="cod" 
                                       value="cod" 
                                       {{ old('payment_method', 'cod') == 'cod' ? 'checked' : '' }}>
                                <label class="form-check-label d-flex align-items-center" for="cod">
                                    <i class="fas fa-money-bill-wave me-3 text-success"></i>
                                    <div>
                                        <strong>Thanh toán khi nhận hàng (COD)</strong>
                                        <small class="d-block text-muted">Thanh toán bằng tiền mặt khi nhận được hàng</small>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="payment_method" 
                                       id="bank_transfer" 
                                       value="bank_transfer" 
                                       {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                                <label class="form-check-label d-flex align-items-center" for="bank_transfer">
                                    <i class="fas fa-university me-3 text-primary"></i>
                                    <div>
                                        <strong>Chuyển khoản ngân hàng</strong>
                                        <small class="d-block text-muted">Chuyển khoản trước khi giao hàng</small>
                                    </div>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="radio" 
                                       name="payment_method" 
                                       id="momo" 
                                       value="momo" 
                                       {{ old('payment_method') == 'momo' ? 'checked' : '' }}>
                                <label class="form-check-label d-flex align-items-center" for="momo">
                                    <i class="fas fa-mobile-alt me-3 text-danger"></i>
                                    <div>
                                        <strong>Ví điện tử MoMo</strong>
                                        <small class="d-block text-muted">Thanh toán qua ví điện tử MoMo</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coupon -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-tags me-2 text-primary"></i>
                            Mã giảm giá
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8 mb-3 mb-md-0">
                                <input type="text" 
                                       class="form-control" 
                                       id="coupon_code" 
                                       name="coupon_code" 
                                       value="{{ old('coupon_code') }}" 
                                       placeholder="Nhập mã giảm giá">
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-outline-primary w-100" id="apply-coupon">
                                    <i class="fas fa-check me-2"></i>Áp dụng
                                </button>
                            </div>
                        </div>
                        <div id="coupon-result" class="mt-3"></div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-bag me-2 text-primary"></i>
                            Đơn hàng của bạn
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Cart Items -->
                        <div class="order-items mb-3">
                            @foreach($cartItems as $item)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <img src="{{ $item->product->images && count($item->product->images) > 0 ? Storage::url($item->product->images[0]) : 'https://via.placeholder.com/60x80' }}" 
                                         class="me-3 rounded" 
                                         width="50" height="60" 
                                         style="object-fit: cover;" 
                                         alt="{{ $item->product->name }}">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ Str::limit($item->product->name, 30) }}</h6>
                                        <small class="text-muted">
                                            {{ number_format($item->price) }}đ × {{ $item->quantity }}
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <strong>{{ number_format($item->price * $item->quantity) }}đ</strong>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="order-summary">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tạm tính:</span>
                                <span id="subtotal">{{ number_format($subtotal) }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Phí vận chuyển:</span>
                                <span id="shipping">{{ number_format($shipping) }}đ</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2" id="discount-row" style="display: none !important;">
                                <span class="text-success">Giảm giá:</span>
                                <span class="text-success" id="discount">0đ</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Tổng cộng:</strong>
                                <strong class="text-danger" id="total">{{ number_format($total) }}đ</strong>
                            </div>
                        </div>

                        <!-- Hidden inputs for totals -->
                        <input type="hidden" name="subtotal" value="{{ $subtotal }}">
                        <input type="hidden" name="shipping" value="{{ $shipping }}">
                        <input type="hidden" name="discount" value="0" id="discount-input">
                        <input type="hidden" name="total" value="{{ $total }}" id="total-input">

                        <!-- Place Order Button -->
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-check-circle me-2"></i>
                            Đặt hàng
                        </button>

                        <!-- Security Info -->
                        <div class="mt-3 text-center">
                            <small class="text-muted">
                                <i class="fas fa-lock me-1"></i>
                                Thông tin của bạn được bảo mật an toàn
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@if(session('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header">
                <strong class="me-auto text-danger">Lỗi</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif
@endsection

@push('styles')
<style>
.checkout-progress {
    margin-bottom: 2rem;
}

.checkout-progress .step {
    text-align: center;
    position: relative;
}

.checkout-progress .step-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.5rem;
    font-size: 1.2rem;
}

.checkout-progress .step.active .step-icon {
    background: var(--primary-color);
    color: white;
}

.checkout-progress .step.completed .step-icon {
    background: #28a745;
    color: white;
}

.checkout-progress .step-title {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 500;
}

.checkout-progress .step.active .step-title {
    color: var(--primary-color);
    font-weight: 600;
}

.checkout-progress .step.completed .step-title {
    color: #28a745;
    font-weight: 600;
}

.checkout-progress .step-line {
    height: 2px;
    background: #e9ecef;
    flex: 1;
    align-self: center;
    margin: 0 1rem;
}

.checkout-progress .step-line.completed {
    background: #28a745;
}

.payment-methods .form-check {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.3s ease;
}

.payment-methods .form-check:hover {
    border-color: var(--primary-color);
    background-color: #f8f9fa;
}

.payment-methods .form-check-input:checked + .form-check-label {
    color: var(--primary-color);
}

.order-items {
    max-height: 300px;
    overflow-y: auto;
}

@media (max-width: 768px) {
    .checkout-progress .step-line {
        margin: 0 0.5rem;
    }
    
    .checkout-progress .step-title {
        font-size: 0.8rem;
    }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/address-form.js') }}"></script>
<script src="{{ asset('js/enhanced-address-form.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize enhanced address form with shipping prefix
    const addressHandler = new EnhancedAddressFormHandler('shipping_');
    
    // Enhanced checkout form validation
    const checkoutForm = document.getElementById('checkout-form');
    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function(e) {
            // Validate address form
            const addressValidation = addressHandler.validateForm();
            
            // Validate email
            const emailField = document.getElementById('email');
            const emailValid = emailField && emailField.value && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value);
            
            // Validate payment method
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
            
            const errors = [];
            
            if (!emailValid) {
                errors.push('Email không hợp lệ');
                if (emailField) emailField.classList.add('is-invalid');
            }
            
            if (!paymentMethod) {
                errors.push('Vui lòng chọn phương thức thanh toán');
            }
            
            if (!addressValidation.isValid) {
                errors.push(...addressValidation.errors);
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                
                // Show errors with alert
                showToast(errors.join('<br>'), 'error');
                
                // Focus on first invalid field
                const firstInvalid = checkoutForm.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
                
                return false;
            }
            
            // Show loading on submit
            const submitBtn = checkoutForm.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
                submitBtn.disabled = true;
            }
        });
    }

    // Toast notification function
    function showToast(message, type = 'info') {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'info' ? 'primary' : type === 'error' ? 'danger' : 'success'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = toastContainer.lastElementChild;
        const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
        toast.show();
        
        // Remove element after hide
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }

    // Apply coupon
    document.getElementById('apply-coupon').addEventListener('click', function() {
        const couponCode = document.getElementById('coupon_code').value;
        const resultDiv = document.getElementById('coupon-result');
        
        if (!couponCode) {
            resultDiv.innerHTML = '<div class="alert alert-warning alert-sm">Vui lòng nhập mã giảm giá</div>';
            return;
        }
            return;
        }

        // Show loading
        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
        this.disabled = true;

        // Make AJAX request
        fetch('{{ route("client.checkout.apply-coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                coupon_code: couponCode,
                subtotal: {{ $subtotal }}
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update discount
                const discountAmount = data.discount;
                const newTotal = {{ $subtotal }} + {{ $shipping }} - discountAmount;
                
                document.getElementById('discount').textContent = number_format(discountAmount) + 'đ';
                document.getElementById('total').textContent = number_format(newTotal) + 'đ';
                document.getElementById('discount-input').value = discountAmount;
                document.getElementById('total-input').value = newTotal;
                document.getElementById('discount-row').style.display = 'flex';
                
                resultDiv.innerHTML = '<div class="alert alert-success alert-sm">Áp dụng mã giảm giá thành công!</div>';
            } else {
                resultDiv.innerHTML = '<div class="alert alert-danger alert-sm">' + data.message + '</div>';
            }
        })
        .catch(error => {
            resultDiv.innerHTML = '<div class="alert alert-danger alert-sm">Có lỗi xảy ra, vui lòng thử lại</div>';
        })
        .finally(() => {
            this.innerHTML = '<i class="fas fa-check me-2"></i>Áp dụng';
            this.disabled = false;
        });
    });    // Form validation
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc');
        }
    });    // Load địa chỉ từ API
    console.log('Loading provinces...');
    
    // Kiểm tra xem jQuery có sẵn hay không
    if (typeof $ === 'undefined') {
        console.error('jQuery is not loaded!');
        alert('Trang web chưa tải đầy đủ, vui lòng tải lại trang.');
        return;
    }
    
    // Lấy tỉnh thành
    $.getJSON('https://provinces.open-api.vn/api/p/')
        .done(function(provinces) {
            console.log('Provinces loaded:', provinces.length);
            provinces.forEach(function(province) {
                $("#tinh").append(`<option value="${province.code}">${province.name}</option>`);
            });
        })        .fail(function(jqxhr, textStatus, error) {
            console.error('Error loading provinces:', textStatus, error);
            console.log('Using fallback provinces...');
            
            showToast('Đang sử dụng danh sách tỉnh thành cơ bản do lỗi kết nối', 'info');
            
            // Fallback data nếu API bị lỗi
            const fallbackProvinces = [
                {code: "01", name: "Hà Nội"},
                {code: "79", name: "TP. Hồ Chí Minh"},
                {code: "48", name: "Đà Nẵng"},
                {code: "92", name: "Cần Thơ"},
                {code: "31", name: "Hải Phòng"},
                {code: "89", name: "An Giang"},
                {code: "77", name: "Bà Rịa - Vũng Tàu"},
                {code: "24", name: "Bắc Giang"},
                {code: "06", name: "Bắc Kạn"},
                {code: "95", name: "Bạc Liêu"}
            ];

            fallbackProvinces.forEach(function(province) {
                $("#tinh").append(`<option value="${province.code}">${province.name}</option>`);
            });
        });// Xử lý khi chọn tỉnh
    $("#tinh").change(function() {
        const provinceCode = $(this).val();
        const provinceName = $(this).find("option:selected").text();
        
        console.log('Province selected:', provinceCode, provinceName);
        
        // Cập nhật tên tỉnh vào hidden input
        $("#ten_tinh").val(provinceName);
        
        // Reset quận và phường
        $("#quan").html('<option value="">Chọn Quận/Huyện</option>');
        $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
        $("#ten_quan").val('');
        $("#ten_phuong").val('');
        
        if (provinceCode) {
            console.log('Loading districts for province:', provinceCode);
            // Lấy quận/huyện
            $.getJSON(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                .done(function(provinceData) {
                    console.log('Districts loaded:', provinceData.districts.length);
                    provinceData.districts.forEach(function(district) {
                        $("#quan").append(`<option value="${district.code}">${district.name}</option>`);
                    });
                })                .fail(function(jqxhr, textStatus, error) {
                    console.error('Error loading districts:', textStatus, error);
                    console.log('Using fallback districts for province:', provinceCode);
                    
                    showToast('Đang sử dụng danh sách quận/huyện cơ bản', 'info');
                    
                    // Fallback districts cho các tỉnh thành phổ biến
                    let fallbackDistricts = [];
                    
                    if (provinceCode === "01") { // Hà Nội
                        fallbackDistricts = [
                            {code: "001", name: "Ba Đình"},
                            {code: "002", name: "Hoàn Kiếm"},
                            {code: "003", name: "Tây Hồ"},
                            {code: "004", name: "Long Biên"},
                            {code: "005", name: "Cầu Giấy"},
                            {code: "006", name: "Đống Đa"},
                            {code: "007", name: "Hai Bà Trưng"},
                            {code: "008", name: "Hoàng Mai"},
                            {code: "009", name: "Thanh Xuân"},
                            {code: "010", name: "Hà Đông"}
                        ];
                    } else if (provinceCode === "79") { // TP.HCM
                        fallbackDistricts = [
                            {code: "760", name: "Quận 1"},
                            {code: "761", name: "Quận 2"},
                            {code: "762", name: "Quận 3"},
                            {code: "763", name: "Quận 4"},
                            {code: "764", name: "Quận 5"},
                            {code: "765", name: "Quận 6"},
                            {code: "766", name: "Quận 7"},
                            {code: "767", name: "Quận 8"},
                            {code: "768", name: "Quận 9"},
                            {code: "769", name: "Quận 10"},
                            {code: "770", name: "Quận 11"},
                            {code: "771", name: "Quận 12"},
                            {code: "772", name: "Bình Thạnh"},
                            {code: "773", name: "Gò Vấp"},
                            {code: "774", name: "Phú Nhuận"},
                            {code: "775", name: "Tân Bình"},
                            {code: "776", name: "Tân Phú"},
                            {code: "777", name: "Thủ Đức"}
                        ];
                    } else if (provinceCode === "48") { // Đà Nẵng
                        fallbackDistricts = [
                            {code: "490", name: "Hải Châu"},
                            {code: "491", name: "Cẩm Lệ"},
                            {code: "492", name: "Thanh Khê"},
                            {code: "493", name: "Liên Chiểu"},
                            {code: "494", name: "Ngũ Hành Sơn"},
                            {code: "495", name: "Sơn Trà"}
                        ];
                    } else {
                        // Fallback chung cho các tỉnh khác
                        fallbackDistricts = [
                            {code: "001", name: "Trung tâm thành phố"},
                            {code: "002", name: "Quận/Huyện 1"},
                            {code: "003", name: "Quận/Huyện 2"},
                            {code: "004", name: "Quận/Huyện 3"}
                        ];
                    }
                    
                    fallbackDistricts.forEach(function(district) {
                        $("#quan").append(`<option value="${district.code}">${district.name}</option>`);
                    });
                    
                    // Hiển thị thông báo nhẹ thay vì alert
                    console.log('Loaded', fallbackDistricts.length, 'fallback districts');
                });
        }
    });    // Xử lý khi chọn quận
    $("#quan").change(function() {
        const districtCode = $(this).val();
        const districtName = $(this).find("option:selected").text();
        
        console.log('District selected:', districtCode, districtName);
        
        // Cập nhật tên quận vào hidden input
        $("#ten_quan").val(districtName);
        
        // Reset phường
        $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
        $("#ten_phuong").val('');
        
        if (districtCode) {
            console.log('Loading wards for district:', districtCode);
            // Lấy phường/xã
            $.getJSON(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                .done(function(districtData) {
                    console.log('Wards loaded:', districtData.wards.length);
                    districtData.wards.forEach(function(ward) {
                        $("#phuong").append(`<option value="${ward.code}">${ward.name}</option>`);
                    });
                })                .fail(function(jqxhr, textStatus, error) {
                    console.error('Error loading wards:', textStatus, error);
                    console.log('Using fallback wards for district:', districtCode);
                    
                    showToast('Đang sử dụng danh sách phường/xã cơ bản', 'info');
                    
                    // Fallback wards
                    const fallbackWards = [
                        {code: "00001", name: "Phường 1"},
                        {code: "00002", name: "Phường 2"},
                        {code: "00003", name: "Phường 3"},
                        {code: "00004", name: "Phường 4"},
                        {code: "00005", name: "Phường 5"},
                        {code: "00006", name: "Phường 6"},
                        {code: "00007", name: "Phường 7"},
                        {code: "00008", name: "Phường 8"},
                        {code: "00009", name: "Phường 9"},
                        {code: "00010", name: "Phường 10"}
                    ];
                    
                    fallbackWards.forEach(function(ward) {
                        $("#phuong").append(`<option value="${ward.code}">${ward.name}</option>`);
                    });
                    
                    console.log('Loaded', fallbackWards.length, 'fallback wards');                });
        }
    });

    // Xử lý khi chọn phường
    $("#phuong").change(function() {
        const wardCode = $(this).val();
        const wardName = $(this).find("option:selected").text();
        console.log('Ward selected:', wardCode, wardName);
        
        // Cập nhật tên phường vào hidden input
        $("#ten_phuong").val(wardName);
    });
});

function number_format(number) {
    return new Intl.NumberFormat('vi-VN').format(number);
}
</script>
@endpush
