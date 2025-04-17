@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/about-us.css') }}">
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="about-hero-section">
        <div class="about-hero-content">
            <h1>OUR STORY</h1>
            <p>Ra đời vào tháng 3 năm 2025, PureWare là thương hiệu thời trang trẻ với khát vọng mang đến sự kết hợp giữa phong cách hiện đại và lối sống bền vững. Chúng tôi tin rằng thời trang không chỉ là vẻ ngoài – mà còn là cách bạn thể hiện chính mình.</p>
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
            <h2 class="section-title">TƯ DUY SÁNG TẠO CỦA CHÚNG TÔI</h2>
            <div class="philosophy-content">
                <div class="philosophy-image">
                    <img src="{{ file_url('storage/Badge/PHILOSOPHY.jpg') }}" alt="Our philosophy in fashion">
                </div>
                <div class="philosophy-text">
                    <h3>MỤC TIÊU</h3>
                    <p>Dù là thương hiệu mới thành lập, chúng tôi định hình PureWare trở thành nơi hội tụ giữa thiết kế tinh tế, chất lượng bền bỉ và trách nhiệm với cộng đồng. Mỗi sản phẩm đều được tạo nên với tâm huyết và sự tỉ mỉ.</p>
                    <p>Chúng tôi hướng đến các thiết kế có thể phối linh hoạt, phù hợp nhiều hoàn cảnh – từ đi làm, đi chơi đến gặp gỡ đối tác.</p>
                    <a href="{{ file_url('views/client/list-product.css') }}" class="learn-more-btn">Khám phá sản phẩm</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Journey Section -->
    <section class="journey-section">
        <div class="section-container">
            <h2 class="section-title">HÀNH TRÌNH CỦA CHÚNG TÔI</h2>
            <div class="timeline">
                <div class="timeline-item">
                    <div class="timeline-year">03.2025</div>
                    <div class="timeline-content">
                        <h3>THÀNH LẬP</h3>
                        <p>PureWare chính thức ra mắt thị trường với bộ sưu tập đầu tiên mang thông điệp “Chất riêng - Bền vững”.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">06.2025</div>
                    <div class="timeline-content">
                        <h3>THƯƠNG MẠI ĐIỆN TỬ</h3>
                        <p>Chúng tôi triển khai nền tảng mua sắm trực tuyến nhằm phục vụ khách hàng toàn quốc và mở rộng tệp người dùng.</p>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-year">2026+</div>
                    <div class="timeline-content">
                        <h3>HƯỚNG TỚI TƯƠNG LAI</h3>
                        <p>Tiếp tục mở rộng thị trường, hợp tác với các nhà thiết kế và cam kết phát triển thời trang theo hướng bền vững.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Our Values Section -->
    <section class="values-section">
        <div class="section-container">
            <h2 class="section-title">GIÁ TRỊ CỐT LÕI</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <img src="{{ file_url('storage/Badge/sustainability.png') }}" alt="Sustainability Icon">
                    </div>
                    <h3>BỀN VỮNG</h3>
                    <p>Ưu tiên sử dụng chất liệu thân thiện môi trường, đóng gói tái chế và quy trình sản xuất có đạo đức.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <img src="{{ file_url('storage/Badge/quality.png') }}" alt="Quality Icon">
                    </div>
                    <h3>CHẤT LƯỢNG</h3>
                    <p>Cam kết mang lại sản phẩm có độ bền cao, đường may chắc chắn, thoải mái khi mặc.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <img src="{{ file_url('storage/Badge/value.png') }}" alt="Transparency Icon">
                    </div>
                    <h3>MINH BẠCH</h3>
                    <p>Chúng tôi minh bạch trong quy trình sản xuất và chia sẻ thông tin đầy đủ đến khách hàng.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <img src="{{ file_url('storage/Badge/community.jpg') }}" alt="Community Icon">
                    </div>
                    <h3>CỘNG ĐỒNG</h3>
                    <p>Chúng tôi tin vào việc xây dựng cộng đồng người tiêu dùng thời trang có ý thức và trách nhiệm.</p>
                </div>
            </div>
        </div>
    </section>
@endsection