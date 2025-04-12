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