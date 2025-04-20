document.addEventListener('DOMContentLoaded', function () {

    const productImg = document.querySelector('.product-img');
    const zoomImage = document.querySelector('.zoom-image');

    productImg.addEventListener('mousemove', (e) => {
        const containerRect = productImg.getBoundingClientRect(); // Lấy vị trí của phần tử chứa ảnh
        const x = e.clientX - containerRect.left; // Tọa độ X của chuột trong phần tử
        const y = e.clientY - containerRect.top; // Tọa độ Y của chuột trong phần tử

        const xPercent = (x / containerRect.width) * 100; // Tính toán phần trăm X
        const yPercent = (y / containerRect.height) * 100; // Tính toán phần trăm Y

        // Thay đổi `transform-origin` để phóng to ảnh tại vị trí chuột
        zoomImage.style.transformOrigin = `${xPercent}% ${yPercent}%`;
        zoomImage.style.transform = 'scale(2)'; // Phóng to ảnh (có thể thay đổi tỷ lệ)
    });

    productImg.addEventListener('mouseleave', () => {
        zoomImage.style.transform = 'scale(1)'; // Trả lại kích thước ban đầu khi rời khỏi ảnh
    });
    // Size selection
    const sizeOptions = document.querySelectorAll('.size-option');

    sizeOptions.forEach(option => {
        option.addEventListener('click', function () {
            sizeOptions.forEach(item => item.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Quantity selector
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');
    const quantityInput = document.querySelector('.quantity-input');

    minusBtn.addEventListener('click', function () {
        let quantity = parseInt(quantityInput.value);
        if (quantity > 1) {
            quantityInput.value = quantity - 1;
        }
    });

    plusBtn.addEventListener('click', function () {
        let quantity = parseInt(quantityInput.value);
        quantityInput.value = quantity + 1;
    });

    // Tab switching
    const tabItems = document.querySelectorAll('.tab-item');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabItems.forEach(tab => {
        tab.addEventListener('click', function () {
            tabItems.forEach(item => item.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            this.classList.add('active');
            const tabTarget = this.getAttribute('data-tab');
            document.getElementById(tabTarget).classList.add('active');
        });
    });
});
