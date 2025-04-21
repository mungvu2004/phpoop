document.addEventListener('DOMContentLoaded', function() {
    const checkoutForm = document.getElementById('checkout-form');
    const selectedOrderIds = document.getElementById('selected-order-ids');
    const totalAmount = document.getElementById('total-amount');
    const closeModalBtn = document.querySelector('.close-modal');
    const checkoutModal = document.getElementById('checkout-modal');
    const checkoutBtn = document.getElementById('checkout-btn');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');

    // Lấy danh sách đơn hàng đã chọn
    const selectedOrders = document.querySelectorAll('.order-checkbox:checked');
    const orderIds = Array.from(selectedOrders).map(checkbox => checkbox.dataset.orderId);
    selectedOrderIds.value = orderIds.join(',');

    // Tính tổng tiền
    let total = 0;
    selectedOrders.forEach(checkbox => {
        const orderCard = checkbox.closest('.order-card');
        total += parseFloat(orderCard.dataset.total);
    });
    totalAmount.value = total;

    // Kiểm tra trạng thái nút thanh toán
    function updateCheckoutButton() {
        const hasSelectedOrder = Array.from(orderCheckboxes).some(checkbox => checkbox.checked);
        if (checkoutBtn) {
            checkoutBtn.disabled = !hasSelectedOrder;
            checkoutBtn.style.opacity = hasSelectedOrder ? '1' : '0.5';
            checkoutBtn.style.cursor = hasSelectedOrder ? 'pointer' : 'not-allowed';
        }
    }

    // Lắng nghe sự kiện khi chọn đơn hàng
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateCheckoutButton);
    });

    // Mở modal khi click vào nút thanh toán
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            if (!this.disabled) {
                // Cập nhật order_ids trước khi hiển thị modal
                selectedOrderIds.value = getSelectedOrderIds();
                checkoutModal.style.display = 'block';
            }
        });
    }

    // Đóng modal khi click vào nút đóng
    closeModalBtn.addEventListener('click', function() {
        checkoutModal.style.display = 'none';
    });

    // Đóng modal khi click ra ngoài
    window.addEventListener('click', function(e) {
        if (e.target === checkoutModal) {
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

    // Xử lý submit form
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        const formData = new FormData(this);
        formData.append('payment_method', paymentMethod);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.redirect_url) {
                window.location.href = data.redirect_url;
            } else {
                alert(data.message || 'Có lỗi xảy ra. Vui lòng thử lại sau.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại sau.');
        });
    });

    // Hàm lấy danh sách order_id đã chọn
    function getSelectedOrderIds() {
        return Array.from(orderCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.dataset.orderId)
            .join(',');
    }
}); 