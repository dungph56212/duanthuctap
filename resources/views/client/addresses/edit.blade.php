@extends('client.layouts.app')

@section('title', 'Chỉnh sửa địa chỉ')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Chỉnh sửa địa chỉ</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('client.addresses.update', $address) }}">
                        @csrf
                        @method('PUT')

                        <!-- Address Type -->
                        <div class="mb-4">
                            <label class="form-label">Loại địa chỉ <span class="text-danger">*</span></label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="shipping" value="shipping" 
                                               {{ old('type', $address->type) === 'shipping' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="shipping">
                                            <i class="fas fa-shipping-fast text-primary"></i> Địa chỉ giao hàng
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="type" id="billing" value="billing"
                                               {{ old('type', $address->type) === 'billing' ? 'checked' : '' }}>
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
                                       value="{{ old('name', $address->name) }}" 
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
                                       value="{{ old('phone', $address->phone) }}" 
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Address Selection -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="tinh" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select class="form-select @error('ten_tinh') is-invalid @enderror" id="tinh" required>
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                </select>
                                <input type="hidden" name="ten_tinh" id="ten_tinh" value="{{ old('ten_tinh', $address->province) }}">
                                @error('ten_tinh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="quan" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select class="form-select @error('ten_quan') is-invalid @enderror" id="quan" required>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                                <input type="hidden" name="ten_quan" id="ten_quan" value="{{ old('ten_quan', $address->district) }}">
                                @error('ten_quan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="phuong" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select class="form-select @error('ten_phuong') is-invalid @enderror" id="phuong" required>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                                <input type="hidden" name="ten_phuong" id="ten_phuong" value="{{ old('ten_phuong', $address->ward) }}">
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
                                      required>{{ old('address_line', $address->address_line) }}</textarea>
                            @error('address_line')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Default Address -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_default" id="is_default" value="1"
                                       {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
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
                                <i class="fas fa-save"></i> Cập nhật địa chỉ
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    const initialProvince = '{{ $address->province }}';
    const initialDistrict = '{{ $address->district }}';
    const initialWard = '{{ $address->ward }}';
    
    // Lấy tỉnh thành
    $.getJSON('https://provinces.open-api.vn/api/p/', function(provinces) {
        provinces.forEach(function(province) {
            const selected = province.name === initialProvince ? 'selected' : '';
            $("#tinh").append(`<option value="${province.code}" ${selected}>${province.name}</option>`);
        });
        
        // Nếu có tỉnh được chọn, load quận/huyện
        if ($("#tinh").val()) {
            loadDistricts($("#tinh").val(), initialDistrict, initialWard);
        }
    });

    // Xử lý khi chọn tỉnh
    $("#tinh").change(function() {
        const provinceCode = $(this).val();
        $("#ten_tinh").val($(this).find("option:selected").text());
        
        // Reset quận và phường
        $("#quan").html('<option value="">Chọn Quận/Huyện</option>');
        $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
        $("#ten_quan").val('');
        $("#ten_phuong").val('');
        
        if (provinceCode) {
            loadDistricts(provinceCode);
        }
    });

    // Xử lý khi chọn quận
    $("#quan").change(function() {
        const districtCode = $(this).val();
        $("#ten_quan").val($(this).find("option:selected").text());
        
        // Reset phường
        $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
        $("#ten_phuong").val('');
        
        if (districtCode) {
            loadWards(districtCode);
        }
    });

    // Xử lý khi chọn phường
    $("#phuong").change(function() {
        $("#ten_phuong").val($(this).find("option:selected").text());
    });

    // Function to load districts
    function loadDistricts(provinceCode, selectDistrict = null, selectWard = null) {
        $.getJSON(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`, function(provinceData) {
            provinceData.districts.forEach(function(district) {
                const selected = district.name === selectDistrict ? 'selected' : '';
                $("#quan").append(`<option value="${district.code}" ${selected}>${district.name}</option>`);
            });
            
            // Nếu có quận được chọn, load phường/xã
            if ($("#quan").val()) {
                loadWards($("#quan").val(), selectWard);
            }
        });
    }

    // Function to load wards
    function loadWards(districtCode, selectWard = null) {
        $.getJSON(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`, function(districtData) {
            districtData.wards.forEach(function(ward) {
                const selected = ward.name === selectWard ? 'selected' : '';
                $("#phuong").append(`<option value="${ward.code}" ${selected}>${ward.name}</option>`);
            });
        });
    }

    // Form validation
    $('form').on('submit', function(e) {
        if (!$("#ten_tinh").val() || !$("#ten_quan").val() || !$("#ten_phuong").val()) {
            e.preventDefault();
            alert('Vui lòng chọn đầy đủ Tỉnh/Thành phố, Quận/Huyện và Phường/Xã');
        }
    });
});
</script>
@endpush
