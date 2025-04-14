@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/product-detail.css') }}">
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
        {{-- <pre>{{ print_r($productDetail) }}</pre> --}}
        <div class="product-gallery">
            <div class="product-thumbnails">
                @php
                    $imgPath = $productDetail['image_url'];
                    $defaultImg = file_url('storage/Badge/Badge-1.png');
                    $imgSrc = file_exists($imgPath) ? file_url($imgPath) : $defaultImg;
                @endphp
                @for ($i = 0; $i <= 2; $i++)
                    <img src="{{ $imgSrc }}"></img>
                @endfor
            </div>
            <div class="product-main-image">
                <img src="{{ $imgSrc }}"></img>
            </div>
        </div>

        <div class="product-info">
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
                <span class="old-price">${{ $productDetail['price'] }}</span>
            </div>

            <div class="product-size">
                {{-- <pre>{{ print_r($productSize) }}</pre> --}}
                @foreach ($productSize as $size)
                    <button class="size-option">{{ $size['size_name'] }}</button>
                @endforeach
            </div>

            <div class="action-row">
                <div class="quantity-selector">
                    <div class="quantity-btn minus">-</div>
                    <input type="text" class="quantity-input" value="1" readonly>
                    <div class="quantity-btn plus">+</div>
                </div>

                <button class="add-to-cart">ADD TO CART</button>
            </div>
        </div>
    </div>

    {{-- Tabs Section --}}
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
                    <h2>Product Details</h2>
            
                    <h3>{{ $productDetail['name'] ?? 'Unnamed Product' }}</h3>
                    <p class="price">{{ number_format($productDetail['price'], 0, ',', '.') }} VND</p>
            
                    <p class="description">
                        {{ $productDetail['description'] ?: 'No product description available.' }}
                    </p>
            
                    <ul class="product-specs">
                        <li><strong>Material:</strong> 100% Cotton</li>
                        <li><strong>Color:</strong> {{ $productDetail['color'] ?? 'Various' }}</li>
                        <li><strong>Available Sizes:</strong>
                            @php $count = count($productSize); @endphp
                            @foreach ($productSize as $index => $size)
                                {{ $size['size_name'] }}@if ($index < $count - 1), @endif
                            @endforeach
                        </li>
                    </ul>
                </div>
            </div>

            {{-- Rating & Reviews --}}
            <div class="tab-panel" id="reviews">
                <div class="reviews-container">
                    <h2 class="reviews-title">All Reviews
                        ({{ isset($productDetail['review_count']) ? $productDetail['review_count'] : '25' }})</h2>

                    <div class="reviews-header">
                        <div class="reviews-sort">
                            <div class="sort-button active">Latest</div>
                        </div>
                        <button class="write-review-btn">Write a Review</button>
                    </div>

                    <div class="reviews-list">
                        @if (isset($productReview) && !empty($productReview['review']))
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <span class="reviewer-name">{{ $productReview['user_name'] ?? 'Customer' }}</span>
                                        <span class="verified-badge">✓</span>
                                    </div>
                                    <div class="more-options">···</div>
                                </div>

                                <div class="review-rating">
                                    <div class="stars">
                                        @php
                                            $rating = isset($productReview['rating'])
                                                ? floor($productReview['rating'])
                                                : 5;
                                            $remainingStars = 5 - $rating;
                                        @endphp

                                        @for ($i = 0; $i < $rating; $i++)
                                            <span class="star">★</span>
                                        @endfor

                                        @for ($i = 0; $i < $remainingStars; $i++)
                                            <span class="star">☆</span>
                                        @endfor
                                    </div>
                                </div>

                                <div class="review-content">
                                    "{{ $productReview['review'] }}"
                                </div>

                                <div class="review-date">Posted on {{ $productReview['date'] ?? 'August 15, 2023' }}</div>
                            </div>
                        @else
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <span class="reviewer-name">Samantha O.</span>
                                        <span class="verified-badge">✓</span>
                                    </div>
                                    <div class="more-options">···</div>
                                </div>

                                <div class="review-rating">
                                    <div class="stars">
                                        @for ($i = 0; $i < 5; $i++)
                                            <span class="star">★</span>
                                        @endfor
                                    </div>
                                </div>

                                <div class="review-content">
                                    "I absolutely love this t-shirt! Very comfortable and stylish."
                                </div>

                                <div class="review-date">Posted on August 15, 2023</div>
                            </div>
                        @endif
                    </div>

                    <div class="load-more-reviews">Load More Reviews</div>
                </div>
            </div>

            {{-- FAQs --}}
            <div class="tab-panel" id="faqs">
                <h2>Frequently Asked Questions</h2>
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

    {{-- JavaScript for Interactive Elements --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Thumbnail switching
            const thumbnails = document.querySelectorAll('.thumbnail-item');
            const mainImage = document.querySelector('.product-main-image img');

            thumbnails.forEach(thumbnail => {
                thumbnail.addEventListener('click', function() {
                    // Remove active class from all thumbnails
                    thumbnails.forEach(item => item.classList.remove('active'));

                    // Add active class to clicked thumbnail
                    this.classList.add('active');

                    // Update main image
                    mainImage.src = this.querySelector('img').src;
                });
            });

            // Color selection
            const colorOptions = document.querySelectorAll('.color-option');

            colorOptions.forEach(option => {
                option.addEventListener('click', function() {
                    colorOptions.forEach(item => item.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Size selection
            const sizeOptions = document.querySelectorAll('.size-option');

            sizeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    sizeOptions.forEach(item => item.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Quantity selector
            const minusBtn = document.querySelector('.quantity-btn.minus');
            const plusBtn = document.querySelector('.quantity-btn.plus');
            const quantityInput = document.querySelector('.quantity-input');

            minusBtn.addEventListener('click', function() {
                let quantity = parseInt(quantityInput.value);
                if (quantity > 1) {
                    quantityInput.value = quantity - 1;
                }
            });

            plusBtn.addEventListener('click', function() {
                let quantity = parseInt(quantityInput.value);
                quantityInput.value = quantity + 1;
            });

            // Tab switching
            // Tab switching
            const tabItems = document.querySelectorAll('.tab-item');
            const tabPanels = document.querySelectorAll('.tab-panel');

            tabItems.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active state from all tabs and panels
                    tabItems.forEach(item => item.classList.remove('active'));
                    tabPanels.forEach(panel => panel.classList.remove('active'));

                    // Activate current tab
                    this.classList.add('active');

                    // Show associated panel
                    const tabTarget = this.getAttribute('data-tab');
                    document.getElementById(tabTarget).classList.add('active');
                });
            });
        });
    </script>
@endsection
