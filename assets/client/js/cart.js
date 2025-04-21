document.addEventListener('DOMContentLoaded', function () {
    const orderList = document.getElementById('order-list');
    const detailButtons = document.querySelectorAll('.details-btn');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const orderId = this.getAttribute('data-order-id');
            const orderBody = document.getElementById(`order-body-${orderId}`);
            orderBody.classList.toggle('show');
        });
    });
    
    orderList.addEventListener('click', (e) => {
        const target = e.target;
        const quantityInput = target.closest('.quantity-control')?.querySelector('.quantity-input');

        if (!quantityInput) return;

        let currentValue = parseInt(quantityInput.value);

        if (target.classList.contains('decrease-btn') && currentValue > 1) {
            requestAnimationFrame(() => {
                quantityInput.value = currentValue - 1;
                updateSubtotal(quantityInput);
                triggerCheckboxUpdate(); 
            });
        } else if (target.classList.contains('increase-btn')) {
            requestAnimationFrame(() => {
                quantityInput.value = currentValue + 1;
                updateSubtotal(quantityInput);
                triggerCheckboxUpdate();
            });
        }
    });
    function updateSubtotal(input) {
        const row = input.closest('tr');
        const price = parseFloat(row.querySelector('td:nth-child(2)').textContent.replace(/[^0-9]/g, ''));
        const quantity = parseInt(input.value);
        const orderDetailId = input.getAttribute('data-order-detail-id');
        const subtotal = price * quantity;
        const subtotalCell = row.querySelector('td[data-subtotal]');
        subtotalCell.setAttribute('data-subtotal', subtotal);
        subtotalCell.textContent = subtotal.toLocaleString('vi-VN') + ' ₫';
        const orderCard = row.closest('.order-card');
        const allProducts = orderCard.querySelectorAll('td[data-subtotal]');
        let total = 0;

        allProducts.forEach(product => {
            total += parseFloat(product.getAttribute('data-subtotal'));
        });

        orderCard.setAttribute('data-total', total);
        // Gửi AJAX (tùy chọn)
        fetch(`/order_detail/update/${orderDetailId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id: orderDetailId,
                quantity: quantity
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const orderCard = row.closest('.order-card');
                    const checkbox = orderCard.querySelector('.order-checkbox');
                    if (checkbox.checked) {
                        triggerCheckboxUpdate();
                    }
                } else {
                    console.error('Cập nhật số lượng thất bại:', data.message);
                }
            })
            .catch(error => console.error('Lỗi:', error));
            triggerCheckboxUpdate();
    }
    // Cập nhật tổng đơn hàng
    function triggerCheckboxUpdate() {
        const total = document.getElementById('summary-total');
        const discount = document.getElementById('summary-discount');
        const shipping = document.getElementById('summary-shipping');
        const final = document.getElementById('summary-final');
    
        let totalAll = 0;
        let discountAll = 0;
        let shippingAll = 0;
        let finalAll = 0;
    
        orderCheckboxes.forEach(cb => {
            if (cb.checked) {
                const card = cb.closest('.order-card');
                const dataTotal = parseFloat(card.getAttribute('data-total')) || 0;
                const dataDiscount = parseFloat(card.getAttribute('data-discount')) || 0;
                let dataShip = parseFloat(card.getAttribute('data-shipping')) || 0;
    
                if (dataShip === 0) {
                    dataShip = 20000;
                }
    
                const finalEach = dataTotal * (1 - dataDiscount / 100) + dataShip;
    
                totalAll += dataTotal;
                discountAll += dataTotal * (dataDiscount / 100);
                shippingAll += dataShip;
                finalAll += finalEach;
            }
        });
    
        total.textContent = totalAll.toLocaleString('vi-VN') + ' ₫';
        
        if (totalAll > 0) {
            const avgDiscount = (discountAll / totalAll) * 100;
            discount.textContent = avgDiscount.toFixed(2) + ' %';
        } else {
            discount.textContent = '0 %';
        }
    
        shipping.textContent = shippingAll.toLocaleString('vi-VN') + ' ₫';
        final.textContent = finalAll.toLocaleString('vi-VN') + ' ₫';
    }
    orderCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            if (this.checked) {
                // Bỏ chọn tất cả các checkbox khác
                orderCheckboxes.forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            }
    
            triggerCheckboxUpdate(); // Cập nhật tổng đơn hàng
        });
    });
    
    // Xử lý checkbox
    orderCheckboxes.forEach(checkbox => {
        const status = checkbox.getAttribute('data-status');
        if (status !== 'pending') {
            checkbox.disabled = true;
        }
        checkbox.addEventListener('change', triggerCheckboxUpdate);
    });
    const applyCoupon = document.querySelector('.apply-btn');
    const coupon = document.querySelector('.coupon_name');

    // Thêm hàm hiển thị thông báo
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    applyCoupon.addEventListener('click', function () {
        const couponCode = coupon.value.trim();
        const checkedOrders = Array.from(document.querySelectorAll('.order-checkbox:checked'));
        if (checkedOrders.length === 0 || !couponCode) {
            showNotification('Vui lòng chọn đơn hàng và nhập mã giảm giá', 'error');
            return;
        }

        const orderIds = checkedOrders.map(cb => cb.getAttribute('data-order-id'));

        fetch('/order/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                coupon: couponCode,
                orders: orderIds
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message);
                
                // Cập nhật thông tin giảm giá trong summary
                const summaryDiscount = document.getElementById('summary-discount');
                const summaryFinal = document.getElementById('summary-final');
                
                if (data.data.discount_type === 'percentage') {
                    summaryDiscount.textContent = `${data.data.discount}%`;
                } else {
                    summaryDiscount.textContent = `${data.data.discount.toLocaleString('vi-VN')} ₫`;
                }
                
                summaryFinal.textContent = `${data.data.final_total.toLocaleString('vi-VN')} ₫`;
                
                // Cập nhật data-discount cho order card
                const orderCard = document.querySelector(`[data-order-id="${orderIds[0]}"]`);
                if (orderCard) {
                    orderCard.setAttribute('data-discount', data.data.discount);
                }
                
                // Cập nhật lại tổng tiền
                triggerCheckboxUpdate();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Lỗi khi áp dụng mã giảm giá:', error);
            showNotification('Có lỗi xảy ra khi áp dụng mã giảm giá', 'error');
        });
    });
    

    orderList.addEventListener('click', function (e) {
        const target = e.target;

        // Kiểm tra xem có bấm vào nút xóa không
        if (target.closest('.delete-product-btn')) {
            const deleteButton = target.closest('.delete-product-btn');
            const productId = deleteButton.getAttribute('data-product-id'); // Lấy product_id
            const orderDetailId = deleteButton.getAttribute('data-order-detail-id'); // Lấy order_detail_id
            const row = deleteButton.closest('tr'); // Lấy dòng của sản phẩm
            const orderCard = deleteButton.closest('.order-card'); // Lấy đơn hàng chứa sản phẩm

            if (!orderDetailId) {
                console.error('Không tìm thấy order_detail_id!');
                return;
            }

            // Gửi AJAX để xóa sản phẩm
            fetch(`/order/delete/${orderDetailId}`, {
                method: 'POST', // Phương thức POST
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    order_detail_id: orderDetailId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị thông báo xóa thành công
                    showNotification('Sản phẩm đã được xóa thành công.');

                    // Xóa dòng sản phẩm khỏi DOM
                    row.remove();

                    // Cập nhật lại tổng tiền trong order-card
                    let total = 0;
                    const allProducts = orderCard.querySelectorAll('td[data-subtotal]');
                    allProducts.forEach(product => {
                        total += parseFloat(product.getAttribute('data-subtotal'));
                    });
                    orderCard.setAttribute('data-total', total);

                    // Cập nhật lại tổng đơn hàng
                    triggerCheckboxUpdate();
                } else {
                    // Hiển thị lỗi nếu xóa thất bại
                    showNotification(data.message || 'Có lỗi xảy ra khi xóa sản phẩm.', 'error');
                }
            })
            .catch(error => {
                console.error('Lỗi khi gửi yêu cầu:', error);
                showNotification('Không thể kết nối đến server.', 'error');
            });
        }
    });
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
function confirmDelete() {
    // Thông báo xác nhận lần đầu tiên
    var firstConfirmation = confirm("Bạn có chắc chắn muốn xóa sản phẩm này hay không?");

    if (firstConfirmation) {
        // Thông báo xác nhận lần thứ hai
        var secondConfirmation = confirm("Lần nữa, bạn có chắc chắn muốn xóa sản phẩm này không?");

        if (secondConfirmation) {
            return true;  // Chấp nhận xóa
        } else {
            return false;  // Hủy xóa
        }
    } else {
        return false;  // Hủy xóa nếu lần đầu không đồng ý
    }
}