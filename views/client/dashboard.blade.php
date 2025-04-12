@extends('client.layouts.main')

@push('dashboard')
    <link rel="stylesheet" href="{{ file_url('assets/client/dashboard.css') }}">
@endpush
@section('content')
<section class="hero-section">
        <div class="hero-content">
            <h1>FIND CLOTHES<br>THAT MATCHES<br>YOUR STYLE</h1>
            <p>Browse through our diverse range of meticulously crafted garments, designed to bring out your individuality and cater to your sense of style.</p>
            <a href="#" class="shop-btn">Shop Now</a>
            
            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">200+</div>
                    <div class="stat-label">International Brands</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">2,000+</div>
                    <div class="stat-label">High Quality Products</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">30,000+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
            </div>
        </div>
        
        <div class="hero-image">
            <img src="/storage/Badge/Banner.png" alt="Stylish models wearing fashionable clothes">
        </div>
        
        <!-- Star decorations -->
        
        
    </section>
    
    <div class="brands-strip">
        <div class="brand versace">VERSACE</div>
        <div class="brand zara">ZARA</div>
        <div class="brand gucci">GUCCI</div>
        <div class="brand prada">PRADA</div>
        <div class="brand calvin-klein">Calvin Klein</div>
    </div>
    <pre>{{ print_r($product4) }}</pre>
        <section class="section-container">
        <h2 class="section-title">NEW ARRIVALS</h2>
        <div class="product-grid">
            @php
                $product = $product4['data']
            @endphp
            <!-- Product 1 -->
             @foreach($product as $item)
             <div class="product-card">
                <div class="product-image">
                    <img src="{{ file_url($item['image_url']) }}" alt="T-shirt with Tape Details">
                </div>
                <h3 class="product-name">{{ $item['name'] }}</h3>
                <div class="rating">
                <div class="stars">
                    @php
                        $rating = isset($item['average_rating']) ? floor($item['average_rating']) : 0;
                        $remainingStars = 5 - $rating;
                    @endphp
                    
                    @for($i = 0; $i < $rating; $i++)
                        <div class="star">★</div>
                    @endfor
                    
                    @for($i = 0; $i < $remainingStars; $i++)
                        <div class="star">☆</div>
                    @endfor
                </div>
                    <span class="review-count">({{ isset($item['review_count']) ? number_format($item['review_count'], 1) . 'K' : '0' }})</span>
                </div>
                <div class="product-price">
                    <span class="current-price">${{ $item['price'] }}</span>
                </div>
            </div>
             @endforeach
                      
        </div>
        <div class="view-all">
            <a href="/views/client/product-detail.blade.php" class="view-all-btn">View All</a>
        </div>
    </section>

    <pre>{{ print_r($product4) }}</pre>
    <section class="section-container">
        <h2 class="section-title">TOP SELLING</h2>
        <div class="product-grid">
            @php
                $product = $product4['data']
            @endphp
            <!-- Product 1 -->
             @foreach($product as $item)
             <div class="product-card">
                <div class="product-image">
                    <img src="{{ file_url($item['image_url']) }}" alt="T-shirt with Tape Details">
                </div>
                <h3 class="product-name">{{ $item['name'] }}</h3>
                <div class="rating">
                <div class="stars">
                    @php
                        $rating = isset($item['average_rating']) ? floor($item['average_rating']) : 0;
                        $remainingStars = 5 - $rating;
                    @endphp
                    
                    @for($i = 0; $i < $rating; $i++)
                        <div class="star">★</div>
                    @endfor
                    
                    @for($i = 0; $i < $remainingStars; $i++)
                        <div class="star">☆</div>
                    @endfor
                </div>
                <span class="review-count">({{ isset($item['review_count']) ? number_format($item['review_count'], 1) . 'K' : '0' }})</span>
                </div>
                <div class="product-price">
                    <span class="current-price">${{ $item['price'] }}</span>
                </div>
            </div>
             @endforeach
                      
        </div>
        <div class="view-all">
            <a href="#" class="view-all-btn">View All</a>
        </div>
    </section>

    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 30px 0;">
        <!-- Browse by Dress Style Section -->
        <section class="style-section">
        <h2 class="section-title">BROWSE BY DRESS STYLE</h2>
        <div class="style-grid">
            <div class="style-row">
                <div class="style-card small">
                    <div class="style-name">Casual</div>
                    <div class="style-image">
                        <img src="/storage/Badge/image 11.png" alt="Casual style clothing">
                    </div>
                </div>
                
                <div class="style-card large">
                    <div class="style-name">Formal</div>
                    <div class="style-image">
                        <img src="/storage/Badge/image 12.png" alt="Formal style clothing">
                    </div>
                </div>
            </div>
            
            <div class="style-row">
                <div class="style-card large">
                    <div class="style-name">Party</div>
                    <div class="style-image">
                        <img src="/storage/Badge/image 13.png" alt="Party style clothing">
                    </div>
                </div>
                
                <div class="style-card small">
                    <div class="style-name">Gym</div>
                    <div class="style-image">
                        <img src="/storage/Badge/image 14.png" alt="Gym style clothing">
                    </div>
                </div>
            </div>
        </div>
    </section>
        
        <!-- Testimonials Section -->
        <section class="testimonials-section">
            <div class="testimonials-header">
                <h2 class="testimonials-title">OUR HAPPY CUSTOMERS</h2>
                <div class="navigation-arrows">
                    <div class="arrow">←</div>
                    <div class="arrow">→</div>
                </div>
            </div>
            
            <div class="testimonials-grid">
                <!-- Testimonial 1 -->
                <div class="testimonial-card">
                    <div class="rating">
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                    </div>
                    <div class="customer-info">
                        <span class="customer-name">Sarah M.</span>
                        <span class="verified-badge">✓</span>
                    </div>
                    <p class="testimonial-text">"I'm blown away by the quality and style of the items I received. The fabrics are top-notch and the sizes fit exactly as expected. I've already got compliments from my coworkers!"</p>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="testimonial-card">
                    <div class="rating">
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                    </div>
                    <div class="customer-info">
                        <span class="customer-name">Alex K.</span>
                        <span class="verified-badge">✓</span>
                    </div>
                    <p class="testimonial-text">"Kudos to your shop with its amazing customer service and incredibly stylish collection. The range of options they offer is truly impressive, catering to a variety of tastes and styles."</p>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="testimonial-card">
                    <div class="rating">
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                    </div>
                    <div class="customer-info">
                        <span class="customer-name">James L.</span>
                        <span class="verified-badge">✓</span>
                    </div>
                    <p class="testimonial-text">"The garments which arrived on my doorstep far exceeded even my highest expectations. The selection of dressed up options was beyond my wildest dreams but also on-point with the latest trends."</p>
                </div>
                
                <!-- Testimonial 4 (Partially visible) -->
                <div class="testimonial-card">
                    <div class="rating">
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                        <div class="star">★</div>
                    </div>
                    <div class="customer-info">
                        <span class="customer-name">Michael P.</span>
                        <span class="verified-badge">✓</span>
                    </div>
                    <p class="testimonial-text">"The clothes are not only stylish but also durable. I've been wearing them regularly for months and they still look brand new. The sizing guide was spot on..."</p>
                </div>
            </div>
        </section>
    </div>
@endsection