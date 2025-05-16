// Constants and Cache DOM Elements
const ELEMENTS = {
    orderList: document.getElementById('order-list'),
    detailButtons: document.querySelectorAll('.details-btn'),
    orderCheckboxes: document.querySelectorAll('.order-checkbox'),
    deleteButtons: document.querySelectorAll('.delete-product-btn'),
    applyCoupon: document.querySelector('.apply-btn'),
    couponInput: document.querySelector('.coupon_name'),
    summary: {
        total: document.getElementById('summary-total'),
        discount: document.getElementById('summary-discount'),
        shipping: document.getElementById('summary-shipping'),
        final: document.getElementById('summary-final')
    }
};

// Utility Functions
function formatPrice(number) {
    return number.toLocaleString('vi-VN') + ' ₫';
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => notification.remove(), 3000);
}

function confirmDelete() {
    if (!confirm("Bạn có chắc chắn muốn xóa sản phẩm này hay không?")) return false;
    return confirm("Lần nữa, bạn có chắc chắn muốn xóa sản phẩm này không?");
}

// Cart Functions
function updateSubtotal(input) {
    try {
        const row = input.closest('tr');
        const price = parseFloat(row.querySelector('td:nth-child(2)').textContent.replace(/[^0-9]/g, ''));
        const quantity = parseInt(input.value);
        const orderDetailId = input.getAttribute('data-order-detail-id');
        
        if (!validateQuantity(quantity)) return;

        updateUIPrice(row, price, quantity);
        sendUpdateRequest(orderDetailId, quantity);
        updateOrderTotal(row.closest('.order-card'));
        triggerCheckboxUpdate();
    } catch (error) {
        console.error('Error updating subtotal:', error);
        showNotification('Có lỗi xảy ra khi cập nhật số lượng', 'error');
    }
}

function validateQuantity(quantity) {
    if (quantity < 1 || quantity > 99) {
        showNotification('Số lượng phải từ 1 đến 99', 'error');
        return false;
    }
    return true;
}

function updateUIPrice(row, price, quantity) {
    const subtotal = price * quantity;
    const subtotalCell = row.querySelector('td[data-subtotal]');
    subtotalCell.setAttribute('data-subtotal', subtotal);
    subtotalCell.textContent = formatPrice(subtotal);
}

function updateOrderTotal(orderCard) {
    let total = 0;
    const products = orderCard.querySelectorAll('td[data-subtotal]');
    products.forEach(product => {
        total += parseFloat(product.getAttribute('data-subtotal'));
    });
    orderCard.setAttribute('data-total', total);
}

// Sửa lại hàm handleDeleteProduct
function handleDeleteProduct(button) {
    // Kiểm tra trạng thái
    const status = button.getAttribute('data-status');
    console.log('Order status:', status); // Debug

    if (status !== 'pending') {
        showNotification('Không thể xóa sản phẩm từ đơn hàng đã xử lý', 'error');
        return;
    }

    if (!confirmDelete()) return;

    const orderDetailId = button.getAttribute('data-order-detail-id');
    const row = button.closest('tr');
    const orderCard = button.closest('.order-card');

    fetch(`/order/delete/${orderDetailId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ order_detail_id: orderDetailId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            row.remove();
            updateOrderTotal(orderCard);
            showNotification('Sản phẩm đã được xóa thành công');
        } else {
            throw new Error(data.message || 'Có lỗi xảy ra khi xóa sản phẩm');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message, 'error');
    });
}

// Thêm vào phần Cart Functions
function handleCouponApply() {
    const couponCode = ELEMENTS.couponInput.value.trim();
    
    if (!couponCode) {
        showNotification('Vui lòng nhập mã giảm giá', 'error');
        return;
    }

    // Lấy order_id từ checkbox đã chọn
    const checkedOrder = document.querySelector('.order-checkbox:checked');
    if (!checkedOrder) {
        showNotification('Vui lòng chọn đơn hàng để áp dụng mã giảm giá', 'error');
        return;
    }

    const orderId = checkedOrder.value;

    fetch('/order/coupon/apply', { // Đổi URL theo route của bạn
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json' // Thêm header Accept
        },
        body: JSON.stringify({
            coupon_code: couponCode,
            order_id: orderId
        })
    })
    .then(async response => {
        // Kiểm tra response
        if (!response.ok) {
            const error = await response.text();
            throw new Error(error || 'Lỗi kết nối server');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Áp dụng mã giảm giá thành công');
            // Cập nhật UI
            if (ELEMENTS.summary.discount) {
                ELEMENTS.summary.discount.textContent = formatPrice(data.discount_amount);
            }
            if (ELEMENTS.summary.final) {
                ELEMENTS.summary.final.textContent = formatPrice(data.final_amount);
            }
            ELEMENTS.couponInput.value = '';
            // Trigger cập nhật tổng tiền
            triggerCheckboxUpdate();
        } else {
            throw new Error(data.message || 'Mã giảm giá không hợp lệ');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification(error.message || 'Có lỗi xảy ra khi áp dụng mã giảm giá', 'error');
    });
}

// Thêm vào phần Cart Functions
function triggerCheckboxUpdate() {
    const checkedOrder = document.querySelector('.order-checkbox:checked');
    let totalAmount = 0;
    let shippingFee = 0;
    
    if (checkedOrder) {
        const orderCard = checkedOrder.closest('.order-card');
        if (orderCard) {
            totalAmount = parseFloat(orderCard.getAttribute('data-total')) || 0;
            shippingFee = 30000; // Phí ship mặc định, có thể thay đổi theo logic của bạn
        }
    }

    // Cập nhật tổng tiền hàng
    if (ELEMENTS.summary.total) {
        ELEMENTS.summary.total.textContent = formatPrice(totalAmount);
    }

    // Cập nhật phí ship
    if (ELEMENTS.summary.shipping) {
        ELEMENTS.summary.shipping.textContent = totalAmount > 0 ? formatPrice(shippingFee) : formatPrice(0);
    }

    // Cập nhật tổng cộng
    if (ELEMENTS.summary.final) {
        const discount = ELEMENTS.summary.discount ? 
            parseFloat(ELEMENTS.summary.discount.textContent.replace(/[^0-9]/g, '')) || 0 : 0;
        
        const finalAmount = totalAmount > 0 ? 
            (totalAmount + shippingFee - discount) : 0;
            
        ELEMENTS.summary.final.textContent = formatPrice(finalAmount);
    }

    // Cập nhật input hidden cho form thanh toán
    const amountInput = document.querySelector('input[name="amount"]');
    if (amountInput) {
        amountInput.value = totalAmount;
    }

    const orderIdsInput = document.querySelector('input[name="order_ids"]');
    if (orderIdsInput && checkedOrder) {
        orderIdsInput.value = checkedOrder.value;
    }
}

// Initialize Functions
function initializeDetailButtons() {
    ELEMENTS.detailButtons.forEach(button => {
        button.addEventListener('click', () => {
            const orderId = button.getAttribute('data-order-id');
            document.getElementById(`order-body-${orderId}`).classList.toggle('show');
        });
    });
}

function initializeQuantityControls() {
    ELEMENTS.orderList.addEventListener('click', (e) => {
        const quantityControl = e.target.closest('.quantity-control');
        if (!quantityControl) return;

        const input = quantityControl.querySelector('.quantity-input');
        const currentValue = parseInt(input.value);

        if (e.target.classList.contains('decrease-btn') && currentValue > 1) {
            input.value = currentValue - 1;
            updateSubtotal(input);
        } else if (e.target.classList.contains('increase-btn') && currentValue < 99) {
            input.value = currentValue + 1;
            updateSubtotal(input);
        }
    });
}

function initializeDeleteButtons() {
    ELEMENTS.deleteButtons.forEach(button => {
        const status = button.getAttribute('data-status');
        if (status !== 'pending') {
            button.disabled = true;
            button.style.opacity = '0.5';
            button.style.cursor = 'not-allowed';
        }
    });
}

function initializeCheckboxes() {
    ELEMENTS.orderCheckboxes.forEach(checkbox => {
        const status = checkbox.getAttribute('data-status');
        checkbox.disabled = status !== 'pending';

        checkbox.addEventListener('change', () => {
            if (checkbox.checked) {
                ELEMENTS.orderCheckboxes.forEach(cb => {
                    if (cb !== checkbox) cb.checked = false;
                });
            }
            triggerCheckboxUpdate();
        });
    });
}

// Main Initialization
document.addEventListener('DOMContentLoaded', function() {
    initializeDetailButtons();
    initializeQuantityControls();
    initializeDeleteButtons();
    initializeCheckboxes();
    
    // Initialize delete product handling
    ELEMENTS.orderList.addEventListener('click', (e) => {
        const deleteButton = e.target.closest('.delete-product-btn');
        if (deleteButton) {
            handleDeleteProduct(deleteButton);
        }
    });

    // Initialize coupon handling
    ELEMENTS.applyCoupon?.addEventListener('click', handleCouponApply);
});

document.addEventListener('DOMContentLoaded', function () {
    const notifications = document.querySelectorAll('.notifis');

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

document.addEventListener('DOMContentLoaded', function() {
    // Tìm tất cả các nút delete
    const deleteButtons = document.querySelectorAll('.delete-product-btn');
    
    deleteButtons.forEach(button => {
        // Lấy trạng thái từ data attribute
        const status = button.getAttribute('data-status');
        
        if (status !== 'pending') {
            // Vô hiệu hóa nút nếu trạng thái khác pending
            button.disabled = true;
            button.style.opacity = '0.5';
            button.style.cursor = 'not-allowed';
            // Gỡ bỏ sự kiện onclick
            button.onclick = function(e) {
                e.preventDefault();
                return false;
            };
        }
    });
});