@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/admin/product.css') }}">
@endpush
@push('scripts')
    <script src="{{ file_url('assets/js/product.js') }}"></script>
@endpush

@section('content')
    <main class="main">
        <div class="main-title">
            <h1 class="title-h1"></h1>
            <button class="create-product">Thêm mới</button>
        </div>
        
        @php
            $products = $product['data'];
            $currentPage = $product['page'];
            $totalPage = $product['totalPage'];
        @endphp
        
        <div class="main-product">
            @foreach($products as $item)
                <div class="product-item">
                    @php
                        $imgPath = 'storage/uploads/' . $item['image_url'];
                        $defaultImg = file_url("storage/uploads/users/download.jpg");
                        $imgSrc = file_exists($imgPath) ? file_url($imgPath) : $defaultImg;
                    @endphp 
                    <img src="{{ $imgSrc }}" alt="{{ $item['name'] }}">
                    <div class="item-tp">
                        <h3>{{ $item['name'] }}</h3>
                        <span>Đơn giá: {{ number_format($item['price'], 0, ',', '.') }} VNĐ</span>
                        <span>Số lượng: {{ $item['stock_quantity'] }}</span>
                        <button>Chỉnh sửa</button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Phân trang -->
        @if($totalPage >= 1)
            <div class="pagination">
                @if($currentPage >= 1)
                    <a href="?page={{1}}" class="page-link"><<</a>
                    <a href="?page={{ $currentPage - 1 }}" class="page-link"><</a>
                @endif

                <!-- Hiển thị các số trang -->
                @for($i = 1; $i <= $totalPage; $i++)
                    <a href="?page={{ $i }}" 
                       class="page-number {{ $currentPage == $i ? 'active' : '' }}">
                        {{ $i }} ... 
                    </a>
                    @if($totalPage > 1)
                        <a href="?page={{ $totalPage}}" class="page-link">{{$totalPage}}</a>
                    @endif 
                @endfor

                @if($currentPage <= $totalPage)
                    <a href="?page={{ $currentPage + 1 }}" class="page-link">></a>
                    <a href="?page={{ $totalPage}}" class="page-link">>></a>
                @endif
            </div>
        @endif
    </main>
    <div class="form">
        <form action="{{ route_url('admin/product/create') }}" method="post" enctype="multipart/form-data">
            <div class="form-title">
                <h2>Thêm sản phẩm</h2>
            </div>
            <div class="form-input">
                <div class="input-name input1">
                    <h4>Tên sản phẩm</h4>
                    <input type="text" name="name" placeholder="Nhập tên sản phẩm" required>
                </div>
                <div class="input-category input1">
                    <h4>Loại</h4>
                    <select name="category_id" required>
                        @foreach($category as $item)
                            <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-price input1">
                    <h4>Số lượng</h4>
                    <input type="number" name="stock_quantity" placeholder="Số lượng" required>
                </div>
                <div class="input-stock input1">
                    <h4>Đơn giá</h4>
                    <input type="text" name="price" placeholder="Đơn giá" required>
                </div>
                <div class="input-is">
                    <h4>Trạng thái</h4>
                    <div class="active">
                        <label>
                            <input type="radio" name="is_active" value="1" checked> Có
                        </label>
                        <label>
                            <input type="radio" name="is_active" value="0"> Không
                        </label>
                    </div>
                </div>
            </div>
            <div class="des-img">
                <div class="form-des">
                    <h4>Mô tả sản phẩm</h4>
                    <textarea name="description" maxlength="500" rows="5" cols="50" placeholder="Nhập tối đa 500 ký tự..."></textarea>
                </div>
                <div class="form-img">
                    <div class="img-button">
                        <h4>Thêm ảnh sản phẩm</h4>
                        <div class="file-upload">
                            <input type="file" name="image" id="fileInput" style="display: none;" accept="image/*">
                            <button type="button" onclick="document.getElementById('fileInput').click();">Chọn tệp</button>
                        </div>
                    </div>
                    <div class="preview-container" id="preview"></div>
                </div>
            </div>
            <div class="form-button">
                <input type="submit" value="Thêm">
                <input class="close-form" type="button" value="Hủy">
            </div>
        </form>
    </div>
@endsection