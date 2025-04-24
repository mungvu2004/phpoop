<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'PWShop Admin' }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ file_url('assets/admin/style.css') }}">
    <link rel="stylesheet" href="{{ file_url('assets/admin/media.css') }}">
    @stack('styles')
    <script src="{{ file_url('assets/js/admin.js') }}"></script>
</head>
<body>
    <div class="overlay"></div>
    @include('admin.layouts.partials.nav')
    <div class="container">
        @include('admin.layouts.partials.sidebar')
        @if (isset($_SESSION['errors']) && !empty($_SESSION['errors']))
        <div class="error-messages" style="color: red; margin-bottom: 10px;">
            @foreach ($_SESSION['errors'] as $err)
                <p>{{ $err }}</p>
            @endforeach
        </div>
        @php unset($_SESSION['errors']) @endphp
    @endif

    @if (isset($_SESSION['success']) && !empty($_SESSION['success']))
        <div class="success-messages" style="color: green; margin-bottom: 10px;">
            @foreach ($_SESSION['success'] as $msg)
                <p>{{ $msg }}</p>
            @endforeach
        </div>
        @php unset($_SESSION['success']) @endphp
    @endif
        @yield('content')

    </div>
    @stack('scripts')
    {{-- @include('admin.layouts.partials.footer') --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script src="{{ file_url('assets/js/notifi.js') }}"></script>
</body>
</html>