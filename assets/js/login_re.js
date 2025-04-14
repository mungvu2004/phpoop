document.addEventListener('DOMContentLoaded', function () {
    const formLogin = document.querySelector('#form-log');
    const formSignup = document.querySelector('#form-reg');
    const imgLogin = document.querySelectorAll('.imgLogin');
    const imgSignup = document.querySelectorAll('.imgSignup');
    const btnLogin = document.querySelector('#toggle-login');
    const btnSignup = document.querySelector('#toggle-signup');

    btnSignup.addEventListener('click', function (e) {
        e.preventDefault(); // ngăn chuyển trang

        formLogin.classList.add('none');
        formSignup.classList.remove('none');

        imgLogin.forEach(img => img.classList.add('none'));
        imgSignup.forEach(img => img.classList.remove('none'));
    });

    btnLogin.addEventListener('click', function (e) {
        e.preventDefault();

        formSignup.classList.add('none');
        formLogin.classList.remove('none');

        imgSignup.forEach(img => img.classList.add('none'));
        imgLogin.forEach(img => img.classList.remove('none'));
    });
});
