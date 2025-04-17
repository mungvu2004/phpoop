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

    <!-- Partner Brands Section -->
    <div class="brands-strip">
        <div class="brand versace">VERSACE</div>
        <div class="brand zara">ZARA</div>
        <div class="brand gucci">GUCCI</div>
        <div class="brand prada">PRADA</div>
        <div class="brand calvin-klein">Calvin Klein</div>
    </div>

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
                    <a href="{{ file_url('views/client/list-product.css') }}" class="learn-more-btn">Learn More</a>
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
                        <img src="{{ file_url('/storage/Badge/sustainability.png') }}" alt="Sustainability Icon">
                    </div>
                    <h3>SUSTAINABILITY</h3>
                    <p>We're committed to reducing our environmental footprint through sustainable sourcing, eco-friendly packaging, and ethical production practices.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <img src="{{ file_url('/storage/Badge/quality.png') }}" alt="Quality Icon">
                    </div>
                    <h3>QUALITY</h3>
                    <p>We never compromise on quality. Each piece is crafted to last and become a beloved wardrobe staple that withstands the test of time.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <img src="{{ file_url('/storage/Badge/value.jpg') }}" alt="Transparency Icon">
                    </div>
                    <h3>TRANSPARENCY</h3>
                    <p>We are transparent about our production processes, costs, and business practices because we believe you deserve to know how your clothes are made.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <img src="{{ file_url('/storage/Badge/community.jpg') }}" alt="Community Icon">
                    </div>
                    <h3>COMMUNITY</h3>
                    <p>We believe in building a community of like-minded individuals who value quality, style, and conscious consumption.</p>
                </div>
            </div>
        </div>
    </section>

    
@endsection