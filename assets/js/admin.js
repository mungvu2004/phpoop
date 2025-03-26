document.addEventListener("DOMContentLoaded", function () {
    const items = document.querySelectorAll(".menu-item, .page-item");
    const mainTitle = document.querySelector('.title-h1');

    // Hàm cập nhật trạng thái
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

        if (mainTitle && icon) {
            const title = icon.querySelector('h3').innerText;
            mainTitle.innerText = title;
            localStorage.setItem('pageTitle', title); // Lưu tiêu đề vào localStorage
        }
    }

    // Lấy URL hiện tại
    const currentPath = window.location.pathname.replace(/\/$/, ''); // Xóa '/' ở cuối nếu có

    // Khởi tạo dựa trên URL hiện tại
    let matched = false;
    items.forEach(item => {
        const link = item.querySelector('a');
        const href = link.getAttribute('href').replace(/\/$/, '');

        if (href === currentPath) {
            updateSelection(item);
            matched = true;
        }
    });

    // Nếu không khớp với URL, dùng localStorage hoặc mặc định mục đầu tiên
    if (!matched) {
        const savedTitle = localStorage.getItem('pageTitle');
        if (savedTitle) {
            items.forEach(item => {
                const title = item.querySelector('h3')?.innerText;
                if (title === savedTitle) {
                    updateSelection(item);
                }
            });
        } else if (items.length > 0) {
            updateSelection(items[0]); // Mặc định chọn mục đầu tiên
        }
    }

    // Xử lý sự kiện nhấp chuột
    items.forEach(item => {
        item.addEventListener("click", function (e) {
            updateSelection(this);
            // Không ngăn chuyển hướng, để trang tải lại bình thường
        });
    });
});