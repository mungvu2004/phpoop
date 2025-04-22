@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/list-product.css') }}">
@endpush
@push('scripts')
    <script src="{{file_url('assets/client/list-product.js')}}"></script>
@endpush
@section('content')
    <div class="product-page-container">
        <div class="filter-sidebar">
            <h3>BỘ LỌC</h3>
            <div class="filter-group">
                <h4>DANH MỤC</h4>
                @foreach ($categories as $cat)
                    <label>
                        <input type="checkbox" name="category" value="{{ $cat['id'] }}" onchange="applyFilter()">
                        {{ $cat['name'] }}
                    </label>
                @endforeach
            </div>
<<<<<<< Updated upstream
            <div class="filter-group">
                <h4>GIÁ</h4>
                <div class="range-slider">
                    <input type="range" id="price-min" min="0" max="1000000" step="10000" value="0"
                        oninput="updatePriceRange()">
                    <input type="range" id="price-max" min="0" max="1000000" step="10000" value="1000000"
                        oninput="updatePriceRange()">
=======
            <div class="price-label">
                <span id="price-min-display">0</span> – <span id="price-max-display">1,000,000</span> VNĐ
            </div>
        </div>

        

        <div class="filter-group">
            <h4>KÍCH THƯỚC</h4>
            @foreach (['S', 'M', 'L', 'XL', 'XXL'] as $size)
                <label>
                    <input type="checkbox" name="size" value="{{ $size }}" onchange="applyFilter()">
                    {{ $size }}
                </label>
            @endforeach
        </div>

        <button onclick="applyFilter()">ÁP DỤNG BỘ LỌC</button>
    </div>

    <div class="product-content">
        <div class="product-header">
            <h2 id="category-title">TẤT CẢ SẢN PHẨM</h2>
            <div class="sort-bar">
                <span id="product-count">Hiển thị 1–9 trên {{ count($products) }} sản phẩm</span>
                <select id="sort-filter" onchange="applyFilter()">
                    <option value="default">PHỔ BIẾN NHẤT</option>
                    <option value="price-asc">GIÁ: THẤP ĐẾN CAO</option>
                    <option value="price-desc">GIÁ: CAO ĐẾN THẤP</option>
                </select>
            </div>
        </div>

        <div class="product-grid" id="all-products-grid">
            @foreach ($products as $item)
                <div class="product-card"
                     data-category="{{ $item['category'] }}"
                     data-price="{{ $item['price'] }}"
                     data-color="{{ $item['color'] }}"
                     data-size="{{ $item['size'] }}"
                     data-popularity="{{ $item['popularity'] }}"
                     onclick="goToProductDetail(&quot;{{ $item['id'] }}&quot;)">
                    <img src="{{ file_url($item['image_url']) }}" alt="{{ $item['name'] }}">
                    <h3>{{ $item['name'] }}</h3>
                    <div class="price">
                        {{ number_format($item['price'], 0, ',', '.') }} VNĐ
                    </div>
>>>>>>> Stashed changes
                </div>
                <div class="price-label">
                    <span id="price-min-display">0</span> – <span id="price-max-display">1,000,000</span> VNĐ
                </div>
            </div>

            <button onclick="applyFilter()">ÁP DỤNG BỘ LỌC</button>
        </div>

        <div class="product-content">
            <div class="product-header">
                <h2 id="category-title">TẤT CẢ SẢN PHẨM</h2>
                <div class="sort-bar">
                    <span id="product-count">Hiển thị 1–9 trên {{ count($products) }} sản phẩm</span>
                    <select id="sort-filter" onchange="applyFilter()">
                        <option value="default">PHỔ BIẾN NHẤT</option>
                        <option value="price-asc">GIÁ: THẤP ĐẾN CAO</option>
                        <option value="price-desc">GIÁ: CAO ĐẾN THẤP</option>
                    </select>
                </div>
            </div>
            <div class="product-grid" id="all-products-grid">
                
                @foreach ($products as $item)
                    <div class="product-card" data-category="{{ $item['category_id'] }}" data-price="{{ $item['price'] }}"
                        data-size="{{ $item['size'] }}" data-id="{{$item['id']}}" onclick="goToProductDetail(this)">
                        <img src="{{ file_url($item['image_url']) }}" alt="{{ $item['name'] }}"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-block';">
                        <span class="fa fa-image fallback-icon" style="display:none;"></span>
                        <h3>{{ $item['name'] }}</h3>
                        <div class="rating">
                            <div class="stars">
                                @php
                                    $rating = isset($item['average_rating']) ? floor($item['average_rating']) : 0;
                                    $remainingStars = 5 - $rating;
                                @endphp
                                @for ($i = 0; $i < $rating; $i++)
                                    <div class="star">★</div>
                                @endfor
                                @for ($i = 0; $i < $remainingStars; $i++)
                                    <div class="star">☆</div>
                                @endfor
                            </div>
                            <p class="review-count">({{ (int)$item['average_rating'] . '/5' }})</p>
                        </div>
                        <div class="price">
                            {{ number_format($item['price'], 0, ',', '.') }} VNĐ
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination">
                <button id="prev-page" onclick="changePage(-1)">TRƯỚC</button>
                <div id="page-numbers"></div>
                <button id="next-page" onclick="changePage(1)">SAU</button>
            </div>
        </div>
    </div>
@endsection