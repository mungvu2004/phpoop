document.addEventListener("DOMContentLoaded", function () {
    const create = document.querySelector(".create-product");
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