document.addEventListener('DOMContentLoaded', function () {
    const status = document.querySelectorAll('.status-color');
    status.forEach(item => {
        const textContent = item.textContent.trim().toLowerCase();
        console.log(textContent);
        if (!item || !item.textContent) return; // bỏ qua nếu element null hoặc rỗng
        switch (textContent) {
            // 'pending','processing','shipped','completed','cancelled'
            case 'pending':
                item.classList.add('pending');
                break;
            case 'processing':
                item.classList.add('processing');
                break;
            case 'shipped':
                item.classList.add('shipped'); 
                break;
            case 'completed':
                item.classList.add('completed');
                break;
            case 'cancelled':
                item.classList.add('cancelled');
                break;
            default:
                break;
        }
    });
    
});

