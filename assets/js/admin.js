document.addEventListener("DOMContentLoaded", function () {
    let items = document.querySelectorAll(".menu-item, .page-item"); // Lấy tất cả menu-item và page-item
    let mainTitle = document.querySelector('.title-h1');

    if (items.length > 0) {
        let firstItem = items[0]; // Chọn phần tử đầu tiên
        let firstIcon = firstItem.querySelector(".menu-icon");
        let firstHide = firstItem.querySelector(".hide");

        if (firstIcon) firstIcon.classList.add("selected");
        if (firstHide) firstHide.classList.add("selected");

        // Đặt tiêu đề ban đầu từ thẻ h3 đầu tiên
        if (mainTitle && firstIcon) {
            let firstTitle = firstIcon.querySelector('h3').innerText;
            mainTitle.innerText = firstTitle;
        }
    }

    items.forEach(item => {
        item.addEventListener("click", function () {
            items.forEach(el => {
                let icon = el.querySelector(".menu-icon");
                let hide = el.querySelector(".hide");
                if (icon) icon.classList.remove("selected");
                if (hide) hide.classList.remove("selected");
            });

            let icon = this.querySelector(".menu-icon");
            let hide = this.querySelector(".hide");
            if (icon) icon.classList.add("selected");
            if (hide) hide.classList.add("selected");

            // Cập nhật tiêu đề
            if (mainTitle && icon) {
                let title = icon.querySelector('h3').innerText;
                mainTitle.innerText = title;
            }
        });
    });
});
