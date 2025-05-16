let currentPage = 1;
const productsPerPage = 8;
let allProducts = [];
let filteredProducts = [];

document.addEventListener("DOMContentLoaded", () => {
    // Lấy tất cả sản phẩm từ DOM ban đầu
    allProducts = Array.from(document.querySelectorAll('.product-card'));

    // Khởi tạo thanh trượt giá
    initializePriceRangeSlider();

    // Áp dụng bộ lọc ban đầu
    applyFilter();
});

function initializePriceRangeSlider() {
    const minPriceInput = document.getElementById('price-min');
    const maxPriceInput = document.getElementById('price-max');
    const minPriceDisplay = document.getElementById('price-min-display');
    const maxPriceDisplay = document.getElementById('price-max-display');

    minPriceInput.addEventListener('input', updatePriceDisplays);
    maxPriceInput.addEventListener('input', updatePriceDisplays);

    updatePriceDisplays();

    function updatePriceDisplays() {
        let minPrice = parseInt(minPriceInput.value);
        let maxPrice = parseInt(maxPriceInput.value);

        if (minPrice > maxPrice) {
            minPrice = maxPrice;
            minPriceInput.value = minPrice;
        }

        minPriceDisplay.textContent = minPrice.toLocaleString();
        maxPriceDisplay.textContent = maxPrice.toLocaleString();
    }
}

function applyFilter() {
    // Lấy giá trị lọc
    const priceMin = parseInt(document.getElementById('price-min').value) || 0;
    const priceMax = parseInt(document.getElementById('price-max').value) || 1000000;
    const selectedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked')).map(c => c.value);
    const sortOption = document.getElementById('sort-filter').value;

    // Lọc sản phẩm
    filteredProducts = allProducts.filter(product => {
        const price = parseInt(product.dataset.price);
        const category = product.dataset.category;

        const priceInRange = price >= priceMin && price <= priceMax;
        const categoryMatch = selectedCategories.length === 0 || selectedCategories.includes(category);

        return priceInRange && categoryMatch;
    });

    // Sắp xếp
    if (sortOption === 'price-asc') {
        filteredProducts.sort((a, b) => parseInt(a.dataset.price) - parseInt(b.dataset.price));
    } else if (sortOption === 'price-desc') {
        filteredProducts.sort((a, b) => parseInt(b.dataset.price) - parseInt(a.dataset.price));
    }

    // Reset về trang 1
    currentPage = 1;

    // Cập nhật giao diện
    updateProductDisplay(filteredProducts);
}

function updateProductDisplay(products) {
    const grid = document.getElementById('all-products-grid');
    grid.innerHTML = '';

    const total = products.length;
    const totalPages = Math.ceil(total / productsPerPage);
    const start = (currentPage - 1) * productsPerPage;
    const end = Math.min(start + productsPerPage, total);

    if (total > 0) {
        for (let i = start; i < end; i++) {
            const clone = products[i].cloneNode(true);
            const productId = products[i].dataset.id;
            clone.onclick = function () {
                goToProductDetail(productId);
            };
            grid.appendChild(clone);
        }
    } else {
        const noProductsDiv = document.createElement('div');
        noProductsDiv.className = 'no-products';
        noProductsDiv.textContent = 'Không có sản phẩm phù hợp với bộ lọc';
        grid.appendChild(noProductsDiv);
    }

    updatePagination(total, totalPages);
}

function updatePagination(total, totalPages) {
    const paginationContainer = document.querySelector('.pagination');
    const pageNumbersContainer = document.getElementById('page-numbers');
    const prevButton = document.getElementById('prev-page');
    const nextButton = document.getElementById('next-page');
    const productCountDisplay = document.getElementById('product-count');

    pageNumbersContainer.innerHTML = '';

    if (totalPages <= 1) {
        paginationContainer.style.display = 'none';
    } else {
        paginationContainer.style.display = 'flex';

        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement('button');
            button.textContent = i;
            button.className = i === currentPage ? 'active' : '';
            button.onclick = () => {
                currentPage = i;
                updateProductDisplay(filteredProducts);
            };
            pageNumbersContainer.appendChild(button);
        }
    }

    prevButton.disabled = currentPage === 1 || total === 0;
    nextButton.disabled = currentPage === totalPages || total === 0;

    if (total > 0) {
        const fromItem = Math.min((currentPage - 1) * productsPerPage + 1, total);
        const toItem = Math.min(currentPage * productsPerPage, total);
        productCountDisplay.textContent = `Hiển thị ${fromItem}–${toItem} trên ${total} sản phẩm`;
    } else {
        productCountDisplay.textContent = 'Không có sản phẩm phù hợp';
    }
}

function changePage(direction) {
    currentPage += direction;
    updateProductDisplay(filteredProducts);
}

function goToProductDetail(id) {
    window.location.href = `/products/show/${id}`;
}
