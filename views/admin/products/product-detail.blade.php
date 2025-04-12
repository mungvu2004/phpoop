@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/admin/product-detail.css') }}">
@endpush
@push('scripts')
    <script src="{{ file_url('assets/js/product-detail.js') }}"></script>
@endpush
@section('content')
    <main class="main">

        {{-- <pre>{{ print_r($product) }}</pre>
        <pre>{{ print_r($review) }}</pre>
        
        <pre>{{ print_r($sizes) }}</pre> --}}
        @php
            $defaultImg = 'storage/uploads/users/error.png';
            $imagePath = file_exists($product['image_url']) ? $product['image_url'] : $defaultImg;
            $rating = 0;
            $count = 0;
            $sizeDefault = ['S', 'M', 'L', 'XL'];
            foreach($review as $item) {
                $count++;
                $rating += $item['rating'];
            }
            if($count != 0){
                $averageRating = $rating / $count;
            }
            else {
                $averageRating = 0;
            }
        @endphp
        <div class="product-img">
            <img src="{{file_url($imagePath)}}" alt="{{ $product['name'] }}" class="zoom-image">
        </div>
        <div class="main-product">
            <div class="product-content">
                <h1>{{ $product['name'] }}</h1>
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= floor($averageRating)): ?>
                            <span class="star">&#9733;</span> <!-- sao đầy -->
                        <?php elseif ($i - $averageRating < 1): ?>
                            <span class="star half">&#9733;</span> <!-- sao nửa (giả lập) -->
                        <?php else: ?>
                            <span class="star">&#9734;</span> <!-- sao rỗng -->
                        <?php endif; ?>
                    <?php endfor; ?>
                    <span>{{$averageRating}}/5</span>
                </div>
                <div class="price">
                    <span>{{$product['price'] }}</span>
                </div>
                <div class="description">
                    <p>{{$product['description']}}</p>
                </div>
                <div class="size">
                    @foreach ($sizes as $size)
                        <div class="size-item">
                            <span>{{$size['size_name']}}</span>
                        </div>
                    @endforeach
                </div>
                <div class="stock">
                    <h4>Tổng số lượng: {{$product['stock_quantity']}}</h4>
                    @foreach ($sizes as $size)
                        <div class="stock-item">
                            <span>{{$size['size_name']}}</span>
                            <span>{{ $size['stock_quantity'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="edit-product">
                <button class="edit">Chỉnh sửa</button>
                <!-- Nút Xóa sản phẩm -->
                <form action="/admin/product/delete/{{ $product['id'] }}" method="POST" onsubmit="return confirmDelete()">
                    <button type="submit" class="delete">Xóa sản phẩm</button>
                </form>
            </div>
        </div>
    </main>
    <div class="review">
        @foreach ($review as $user)
            <div class="review-conten">
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?php if ($i <= floor($user['rating'])): ?>
                            <span class="star">&#9733;</span> <!-- sao đầy -->
                        <?php elseif ($i - $user['rating'] < 1): ?>
                            <span class="star half">&#9733;</span> <!-- sao nửa (giả lập) -->
                        <?php else: ?>
                            <span class="star">&#9734;</span> <!-- sao rỗng -->
                        <?php endif; ?>
                    <?php endfor; ?>
                </div>
                <div class="comment">
                    <p>{{ $user['comment'] }}</p>
                </div>
                <div class="time">
                    <p>{{ $user['created_at'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="form">
        <form action="/admin/product/edit/{{ $product['id'] }}" method="post" enctype="multipart/form-data" onsubmit="return confirmSubmit()">
            <div class="form-title">
                <h2>Chỉnh sửa sản phẩm</h2>
            </div>
            <div class="form-input">
                <div class="input-name input1">
                    <h4>Tên sản phẩm</h4>
                    <input type="text" name="name" placeholder="Nhập tên sản phẩm" value="{{$product['name']}}" required>
                </div>
                
                <div class="input-price input1">
                    <h4>Số lượng</h4>
                    <input type="number" name="stock_quantity" placeholder="Số lượng" value="{{ $product['stock_quantity'] }}" required>
                </div>
                <div class="input-stock input1">
                    <h4>Đơn giá</h4>
                    <input type="text" name="price" placeholder="Đơn giá" value="{{ $product['price'] }}" required>
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
            <div class="form-size">
                <h4>Chọn các size có sẵn:</h4>
                @php
                    $selectedSizes = [];
                    foreach ($sizes as $item) {
                        $selectedSizes[] = $item['size_name'];
                    }
                @endphp

                @foreach ($sizeDefault as $size)
                    <label>
                        <input 
                            type="checkbox" 
                            name="sizes[]" 
                            value="{{ $size }}" 
                            {{ in_array($size, $selectedSizes) ? 'checked' : '' }}>
                        {{ $size }}
                    </label>
                @endforeach


            </div>
            <div class="des-img">
                <div class="form-des">
                    <h4>Mô tả sản phẩm</h4>
                    <textarea name="description" maxlength="500" rows="5" cols="50" placeholder="Nhập tối đa 500 ký tự...">{{ $product['description'] }}</textarea>
                </div>
                <div class="form-img">
                    <div class="img-button">
                        <h4>Thêm ảnh sản phẩm</h4>
                        <div class="file-upload">
                            <input type="file" name="image" id="fileInput" style="display: none;" accept="image/*">
                            <button type="button" onclick="document.getElementById('fileInput').click();">Chọn tệp</button>
                        </div>
                    </div>
                    <div class="preview-container" id="preview">
                        <img src="{{ file_url($imagePath) }}" alt="">
                    </div>
                </div>
            </div>
            <div class="form-button">
                <input type="submit" value="Chỉnh sửa">
                <input class="close-form" type="button" value="Hủy">
            </div>
        </form>
    </div>
@endsection