const productImg = document.querySelector('.product-img');
const zoomImage = document.querySelector('.zoom-image');

productImg.addEventListener('mousemove', (e) => {
    const containerRect = productImg.getBoundingClientRect(); // Lấy vị trí của phần tử chứa ảnh
    const x = e.clientX - containerRect.left; // Tọa độ X của chuột trong phần tử
    const y = e.clientY - containerRect.top; // Tọa độ Y của chuột trong phần tử
    
    const xPercent = (x / containerRect.width) * 100; // Tính toán phần trăm X
    const yPercent = (y / containerRect.height) * 100; // Tính toán phần trăm Y

    // Thay đổi `transform-origin` để phóng to ảnh tại vị trí chuột
    zoomImage.style.transformOrigin = `${xPercent}% ${yPercent}%`;
    zoomImage.style.transform = 'scale(2)'; // Phóng to ảnh (có thể thay đổi tỷ lệ)
});

productImg.addEventListener('mouseleave', () => {
    zoomImage.style.transform = 'scale(1)'; // Trả lại kích thước ban đầu khi rời khỏi ảnh
});
document.addEventListener("DOMContentLoaded", function () {
    const create = document.querySelector(".edit");
    const form = document.querySelector(".form");
    const overlay = document.querySelector(".overlay");
    const close = document.querySelector(".close-form");
    create.addEventListener("click", function () {
        form.classList.add("hidden");
        overlay.style.display = "block";
    })
    close.addEventListener("click", function () {
        form.classList.remove("hidden");
        overlay.style.display = "none";
    });


    const preview = document.getElementById("preview");
    document.getElementById("fileInput").addEventListener("change", function() {
        const file = this.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement("img");
                img.src = e.target.result;
                img.alt = file.name;
                preview.innerHTML = "";
                preview.appendChild(img);
            }
            reader.readAsDataURL(file);
        }
    })
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
