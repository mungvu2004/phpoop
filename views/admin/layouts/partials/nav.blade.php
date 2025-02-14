<nav class="navbar flex-column">
    <div class="navbar-logo flex-column">
        <a href="{{ file_url('admin') }}">
            <img src="{{ file_url('assets/img/favicon.png') }}" alt="Logo">
        </a>
        <h1>MNA</h1>
    </div>
    <div class="navbar-menu ">
        <ul class="navbar-ul flex-column">
            <li class="flex-row">
                <i class="fa-solid fa-gauge"></i>
                <a href=""><h3>Dashboard</h3></a>
            </li>
            <li class="flex-row">
                <i class="fa-solid fa-chart-simple"></i>
                <a href=""><h3>Anlytics</h3></a>
            </li>
            <li class="flex-row">
                <i class="fa-solid fa-file-invoice"></i>
                <a href=""><h3>Invoice</h3></a>
            </li>
            <li class="flex-row">
                <i class="fa-solid fa-gear"></i>
                <a href=""><h3>Settings</h3></a>
            </li>
        </ul>
    </div>
    <div class="navbar-user flex-column">
        <div class="user_img">
            <img src="{{ file_url('assets/img/favicon.png') }}" alt="User">
        </div>
        <div class="navbar-icon">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
        </div>
    </div>
</nav>