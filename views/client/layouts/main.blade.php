<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,400,500,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ file_url('assets/admin/home.css') }}">
    <link rel="stylesheet" href="{{ file_url('assets/admin/media-home.css') }}">
    <link rel="stylesheet" href="{{ file_url('assets/client/dashboard.css') }}">
    @stack("styles")
</head>
<body>
    @include('client.layouts.partials.header')
    @include('client.layouts.partials.nav')
    <div class="container">
        <div class="row">
            @yield('content')

            {{-- @include('client.layouts.partials.sidebar') --}}
        </div>
    </div>
    @include('client.layouts.partials.footer')
    
</body>
</html>