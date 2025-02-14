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
        <a id="toggle-button" href="">Register?</a>
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