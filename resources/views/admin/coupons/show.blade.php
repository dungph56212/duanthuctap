@extends('admin.layouts.app')

@section('title', 'Chi tiết mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết mã giảm giá: {{ $coupon->code }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon bg-info">
                                    <i class="fas fa-ticket-alt"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Mã giảm giá</span>
                                    <span class="info-box-number">{{ $coupon->code }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-box">
                                <span class="info-box-icon {{ $coupon->is_active ? 'bg-success' : 'bg-danger' }}">
                                    <i class="fas {{ $coupon->is_active ? 'fa-check' : 'fa-times' }}"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Trạng thái</span>
                                    <span class="info-box-number">
                                        {{ $coupon->is_active ? 'Kích hoạt' : 'Tạm dừng' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="200">Tên mã giảm giá</th>
                                            <td>{{ $coupon->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Mô tả</th>
                                            <td>{{ $coupon->description ?: 'Không có mô tả' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Loại giảm giá</th>
                                            <td>
                                                @if($coupon->type === 'percentage')
                                                    <span class="badge badge-info">Phần trăm</span>
                                                @else
                                                    <span class="badge badge-success">Số tiền cố định</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Giá trị giảm</th>
                                            <td>
                                                @if($coupon->type === 'percentage')
                                                    {{ number_format($coupon->value) }}%
                                                @else
                                                    {{ number_format($coupon->value) }}đ
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Giá trị đơn hàng tối thiểu</th>
                                            <td>{{ $coupon->min_amount ? number_format($coupon->min_amount) . 'đ' : 'Không yêu cầu' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Giảm tối đa</th>
                                            <td>{{ $coupon->max_discount ? number_format($coupon->max_discount) . 'đ' : 'Không giới hạn' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Số lượng ban đầu</th>
                                            <td>{{ $coupon->quantity ? number_format($coupon->quantity) : 'Không giới hạn' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Đã sử dụng</th>
                                            <td>{{ number_format($coupon->used_count) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Còn lại</th>
                                            <td>
                                                @if($coupon->quantity)
                                                    {{ number_format($coupon->quantity - $coupon->used_count) }}
                                                @else
                                                    Không giới hạn
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Ngày bắt đầu</th>
                                            <td>{{ $coupon->start_date ? $coupon->start_date->format('d/m/Y H:i') : 'Ngay lập tức' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ngày kết thúc</th>
                                            <td>{{ $coupon->end_date ? $coupon->end_date->format('d/m/Y H:i') : 'Không giới hạn' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Ngày tạo</th>
                                            <td>{{ $coupon->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Cập nhật lần cuối</th>
                                            <td>{{ $coupon->updated_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Usage Statistics -->
                    @if($coupon->used_count > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Thống kê sử dụng</h5>
                            <div class="progress">
                                @php
                                    $percentage = $coupon->quantity ? ($coupon->used_count / $coupon->quantity * 100) : 0;
                                @endphp
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ min($percentage, 100) }}%" 
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ number_format($percentage, 1) }}%
                                </div>
                            </div>
                            <small class="text-muted">
                                Đã sử dụng {{ number_format($coupon->used_count) }}
                                @if($coupon->quantity)
                                    / {{ number_format($coupon->quantity) }}
                                @endif
                                lần
                            </small>
                        </div>
                    </div>
                    @endif
                </div>
                
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <div>
                            @if($coupon->is_active)
                                <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning btn-sm" 
                                            onclick="return confirm('Bạn có chắc muốn tắt mã giảm giá này?')">
                                        <i class="fas fa-pause"></i> Tạm dừng
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.coupons.toggle-status', $coupon) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-play"></i> Kích hoạt
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <div>
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush
