document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('login-element');
    const element1 = document.getElementById('element-1');
    const element2 = document.getElementById('element-2');
    const element3 = document.getElementById('element-3');
    const element4 = document.getElementById('element-4');
    const img1 = document.getElementById('img-1');
    const img2 = document.getElementById('img-2');

    function handelToggle(event) {
        event.preventDefault();

        loginForm.classList.toggle('swapped');
        element3.classList.toggle('swapped');
        element4.classList.toggle('swapped');
        // img1.classList.toggle('swapped');
        // img2.classList.toggle('swapped');

        if(loginForm.classList.contains('swapped')) {
            fetch('/views/elements/sign-up.blade.php')
            .then(res => res.text())
            .then(data => {
                element1.innerHTML = data;
                document.getElementById('toggle-button').addEventListener('click', handelToggle);
                const signupForm = document.getElementById('form-reg');
                signupForm.addEventListener('submit', submitForm);
            })
            .catch(
                error => console.log(error)
            )
            fetch('/views/elements/img-reg.blade.php')
            .then(res => res.text())
            .then(data => {
                element2.innerHTML = data;
            })
            .catch(error => console.log(error))
        } else {
            fetch('/views/elements/login.blade.php')
            .then(res => res.text())
            .then(data => {
                element1.innerHTML = data;
                document.getElementById('toggle-button').addEventListener('click', handelToggle);
            })
            .catch(
                error => console.log(error)
            )
            fetch('/views/elements/img-login.blade.php')
            .then(res => res.text())
            .then(data => {
                element2.innerHTML = data;
            })
            .catch(error => console.log(error))
        }
    }

    function submitForm(event) {
        event.preventDefault();

        const form = document.getElementById('form-reg');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            const errorMessage = document.getElementById('error-msg');
            if (data.success) {
                errorMessage.style.color = 'green';
                errorMessage.textContent = data.message;
                form.reset();
                setTimeout(() => {
                    window.location.href = '/login';
                }, 2000);
                // Có thể chuyển hướng người dùng tới trang khác nếu đăng ký thành công
            } else {
                errorMessage.style.color = 'red';
                errorMessage.textContent = data.message;
            }
        })
        .catch(error => console.log(error));
    }
    document.getElementById('toggle-button').addEventListener('click', handelToggle);
});

