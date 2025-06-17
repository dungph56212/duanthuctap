@extends('client.layouts.app')

@section('title', 'Về chúng tôi')
@section('description', 'Tìm hiểu về BookStore - cửa hàng sách trực tuyến hàng đầu Việt Nam với hàng ngàn đầu sách chất lượng.')

@section('content')
<div class="container py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 mb-4">Về chúng tôi</h1>
            <p class="lead text-muted">Câu chuyện về BookStore - Người bạn đồng hành trong hành trình tri thức</p>
        </div>
    </div>

    <!-- Story Section -->
    <div class="row mb-5">
        <div class="col-lg-6 mb-4">
            <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" 
                 class="img-fluid rounded shadow" 
                 alt="Về BookStore">
        </div>
        <div class="col-lg-6">
            <h2 class="h3 mb-4">Câu chuyện của chúng tôi</h2>
            <p class="mb-3">
                BookStore được thành lập vào năm 2020 với sứ mệnh mang tri thức đến gần hơn với mọi người. 
                Chúng tôi tin rằng sách không chỉ là nguồn kiến thức mà còn là cầu nối kết nối con người với thế giới xung quanh.
            </p>
            <p class="mb-3">
                Với hệ thống cửa hàng trực tuyến hiện đại, chúng tôi cung cấp hàng ngàn đầu sách từ nhiều thể loại khác nhau: 
                văn học, khoa học, kinh tế, tâm lý học, thiếu nhi, và nhiều lĩnh vực khác.
            </p>
            <p class="mb-4">
                Đội ngũ BookStore luôn nỗ lực để mang đến cho khách hàng trải nghiệm mua sắm tuyệt vời nhất 
                với dịch vụ chuyên nghiệp và tận tâm.
            </p>
            <div class="row text-center">
                <div class="col-4">
                    <h4 class="text-primary">10,000+</h4>
                    <p class="small text-muted">Đầu sách</p>
                </div>
                <div class="col-4">
                    <h4 class="text-primary">50,000+</h4>
                    <p class="small text-muted">Khách hàng</p>
                </div>
                <div class="col-4">
                    <h4 class="text-primary">99%</h4>
                    <p class="small text-muted">Hài lòng</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mission Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-bullseye fa-3x text-primary"></i>
                                </div>
                                <h4>Sứ mệnh</h4>
                                <p class="text-muted">
                                    Mang tri thức đến gần hơn với mọi người thông qua việc cung cấp sách chất lượng 
                                    với giá cả hợp lý và dịch vụ tận tâm.
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-eye fa-3x text-primary"></i>
                                </div>
                                <h4>Tầm nhìn</h4>
                                <p class="text-muted">
                                    Trở thành nền tảng mua sắm sách trực tuyến hàng đầu Việt Nam, 
                                    góp phần xây dựng xã hội học tập suốt đời.
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-heart fa-3x text-primary"></i>
                                </div>
                                <h4>Giá trị</h4>
                                <p class="text-muted">
                                    Chất lượng, uy tín, sáng tạo và luôn đặt khách hàng làm trung tâm 
                                    trong mọi hoạt động kinh doanh.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="row mb-5">
        <div class="col-12 text-center mb-5">
            <h2 class="h3">Đội ngũ của chúng tôi</h2>
            <p class="text-muted">Những con người tận tâm đằng sau thành công của BookStore</p>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 text-center">
                <div class="card-body">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=300&q=80" 
                         class="rounded-circle mb-3" 
                         width="100" height="100" 
                         style="object-fit: cover;" 
                         alt="CEO">
                    <h5>Nguyễn Văn A</h5>
                    <p class="text-muted small">CEO & Founder</p>
                    <p class="small">10 năm kinh nghiệm trong ngành xuất bản và phân phối sách.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 text-center">
                <div class="card-body">
                    <img src="https://images.unsplash.com/photo-1494790108755-2616b612b1e8?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=300&q=80" 
                         class="rounded-circle mb-3" 
                         width="100" height="100" 
                         style="object-fit: cover;" 
                         alt="CTO">
                    <h5>Trần Thị B</h5>
                    <p class="text-muted small">CTO</p>
                    <p class="small">Chuyên gia công nghệ với nhiều năm kinh nghiệm phát triển e-commerce.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 text-center">
                <div class="card-body">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=300&q=80" 
                         class="rounded-circle mb-3" 
                         width="100" height="100" 
                         style="object-fit: cover;" 
                         alt="Head of Marketing">
                    <h5>Lê Văn C</h5>
                    <p class="text-muted small">Trưởng phòng Marketing</p>
                    <p class="small">Chuyên gia marketing số với đam mê lan toa văn hóa đọc.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card border-0 text-center">
                <div class="card-body">
                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=300&q=80" 
                         class="rounded-circle mb-3" 
                         width="100" height="100" 
                         style="object-fit: cover;" 
                         alt="Customer Service">
                    <h5>Phạm Thị D</h5>
                    <p class="text-muted small">Trưởng phòng CSKH</p>
                    <p class="small">Luôn sẵn sàng hỗ trợ khách hàng với tinh thần nhiệt tình và chuyên nghiệp.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h2 class="h3">Tại sao chọn BookStore?</h2>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-shipping-fast fa-2x text-primary"></i>
                </div>
                <div>
                    <h5>Giao hàng nhanh chóng</h5>
                    <p class="text-muted">Giao hàng trong 1-3 ngày trên toàn quốc với đối tác vận chuyển uy tín.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-medal fa-2x text-primary"></i>
                </div>
                <div>
                    <h5>Sản phẩm chính hãng</h5>
                    <p class="text-muted">100% sách chính hãng từ các nhà xuất bản uy tín trong và ngoài nước.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-headset fa-2x text-primary"></i>
                </div>
                <div>
                    <h5>Hỗ trợ 24/7</h5>
                    <p class="text-muted">Đội ngũ CSKH luôn sẵn sàng hỗ trợ bạn mọi lúc, mọi nơi.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-undo-alt fa-2x text-primary"></i>
                </div>
                <div>
                    <h5>Đổi trả dễ dàng</h5>
                    <p class="text-muted">Chính sách đổi trả linh hoạt trong vòng 7 ngày nếu có lỗi từ nhà sản xuất.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-gift fa-2x text-primary"></i>
                </div>
                <div>
                    <h5>Ưu đãi hấp dẫn</h5>
                    <p class="text-muted">Nhiều chương trình khuyến mãi và ưu đãi đặc biệt dành cho khách hàng thân thiết.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="d-flex">
                <div class="me-3">
                    <i class="fas fa-lock fa-2x text-primary"></i>
                </div>
                <div>
                    <h5>Bảo mật thông tin</h5>
                    <p class="text-muted">Cam kết bảo mật tuyệt đối thông tin cá nhân và giao dịch của khách hàng.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
