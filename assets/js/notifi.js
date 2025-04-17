document.addEventListener("DOMContentLoaded", function () {
    // Lấy các phần tử thông báo
    const errorMessages = document.querySelector(".error-messages");
    const successMessages = document.querySelector(".success-messages");

    // Hàm ẩn thông báo sau 3 giây
    function hideMessage(element) {
        if (element) {
            setTimeout(() => {
                element.remove(); // Xóa phần tử khỏi DOM
            }, 3000); // 3 giây
        }
    }

    // Áp dụng cho thông báo lỗi
    hideMessage(errorMessages);

    // Áp dụng cho thông báo thành công
    hideMessage(successMessages);
});
document.addEventListener('DOMContentLoaded', function () {
    const notifications = document.querySelectorAll('.notifi');
    console.log(notifications);

    notifications.forEach((notif, index) => {
        // Delay hiển thị nếu có nhiều cái (xếp chồng nhau)
        setTimeout(() => {
            notif.classList.add('show');
        }, 100 * index);

        // Tự ẩn sau 5 giây
        setTimeout(() => {
            notif.classList.remove('show');
            notif.classList.add('hide');
        }, 5000 + (100 * index));

        // Nút đóng thủ công
        const closeBtn = notif.querySelector('.close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                notif.classList.remove('show');
                notif.classList.add('hide');
            });
        }
    });
});