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
        <div class="login-element flex">
            <div class="element-1 flex-column">
                <form action="/admin/users" method="post">
                    <div class="form-header flex-column">
                        <h3>login</h3>
                        <p>How to i get started lorem ipsum dolor at?</p>
                        <?php if (isset($_SESSION['msg'])): ?>
                            <p style="color: red;"><?php echo $_SESSION['msg']; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="form-input flex-column">
                        <div class="input-user">
                            <input type="text" name="username" placeholder="Username" autocomplete="off">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <div class="input-pass">
                            <input type="password" name="password" placeholder="Password" autocomplete="current-password">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <button type="submit">Login Now</button>
                    </div>
                    <div class="form-p flex-column">
                        <h3>Login with Others</h3>
                    </div>
                    <div class="form-icon flex-column">
                        <div class="icon-gg flex">
                            <img src="{{ file_url('assets/img/google 1.png')}}" alt="">
                            <p>login with google</p>
                        </div>
                        <div class="icon-fb flex">
                            <img src="{{ file_url('assets/img/facebook 1.png')}}" alt="">
                            <p>login with facebook</p>
                        </div>
                    </div>
                </form>
            </div>
            <div class="element-2">
                <div class="element-img">
                    <h3 class="element-h3">Very good works are waiting for you Login Now!!!</h3>
                    <img class="img-ring" src="{{ file_url('assets/img/banner-home.png') }}" alt="">
                    <div class="element-div flex">
                        <img src="{{ file_url('assets/img/thunderbolt1.png') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="element-3 position-abs"></div>
        <div class="element-4 position-abs"></div>
    </div>
</body>
</html>