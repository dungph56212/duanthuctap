@extends('admin.layouts.app')

@section('title', 'Tạo đơn hàng mới')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tạo đơn hàng mới</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.orders.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Khách hàng <span class="text-danger">*</span></label>
                                    <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                                        <option value="">Chọn khách hàng</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="shipping_address">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('shipping_address') is-invalid @enderror" 
                                              id="shipping_address" name="shipping_address" rows="3" required>{{ old('shipping_address') }}</textarea>
                                    @error('shipping_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Sản phẩm <span class="text-danger">*</span></label>
                            <div id="products-container">
                                <div class="product-item row mb-3">
                                    <div class="col-md-8">
                                        <select class="form-control product-select" name="products[0][id]" required>
                                            <option value="">Chọn sản phẩm</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                    {{ $product->name }} - {{ number_format($product->price) }}đ
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" class="form-control quantity-input" 
                                               name="products[0][quantity]" placeholder="Số lượng" min="1" value="1" required>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger remove-product" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-success" id="add-product">
                                <i class="fas fa-plus"></i> Thêm sản phẩm
                            </button>
                        </div>

                        <div class="form-group">
                            <label for="notes">Ghi chú</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <strong>Tổng tiền:</strong> <span id="total-amount">0đ</span>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Tạo đơn hàng
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Hủy</a>
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
$(document).ready(function() {
    let productIndex = 1;

    // Add product
    $('#add-product').click(function() {
        const newProduct = `
            <div class="product-item row mb-3">
                <div class="col-md-8">
                    <select class="form-control product-select" name="products[${productIndex}][id]" required>
                        <option value="">Chọn sản phẩm</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }} - {{ number_format($product->price) }}đ
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" class="form-control quantity-input" 
                           name="products[${productIndex}][quantity]" placeholder="Số lượng" min="1" value="1" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger remove-product">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#products-container').append(newProduct);
        productIndex++;
        updateRemoveButtons();
    });

    // Remove product
    $(document).on('click', '.remove-product', function() {
        $(this).closest('.product-item').remove();
        updateRemoveButtons();
        calculateTotal();
    });

    // Update remove buttons
    function updateRemoveButtons() {
        const items = $('.product-item');
        if (items.length <= 1) {
            $('.remove-product').attr('disabled', true);
        } else {
            $('.remove-product').attr('disabled', false);
        }
    }

    // Calculate total
    function calculateTotal() {
        let total = 0;
        $('.product-item').each(function() {
            const price = parseFloat($(this).find('.product-select option:selected').data('price')) || 0;
            const quantity = parseInt($(this).find('.quantity-input').val()) || 0;
            total += price * quantity;
        });
        $('#total-amount').text(new Intl.NumberFormat('vi-VN').format(total) + 'đ');
    }

    // Update total when product or quantity changes
    $(document).on('change', '.product-select, .quantity-input', function() {
        calculateTotal();
    });

    // Initial calculation
    calculateTotal();
});
</script>
@endpush
