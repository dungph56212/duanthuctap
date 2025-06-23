@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('styles')
<style>
.bg-purple {
    background-color: #6f42c1 !important;
}
.stats-card {
    transition: transform 0.2s;
}
.stats-card:hover {
    transform: translateY(-2px);
}
.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
</style>
@endsection

@section('content')
<!-- DEBUG - Remove this after fixing -->
<div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ccc;">
    <h5>DEBUG INFO:</h5>
    <p>Order Stats: 
        Total: {{ $orderStats['total'] ?? 'NOT SET' }}, 
        Pending: {{ $orderStats['pending'] ?? 'NOT SET' }}, 
        Delivered: {{ $orderStats['delivered'] ?? 'NOT SET' }}, 
        Revenue: {{ $orderStats['total_revenue'] ?? 'NOT SET' }}
    </p>
    <p>User Stats: 
        Total: {{ $userStats['total'] ?? 'NOT SET' }}, 
        Customers: {{ $userStats['customers'] ?? 'NOT SET' }}, 
        Admins: {{ $userStats['admins'] ?? 'NOT SET' }}
    </p>
</div>
<!-- END DEBUG -->

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Tổng người dùng</h6>
                        <h3 class="mb-0">{{ number_format($totalUsers) }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Tổng sản phẩm</h6>
                        <h3 class="mb-0">{{ number_format($productStats['total']) }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Tổng đơn hàng</h6>
                        <h3 class="mb-0">{{ number_format($totalOrders) }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card stats-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title text-muted">Danh mục</h6>
                        <h3 class="mb-0">{{ number_format($totalCategories) }}</h3>
                    </div>
                    <div class="text-danger">
                        <i class="fas fa-tags fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Coupon Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-purple text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Tổng mã giảm giá</h6>
                        <h3 class="mb-0">{{ number_format($couponStats['total'] ?? 0) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-tags fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Đang hoạt động</h6>
                        <h3 class="mb-0">{{ number_format($couponStats['active'] ?? 0) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Đã sử dụng</h6>
                        <h3 class="mb-0">{{ number_format($couponStats['used'] ?? 0) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-receipt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Tiết kiệm</h6>
                        <h3 class="mb-0">{{ number_format($couponStats['total_savings'] ?? 0) }}đ</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Đang hoạt động</h6>
                        <h3 class="mb-0">{{ $productStats['active'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Không hoạt động</h6>
                        <h3 class="mb-0">{{ $productStats['inactive'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-pause-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Sắp hết hàng</h6>
                        <h3 class="mb-0">{{ $productStats['low_stock'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Hết hàng</h6>
                        <h3 class="mb-0">{{ $productStats['out_of_stock'] ?? 0 }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Stats -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="card-title text-muted">Doanh thu hôm nay</h6>
                <h4 class="text-primary">{{ number_format($todayRevenue) }} VNĐ</h4>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="card-title text-muted">Doanh thu tháng này</h6>
                <h4 class="text-success">{{ number_format($monthRevenue) }} VNĐ</h4>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h6 class="card-title text-muted">Doanh thu năm nay</h6>
                <h4 class="text-warning">{{ number_format($yearRevenue) }} VNĐ</h4>
            </div>
        </div>
    </div>
</div>

<!-- Order Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Tổng đơn hàng</h6>
                        <h3 class="mb-0">{{ number_format($orderStats['total'] ?? 0) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Chờ xử lý</h6>
                        <h3 class="mb-0">{{ number_format($orderStats['pending'] ?? 0) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Đã giao</h6>
                        <h3 class="mb-0">{{ number_format($orderStats['delivered'] ?? 0) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Doanh thu</h6>
                        <h3 class="mb-0">{{ number_format($orderStats['total_revenue'] ?? 0) }}đ</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Stats -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Tổng người dùng</h6>
                        <h3 class="mb-0">{{ number_format($userStats['total'] ?? 0) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Khách hàng</h6>
                        <h3 class="mb-0">{{ number_format($userStats['customers'] ?? 0) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Admin</h6>
                        <h3 class="mb-0">{{ number_format($userStats['admins'] ?? 0) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-shield fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Đăng ký tháng này</h6>
                        <h3 class="mb-0">{{ number_format($userStats['this_month'] ?? 0) }}</h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-plus fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Chart -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Doanh thu 12 tháng gần đây</h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Low Stock Products -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Sản phẩm sắp hết hàng</h6>
            </div>
            <div class="card-body p-0">
                @if($lowStockProducts->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($lowStockProducts as $product)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">{{ Str::limit($product->name, 30) }}</h6>
                            <small class="text-muted">SKU: {{ $product->sku }}</small>
                        </div>
                        <span class="badge bg-warning">{{ $product->stock }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-center text-muted m-3">Không có sản phẩm sắp hết hàng</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Đơn hàng gần đây</h6>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                    Xem tất cả
                </a>
            </div>
            <div class="card-body p-0">
                @if($recentOrders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn hàng</th>
                                <th>Khách hàng</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                        {{ $order->order_number }}
                                    </a>
                                </td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ number_format($order->total_amount) }} VNĐ</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'processing' => 'info',
                                            'shipped' => 'primary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'shipped' => 'Đã gửi',
                                            'delivered' => 'Đã giao',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$order->status] }}">
                                        {{ $statusLabels[$order->status] }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-center text-muted m-3">Chưa có đơn hàng nào</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: {!! json_encode(array_column($monthlyRevenue, 'revenue')) !!},
            borderColor: 'rgb(102, 126, 234)',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                    }
                }
            }
        },
        elements: {
            point: {
                radius: 4,
                hoverRadius: 6
            }
        }
    }
});
</script>
@endpush
