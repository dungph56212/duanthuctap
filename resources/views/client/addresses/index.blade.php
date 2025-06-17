@extends('client.layouts.app')

@section('title', 'Quản lý địa chỉ')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Quản lý địa chỉ</h2>
                <a href="{{ route('client.addresses.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm địa chỉ mới
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Addresses List -->
            @if($addresses->count() > 0)
                <div class="row">
                    @foreach($addresses as $address)
                    <div class="col-lg-6 mb-4">
                        <div class="card border-0 shadow-sm {{ $address->is_default ? 'border-primary' : '' }}">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">
                                        @if($address->type === 'shipping')
                                            <i class="fas fa-shipping-fast text-primary"></i> Địa chỉ giao hàng
                                        @else
                                            <i class="fas fa-credit-card text-success"></i> Địa chỉ thanh toán
                                        @endif
                                    </h6>
                                    @if($address->is_default)
                                        <span class="badge bg-primary">Mặc định</span>
                                    @endif
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('client.addresses.edit', $address) }}">
                                                <i class="fas fa-edit"></i> Chỉnh sửa
                                            </a>
                                        </li>
                                        @if(!$address->is_default)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('client.addresses.setDefault', $address) }}">
                                                <i class="fas fa-star"></i> Đặt làm mặc định
                                            </a>
                                        </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('client.addresses.destroy', $address) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" 
                                                        onclick="return confirm('Bạn có chắc muốn xóa địa chỉ này?')">
                                                    <i class="fas fa-trash"></i> Xóa
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-2">{{ $address->name }}</h6>
                                <p class="text-muted mb-2">
                                    <i class="fas fa-phone"></i> {{ $address->phone }}
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-map-marker-alt"></i> 
                                    {{ $address->address_line }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-5">
                    <i class="fas fa-map-marker-alt fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted mb-3">Chưa có địa chỉ nào</h5>
                    <p class="text-muted mb-4">Thêm địa chỉ để tiện lợi hơn khi mua sắm</p>
                    <a href="{{ route('client.addresses.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm địa chỉ đầu tiên
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
