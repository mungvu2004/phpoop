@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/product-detail.css') }}">
@endpush
@push('scripts')
    <script src="{{ file_url('assets/js/client/product-detail.js') }}"></script>
@endpush
@section('content')
    <div class="container1">
        <div class="breadcrumb">
            <a href="{{ route_url('home') }}">Home</a> <span>/</span>
            <a href="{{ route_url('shop') }}">Shop</a> <span>/</span>
            <a href="{{ route_url('men') }}">Men</a> <span>/</span>
            T-shirts
        </div>
    </div>
    <div class="product-detail">
        <div class="product-gallery">
            @php
                $imgPath = $productDetail['image_url'];
                $defaultImg = file_url('storage/Badge/Badge-1.png');
                $imgSrc = file_exists($imgPath) ? file_url($imgPath) : $defaultImg;
            @endphp
            <div class="product-main-image">
                <div class="product-img">
                    <img src="{{$imgSrc}}" alt="" class="zoom-image">
                </div>
            </div>
        </div>
        <form action="/order_detail/create" method="post">
            <div class="product-info">
                <input type="hidden" name="product_id" value="{{ $productDetail['id'] }}">
                @if(!empty($orders) && is_iterable($orders))
                    @foreach ($orders as $order)
                        @if($order['status'] == 'pending')
                            <input type="hidden" name="order_id" value="{{$order['id']}}">
                        @endif
                    @endforeach
                @else
                    <input type="hidden" name="order_id" value="">
                @endif
                <h1 class="product-title">{{ $productDetail['name'] }}</h1>
                <div class="product-rating">
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
                    <span
                        class="review-count">({{ isset($item['review_count']) ? number_format($item['review_count'], 1) . 'K' : '0' }})</span>
                </div>
                <div class="product-price">
                    <span class="old-price">{{ number_format($productDetail['price'], 0, ',', '.') }} VNĐ</span>
                </div>

                <div class="product-size">
                    @foreach ($productSize as $size)
                        <button type="button" class="size-option" data-size-id="{{$size['id']}}">{{ $size['size_name'] }}</button>
                    @endforeach
                    <input type="hidden" name="size_id" id="selected_size_id" value="">
                </div>
                <div class="action-row">
                    <div class="quantity-selector">
                        <div class="quantity-btn minus">-</div>
                        <input type="text" name="quantity" class="quantity-input" value="1" readonly>
                        <div class="quantity-btn plus">+</div>
                    </div>

                    <button type="submit" class="add-to-cart">ADD TO CART</button>
                </div>

            </div>
        </form>
    </div>

    {{-- Tabs Section --}}
    <div class="container2">
        <div class="tabs">
            <div class="tab-header">
                <div class="tab-item active" data-tab="details">Product Details</div>
                <div class="tab-item" data-tab="reviews">Rating & Reviews</div>
                <div class="tab-item" data-tab="faqs">FAQs</div>
            </div>
        </div>

        <div class="tab-content">
            {{-- Product Details --}}
            <div class="tab-panel active" id="details">
                <div class="product-card">

                    <p class="description">
                        {{ $productDetail['description'] ?: 'No product description available.' }}
                    </p>

                    <ul class="product-specs">
                        <li><strong>Material:</strong> 100% Cotton</li>
                        <li><strong>Color:</strong> {{ $productDetail['color'] ?? 'Various' }}</li>
                        <li><strong>Available Sizes:</strong>
                            @php $count = count($productSize); @endphp
                            @foreach ($productSize as $index => $size)
                                {{ $size['size_name'] }}@if ($index < $count - 1)
                                    ,
                                @endif
                            @endforeach
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Rating & Reviews --}}
            <div class="tab-panel" id="product-reviews-section">
                <div class="prd-reviews-container">
                    <h2 class="prd-reviews-title">
                        All Reviews ({{ isset($productDetail['review_count']) ? $productDetail['review_count'] : '25' }})
                    </h2>

                    <div class="prd-reviews-header">
                        <div class="prd-reviews-sort">
                            <div class="prd-sort-button prd-active">Latest</div>
                        </div>
                        <button class="prd-write-review-btn">Write a Review</button>
                    </div>

                    <div class="prd-reviews-list">
                        @if (isset($productReview) && !empty($productReview['review']))
                                            <div class="prd-review-item">
                                                <div class="prd-review-header">
                                                    <div class="prd-reviewer-info">
                                                        <span class="prd-reviewer-name">{{ $productReview['user_name'] ?? 'Customer' }}</span>
                                                        <span class="prd-verified-badge"></span>
                                                    </div>
                                                    <div class="prd-more-options">···</div>
                                                </div>

                                                <div class="prd-review-rating">
                                                    <div class="prd-stars">
                                                        @php
                                                            $rating = isset($productReview['rating'])
                                                                ? floor($productReview['rating'])
                                                                : 5;
                                                            $remainingStars = 5 - $rating;
                                                        @endphp

                                                        @for ($i = 0; $i < $rating; $i++)
                                                            <span class="prd-star">★</span>
                                                        @endfor

                                                        @for ($i = 0; $i < $remainingStars; $i++)
                                                            <span class="prd-star">☆</span>
                                                        @endfor
                                                    </div>
                                                </div>

                                                <div class="prd-review-content">
                                                    "{{ $productReview['review'] }}"
                                                </div>

                                                <div class="prd-review-date">
                                                    Posted on {{ $productReview['date'] ?? 'August 15, 2023' }}
                                                </div>
                                            </div>
                        @else
                            <div class="prd-review-item">
                                <div class="prd-review-header">
                                    <div class="prd-reviewer-info">
                                        <span class="prd-reviewer-name">Samantha O.</span>
                                        <span class="prd-verified-badge"></span>
                                    </div>
                                    <div class="prd-more-options">···</div>
                                </div>

                                <div class="prd-review-rating">
                                    <div class="prd-stars">
                                        @for ($i = 0; $i < 5; $i++)
                                            <span class="prd-star">★</span>
                                        @endfor
                                    </div>
                                </div>

                                <div class="prd-review-content">
                                    "I absolutely love this t-shirt! Very comfortable and stylish."
                                </div>

                                <div class="prd-review-date">Posted on August 15, 2023</div>
                            </div>
                        @endif
                    </div>

                    <div class="prd-load-more-reviews">Load More Reviews</div>
                </div>
            </div>

            {{-- FAQs --}}
            <div class="tab-panel" id="faqs">
                <div class="faq-item">
                    <h4>Q: What is the return policy?</h4>
                    <p>A: You can return this product within 14 days of receiving it.</p>
                </div>
                <div class="faq-item">
                    <h4>Q: Is this t-shirt machine washable?</h4>
                    <p>A: Yes, it's machine washable. We recommend using cold water.</p>
                </div>
            </div>
        </div>
    </div>


    {{-- You Might Also Like Section --}}
    <div class="review">
        <div class="related-products">
            <h2 class="section-title">YOU MIGHT ALSO LIKE</h2>

            <div class="product-grid">
                {{-- Product Card 1 --}}
                <div class="product-card">
                    <img src="{{ file_url('assets/images/product-polo-blue.jpg') }}" alt="Polo with Contrast Trims">
                    <div class="product-card-info">
                        <h3 class="product-card-title">Polo with Contrast Trims</h3>
                        <div class="product-rating">
                            <div class="stars">
                                @for ($i = 0; $i < 5; $i++)
                                    <span class="star">★</span>
                                @endfor
                            </div>
                            <span class="rating-count">(420)</span>
                        </div>
                        <div class="product-card-price">
                            <span class="old-price">${{ $productDetail['price'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Additional product cards --}}
                <div class="product-card">
                    <img src="{{ file_url('assets/images/product-gradient.jpg') }}" alt="Gradient Graphic T-shirt">
                    <div class="product-card-info">
                        <h3 class="product-card-title">Gradient Graphic T-shirt</h3>
                        <div class="product-rating">
                            <div class="stars">
                                @for ($i = 0; $i < 4; $i++)
                                    <span class="star">★</span>
                                @endfor
                                <span class="star">☆</span>
                            </div>
                            <span class="rating-count">(335)</span>
                        </div>
                        <div class="product-card-price">
                            <span class="old-price">${{ $productDetail['price'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- More product cards can be added here --}}
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sizeOptions = document.querySelectorAll('.size-option');
        const sizeInput = document.getElementById('selected_size_id');
        const addToCartBtn = document.querySelector('.add-to-cart');
        
        // Add click event to size options
        sizeOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Remove active class from all options
                sizeOptions.forEach(opt => opt.classList.remove('active'));
                // Add active class to clicked option
                this.classList.add('active');
                // Update hidden input value
                sizeInput.value = this.getAttribute('data-size-id');
            });
        });

        // Add form submit validation
        document.querySelector('form').addEventListener('submit', function(e) {
            if (!sizeInput.value) {
                e.preventDefault();
                alert('Vui lòng chọn size trước khi thêm vào giỏ hàng');
            }
        });
    });
    </script>

@endsection