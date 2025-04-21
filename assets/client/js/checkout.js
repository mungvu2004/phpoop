document.addEventListener('DOMContentLoaded', function() {
    const checkoutBtn = document.getElementById('checkout-btn');
    const checkoutModal = document.getElementById('checkout-modal');
    const closeButtons = document.querySelectorAll('.close-modal');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const selectedOrderIdsInput = document.getElementById('selected-order-ids');
    const totalAmountInput = document.getElementById('total-amount');

    // Kiểm tra trạng thái nút thanh toán
    function updateCheckoutButton() {
        const hasSelectedOrder = Array.from(orderCheckboxes).some(checkbox => checkbox.checked);
        checkoutBtn.disabled = !hasSelectedOrder;
        checkoutBtn.style.opacity = hasSelectedOrder ? '1' : '0.5';
        checkoutBtn.style.cursor = hasSelectedOrder ? 'pointer' : 'not-allowed';
    }

    // Lấy danh sách các order_id đã chọn
    function getSelectedOrderIds() {
        return Array.from(orderCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.dataset.orderId)
            .join(',');
    }

    // Tính tổng tiền các đơn hàng đã chọn
    function calculateTotalAmount() {
        let total = 0;
        Array.from(orderCheckboxes)
            .filter(checkbox => checkbox.checked)
            .forEach(checkbox => {
                const orderId = checkbox.dataset.orderId;
                const orderCard = document.querySelector(`.order-card[data-order-id="${orderId}"]`);
                if (orderCard) {
                    const orderTotal = parseFloat(orderCard.dataset.total) || 0;
                    total += orderTotal;
                }
            });
        return total;
    }

    // Lắng nghe sự kiện khi chọn đơn hàng
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateCheckoutButton();
            // Cập nhật tổng tiền trong summary
            const totalAmount = calculateTotalAmount();
            document.getElementById('summary-total').textContent = totalAmount.toLocaleString('vi-VN') + ' ₫';
            
            // Cập nhật giá trị cuối cùng
            const finalAmount = totalAmount;
            document.getElementById('summary-final').textContent = finalAmount.toLocaleString('vi-VN') + ' ₫';
        });
    });

    // Mở modal khi click vào nút thanh toán
    checkoutBtn.addEventListener('click', function() {
        if (!this.disabled) {
            // Cập nhật order_ids và amount trước khi hiển thị modal
            const selectedIds = getSelectedOrderIds();
            const totalAmount = calculateTotalAmount();
            
            selectedOrderIdsInput.value = selectedIds;
            totalAmountInput.value = totalAmount;
            
            console.log("Order IDs:", selectedOrderIdsInput.value);
            console.log("Total Amount:", totalAmountInput.value);
            
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