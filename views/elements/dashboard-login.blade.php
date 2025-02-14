<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ file_url('assets/admin/login.css') }}">
    
</head>
<body>
    <div class="container-login flex">
        <div id="login-element" class="login-element flex">
            <div id="element-1" class="element-1 flex-column">
                @include('elements.login')
            </div>
            <div id="element-2" class="element-2">
                @include('elements.img-login')
            </div>
        </div>
        <div id="element-3" class="element-3 position-abs"></div>
        <div id="element-4" class="element-4 position-abs"></div>
    </div>
    <script src="{{ file_url('assets/js/login_re.js') }}"></script>
</body>
</html>