@extends('client.layouts.app')

@section('title', 'Thêm địa chỉ mới')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-map-marker-alt me-3 text-primary"></i>
                        <h5 class="mb-0">Thêm địa chỉ mới</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('client.addresses.store') }}" id="address-form">
                        @csrf

                        @component('components.enhanced-address-form', [
                            'prefix' => '',
                            'defaultName' => auth()->user()->name,
                            'defaultPhone' => auth()->user()->phone,
                            'showDefaultOption' => true,
                            'showAddressType' => true
                        ])
                        @endcomponent

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <a href="{{ route('client.addresses.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save me-2"></i>Lưu địa chỉ
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Tips -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="text-primary mb-3">
                        <i class="fas fa-lightbulb me-2"></i>Mẹo hữu ích
                    </h6>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled small text-muted">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Hãy nhập đầy đủ thông tin để đảm bảo giao hàng chính xác
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Số điện thoại sẽ được sử dụng để liên hệ khi giao hàng
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled small text-muted">
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Địa chỉ mặc định sẽ được chọn tự động khi đặt hàng
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    Bạn có thể thêm nhiều địa chỉ cho các mục đích khác nhau
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/address-form.js') }}"></script>
<script src="{{ asset('js/enhanced-address-form.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('address-form');
    const addressHandler = new EnhancedAddressFormHandler('');

    // Enhanced form validation
    form.addEventListener('submit', function(e) {
        const validation = addressHandler.validateForm();
        
        if (!validation.isValid) {
            e.preventDefault();
            
            // Show errors in a toast notification
            validation.errors.forEach(error => {
                showToast(error, 'error');
            });
            
            // Focus on first invalid field
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        const toastHtml = `
            <div class="toast align-items-center text-white bg-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'primary'} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">${message}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        `;
        
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = toastContainer.lastElementChild;
        const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
        toast.show();
        
        toastElement.addEventListener('hidden.bs.toast', function() {
            toastElement.remove();
        });
    }
});
</script>
@endpush
