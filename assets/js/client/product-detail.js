document.addEventListener('DOMContentLoaded', function() {
    // Thumbnail switching
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const mainImage = document.querySelector('.product-main-image img');

    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            thumbnails.forEach(item => item.classList.remove('active'));
            this.classList.add('active');
            mainImage.src = this.querySelector('img').src;
        });
    });

    // Color selection
    const colorOptions = document.querySelectorAll('.color-option');

    colorOptions.forEach(option => {
        option.addEventListener('click', function() {
            colorOptions.forEach(item => item.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Size selection
    const sizeOptions = document.querySelectorAll('.size-option');

    sizeOptions.forEach(option => {
        option.addEventListener('click', function() {
            sizeOptions.forEach(item => item.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Quantity selector
    const minusBtn = document.querySelector('.quantity-btn.minus');
    const plusBtn = document.querySelector('.quantity-btn.plus');
    const quantityInput = document.querySelector('.quantity-input');

    minusBtn.addEventListener('click', function() {
        let quantity = parseInt(quantityInput.value);
        if (quantity > 1) {
            quantityInput.value = quantity - 1;
        }
    });

    plusBtn.addEventListener('click', function() {
        let quantity = parseInt(quantityInput.value);
        quantityInput.value = quantity + 1;
    });

    // Tab switching
    const tabItems = document.querySelectorAll('.tab-item');
    const tabPanels = document.querySelectorAll('.tab-panel');

    tabItems.forEach(tab => {
        tab.addEventListener('click', function() {
            tabItems.forEach(item => item.classList.remove('active'));
            tabPanels.forEach(panel => panel.classList.remove('active'));

            this.classList.add('active');
            const tabTarget = this.getAttribute('data-tab');
            document.getElementById(tabTarget).classList.add('active');
        });
    });
});
