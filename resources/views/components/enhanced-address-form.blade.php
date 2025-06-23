<!-- Enhanced Address Selector Component -->
<div class="address-selector-component" data-prefix="{{ $prefix }}">
    @if(isset($existingAddresses) && $existingAddresses->count() > 0)
        <!-- Address Selection Mode -->
        <div class="address-selection-mode mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">
                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                    Chọn địa chỉ giao hàng
                </h6>
                <div class="btn-group btn-group-sm" role="group">
                    <input type="radio" class="btn-check" name="{{ $prefix }}address_mode" id="{{ $prefix }}existing" value="existing" checked>
                    <label class="btn btn-outline-primary" for="{{ $prefix }}existing">
                        <i class="fas fa-list me-1"></i>Chọn có sẵn
                    </label>

                    <input type="radio" class="btn-check" name="{{ $prefix }}address_mode" id="{{ $prefix }}new" value="new">
                    <label class="btn btn-outline-success" for="{{ $prefix }}new">
                        <i class="fas fa-plus me-1"></i>Thêm mới
                    </label>
                </div>
            </div>

            <!-- Existing Address Selection -->
            <div class="existing-addresses" id="{{ $prefix }}existing_addresses">
                <div class="row">
                    @foreach($existingAddresses as $address)
                        <div class="col-md-6 mb-3">
                            <div class="address-card" data-address-id="{{ $address->id }}">
                                <input type="radio" 
                                       class="btn-check address-radio" 
                                       name="{{ $prefix }}selected_address_id" 
                                       id="{{ $prefix }}addr_{{ $address->id }}" 
                                       value="{{ $address->id }}"
                                       {{ $loop->first ? 'checked' : '' }}
                                       data-name="{{ $address->name }}"
                                       data-phone="{{ $address->phone }}"
                                       data-province="{{ $address->ten_tinh }}"
                                       data-district="{{ $address->ten_quan }}"
                                       data-ward="{{ $address->ten_phuong }}"
                                       data-address-line="{{ $address->address_line }}"
                                       data-type="{{ $address->type }}">
                                <label class="btn btn-outline-secondary w-100 text-start address-label" for="{{ $prefix }}addr_{{ $address->id }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="fw-bold d-flex align-items-center">
                                                {{ $address->name }}
                                                @if($address->is_default)
                                                    <span class="badge bg-warning text-dark ms-2">
                                                        <i class="fas fa-star"></i> Mặc định
                                                    </span>
                                                @endif
                                                <span class="badge bg-info ms-2">
                                                    {{ $address->type == 'shipping' ? 'Giao hàng' : 'Thanh toán' }}
                                                </span>
                                            </div>
                                            <div class="text-muted small mt-1">
                                                <i class="fas fa-phone me-1"></i>{{ $address->phone }}
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $address->address_line }}, {{ $address->ten_phuong }}, {{ $address->ten_quan }}, {{ $address->ten_tinh }}
                                            </div>
                                        </div>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-primary edit-address-btn" 
                                                data-address-id="{{ $address->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    </div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- New Address Form -->
    <div class="new-address-form" id="{{ $prefix }}new_address_form" style="{{ isset($existingAddresses) && $existingAddresses->count() > 0 ? 'display: none;' : '' }}">
        <h6 class="mb-3">
            <i class="fas fa-plus-circle text-success me-2"></i>
            {{ isset($existingAddresses) && $existingAddresses->count() > 0 ? 'Thêm địa chỉ mới' : 'Thông tin địa chỉ' }}
        </h6>

        <!-- Contact Information -->
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="{{ $prefix }}name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control @error($prefix.'name') is-invalid @enderror" 
                       id="{{ $prefix }}name" 
                       name="{{ $prefix }}name" 
                       value="{{ old($prefix.'name', $defaultName ?? '') }}" 
                       placeholder="Nhập họ và tên"
                       required>
                @error($prefix.'name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6 mb-3">
                <label for="{{ $prefix }}phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                <input type="tel" 
                       class="form-control @error($prefix.'phone') is-invalid @enderror" 
                       id="{{ $prefix }}phone" 
                       name="{{ $prefix }}phone" 
                       value="{{ old($prefix.'phone', $defaultPhone ?? '') }}" 
                       placeholder="0912345678"
                       pattern="^[0-9]{10,11}$"
                       required>
                @error($prefix.'phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Address Selection with Smart Search -->
        <div class="address-selector mb-4">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="{{ $prefix }}province" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                    <select class="form-select address-select @error($prefix.'province') is-invalid @enderror" 
                            id="{{ $prefix }}province" 
                            name="{{ $prefix }}province" 
                            data-target="district"
                            data-prefix="{{ $prefix }}"
                            required>
                        <option value="">Chọn Tỉnh/Thành phố</option>
                    </select>
                    @error($prefix.'province')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="{{ $prefix }}district" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                    <select class="form-select address-select @error($prefix.'district') is-invalid @enderror" 
                            id="{{ $prefix }}district" 
                            name="{{ $prefix }}district" 
                            data-target="ward"
                            data-prefix="{{ $prefix }}"
                            disabled
                            required>
                        <option value="">Chọn Quận/Huyện</option>
                    </select>
                    @error($prefix.'district')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="{{ $prefix }}ward" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                    <select class="form-select address-select @error($prefix.'ward') is-invalid @enderror" 
                            id="{{ $prefix }}ward" 
                            name="{{ $prefix }}ward" 
                            data-prefix="{{ $prefix }}"
                            disabled
                            required>
                        <option value="">Chọn Phường/Xã</option>
                    </select>
                    @error($prefix.'ward')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Detailed Address -->
        <div class="mb-3">
            <label for="{{ $prefix }}address_line" class="form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                <input type="text" 
                       class="form-control @error($prefix.'address_line') is-invalid @enderror" 
                       id="{{ $prefix }}address_line" 
                       name="{{ $prefix }}address_line" 
                       value="{{ old($prefix.'address_line') }}" 
                       placeholder="Số nhà, tên đường, toà nhà..."
                       required>
            </div>
            @error($prefix.'address_line')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @if(isset($showAddressType) && $showAddressType)
            <!-- Address Type -->
            <div class="mb-3">
                <label class="form-label">Loại địa chỉ</label>
                <div class="btn-group w-100" role="group">
                    <input type="radio" 
                           class="btn-check" 
                           name="{{ $prefix }}type" 
                           id="{{ $prefix }}shipping" 
                           value="shipping" 
                           {{ old($prefix.'type', 'shipping') === 'shipping' ? 'checked' : '' }}>
                    <label class="btn btn-outline-primary" for="{{ $prefix }}shipping">
                        <i class="fas fa-shipping-fast me-2"></i>Giao hàng
                    </label>

                    <input type="radio" 
                           class="btn-check" 
                           name="{{ $prefix }}type" 
                           id="{{ $prefix }}billing" 
                           value="billing"
                           {{ old($prefix.'type') === 'billing' ? 'checked' : '' }}>
                    <label class="btn btn-outline-success" for="{{ $prefix }}billing">
                        <i class="fas fa-credit-card me-2"></i>Thanh toán
                    </label>
                </div>
            </div>
        @endif

        @if(isset($showDefaultOption) && $showDefaultOption)
            <!-- Default Address Option -->
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="{{ $prefix }}is_default" 
                           id="{{ $prefix }}is_default" 
                           value="1"
                           {{ old($prefix.'is_default') ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $prefix }}is_default">
                        <i class="fas fa-star text-warning me-1"></i> Đặt làm địa chỉ mặc định
                    </label>
                </div>
            </div>
        @endif

        @if(isset($showSaveOption) && $showSaveOption)
            <!-- Save Address Option for Checkout -->
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" 
                           type="checkbox" 
                           name="{{ $prefix }}save_address" 
                           id="{{ $prefix }}save_address" 
                           value="1"
                           {{ old($prefix.'save_address', true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $prefix }}save_address">
                        <i class="fas fa-bookmark text-info me-1"></i> Lưu địa chỉ này để sử dụng lần sau
                    </label>
                </div>
            </div>
        @endif
    </div>

    <!-- Address Preview -->
    <div class="address-preview mt-3" id="{{ $prefix }}address_preview" style="display: none;">
        <div class="alert alert-info border-0">
            <h6 class="mb-2"><i class="fas fa-eye me-2"></i>Địa chỉ giao hàng:</h6>
            <div id="{{ $prefix }}preview_content"></div>
        </div>
    </div>
</div>

<style>
.address-selector-component .address-card {
    transition: all 0.3s ease;
}

.address-selector-component .address-label {
    transition: all 0.3s ease;
    border: 2px solid #dee2e6;
    min-height: 100px;
    align-items: flex-start;
    text-align: left;
}

.address-selector-component .address-radio:checked + .address-label {
    border-color: var(--bs-primary);
    background-color: rgba(13, 110, 253, 0.1);
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.address-selector-component .address-label:hover {
    border-color: var(--bs-primary);
    background-color: rgba(13, 110, 253, 0.05);
}

.address-selector-component .edit-address-btn {
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.address-selector-component .address-card:hover .edit-address-btn {
    opacity: 1;
}

.address-selector-component .form-select:disabled {
    background-color: #f8f9fa;
    opacity: 0.6;
}

.address-selector-component .btn-group .btn-check:checked + .btn {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
    color: white;
}

.new-address-form {
    transition: all 0.3s ease;
}

.address-preview {
    transition: all 0.3s ease;
}

.address-preview.show {
    display: block !important;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
