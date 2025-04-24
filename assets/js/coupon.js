function sendToggleRequest(id, value) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/admin/coupon/edit/${id}`;
    form.style.display = 'none';

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'is_active';
    input.value = value;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}


document.addEventListener('DOMContentLoaded', function() {
    document.getElementById("toggleSwitch").addEventListener("change", function (e) {
        const id = this.dataset.id;
        const status = parseInt(this.dataset.status); // Trạng thái hiện tại trước khi thay đổi
        const isChecked = this.checked; // Trạng thái mới sau khi người dùng thao tác
    
        // Nếu đang bật và tắt đi
        if (status === 1 && !isChecked) {
            Swal.fire({
                title: 'Bạn có chắc muốn tắt?',
                text: "Hành động này sẽ thay đổi trạng thái của mã giảm giá.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Vâng, tắt đi!',
                cancelButtonText: 'Không, giữ nguyên'
            }).then((result) => {
                if (result.isConfirmed) {
                    sendToggleRequest(id, 0);
                } else {
                    e.target.checked = true;
                }
            });
        }
    
        // Nếu đang tắt và bật lên
        if (status === 0 && isChecked) {
            sendToggleRequest(id, 1);
        }
    });
})

document.addEventListener('DOMContentLoaded', function () {
    const btnCoupon = document.querySelector('.btn-coupon');
    const formCoupon = document.querySelector('.form-coupon');
    const overlay = document.querySelector('.overlay');
    console.log(overlay);
    const outCoupon = document.querySelector('.btn-secondary');

    btnCoupon.addEventListener('click', function() {
        formCoupon.style.display = "block";
        overlay.style.display = "block";
    });
    outCoupon.addEventListener('click', function() {
        formCoupon.style.display = "none";
        overlay.style.display = "none";
    })

})