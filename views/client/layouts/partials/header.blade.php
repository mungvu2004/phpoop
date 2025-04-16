<div class="header">
    <div class="header-sign">
        <h3>Đăng ký và được giảm giá 20% cho đơn hàng đầu tiên của bạn.
            </h3>
        <a href="">Đăng ký ngay</a>
    </div>
    <div class="header-i">
        <div class="header-logo">
            <a href="{{ route_url(  '') }}"><li>PureWare</li></a>
        </div>
        <div class="header-menu">
            <ul>
                <a href="{{route_url('/products')}}">

                    <li>Danh mục</li>
                </a>
                <a href="">
                    <li>top sản phẩm</li>
                </a>
                <a href="">
                    <li>tin tức</li>
                </a>
                <a href="">
                    <li>Giới thiệu</li>
                </a>
            </ul>
        </div>
        <div class="header-search">
            <form action="/product/search" method="GET" id="searchForm">
                <i class="bi bi-search"></i>
                <input type="text" name="query" id="searchInput" placeholder="Search for product" autocomplete="off">
                <div id="autocomplete-box" class="autocomplete-results">
                </div>
            </form>
        </div>
        <div class="header-set">
           <a href=""><i class="bi bi-cart2"></i></a>
            @if (!isset($_SESSION['user']))
                <a href="/login"><i class="bi bi-person-circle"></i></a>
            @else 
                <a href="/account"><i class="bi bi-person-check"></i></i></a>
            @endif

        </div>
    </div>
</div>