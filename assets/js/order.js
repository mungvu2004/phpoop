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

function confirmSubmit() {
    return confirm("Bạn có chắc chắn muốn sửa sản phẩm này hay không");
}



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
document.addEventListener('DOMContentLoaded', function() {
    const btnDelete = document.querySelectorAll('.btn-delete');
    btnDelete.forEach(item => {
        item.addEventListener('click', e => {
            if(!confirmDelete()) {
                e.preventDefault();
            }
        })
    })
})