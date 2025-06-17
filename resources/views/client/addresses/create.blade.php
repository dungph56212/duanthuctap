@extends('client.layouts.app')

@section('title', 'Thêm địa chỉ mới')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Thêm địa chỉ mới</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('client.addresses.store') }}">
                        @csrf

                        <!-- Address Type -->
                        <div class="mb-4">
                            <label class="form-label">Loại địa chỉ <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="shipping" value="shipping" 
                                               {{ old('type', 'shipping') === 'shipping' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="shipping">
                                            <i class="fas fa-shipping-fast text-primary"></i> Địa chỉ giao hàng
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="billing" value="billing"
                                               {{ old('type') === 'billing' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="billing">
                                            <i class="fas fa-credit-card text-success"></i> Địa chỉ thanh toán
                                        </label>
                                    </div>
                                </div>
                            </div>
                            @error('type')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contact Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', auth()->user()->name) }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone', auth()->user()->phone) }}" 
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>                        <!-- Address Selection -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="tinh" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select class="form-select @error('ten_tinh') is-invalid @enderror" id="tinh" name="tinh_select" required>
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                </select>
                                <input type="hidden" name="ten_tinh" id="ten_tinh" value="{{ old('ten_tinh') }}">
                                <small class="text-muted">Giá trị: <span id="debug_tinh">{{ old('ten_tinh') }}</span></small>
                                @error('ten_tinh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="quan" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select class="form-select @error('ten_quan') is-invalid @enderror" id="quan" name="quan_select" required>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                                <input type="hidden" name="ten_quan" id="ten_quan" value="{{ old('ten_quan') }}">
                                <small class="text-muted">Giá trị: <span id="debug_quan">{{ old('ten_quan') }}</span></small>
                                @error('ten_quan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="phuong" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select class="form-select @error('ten_phuong') is-invalid @enderror" id="phuong" name="phuong_select" required>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                                <input type="hidden" name="ten_phuong" id="ten_phuong" value="{{ old('ten_phuong') }}">
                                <small class="text-muted">Giá trị: <span id="debug_phuong">{{ old('ten_phuong') }}</span></small>
                                @error('ten_phuong')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Detailed Address -->
                        <div class="mb-3">
                            <label for="address_line" class="form-label">Địa chỉ chi tiết <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address_line') is-invalid @enderror" 
                                      id="address_line" 
                                      name="address_line" 
                                      rows="3" 
                                      placeholder="Số nhà, tên đường..."
                                      required>{{ old('address_line') }}</textarea>
                            @error('address_line')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Default Address -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1"
                                       {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_default">
                                    Đặt làm địa chỉ mặc định
                                </label>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('client.addresses.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu địa chỉ
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
    console.log('Script loaded - DOM ready');
    
    const tinhSelect = document.getElementById('tinh');
    const quanSelect = document.getElementById('quan');
    const phuongSelect = document.getElementById('phuong');
    const tenTinhInput = document.getElementById('ten_tinh');
    const tenQuanInput = document.getElementById('ten_quan');
    const tenPhuongInput = document.getElementById('ten_phuong');
    
    console.log('Elements found:', {
        tinhSelect: !!tinhSelect,
        quanSelect: !!quanSelect,
        phuongSelect: !!phuongSelect,
        tenTinhInput: !!tenTinhInput,
        tenQuanInput: !!tenQuanInput,
        tenPhuongInput: !!tenPhuongInput
    });
    
    // Thêm data mẫu ngay lập tức
    const sampleProvinces = [
        {code: '01', name: 'Thành phố Hà Nội'},
        {code: '79', name: 'Thành phố Hồ Chí Minh'},
        {code: '48', name: 'Thành phố Đà Nẵng'},
        {code: '31', name: 'Thành phố Hải Phòng'},
        {code: '92', name: 'Thành phố Cần Thơ'},
        {code: '77', name: 'Tỉnh Bà Rịa - Vũng Tàu'},
        {code: '24', name: 'Tỉnh Bắc Giang'},
        {code: '27', name: 'Tỉnh Bắc Ninh'},
        {code: '83', name: 'Tỉnh Bến Tre'},
        {code: '74', name: 'Tỉnh Bình Dương'}
    ];
    
    // Load tỉnh thành ngay lập tức
    console.log('Loading sample provinces...');
    sampleProvinces.forEach(function(province) {
        const option = document.createElement('option');
        option.value = province.code;
        option.textContent = province.name;
        tinhSelect.appendChild(option);
    });
    console.log('Sample provinces loaded');
    
    // Thử load từ API (không block UI)
    fetch('https://provinces.open-api.vn/api/p/')
        .then(response => {
            console.log('API Response status:', response.status);
            return response.json();
        })
        .then(provinces => {
            console.log('API Provinces loaded:', provinces.length);
            // Clear sample data
            tinhSelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
            // Load API data
            provinces.forEach(function(province) {
                const option = document.createElement('option');
                option.value = province.code;
                option.textContent = province.name;
                tinhSelect.appendChild(option);
            });
            console.log('API provinces loaded successfully');
        })
        .catch(error => {
            console.error('API failed, using sample data:', error);
            // Giữ sample data nếu API lỗi
        });

    // Xử lý khi chọn tỉnh
    tinhSelect.addEventListener('change', function() {
        const provinceCode = this.value;
        const provinceName = this.options[this.selectedIndex].text;
        console.log('Province selected:', provinceCode, provinceName);
        tenTinhInput.value = provinceName;
        
        // Reset quận và phường
        quanSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        phuongSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        tenQuanInput.value = '';
        tenPhuongInput.value = '';
        
        if (provinceCode) {
            // Thêm data mẫu cho quận/huyện
            const sampleDistricts = [
                {code: 'dist1', name: 'Quận 1'},
                {code: 'dist2', name: 'Quận 2'},
                {code: 'dist3', name: 'Quận 3'},
                {code: 'dist4', name: 'Huyện A'},
                {code: 'dist5', name: 'Huyện B'}
            ];
            
            sampleDistricts.forEach(function(district) {
                const option = document.createElement('option');
                option.value = district.code;
                option.textContent = district.name;
                quanSelect.appendChild(option);
            });
            
            // Thử load từ API
            fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`)
                .then(response => response.json())
                .then(provinceData => {
                    console.log('Districts loaded from API:', provinceData.districts.length);
                    // Clear sample data
                    quanSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
                    // Load API data
                    provinceData.districts.forEach(function(district) {
                        const option = document.createElement('option');
                        option.value = district.code;
                        option.textContent = district.name;
                        quanSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Districts API failed, using sample data:', error);
                });
        }
    });

    // Xử lý khi chọn quận
    quanSelect.addEventListener('change', function() {
        const districtCode = this.value;
        const districtName = this.options[this.selectedIndex].text;
        console.log('District selected:', districtCode, districtName);
        tenQuanInput.value = districtName;
        
        // Reset phường
        phuongSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        tenPhuongInput.value = '';
        
        if (districtCode) {
            // Thêm data mẫu cho phường/xã
            const sampleWards = [
                {code: 'ward1', name: 'Phường 1'},
                {code: 'ward2', name: 'Phường 2'},
                {code: 'ward3', name: 'Phường 3'},
                {code: 'ward4', name: 'Xã A'},
                {code: 'ward5', name: 'Xã B'}
            ];
            
            sampleWards.forEach(function(ward) {
                const option = document.createElement('option');
                option.value = ward.code;
                option.textContent = ward.name;
                phuongSelect.appendChild(option);
            });
            
            // Thử load từ API
            fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`)
                .then(response => response.json())
                .then(districtData => {
                    console.log('Wards loaded from API:', districtData.wards.length);
                    // Clear sample data
                    phuongSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
                    // Load API data
                    districtData.wards.forEach(function(ward) {
                        const option = document.createElement('option');
                        option.value = ward.code;
                        option.textContent = ward.name;
                        phuongSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Wards API failed, using sample data:', error);
                });
        }
    });

    // Xử lý khi chọn phường
    phuongSelect.addEventListener('change', function() {
        const wardName = this.options[this.selectedIndex].text;
        console.log('Ward selected:', wardName);
        tenPhuongInput.value = wardName;
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        console.log('Form submit - checking values:', {
            tinh: tenTinhInput.value,
            quan: tenQuanInput.value,
            phuong: tenPhuongInput.value
        });
        
        if (!tenTinhInput.value || !tenQuanInput.value || !tenPhuongInput.value) {
            e.preventDefault();
            alert('Vui lòng chọn đầy đủ Tỉnh/Thành phố, Quận/Huyện và Phường/Xã');
            return false;
        }
    });
    
    console.log('Script setup completed');
});
        
        // Reset quận và phường
        $("#quan").html('<option value="">Chọn Quận/Huyện</option>');
        $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
        $("#ten_quan").val('');
        $("#ten_phuong").val('');
        
        if (provinceCode) {
            // Lấy quận/huyện
            $.getJSON(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`, function(provinceData) {
                console.log('Districts loaded:', provinceData.districts.length);
                provinceData.districts.forEach(function(district) {
                    $("#quan").append(`<option value="${district.code}">${district.name}</option>`);
                });
            }).fail(function() {
                console.error('Failed to load districts');
            });
        }
    });

    // Xử lý khi chọn quận
    $("#quan").change(function() {
        const districtCode = $(this).val();
        const districtName = $(this).find("option:selected").text();
        console.log('District selected:', districtCode, districtName);
        $("#ten_quan").val(districtName);
        
        // Reset phường
        $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
        $("#ten_phuong").val('');
        
        if (districtCode) {
            // Lấy phường/xã
            $.getJSON(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`, function(districtData) {
                console.log('Wards loaded:', districtData.wards.length);
                districtData.wards.forEach(function(ward) {
                    $("#phuong").append(`<option value="${ward.code}">${ward.name}</option>`);
                });
            }).fail(function() {
                console.error('Failed to load wards');
            });
        }
    });

    // Xử lý khi chọn phường
    $("#phuong").change(function() {
        const wardName = $(this).find("option:selected").text();
        console.log('Ward selected:', wardName);
        $("#ten_phuong").val(wardName);
    });

    // Form validation
    $('form').on('submit', function(e) {
        console.log('Form submitting...');
        console.log('Province:', $("#ten_tinh").val());
        console.log('District:', $("#ten_quan").val());
        console.log('Ward:', $("#ten_phuong").val());
        
        if (!$("#ten_tinh").val() || !$("#ten_quan").val() || !$("#ten_phuong").val()) {
            e.preventDefault();
            alert('Vui lòng chọn đầy đủ Tỉnh/Thành phố, Quận/Huyện và Phường/Xã');
            return false;
        }
    });
});
</script>
@endpush
