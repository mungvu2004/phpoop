document.addEventListener('DOMContentLoaded', function() {
    // Cache DOM elements
    const orderList = document.getElementById('order-list');
    const detailButtons = document.querySelectorAll('.details-btn');
    const orderCheckboxes = document.querySelectorAll('.order-checkbox');
    const total = document.getElementById('summary-total');
    const discount = document.getElementById('summary-discount');
    const shipping = document.getElementById('summary-shipping');
    const final = document.getElementById('summary-final');
    const applyCoupon = document.querySelector('.apply-btn');
    const couponInput = document.querySelector('.coupon_name');
    
    // Set up event listeners for order detail toggle buttons
    detailButtons.forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            const orderBody = document.getElementById(`order-body-${orderId}`);
            orderBody.classList.toggle('show');
        });
    });
    
    // Helper function to show notifications
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => notification.remove(), 3000);
    }
    
    // Handle quantity controls using event delegation
    orderList.addEventListener('click', handleOrderListClick);
    
    // Handle checkbox changes
    orderCheckboxes.forEach(checkbox => {
        const status = checkbox.getAttribute('data-status');
        if (status !== 'pending') {
            checkbox.disabled = true;
        }
        
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Deselect all other checkboxes
                orderCheckboxes.forEach(cb => {
                    if (cb !== this) cb.checked = false;
                });
            }
            
            updateOrderSummary();
        });
    });
    
    // Apply coupon button handler
    if (applyCoupon) {
        applyCoupon.addEventListener('click', handleApplyCoupon);
    }
    
    // Initialize auto-hiding notifications
    initNotifications();
    
    // Initialize product delete buttons
    initDeleteButtons();
    
    // EVENT HANDLER FUNCTIONS
    
    function handleOrderListClick(e) {
        const target = e.target;
        
        // Handle quantity controls
        const quantityControl = target.closest('.quantity-control');
        if (quantityControl) {
            const quantityInput = quantityControl.querySelector('.quantity-input');
            if (!quantityInput) return;
            
            let currentValue = parseInt(quantityInput.value);
            
            if (target.classList.contains('decrease-btn') && currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateSubtotal(quantityInput);
            } else if (target.classList.contains('increase-btn')) {
                quantityInput.value = currentValue + 1;
                updateSubtotal(quantityInput);
            }
        }
        
        // Handle product deletion
        const deleteButton = target.closest('.delete-product-btn');
        if (deleteButton) {
            handleProductDelete(deleteButton);
        }
    }
    
    function handleProductDelete(deleteButton) {
        const orderDetailId = deleteButton.getAttribute('data-order-detail-id');
        const row = deleteButton.closest('tr');
        const orderCard = deleteButton.closest('.order-card');
        
        if (!orderDetailId) {
            console.error('Could not find order_detail_id!');
            return;
        }
        
        if (deleteButton.disabled) return;
        
        if (!confirmDelete()) return;
        
        // Send delete request
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
                showNotification('Sản phẩm đã được xóa thành công.');
                row.remove();
                
                // Update order total
                updateOrderTotal(orderCard);
                updateOrderSummary();
            } else {
                showNotification(data.message || 'Có lỗi xảy ra khi xóa sản phẩm.', 'error');
            }
        })
        .catch(error => {
            console.error('Lỗi khi gửi yêu cầu:', error);
            showNotification('Không thể kết nối đến server.', 'error');
        });
    }
    
    function handleApplyCoupon() {
        const couponCode = couponInput.value.trim();
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
                
                // Update discount info
                if (data.data.discount_type === 'percentage') {
                    discount.textContent = `${data.data.discount}%`;
                } else {
                    discount.textContent = `${data.data.discount.toLocaleString('vi-VN')} ₫`;
                }
                
                final.textContent = `${data.data.final_total.toLocaleString('vi-VN')} ₫`;
                
                // Update order card discount
                const orderCard = document.querySelector(`[data-order-id="${orderIds[0]}"]`);
                if (orderCard) {
                    orderCard.setAttribute('data-discount', data.data.discount);
                }
                
                updateOrderSummary();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Lỗi khi áp dụng mã giảm giá:', error);
            showNotification('Có lỗi xảy ra khi áp dụng mã giảm giá', 'error');
        });
    }
    
    // UTILITY FUNCTIONS
    
    function updateSubtotal(input) {
        const row = input.closest('tr');
        const price = parseFloat(row.querySelector('td:nth-child(2)').textContent.replace(/[^0-9]/g, ''));
        const quantity = parseInt(input.value);
        const orderDetailId = input.getAttribute('data-order-detail-id');
        const subtotal = price * quantity;
        
        // Update subtotal display
        const subtotalCell = row.querySelector('td[data-subtotal]');
        subtotalCell.setAttribute('data-subtotal', subtotal);
        subtotalCell.textContent = formatCurrency(subtotal);
        
        // Update order total
        const orderCard = row.closest('.order-card');
        updateOrderTotal(orderCard);
        
        // Send update to server
        updateOrderDetailQuantity(orderDetailId, quantity);
        
        // Update order summary
        updateOrderSummary();
    }
    
    function updateOrderDetailQuantity(orderDetailId, quantity) {
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
            if (!data.success) {
                console.error('Failed to update quantity:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function updateOrderTotal(orderCard) {
        const allProducts = orderCard.querySelectorAll('td[data-subtotal]');
        let totalAmount = 0;
        
        allProducts.forEach(product => {
            totalAmount += parseFloat(product.getAttribute('data-subtotal') || 0);
        });
        
        orderCard.setAttribute('data-total', totalAmount);
    }
    
    function updateOrderSummary() {
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
        
        total.textContent = formatCurrency(totalAll);
        
        if (totalAll > 0) {
            const avgDiscount = (discountAll / totalAll) * 100;
            discount.textContent = avgDiscount.toFixed(2) + ' %';
        } else {
            discount.textContent = '0 %';
        }
        
        shipping.textContent = formatCurrency(shippingAll);
        final.textContent = formatCurrency(finalAll);
    }
    
    function formatCurrency(value) {
        return value.toLocaleString('vi-VN') + ' ₫';
    }
    
    function confirmDelete() {
        const firstConfirmation = confirm("Bạn có chắc chắn muốn xóa sản phẩm này hay không?");
        
        if (firstConfirmation) {
            return confirm("Lần nữa, bạn có chắc chắn muốn xóa sản phẩm này không?");
        }
        
        return false;
    }
    
    function initNotifications() {
        const notifications = document.querySelectorAll('.notifis');
        
        notifications.forEach((notif, index) => {
            // Delay display for multiple notifications
            setTimeout(() => {
                notif.classList.add('show');
            }, 100 * index);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                notif.classList.remove('show');
                notif.classList.add('hide');
            }, 5000 + (100 * index));
            
            // Close button handler
            const closeBtn = notif.querySelector('.close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    notif.classList.remove('show');
                    notif.classList.add('hide');
                });
            }
        });
    }
    
    function initDeleteButtons() {
        const deleteButtons = document.querySelectorAll('.delete-product-btn');
        
        deleteButtons.forEach(button => {
            const status = button.getAttribute('data-status');
            
            if (status !== 'pending') {
                button.disabled = true;
                button.style.opacity = '0.5';
                button.style.cursor = 'not-allowed';
                button.onclick = e => {
                    e.preventDefault();
                    return false;
                };
            }
        });
    }
});