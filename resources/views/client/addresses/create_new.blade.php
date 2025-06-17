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
                        </div>

                        <!-- Address Selection -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="tinh" class="form-label">Tỉnh/Thành phố <span class="text-danger">*</span></label>
                                <select class="form-select @error('province') is-invalid @enderror" id="tinh" name="province" required>
                                    <option value="">Chọn Tỉnh/Thành phố</option>
                                </select>
                                @error('province')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="quan" class="form-label">Quận/Huyện <span class="text-danger">*</span></label>
                                <select class="form-select @error('district') is-invalid @enderror" id="quan" name="district" required>
                                    <option value="">Chọn Quận/Huyện</option>
                                </select>
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="phuong" class="form-label">Phường/Xã <span class="text-danger">*</span></label>
                                <select class="form-select @error('ward') is-invalid @enderror" id="phuong" name="ward" required>
                                    <option value="">Chọn Phường/Xã</option>
                                </select>
                                @error('ward')
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Lấy tỉnh thành
    $.getJSON('https://provinces.open-api.vn/api/p/', function(provinces) {
        provinces.forEach(function(province) {
            $("#tinh").append(`<option value="${province.name}">${province.name}</option>`);
        });
    });

    // Xử lý khi chọn tỉnh
    $("#tinh").change(function() {
        const provinceName = $(this).val();
        
        // Reset quận và phường
        $("#quan").html('<option value="">Chọn Quận/Huyện</option>');
        $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
        
        if (provinceName) {
            // Tìm province code và lấy quận/huyện
            $.getJSON('https://provinces.open-api.vn/api/p/', function(provinces) {
                const province = provinces.find(p => p.name === provinceName);
                if (province) {
                    $.getJSON(`https://provinces.open-api.vn/api/p/${province.code}?depth=2`, function(provinceData) {
                        provinceData.districts.forEach(function(district) {
                            $("#quan").append(`<option value="${district.name}">${district.name}</option>`);
                        });
                    });
                }
            });
        }
    });

    // Xử lý khi chọn quận
    $("#quan").change(function() {
        const districtName = $(this).val();
        const provinceName = $("#tinh").val();
        
        // Reset phường
        $("#phuong").html('<option value="">Chọn Phường/Xã</option>');
        
        if (districtName && provinceName) {
            // Tìm district code và lấy phường/xã
            $.getJSON('https://provinces.open-api.vn/api/p/', function(provinces) {
                const province = provinces.find(p => p.name === provinceName);
                if (province) {
                    $.getJSON(`https://provinces.open-api.vn/api/p/${province.code}?depth=2`, function(provinceData) {
                        const district = provinceData.districts.find(d => d.name === districtName);
                        if (district) {
                            $.getJSON(`https://provinces.open-api.vn/api/d/${district.code}?depth=2`, function(districtData) {
                                districtData.wards.forEach(function(ward) {
                                    $("#phuong").append(`<option value="${ward.name}">${ward.name}</option>`);
                                });
                            });
                        }
                    });
                }
            });
        }
    });
});
</script>
@endpush
