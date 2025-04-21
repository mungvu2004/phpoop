document.addEventListener('DOMContentLoaded', function() {
    const checkoutBtn = document.getElementById('checkout-btn');
    const checkoutModal = document.getElementById('checkout-modal');
    const closeButtons = document.querySelectorAll('.close-modal');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const selectedOrderIdsInput = document.getElementById('selected-order-ids');

    // Kiểm tra trạng thái nút thanh toán
    function updateCheckoutButton() {
        const hasSelectedOrder = Array.from(orderCheckboxes).some(checkbox => checkbox.checked);
        checkoutBtn.disabled = !hasSelectedOrder;
        checkoutBtn.style.opacity = hasSelectedOrder ? '1' : '0.5';
        checkoutBtn.style.cursor = hasSelectedOrder ? 'pointer' : 'not-allowed';
    }

    // Lấy danh sách order_id đã chọn
    function getSelectedOrderIds() {
        return Array.from(orderCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.dataset.orderId)
            .join(',');
    }

    // Lắng nghe sự kiện khi chọn đơn hàng
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCheckoutButton);
    });

    // Mở modal khi click vào nút thanh toán
    checkoutBtn.addEventListener('click', function() {
        if (!this.disabled) {
            // Cập nhật order_ids trước khi hiển thị modal
            selectedOrderIdsInput.value = getSelectedOrderIds();
            checkoutModal.style.display = 'block';
        }
    });

    // Đóng modal khi click vào nút đóng
    closeButtons.forEach(button => {
        button.addEventListener('click', function() {
            checkoutModal.style.display = 'none';
        });
    });

    // Đóng modal khi click ra ngoài
    window.addEventListener('click', function(event) {
        if (event.target === checkoutModal) {
            checkoutModal.style.display = 'none';
        }
    });

    // Xử lý sự kiện khi chọn phương thức vận chuyển
    const shippingMethods = document.querySelectorAll('input[name="shipping_method"]');
    shippingMethods.forEach(method => {
        method.addEventListener('change', function() {
            console.log('Phương thức vận chuyển đã chọn:', this.value);
        });
    });

    // Xử lý sự kiện khi chọn phương thức thanh toán
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    paymentMethods.forEach(method => {
        method.addEventListener('change', function() {
            console.log('Phương thức thanh toán đã chọn:', this.value);
        });
    });

    // Khởi tạo trạng thái ban đầu của nút thanh toán
    updateCheckoutButton();
}); 