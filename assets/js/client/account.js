document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.getElementById('city');
    const selectedCity = "{{ $userAddress['city'] ?? '' }}";
    const img = document.getElementById("image_url");
    fetch("https://provinces.open-api.vn/api/p/")
        .then(res => {
            return res.json();
        })
        .then(data => {
            data.forEach(city => {
                const option = document.createElement("option");
                option.value = city.name;
                option.textContent = city.name;
                
                citySelect.appendChild(option);
            });
        }) 
        .catch(error => {
            console.error("Lỗi khi tải danh sách tỉnh thành:", error);
        });
});

document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image_url');
    const preview = document.getElementById('previewImage');
    const imagePreviewDiv = document.getElementById('imagePreview');
    
    // Kiểm tra xem có ảnh từ database không
    const hasImageFromDatabase = preview.getAttribute('src') && preview.getAttribute('src') !== '';
    
    if (hasImageFromDatabase) {
        imagePreviewDiv.classList.add('has-image');
        preview.style.display = 'block';
    }

    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                imagePreviewDiv.classList.add('has-image');
            }
            
            reader.readAsDataURL(file);
        } else {
            // Nếu không chọn file mới
            if (hasImageFromDatabase) {
                preview.style.display = 'block';
                imagePreviewDiv.classList.add('has-image');
            } else {
                preview.style.display = 'none';
                imagePreviewDiv.classList.remove('has-image');
            }
        }
    });
});



