<form id="form-reg" action="/login/register" method="post" onsubmit="submitForm(event)">
    <div class="form-header flex-column">
        <h3>sign up</h3>
        <p>Sign up by entering your information below.</p>
        <p id="error-msg" style="color: red;"></p>
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
        <a id="toggle-button" href="">Login?</a>

        <script src="{{ file_url('assets/js/login_re.js') }}"></script>
    </div>
</form>