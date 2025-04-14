<div class="nav">
    <div class="nav-menu">
        <div class="nav-logo">
            <a href=""><h1>DashStack</h1></a>
        </div>
        <div class="menu-item">
            <div class="hide"></div>
            <a href="{{ route_url("admin/") }}"><div class="menu-icon">
                <i class="bi bi-compass"></i>
                <h3>Dashboard</h3>
            </div></a>
        </div>
        <div class="menu-item">
            <div class="hide"></div>
            <a href="{{ route_url('admin/product') }}"><div class="menu-icon">
                <i class="bi bi-border-all"></i>
                <h3>Products</h3>
            </div></a>
        </div>
        <div class="menu-item">
            <div class="hide"></div>
            <a href="{{ route_url("admin/order") }}"><div class="menu-icon">
                <i class="bi bi-list-check"></i>
                <h3>Order Lists</h3>
            </div></a>
        </div>
    </div>
    <div class="nav-page">
        <h2>PAGES</h2>
        <div class="page-item">
            <div class="hide"></div>
            <a href="{{ route_url("admin/contact") }}"><div class="menu-icon">
                <i class="bi bi-people"></i>
                <h3>Contact</h3>
            </div></a>
        </div>
        <div class="page-item">
            <div class="hide"></div>
            <a href="{{ route_url("admin/coupon/") }}"><div class="menu-icon">
                <i class="bi bi-ticket-perforated"></i>
                <h3>Invoice</h3>
            </div></a>
        </div>
        <div class="page-item">
            <div class="hide"></div>
            <a href="{{ route_url("admin/payment/") }}"><div class="menu-icon">
                <i class="bi bi-credit-card"></i>
                <h3>Payment</h3>
            </div></a>
        </div>
        <div class="page-item">
            <div class="hide"></div>
            <a href="{{ route_url("") }}"><div class="menu-icon">
                <i class="bi bi-table"></i>
                <h3>Table</h3>
            </div></a>
        </div>

    </div>
    <div class="nav-out">
        <div class="out-item">
            <i class="bi bi-gear"></i>
            <h3>Setting</h3>
        </div>
        <div class="out-item">
            <i class="bi bi-box-arrow-right"></i>
            <h3>Logout</h3>
        </div>
    </div>
</div>