@extends('client.layouts.app')

@section('title', 'Thêm địa chỉ mới')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                        Thêm địa chỉ mới
                    </h5>
                </div>                <div class="card-body">                    <form method="POST" action="{{ route('client.addresses.store') }}">
                        @csrf
                        
                        <!-- Hidden input để xác định mode -->
                        <input type="hidden" name="input_mode" id="input_mode" value="select">

                        <!-- Form nhập địa chỉ mới -->
                        <div class="row">
                            <!-- Họ và tên -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    Họ và tên <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', auth()->user()->name ?? '') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Số điện thoại -->
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">
                                    Số điện thoại <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', auth()->user()->phone ?? '') }}" 
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Chọn từ danh sách hoặc nhập tay -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0">Địa chỉ</label>
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="radio" class="btn-check" name="input_mode" id="select_mode" value="select" checked>
                                    <label class="btn btn-outline-primary" for="select_mode">
                                        <i class="fas fa-list me-1"></i>Chọn từ danh sách
                                    </label>

                                    <input type="radio" class="btn-check" name="input_mode" id="manual_mode" value="manual">
                                    <label class="btn btn-outline-success" for="manual_mode">
                                        <i class="fas fa-keyboard me-1"></i>Nhập tay
                                    </label>
                                </div>
                            </div>

                            <!-- Chọn từ danh sách -->
                            <div id="select_address_section">
                                <div class="row">
                                    <!-- Tỉnh/Thành phố -->
                                    <div class="col-md-4 mb-3">
                                        <label for="province" class="form-label">
                                            Tỉnh/Thành phố <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('ten_tinh') is-invalid @enderror" 
                                                id="province" 
                                                name="province_id">
                                            <option value="">Chọn Tỉnh/Thành phố</option>
                                        </select>
                                        <input type="hidden" name="ten_tinh" id="ten_tinh">
                                        @error('ten_tinh')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Quận/Huyện -->
                                    <div class="col-md-4 mb-3">
                                        <label for="district" class="form-label">
                                            Quận/Huyện <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('ten_quan') is-invalid @enderror" 
                                                id="district" 
                                                name="district_id" 
                                                disabled>
                                            <option value="">Chọn Quận/Huyện</option>
                                        </select>
                                        <input type="hidden" name="ten_quan" id="ten_quan">
                                        @error('ten_quan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Phường/Xã -->
                                    <div class="col-md-4 mb-3">
                                        <label for="ward" class="form-label">
                                            Phường/Xã <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('ten_phuong') is-invalid @enderror" 
                                                id="ward" 
                                                name="ward_id" 
                                                disabled>
                                            <option value="">Chọn Phường/Xã</option>
                                        </select>
                                        <input type="hidden" name="ten_phuong" id="ten_phuong">
                                        @error('ten_phuong')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Nhập tay -->
                            <div id="manual_address_section" style="display: none;">
                                <div class="row">
                                    <!-- Tỉnh/Thành phố -->
                                    <div class="col-md-4 mb-3">
                                        <label for="manual_province" class="form-label">
                                            Tỉnh/Thành phố <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-city"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="manual_province" 
                                                   name="manual_ten_tinh" 
                                                   placeholder="Nhập tỉnh/thành phố">
                                        </div>
                                    </div>

                                    <!-- Quận/Huyện -->
                                    <div class="col-md-4 mb-3">
                                        <label for="manual_district" class="form-label">
                                            Quận/Huyện <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-building"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="manual_district" 
                                                   name="manual_ten_quan" 
                                                   placeholder="Nhập quận/huyện">
                                        </div>
                                    </div>

                                    <!-- Phường/Xã -->
                                    <div class="col-md-4 mb-3">
                                        <label for="manual_ward" class="form-label">
                                            Phường/Xã <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-home"></i>
                                            </span>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="manual_ward" 
                                                   name="manual_ten_phuong" 
                                                   placeholder="Nhập phường/xã">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Địa chỉ chi tiết -->
                        <div class="mb-3">
                            <label for="address_line" class="form-label">
                                Địa chỉ chi tiết <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <textarea class="form-control @error('address_line') is-invalid @enderror" 
                                          id="address_line" 
                                          name="address_line" 
                                          rows="3" 
                                          placeholder="Nhập số nhà, tên đường..."
                                          required>{{ old('address_line') }}</textarea>
                            </div>
                            @error('address_line')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Loại địa chỉ -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Loại địa chỉ</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="type" 
                                               id="type_delivery" 
                                               value="Giao hàng" 
                                               {{ old('type', 'Giao hàng') == 'Giao hàng' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_delivery">
                                            <i class="fas fa-truck text-primary me-1"></i>Giao hàng
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="type" 
                                               id="type_payment" 
                                               value="Thanh toán" 
                                               {{ old('type') == 'Thanh toán' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_payment">
                                            <i class="fas fa-credit-card text-success me-1"></i>Thanh toán
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Đặt làm địa chỉ mặc định -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="is_default" 
                                           id="is_default" 
                                           value="1" 
                                           {{ old('is_default') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_default">
                                        <i class="fas fa-star text-warning me-1"></i>Đặt làm địa chỉ mặc định
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Xem trước địa chỉ -->
                        <div class="address-preview bg-light p-3 rounded d-none" id="address_preview">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-eye me-2"></i>Xem trước địa chỉ:
                            </h6>
                            <div id="preview_content" class="text-muted"></div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('client.addresses.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Lưu địa chỉ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectMode = document.getElementById('select_mode');
    const manualMode = document.getElementById('manual_mode');
    const selectSection = document.getElementById('select_address_section');
    const manualSection = document.getElementById('manual_address_section');
    const addressPreview = document.getElementById('address_preview');
    const previewContent = document.getElementById('preview_content');

    // Xử lý chuyển đổi giữa chọn từ danh sách và nhập tay
    function toggleAddressMode() {
        if (selectMode.checked) {
            selectSection.style.display = 'block';
            manualSection.style.display = 'none';
            
            // Clear manual inputs
            document.querySelectorAll('#manual_address_section input').forEach(input => {
                input.value = '';
                input.removeAttribute('required');
            });
            
            // Add required to select inputs
            document.querySelectorAll('#select_address_section select').forEach(select => {
                select.setAttribute('required', 'required');
            });
        } else {
            selectSection.style.display = 'none';
            manualSection.style.display = 'block';
            
            // Clear select inputs
            document.querySelectorAll('#select_address_section select').forEach(select => {
                select.value = '';
                select.removeAttribute('required');
            });
            document.querySelectorAll('#select_address_section input[type="hidden"]').forEach(input => {
                input.value = '';
            });
            
            // Add required to manual inputs
            document.querySelectorAll('#manual_address_section input').forEach(input => {
                input.setAttribute('required', 'required');
            });
        }
        updatePreview();
    }

    // Event listeners cho radio buttons
    selectMode.addEventListener('change', toggleAddressMode);
    manualMode.addEventListener('change', toggleAddressMode);

    // Xử lý API địa chỉ cho phần chọn từ danh sách
    const provinceSelect = document.getElementById('province');
    const districtSelect = document.getElementById('district');
    const wardSelect = document.getElementById('ward');

    // Load danh sách tỉnh/thành phố
    fetch('https://provinces.open-api.vn/api/p/')
        .then(response => response.json())
        .then(data => {
            data.forEach(province => {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Lỗi khi tải danh sách tỉnh:', error));

    // Xử lý khi chọn tỉnh/thành phố
    provinceSelect.addEventListener('change', function() {
        const provinceCode = this.value;
        const provinceName = this.options[this.selectedIndex].text;
        
        // Reset district và ward
        districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        districtSelect.disabled = !provinceCode;
        wardSelect.disabled = true;
        
        // Set tên tỉnh vào hidden input
        document.getElementById('ten_tinh').value = provinceName;
        
        if (provinceCode) {
            fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                .then(response => response.json())
                .then(data => {
                    data.districts.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.code;
                        option.textContent = district.name;
                        districtSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Lỗi khi tải danh sách quận/huyện:', error));
        }
        updatePreview();
    });

    // Xử lý khi chọn quận/huyện
    districtSelect.addEventListener('change', function() {
        const districtCode = this.value;
        const districtName = this.options[this.selectedIndex].text;
        
        // Reset ward
        wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        wardSelect.disabled = !districtCode;
        
        // Set tên quận vào hidden input
        document.getElementById('ten_quan').value = districtName;
        
        if (districtCode) {
            fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                .then(response => response.json())
                .then(data => {
                    data.wards.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.code;
                        option.textContent = ward.name;
                        wardSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Lỗi khi tải danh sách phường/xã:', error));
        }
        updatePreview();
    });

    // Xử lý khi chọn phường/xã
    wardSelect.addEventListener('change', function() {
        const wardName = this.options[this.selectedIndex].text;
        document.getElementById('ten_phuong').value = wardName;
        updatePreview();
    });

    // Cập nhật preview địa chỉ
    function updatePreview() {
        let address = '';
        const name = document.getElementById('name').value;
        const phone = document.getElementById('phone').value;
        const addressLine = document.getElementById('address_line').value;
        
        if (selectMode.checked) {
            const province = document.getElementById('ten_tinh').value;
            const district = document.getElementById('ten_quan').value;
            const ward = document.getElementById('ten_phuong').value;
            
            if (addressLine) address += addressLine;
            if (ward) address += (address ? ', ' : '') + ward;
            if (district) address += (address ? ', ' : '') + district;
            if (province) address += (address ? ', ' : '') + province;
        } else {
            const province = document.getElementById('manual_province').value;
            const district = document.getElementById('manual_district').value;
            const ward = document.getElementById('manual_ward').value;
            
            if (addressLine) address += addressLine;
            if (ward) address += (address ? ', ' : '') + ward;
            if (district) address += (address ? ', ' : '') + district;
            if (province) address += (address ? ', ' : '') + province;
        }
        
        if (name || phone || address) {
            let preview = '';
            if (name) preview += `<strong>${name}</strong><br>`;
            if (phone) preview += `<i class="fas fa-phone text-primary me-1"></i>${phone}<br>`;
            if (address) preview += `<i class="fas fa-map-marker-alt text-danger me-1"></i>${address}`;
            
            previewContent.innerHTML = preview;
            addressPreview.classList.remove('d-none');
        } else {
            addressPreview.classList.add('d-none');
        }
    }

    // Event listeners cho preview
    document.getElementById('name').addEventListener('input', updatePreview);
    document.getElementById('phone').addEventListener('input', updatePreview);
    document.getElementById('address_line').addEventListener('input', updatePreview);
    
    // Event listeners cho manual inputs
    document.getElementById('manual_province').addEventListener('input', updatePreview);
    document.getElementById('manual_district').addEventListener('input', updatePreview);
    document.getElementById('manual_ward').addEventListener('input', updatePreview);

    // Initialize
    toggleAddressMode();
});
</script>
@endpush
