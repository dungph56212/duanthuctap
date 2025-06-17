@extends('admin.layouts.app')

@section('title', 'Chi tiết danh mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Chi tiết danh mục: {{ $category->name }}</h1>
    <div class="btn-group">
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
            <i class="fas fa-edit me-2"></i>Chỉnh sửa
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thông tin danh mục</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Tên danh mục:</th>
                                <td>{{ $category->name }}</td>
                            </tr>
                            <tr>
                                <th>Slug:</th>
                                <td><code>{{ $category->slug }}</code></td>
                            </tr>
                            <tr>
                                <th>Danh mục cha:</th>
                                <td>
                                    @if($category->parent)
                                        <a href="{{ route('admin.categories.show', $category->parent) }}" class="text-decoration-none">
                                            {{ $category->parent->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Không có</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Thứ tự:</th>
                                <td>{{ $category->sort_order }}</td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success">Kích hoạt</span>
                                    @else
                                        <span class="badge bg-danger">Vô hiệu hóa</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        @if($category->image)
                        <div class="text-center">
                            <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" 
                                 class="img-fluid rounded" style="max-width: 300px;">
                        </div>
                        @else
                        <div class="text-center text-muted">
                            <i class="fas fa-image fa-5x mb-3"></i>
                            <p>Chưa có hình ảnh</p>
                        </div>
                        @endif
                    </div>
                </div>

                @if($category->description)
                <hr>
                <div>
                    <h6>Mô tả:</h6>
                    <p class="text-muted">{{ $category->description }}</p>
                </div>
                @endif
            </div>
        </div>

        @if($category->children->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Danh mục con ({{ $category->children->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tên</th>
                                <th>Slug</th>
                                <th>Thứ tự</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->children as $child)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.categories.show', $child) }}" class="text-decoration-none">
                                        {{ $child->name }}
                                    </a>
                                </td>
                                <td><code>{{ $child->slug }}</code></td>
                                <td>{{ $child->sort_order }}</td>
                                <td>
                                    @if($child->is_active)
                                        <span class="badge bg-success">Kích hoạt</span>
                                    @else
                                        <span class="badge bg-danger">Vô hiệu hóa</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.categories.show', $child) }}" class="btn btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $child) }}" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if($category->products->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Sản phẩm trong danh mục ({{ $category->products->count() }})</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->products->take(10) as $product)
                            <tr>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                             class="rounded" width="50" height="50" style="object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.show', $product) }}" class="text-decoration-none">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td>{{ number_format($product->price) }}đ</td>
                                <td>{{ $product->stock_quantity }}</td>
                                <td>
                                    @if($product->is_active)
                                        <span class="badge bg-success">Hoạt động</span>
                                    @else
                                        <span class="badge bg-danger">Tạm ngưng</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @if($category->products->count() > 10)
                <div class="text-center mt-3">
                    <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="btn btn-outline-primary">
                        Xem tất cả sản phẩm
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Thông tin hệ thống</h6>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <th>ID:</th>
                        <td>{{ $category->id }}</td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật cuối:</th>
                        <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Số danh mục con:</th>
                        <td>{{ $category->children->count() }}</td>
                    </tr>
                    <tr>
                        <th>Số sản phẩm:</th>
                        <td>{{ $category->products->count() }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Thao tác nhanh</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>Chỉnh sửa danh mục
                    </a>
                    <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Thêm sản phẩm
                    </a>
                    <a href="{{ route('admin.categories.create', ['parent_id' => $category->id]) }}" class="btn btn-info">
                        <i class="fas fa-plus me-2"></i>Thêm danh mục con
                    </a>
                    <hr>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" 
                          onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash me-2"></i>Xóa danh mục
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
