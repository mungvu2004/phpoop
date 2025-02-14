document.getElementById('toggle-button').addEventListener('click', function() {
    event.preventDefault();

    const loginElement = document.getElementById('login-element');
    const element1 = document.getElementById('element-1');
    const element2 = document.getElementById('element-2');
    const element3 = document.getElementById('element-3');
    const element4 = document.getElementById('element-4');

    loginElement.classList.toggle('swapped');
    element3.classList.toggle('swapped');
    element4.classList.toggle('swapped');
    if(loginElement.classList.contains('swapped')) {
        fetch('/views/elements/register.blade.php')
        .then(response => response.text())
        .then(data => {
            element1.innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
        fetch('/views/elements/img-reg.blade.php')
        .then(response => response.text())
        .then(data => {
            element2.innerHTML = data;
            const images = element1.querySelectorAll('img');
            images.forEach(img => {
                    mg.src = '/assets/img/' + img.src.split('/').pop();
                    console.log(img.src);
            });
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    else {
        fetch('/views/elements/login.blade.php')
        .then(response => response.text())
        .then(data => {
            element1.innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
        fetch('/views/elements/img-login.blade.php')
        .then(response => response.text())
        .then(data => {
            element2.innerHTML = data;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});