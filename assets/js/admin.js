document.addEventListener("DOMContentLoaded", function () {
    const items = document.querySelectorAll(".menu-item, .page-item");
    const mainTitle = document.querySelector('.title-h1');

    function updateSelection(item) {
        items.forEach(el => {
            const icon = el.querySelector(".menu-icon");
            const hide = el.querySelector(".hide");
            if (icon) icon.classList.remove("selected");
            if (hide) hide.classList.remove("selected");
        });

        const icon = item.querySelector(".menu-icon");
        const hide = item.querySelector(".hide");
        if (icon) icon.classList.add("selected");
        if (hide) hide.classList.add("selected");

        if (mainTitle) {
            const h3 = item.querySelector('h3');
            if (h3) {
                const title = h3.innerText;
                mainTitle.innerText = title;
                localStorage.setItem('pageTitle', title);
            }
        }
    }

    // Lấy path hiện tại từ URL
    const currentPath = window.location.pathname.replace(/\/$/, '');

    // Khớp URL với href
    let matched = false;
    items.forEach(item => {
        const link = item.querySelector('a');
        if (link) {
            // Lấy path từ href, loại bỏ domain nếu có
            const href = new URL(link.getAttribute('href'), window.location.origin).pathname.replace(/\/$/, '');
            if (href === currentPath) {
                updateSelection(item);
                matched = true;
            }
        }
    });

    // Nếu không khớp, chọn mặc định hoặc dùng localStorage
    if (!matched) {
        const savedTitle = localStorage.getItem('pageTitle');
        if (savedTitle) {
            items.forEach(item => {
                const h3 = item.querySelector('h3');
                if (h3 && h3.innerText === savedTitle) {
                    updateSelection(item);
                }
            });
        } else if (items.length > 0) {
            updateSelection(items[0]);
        }
    }

    // Xử lý click
    items.forEach(item => {
        item.addEventListener("click", function (e) {
            updateSelection(this);
        });
    });
});