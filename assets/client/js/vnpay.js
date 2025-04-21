document.addEventListener('DOMContentLoaded', function() {
    const cancelBtn = document.getElementById('cancel-payment');
    const proceedBtn = document.getElementById('proceed-payment');
    const paymentType = document.querySelector('input[name="payment_type"]:checked').value;

    // Handle cancel button click
    cancelBtn.addEventListener('click', function() {
        window.location.href = '/cart';
    });

    // Handle proceed payment button click
    proceedBtn.addEventListener('click', function() {
        const orderId = document.getElementById('order-id').textContent;
        const amount = document.getElementById('total-amount').textContent.replace(/[^\d]/g, '');
        const paymentType = document.querySelector('input[name="payment_type"]:checked').value;

        // Show loading state
        proceedBtn.disabled = true;
        proceedBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';

        // Make API call to create VNPay payment URL
        fetch('/payment/create-vnpay-url', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                order_id: orderId,
                amount: amount,
                payment_type: paymentType
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Redirect to VNPay payment page
                window.location.href = data.payment_url;
            } else {
                alert('Có lỗi xảy ra khi tạo URL thanh toán. Vui lòng thử lại sau.');
                proceedBtn.disabled = false;
                proceedBtn.textContent = 'Tiến hành thanh toán';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tạo URL thanh toán. Vui lòng thử lại sau.');
            proceedBtn.disabled = false;
            proceedBtn.textContent = 'Tiến hành thanh toán';
        });
    });

    // Handle payment type change
    document.querySelectorAll('input[name="payment_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Update UI based on selected payment type
            document.querySelectorAll('.payment-option label').forEach(label => {
                label.style.borderColor = '#ddd';
                label.style.backgroundColor = 'transparent';
            });
            this.nextElementSibling.style.borderColor = '#007bff';
            this.nextElementSibling.style.backgroundColor = '#f0f7ff';
        });
    });
}); 