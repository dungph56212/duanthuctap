<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $order->order_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            
            .page-break {
                page-break-after: always;
            }
            
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
        
        .invoice-header {
            border-bottom: 3px solid #007bff;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
        }
        
        .company-info {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .company-info h1 {
            color: #007bff;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        
        .invoice-details {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }
        
        .order-items table {
            margin-bottom: 2rem;
        }
        
        .order-items th {
            background-color: #007bff;
            color: white;
            border: none;
        }
        
        .order-items td {
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }
        
        .order-summary {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 2rem;
        }
        
        .order-summary .total-row {
            font-size: 1.2rem;
            font-weight: bold;
            color: #007bff;
            border-top: 2px solid #007bff;
            padding-top: 0.5rem;
        }
        
        .footer-note {
            text-align: center;
            margin-top: 3rem;
            padding-top: 1rem;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <!-- Print Button -->
        <div class="no-print mb-3">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> In hóa đơn
            </button>
            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>BOOKSTORE</h1>
                <p class="mb-1"><strong>Cửa hàng sách trực tuyến hàng đầu Việt Nam</strong></p>
                <p class="mb-1">Địa chỉ: 123 Đường ABC, Quận XYZ, TP. Hồ Chí Minh</p>
                <p class="mb-1">Điện thoại: (028) 1234 5678 | Email: info@bookstore.vn</p>
                <p class="mb-0">Website: www.bookstore.vn</p>
            </div>
        </div>

        <!-- Invoice Title -->
        <div class="text-center mb-4">
            <h2 class="text-primary">HÓA ĐƠN BÁN HÀNG</h2>
            <h4>Số: {{ $order->order_number }}</h4>
        </div>

        <!-- Order Details -->
        <div class="row">
            <div class="col-md-6">
                <div class="invoice-details">
                    <h5 class="text-primary mb-3">THÔNG TIN KHÁCH HÀNG</h5>
                    @if(is_array($order->shipping_address))
                        <p class="mb-1"><strong>Họ tên:</strong> {{ $order->shipping_address['name'] ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $order->shipping_address['email'] ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Điện thoại:</strong> {{ $order->shipping_address['phone'] ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->shipping_address['address'] ?? 'N/A' }}</p>
                    @else
                        <p class="mb-1"><strong>Họ tên:</strong> {{ $order->user->name ?? 'Khách hàng' }}</p>
                        <p class="mb-1"><strong>Email:</strong> {{ $order->user->email ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Điện thoại:</strong> N/A</p>
                        <p class="mb-0"><strong>Địa chỉ:</strong> {{ $order->shipping_address ?? 'N/A' }}</p>
                    @endif
                </div>
            </div>
            <div class="col-md-6">
                <div class="invoice-details">
                    <h5 class="text-primary mb-3">THÔNG TIN ĐƠN HÀNG</h5>
                    <p class="mb-1"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                    <p class="mb-1"><strong>Trạng thái:</strong> 
                        @switch($order->status)
                            @case('pending')
                                <span class="badge bg-warning">Chờ xử lý</span>
                                @break
                            @case('processing')
                                <span class="badge bg-info">Đang xử lý</span>
                                @break
                            @case('shipped')
                                <span class="badge bg-primary">Đã gửi hàng</span>
                                @break
                            @case('delivered')
                                <span class="badge bg-success">Đã giao hàng</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger">Đã hủy</span>
                                @break
                        @endswitch
                    </p>
                    <p class="mb-1"><strong>Thanh toán:</strong> 
                        @switch($order->payment_method)
                            @case('cod')
                                Thanh toán khi nhận hàng
                                @break
                            @case('bank_transfer')
                                Chuyển khoản ngân hàng
                                @break
                            @case('momo')
                                Ví điện tử MoMo
                                @break
                            @default
                                {{ $order->payment_method }}
                        @endswitch
                    </p>
                    <p class="mb-0"><strong>Trạng thái thanh toán:</strong> 
                        @switch($order->payment_status)
                            @case('pending')
                                <span class="badge bg-warning">Chờ thanh toán</span>
                                @break
                            @case('paid')
                                <span class="badge bg-success">Đã thanh toán</span>
                                @break
                            @case('failed')
                                <span class="badge bg-danger">Thanh toán thất bại</span>
                                @break
                            @case('refunded')
                                <span class="badge bg-info">Đã hoàn tiền</span>
                                @break
                        @endswitch
                    </p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="order-items">
            <h5 class="text-primary mb-3">CHI TIẾT SẢN PHẨM</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="5%">STT</th>
                        <th width="50%">Sản phẩm</th>
                        <th width="15%" class="text-center">Đơn giá</th>
                        <th width="10%" class="text-center">Số lượng</th>
                        <th width="20%" class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $item->product_name }}</strong><br>
                                <small class="text-muted">SKU: {{ $item->product_sku }}</small>
                            </td>
                            <td class="text-center">{{ number_format($item->product_price) }}đ</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-end">{{ number_format($item->total_price) }}đ</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Order Summary -->
        <div class="row">
            <div class="col-md-6 offset-md-6">
                <div class="order-summary">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <span>{{ number_format($order->subtotal) }}đ</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <span>{{ number_format($order->shipping_amount) }}đ</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($order->discount_amount) }}đ</span>
                        </div>
                    @endif
                    @if($order->tax_amount > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Thuế:</span>
                            <span>{{ number_format($order->tax_amount) }}đ</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between total-row">
                        <span>TỔNG CỘNG:</span>
                        <span>{{ number_format($order->total_amount) }}đ</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($order->notes)
            <div class="mt-4">
                <h5 class="text-primary">GHI CHÚ</h5>
                <div class="border p-3 rounded bg-light">
                    {{ $order->notes }}
                </div>
            </div>
        @endif

        <!-- Footer -->
        <div class="footer-note">
            <p class="mb-1">Cảm ơn quý khách đã tin tưởng và mua sắm tại BookStore!</p>
            <p class="mb-1">Hóa đơn được in vào: {{ now()->format('d/m/Y H:i:s') }}</p>
            <p class="mb-0">Mọi thắc mắc xin liên hệ: (028) 1234 5678 hoặc info@bookstore.vn</p>
        </div>
    </div>

    <script>
        // Auto print when loaded (optional)
        // window.onload = function() { window.print(); }
        
        // Print function
        function printInvoice() {
            window.print();
        }
    </script>
</body>
</html>
