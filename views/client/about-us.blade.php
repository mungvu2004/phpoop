@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/about-us.css') }}">
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="about-hero-section">
        <div class="about-hero-content">
            <h1>OUR STORY</h1>
            <p>At PureWare, we believe that style is a reflection of who you are. Our mission is to provide quality clothing that empowers you to express your unique personality and confidence.</p>
        </div>
        <div class="about-hero-image">
            <img src="{{ file_url('storage/Badge/Banner.png') }}" alt="PureWare team working on designs">
        </div>
    </section>

    <!-- Our Philosophy Section -->
    <section class="philosophy-section">
        <div class="section-container">
            <h2 class="section-title">OUR PHILOSOPHY</h2>
            <div class="philosophy-content">
                <div class="philosophy-image">
                    <img src="/storage/Badge/philosophy.png" alt="Our philosophy in fashion">
                </div>
                <div class="philosophy-text">
                    <h3>CLOTHING WITH PURPOSE</h3>
                    <p>Founded in 2015, PureWare began with a vision to create clothing that doesn't just look good, but feels good and does good. We believe that fashion should be both beautiful and responsible.</p>
                    <p>Each garment is thoughtfully designed with attention to detail, quality, and versatility. We create pieces that seamlessly integrate into your existing wardrobe and can be styled in multiple ways for different occasions.</p>
                    <a href="/views/client/list-product.blade.php" class="learn-more-btn">Learn More</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Journey Section -->
    <section class="journey-section">
        <div class="section-container">
            <h2 class="section-title">OUR JOURNEY</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-year">2015</div>
                    <div class="timeline-content">
                        <h3>THE BEGINNING</h3>
                        <p>PureWare started as a small boutique in downtown New York with a vision to create sustainable, high-quality clothing.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2017</div>
                    <div class="timeline-content">
                        <h3>ONLINE EXPANSION</h3>
                        <p>We launched our e-commerce platform to reach fashion enthusiasts worldwide and expanded our collection.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2019</div>
                    <div class="timeline-content">
                        <h3>SUSTAINABILITY COMMITMENT</h3>
                        <p>We pledged to use 100% sustainable materials across all of our product lines by 2025.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2022</div>
                    <div class="timeline-content">
                        <h3>GLOBAL RECOGNITION</h3>
                        <p>PureWare was recognized as one of the top sustainable fashion brands, with stores in 15 countries.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2025</div>
                    <div class="timeline-content">
                        <h3>LOOKING AHEAD</h3>
                        <p>Continuing our mission to revolutionize the fashion industry with innovative, sustainable practices.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="values-section">
        <div class="section-container">
            <h2 class="section-title">OUR VALUES</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <img src="/storage/Badge/sustainability-icon.png" alt="Sustainability Icon">
                    </div>
                    <h3>SUSTAINABILITY</h3>
                    <p>We're committed to reducing our environmental footprint through sustainable sourcing, eco-friendly packaging, and ethical production practices.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <img src="/storage/Badge/quality-icon.png" alt="Quality Icon">
                    </div>
                    <h3>QUALITY</h3>
                    <p>We never compromise on quality. Each piece is crafted to last and become a beloved wardrobe staple that withstands the test of time.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <img src="/storage/Badge/transparency-icon.png" alt="Transparency Icon">
                    </div>
                    <h3>TRANSPARENCY</h3>
                    <p>We are transparent about our production processes, costs, and business practices because we believe you deserve to know how your clothes are made.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <img src="/storage/Badge/community-icon.png" alt="Community Icon">
                    </div>
                    <h3>COMMUNITY</h3>
                    <p>We believe in building a community of like-minded individuals who value quality, style, and conscious consumption.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <div class="section-container">
            <h2 class="section-title">MEET OUR TEAM</h2>
            <div class="team-grid">
                <div class="team-member">
                    <div class="member-image">
                        <img src="/storage/Badge/team-member-1.png" alt="Sarah Johnson - Founder & Creative Director">
                    </div>
                    <h3 class="member-name">Sarah Johnson</h3>
                    <p class="member-title">Founder & Creative Director</p>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="/storage/Badge/team-member-2.png" alt="Michael Chen - Head of Design">
                    </div>
                    <h3 class="member-name">Michael Chen</h3>
                    <p class="member-title">Head of Design</p>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="/storage/Badge/team-member-3.png" alt="Emma Rodriguez - Sustainability Manager">
                    </div>
                    <h3 class="member-name">Emma Rodriguez</h3>
                    <p class="member-title">Sustainability Manager</p>
                </div>
                <div class="team-member">
                    <div class="member-image">
                        <img src="/storage/Badge/team-member-4.png" alt="James Wilson - Operations Director">
                    </div>
                    <h3 class="member-name">James Wilson</h3>
                    <p class="member-title">Operations Director</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Brands Section -->
    <div class="brands-strip">
        <div class="brand versace">VERSACE</div>
        <div class="brand zara">ZARA</div>
        <div class="brand gucci">GUCCI</div>
        <div class="brand prada">PRADA</div>
        <div class="brand calvin-klein">Calvin Klein</div>
    </div>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="section-container">
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
                    <p class="testimonial-text">"I'm blown away by the quality and style of the items I received. The
                        fabrics are top-notch and the sizes fit exactly as expected. I've already got compliments from my
                        coworkers!"</p>
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
                    <p class="testimonial-text">"Kudos to your shop with its amazing customer service and incredibly
                        stylish collection. The range of options they offer is truly impressive, catering to a variety of
                        tastes and styles."</p>
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
                    <p class="testimonial-text">"The garments which arrived on my doorstep far exceeded even my highest
                        expectations. The selection of dressed up options was beyond my wildest dreams but also on-point
                        with the latest trends."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="section-container">
            <div class="cta-content">
                <h2>JOIN OUR SUSTAINABLE FASHION MOVEMENT</h2>
                <p>Discover our collection of thoughtfully designed, sustainable clothing that helps you express your unique style.</p>
                <a href="/views/client/list-product.blade.php" class="shop-btn">Shop Now</a>
            </div>
        </div>
    </section>
@endsection