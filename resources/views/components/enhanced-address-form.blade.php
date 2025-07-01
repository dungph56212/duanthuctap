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
                                       {{ $loop->first ? 'checked' : '' }}                                       data-name="{{ $address->first_name }} {{ $address->last_name }}"
                                       data-phone="{{ $address->phone }}"
                                       data-province="{{ $address->country }}"
                                       data-district="{{ $address->state }}"
                                       data-ward="{{ $address->city }}"
                                       data-address-line="{{ $address->address_line_1 }}"
                                       data-type="{{ $address->type }}">
                                <label class="btn btn-outline-secondary w-100 text-start address-label" for="{{ $prefix }}addr_{{ $address->id }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">                                            <div class="fw-bold d-flex align-items-center">
                                                {{ $address->first_name }} {{ $address->last_name }}
                                                @if($address->is_default)
                                                    <span class="badge bg-warning text-dark ms-2">
                                                        <i class="fas fa-star"></i> Mặc định
                                                    </span>
                                                @endif
                                                <span class="badge bg-info ms-2">
                                                    {{ $address->type == 'Giao hàng' ? 'Giao hàng' : 'Thanh toán' }}
                                                </span>
                                            </div>
                                            <div class="text-muted small mt-1">
                                                <i class="fas fa-phone me-1"></i>{{ $address->phone }}
                                            </div>
                                            <div class="text-muted small">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $address->address_line_1 }}, {{ $address->city }}, {{ $address->state }}, {{ $address->country }}
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
        </div>        <!-- Address Input Mode Selection -->
        <div class="mb-3">
            <div class="btn-group w-100" role="group">
                <input type="radio" 
                       class="btn-check" 
                       name="{{ $prefix }}input_mode" 
                       id="{{ $prefix }}dropdown_mode" 
                       value="dropdown" 
                       checked>
                <label class="btn btn-outline-primary" for="{{ $prefix }}dropdown_mode">
                    <i class="fas fa-list me-2"></i>Chọn từ danh sách
                </label>

                <input type="radio" 
                       class="btn-check" 
                       name="{{ $prefix }}input_mode" 
                       id="{{ $prefix }}manual_mode" 
                       value="manual">
                <label class="btn btn-outline-success" for="{{ $prefix }}manual_mode">
                    <i class="fas fa-keyboard me-2"></i>Nhập tay
                </label>
            </div>
        </div>

        <!-- Address Selection with Smart Search (Dropdown Mode) -->
        <div class="address-selector mb-4" id="{{ $prefix }}dropdown_container">
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

        <!-- Manual Address Input -->
        <div class="manual-address-input mb-4" id="{{ $prefix }}manual_container" style="display: none;">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="{{ $prefix }}province_manual" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marked-alt"></i></span>
                        <input type="text" 
                               class="form-control @error($prefix.'province_manual') is-invalid @enderror" 
                               id="{{ $prefix }}province_manual" 
                               name="{{ $prefix }}province_manual" 
                               value="{{ old($prefix.'province_manual') }}" 
                               placeholder="Ví dụ: Hà Nội, TP. Hồ Chí Minh..."
                               disabled>
                    </div>
                    @error($prefix.'province_manual')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="{{ $prefix }}district_manual" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                        <input type="text" 
                               class="form-control @error($prefix.'district_manual') is-invalid @enderror" 
                               id="{{ $prefix }}district_manual" 
                               name="{{ $prefix }}district_manual" 
                               value="{{ old($prefix.'district_manual') }}" 
                               placeholder="Ví dụ: Quận 1, Huyện Gia Lâm..."
                               disabled>
                    </div>
                    @error($prefix.'district_manual')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4 mb-3">
                    <label for="{{ $prefix }}ward_manual" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                        <input type="text" 
                               class="form-control @error($prefix.'ward_manual') is-invalid @enderror" 
                               id="{{ $prefix }}ward_manual" 
                               name="{{ $prefix }}ward_manual" 
                               value="{{ old($prefix.'ward_manual') }}" 
                               placeholder="Ví dụ: Phường Bến Nghé, Xã Dương Hà..."
                               disabled>
                    </div>
                    @error($prefix.'ward_manual')
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

.manual-address-input {
    transition: all 0.3s ease;
}

.manual-address-input input {
    background-color: #f8f9fa;
}

.manual-address-input input:enabled {
    background-color: #ffffff;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const prefix = '{{ $prefix }}';
    console.log('Initializing address form with prefix:', prefix);
    
    // Xử lý chuyển đổi giữa "Chọn có sẵn" và "Thêm mới"
    const existingModeBtn = document.getElementById(prefix + 'existing');
    const newModeBtn = document.getElementById(prefix + 'new');
    const existingAddresses = document.getElementById(prefix + 'existing_addresses');
    const newAddressForm = document.getElementById(prefix + 'new_address_form');
    
    console.log('Found elements:', {
        existingModeBtn,
        newModeBtn,
        existingAddresses,
        newAddressForm
    });
    
    if (existingModeBtn && newModeBtn) {
        existingModeBtn.addEventListener('change', function() {
            console.log('Existing mode selected');
            if (this.checked) {
                if (existingAddresses) existingAddresses.style.display = 'block';
                if (newAddressForm) newAddressForm.style.display = 'none';
            }
        });

        newModeBtn.addEventListener('change', function() {
            console.log('New mode selected');
            if (this.checked) {
                if (existingAddresses) existingAddresses.style.display = 'none';
                if (newAddressForm) newAddressForm.style.display = 'block';
            }
        });
    }
    
    // Xử lý chuyển đổi giữa chế độ dropdown và nhập tay
    const dropdownMode = document.getElementById(prefix + 'dropdown_mode');
    const manualMode = document.getElementById(prefix + 'manual_mode');
    const dropdownContainer = document.getElementById(prefix + 'dropdown_container');
    const manualContainer = document.getElementById(prefix + 'manual_container');
    
    function toggleInputMode() {
        console.log('Toggling input mode, manual mode checked:', manualMode?.checked);
        
        if (manualMode && manualMode.checked) {
            // Chế độ nhập tay
            if (dropdownContainer) dropdownContainer.style.display = 'none';
            if (manualContainer) manualContainer.style.display = 'block';
            
            // Disable dropdown fields
            const provinceSelect = document.getElementById(prefix + 'province');
            const districtSelect = document.getElementById(prefix + 'district');
            const wardSelect = document.getElementById(prefix + 'ward');
            
            if (provinceSelect) {
                provinceSelect.disabled = true;
                provinceSelect.required = false;
            }
            if (districtSelect) {
                districtSelect.disabled = true;
                districtSelect.required = false;
            }
            if (wardSelect) {
                wardSelect.disabled = true;
                wardSelect.required = false;
            }
            
            // Enable manual fields
            const provinceManual = document.getElementById(prefix + 'province_manual');
            const districtManual = document.getElementById(prefix + 'district_manual');
            const wardManual = document.getElementById(prefix + 'ward_manual');
            
            if (provinceManual) {
                provinceManual.disabled = false;
                provinceManual.required = true;
            }
            if (districtManual) {
                districtManual.disabled = false;
                districtManual.required = true;
            }
            if (wardManual) {
                wardManual.disabled = false;
                wardManual.required = true;
            }
        } else {
            // Chế độ dropdown
            if (dropdownContainer) dropdownContainer.style.display = 'block';
            if (manualContainer) manualContainer.style.display = 'none';
            
            // Enable dropdown fields
            const provinceSelect = document.getElementById(prefix + 'province');
            const districtSelect = document.getElementById(prefix + 'district');
            const wardSelect = document.getElementById(prefix + 'ward');
            
            if (provinceSelect) {
                provinceSelect.disabled = false;
                provinceSelect.required = true;
            }
            if (districtSelect) {
                districtSelect.required = true;
            }
            if (wardSelect) {
                wardSelect.required = true;
            }
            
            // Disable manual fields
            const provinceManual = document.getElementById(prefix + 'province_manual');
            const districtManual = document.getElementById(prefix + 'district_manual');
            const wardManual = document.getElementById(prefix + 'ward_manual');
            
            if (provinceManual) {
                provinceManual.disabled = true;
                provinceManual.required = false;
            }
            if (districtManual) {
                districtManual.disabled = true;
                districtManual.required = false;
            }
            if (wardManual) {
                wardManual.disabled = true;
                wardManual.required = false;
            }
        }
    }
    
    // Gắn sự kiện cho các radio button
    if (dropdownMode) {
        dropdownMode.addEventListener('change', toggleInputMode);
    }
    if (manualMode) {
        manualMode.addEventListener('change', toggleInputMode);
    }
    
    // Khởi tạo trạng thái ban đầu
    toggleInputMode();
    
    // Cập nhật preview khi nhập tay
    const manualInputs = [
        prefix + 'province_manual',
        prefix + 'district_manual', 
        prefix + 'ward_manual',
        prefix + 'address_line'
    ];
    
    manualInputs.forEach(function(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', updateAddressPreview);
        }
    });
    
    function updateAddressPreview() {
        const preview = document.getElementById(prefix + 'address_preview');
        const previewContent = document.getElementById(prefix + 'preview_content');
        
        if (manualMode && manualMode.checked) {
            const province = document.getElementById(prefix + 'province_manual')?.value || '';
            const district = document.getElementById(prefix + 'district_manual')?.value || '';
            const ward = document.getElementById(prefix + 'ward_manual')?.value || '';
            const addressLine = document.getElementById(prefix + 'address_line')?.value || '';
            
            if (province || district || ward || addressLine) {
                let addressText = '';
                if (addressLine) addressText += addressLine + ', ';
                if (ward) addressText += ward + ', ';
                if (district) addressText += district + ', ';
                if (province) addressText += province;
                
                if (previewContent) previewContent.innerHTML = addressText;
                if (preview) preview.style.display = 'block';
            } else {
                if (preview) preview.style.display = 'none';
            }
        }
    }
});
</script>
