document.addEventListener('DOMContentLoaded', function() {
    // Get all quantity control buttons
    const decreaseButtons = document.querySelectorAll('.quantity-btn.decrease');
    const increaseButtons = document.querySelectorAll('.quantity-btn.increase');
    const removeButtons = document.querySelectorAll('.remove-item');
    const applyButton = document.querySelector('.apply-btn');
    const checkoutButton = document.querySelector('.checkout-btn');
    
    // Add event listeners to decrease buttons
    decreaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            const inputElement = this.nextElementSibling;
            let currentQuantity = parseInt(inputElement.value);
            
            if (currentQuantity > 1) {
                currentQuantity--;
                inputElement.value = currentQuantity;
                updateCartItem(itemId, currentQuantity);
            }
        });
    });
    
    // Add event listeners to increase buttons
    increaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            const inputElement = this.previousElementSibling;
            let currentQuantity = parseInt(inputElement.value);
            
            currentQuantity++;
            inputElement.value = currentQuantity;
            updateCartItem(itemId, currentQuantity);
        });
    });
    
    // Add event listeners to remove buttons
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-id');
            removeCartItem(itemId);
        });
    });
    
    // Apply promo code
    if (applyButton) {
        applyButton.addEventListener('click', function() {
            const promoCode = document.querySelector('.promo-input input').value;
            if (promoCode.trim() !== '') {
                applyPromoCode(promoCode);
            }
        });
    }
    
    // Checkout button
    if (checkoutButton) {
        checkoutButton.addEventListener('click', function() {
            window.location.href = '/checkout';
        });
    }
    
    // Function to update cart item quantity via AJAX
    function updateCartItem(itemId, quantity) {
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                item_id: itemId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateOrderSummary(data.subtotal, data.discount, data.deliveryFee, data.total);
            }
        })
        .catch(error => {
            console.error('Error updating cart:', error);
        });
    }
    
    // Function to remove cart item via AJAX
    function removeCartItem(itemId) {
        fetch('/cart/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                item_id: itemId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the item from the DOM
                const itemElement = document.querySelector(`.remove-item[data-id="${itemId}"]`).closest('.cart-item');
                itemElement.remove();
                
                // Update the order summary
                updateOrderSummary(data.subtotal, data.discount, data.deliveryFee, data.total);
                
                // If cart is empty, refresh the page to show empty cart message
                if (data.itemCount === 0) {
                    window.location.reload();
                }
            }
        })
        .catch(error => {
            console.error('Error removing item from cart:', error);
        });
    }
    
    // Function to apply promo code via AJAX
    function applyPromoCode(promoCode) {
        fetch('/cart/apply-promo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                promo_code: promoCode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateOrderSummary(data.subtotal, data.discount, data.deliveryFee, data.total);
                
                // Clear promo code input and show success message
                document.querySelector('.promo-input input').value = '';
                alert('Promo code applied successfully!');
            } else {
                alert(data.message || 'Invalid promo code');
            }
        })
        .catch(error => {
            console.error('Error applying promo code:', error);
        });
    }
    
    // Function to update the order summary with new values
    function updateOrderSummary(subtotal, discount, deliveryFee, total) {
        document.querySelector('.summary-row:nth-child(1) .summary-value').textContent = '$' + subtotal;
        document.querySelector('.summary-row.discount .summary-value').textContent = '-$' + discount;
        document.querySelector('.summary-row:nth-child(3) .summary-value').textContent = '$' + deliveryFee;
        document.querySelector('.summary-row.total .summary-value').textContent = '$' + total;
    }
});