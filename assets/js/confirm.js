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