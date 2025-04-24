<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'PWShop' }}</title>
    <!-- Đặt trong phần <head> -->
    <link rel="icon" href="{{file_url('storage/Badge/PW.PNG')}}" type="image">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ file_url('assets/admin/home.css') }}">
    <link rel="stylesheet" href="{{ file_url('assets/admin/media-home.css') }}">
    <link rel="stylesheet" href="{{ file_url('assets/client/dashboard.css') }}">
    @stack("styles")
</head>
<body>
    @php
        if (isset($_SESSION['msg'])) {
            foreach ($_SESSION['msg'] as $nofi) {
                echo '<div class="notifi slide-in">';
                echo '<p>' . htmlspecialchars($nofi) . '</p>';
                echo '<button class="close-btn">&times;</button>';
                echo '</div>';
            }
            unset($_SESSION['msg']); // Xóa sau khi hiển thị
        }
    @endphp

    @php
        if (isset($_SESSION['success'])) {
            foreach ($_SESSION['success'] as $nofi) {
                echo '<div class="notifi slide-in">';
                echo '<p>' . htmlspecialchars($nofi) . '</p>';
                echo '<button class="close-btn">&times;</button>';
                echo '</div>';
            }
            unset($_SESSION['success']); // Xóa sau khi hiển thị
        }
    @endphp
    @php
    if (isset($_SESSION['errors'])) {
        foreach ($_SESSION['errors'] as $nofi) {
            echo '<div class="notifi slide-in">';
            echo '<p>' . htmlspecialchars($nofi) . '</p>';
            echo '<button class="close-btn">&times;</button>';
            echo '</div>';
        }
        unset($_SESSION['errors']); // Xóa sau khi hiển thị
    }
    @endphp
    
    @include('client.layouts.partials.header')
    <div class="container">
        <div class="row">
            @yield('content')

        </div>
    </div>
    @include('client.layouts.partials.footer')
    @stack("scripts")
    <script src="{{file_url('assets/js/autocomplete.js')}}"></script>
    <script src="{{file_url('assets/js/notifi.js')}}"></script>
</body>
</html>