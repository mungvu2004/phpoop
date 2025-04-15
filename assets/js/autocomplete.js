document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const autoComplete = document.getElementById('autocomplete-box');

    searchInput.addEventListener('focus', function () {
        autoComplete.style.display = 'flex';
    });

    searchInput.addEventListener('blur', function () {
        setTimeout(() => {
            if(!searchInput.value) {
                autoComplete.style.display = 'none';
            }
        }, 200); // Tránh mất gợi ý trước khi click
    });

    searchInput.addEventListener('input', function () {
        const keyword = this.value;
        if (keyword.length < 2) {
            autoComplete.innerHTML = '<div class="notifi">Hãy nhập từ 2 kí tự trở lên để tìm sản phẩm</div>';
            autoComplete.style.display = 'flex';
            return;
        }
        text = encodeURIComponent(keyword);
        fetch(`/products/search/${text}`)
            .then(res => {
                return res.json();  // Phân tích dữ liệu JSON
            })
            .then(data => {
                if (!data || !Array.isArray(data) || !data.length) {
                    autoComplete.innerHTML = '<div class="notifi">Không tìm thấy sản phẩm phù hợp</div>';
                } else {
                    autoComplete.innerHTML = '';
                    data.forEach(product => {
                        const formattedPrice = new Intl.NumberFormat('vi-VN', {
                            style: 'currency',
                            currency: 'VND',
                        }).format(product.price);
                        autoComplete.innerHTML += `
                                <a href="/products/show/${product.id}">
                                    <div class="search-p">
                                        <img src="${product.image_url}" alt="${product.name}">
                                        <div class="search-tt">
                                            <h3>${product.name}</h3>
                                            <span>${formattedPrice}</span>
                                        </div>
                                    </div>
                                </a>
                            `;
                    });
                }
            })
            .catch(err => {
                autoComplete.innerHTML = '<div class="notifi">Lỗi khi tìm kiếm</div>';
                console.error('Lỗi tìm kiếm:', err);
            });
    });
});

function selectSuggestion(name) {
    document.getElementById("searchInput").value = name;
    document.getElementById("searchForm").submit();
}
