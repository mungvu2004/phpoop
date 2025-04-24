<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Đăng nhập - PWShop' }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ file_url('assets/admin/login.css') }}">
    
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

    <div class="container-login flex">
        <div id="login-element" class="login-element flex">
            <div id="element-1" class="element-1 flex-column">
                <form id="form-log" class="" action="/login/users" method="post">
                    <div class="form-header flex-column">
                        <h3>login</h3>
                        <p>Kindly enter your account details to access your Login.</p>
                        
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
                        <a id="toggle-signup" href="">Register?</a>
                    </div>
                    <div class="form-p flex-column">
                        <h3>Login with Others</h3>
                    </div>
                    <div class="form-icon flex-column">
                        <div class="icon-gg flex">
                            <img src="../assets/img/google 1.png" alt="">
                            <p>login with google</p>
                        </div>
                        <div class="icon-fb flex">
                            <img src="../assets/img/facebook 1.png" alt="">
                            <p>login with facebook</p>
                        </div>
                    </div>
                </form>
                <form id="form-reg" class="none" action="/login/register" method="post">
                    <div class="form-header flex-column">
                        <h3>sign up</h3>
                        <p>Sign up by entering your information below.</p>
                    </div>
                    <div class="form-input flex-column">
                        <div class="input-user">
                            <input type="text" name="username" placeholder="Username" autocomplete="off">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <div class="input-pass">
                            <input type="password" name="password" placeholder="Password" autocomplete="current-password">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div class="input-pass">
                            <input type="password" name="password-re" placeholder="Password" autocomplete="current-password">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <div class="input-email">
                            <input type="email" name="email" placeholder="Email" autocomplete="off">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <button type="submit">Sign Up</button>
                        <a id="toggle-login" href="">Login?</a>
                
                        <script src="{{ file_url('assets/js/login_re.js') }}"></script>
                    </div>
                </form>
            </div>
            <div id="element-2" class="element-2">
                <div class="element-img imgLogin">
                    <h3 class="element-h3">Very good works are waiting for you Login Now!!!</h3>
                    <img class="img-ring" src="../assets/img/banner-home.png" alt="">
                    <div class="element-div flex">
                        <img src="../assets/img/thunderbolt1.png" alt="">
                    </div>
                </div>
                <div class="element-img imgSignup none">
                    <h3 class="element-h3">Very good works are waiting for you Login Now!!!</h3>
                    <img class="img-ring" src="../assets/img/baner-reg.png" style="bottom: 20px" alt="">
                    <div class="element-div flex">
                        <img src="../assets/img/thunderbolt1.png" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div id="element-3" class="element-3 position-abs"></div>
        <div id="element-4" class="element-4 position-abs"></div>
    </div>
    <script src="{{ file_url('assets/js/login_re.js') }}"></script>
</body>
</html>