@extends('client.layouts.app')

@section('title', 'Liên hệ')
@section('description', 'Liên hệ với BookStore để được hỗ trợ tốt nhất. Chúng tôi luôn sẵn sàng lắng nghe và giải đáp mọi thắc mắc của bạn.')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 mb-4">Liên hệ với chúng tôi</h1>
            <p class="lead text-muted">Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn</p>
        </div>
    </div>

    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-8 mb-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h3 class="mb-4">Gửi tin nhắn cho chúng tôi</h3>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>Có lỗi xảy ra:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('client.contact.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label">Chủ đề <span class="text-danger">*</span></label>
                                <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                    <option value="">Chọn chủ đề</option>
                                    <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>Thông tin chung</option>
                                    <option value="order" {{ old('subject') == 'order' ? 'selected' : '' }}>Đơn hàng</option>
                                    <option value="product" {{ old('subject') == 'product' ? 'selected' : '' }}>Sản phẩm</option>
                                    <option value="technical" {{ old('subject') == 'technical' ? 'selected' : '' }}>Kỹ thuật</option>
                                    <option value="complaint" {{ old('subject') == 'complaint' ? 'selected' : '' }}>Khiếu nại</option>
                                    <option value="suggestion" {{ old('subject') == 'suggestion' ? 'selected' : '' }}>Góp ý</option>
                                </select>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Tin nhắn <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="6" 
                                      placeholder="Nhập tin nhắn của bạn..." 
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>Gửi tin nhắn
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Info -->
        <div class="col-lg-4">
            <!-- Contact Details -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="mb-4">Thông tin liên hệ</h4>
                    
                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i class="fas fa-map-marker-alt fa-lg text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Địa chỉ</h6>
                                <p class="text-muted mb-0">123 Đường ABC, Quận 1<br>TP. Hồ Chí Minh, Việt Nam</p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i class="fas fa-phone fa-lg text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Điện thoại</h6>
                                <p class="text-muted mb-0">
                                    <a href="tel:+84901234567" class="text-decoration-none">(+84) 90 123 4567</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i class="fas fa-envelope fa-lg text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Email</h6>
                                <p class="text-muted mb-0">
                                    <a href="mailto:contact@bookstore.com" class="text-decoration-none">contact@bookstore.com</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="d-flex align-items-start">
                            <div class="me-3">
                                <i class="fas fa-clock fa-lg text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Giờ làm việc</h6>
                                <p class="text-muted mb-0">
                                    Thứ 2 - Thứ 6: 8:00 - 18:00<br>
                                    Thứ 7 - CN: 9:00 - 17:00
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="mb-4">Câu hỏi thường gặp</h4>
                    
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item border-0 mb-2">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed bg-light border-0 rounded" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq1">
                                    Làm sao để theo dõi đơn hàng?
                                </button>
                            </h6>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body pt-0">
                                    Bạn có thể theo dõi đơn hàng tại mục "Đơn hàng của tôi" sau khi đăng nhập, hoặc sử dụng mã đơn hàng để tra cứu.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0 mb-2">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed bg-light border-0 rounded" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq2">
                                    Chính sách đổi trả như thế nào?
                                </button>
                            </h6>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body pt-0">
                                    Chúng tôi chấp nhận đổi trả trong vòng 7 ngày nếu sản phẩm có lỗi từ nhà sản xuất hoặc giao sai hàng.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0">
                            <h6 class="accordion-header">
                                <button class="accordion-button collapsed bg-light border-0 rounded" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#faq3">
                                    Thời gian giao hàng bao lâu?
                                </button>
                            </h6>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body pt-0">
                                    Thời gian giao hàng từ 1-3 ngày tùy theo khu vực. Nội thành TP.HCM thường nhận hàng trong 24h.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div style="height: 400px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                        <div class="text-center text-muted">
                            <i class="fas fa-map-marked-alt fa-3x mb-3"></i>
                            <h5>Bản đồ cửa hàng</h5>
                            <p>123 Đường ABC, Quận 1, TP. Hồ Chí Minh</p>
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
// Auto dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush
