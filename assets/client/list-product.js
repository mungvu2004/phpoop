// Enhanced list-product.js
let currentPage = 1;
const productsPerPage = 9;
let allProducts = [];

document.addEventListener("DOMContentLoaded", () => {
    // Get all products from DOM
    allProducts = Array.from(document.querySelectorAll('.product-card'));
    
    // Initialize price range slider
    initializePriceRangeSlider();
    
    // Apply initial filtering
    applyFilter();
});

function initializePriceRangeSlider() {
    const priceRangeContainer = document.querySelector('.price-range-slider');
    const minPriceInput = document.getElementById('price-min');
    const maxPriceInput = document.getElementById('price-max');
    const minPriceDisplay = document.getElementById('price-min-display');
    const maxPriceDisplay = document.getElementById('price-max-display');
    
    // Update displays when inputs change
    minPriceInput.addEventListener('input', updatePriceDisplays);
    maxPriceInput.addEventListener('input', updatePriceDisplays);
    
    // Initial update
    updatePriceDisplays();
    
    function updatePriceDisplays() {
        let minPrice = parseInt(minPriceInput.value);
        let maxPrice = parseInt(maxPriceInput.value);
        
        // Ensure min doesn't exceed max
        if (minPrice > maxPrice) {
            minPrice = maxPrice;
            minPriceInput.value = minPrice;
        }
        
        // Update displays with formatted prices
        minPriceDisplay.textContent = minPrice.toLocaleString();
        maxPriceDisplay.textContent = maxPrice.toLocaleString();
    }
}

function applyFilter() {
    // Get filter values
    const priceMin = parseInt(document.getElementById('price-min').value) || 0;
    const priceMax = parseInt(document.getElementById('price-max').value) || 1000000;
    const selectedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked')).map(c => c.value);
    const selectedSizes = Array.from(document.querySelectorAll('input[name="size"]:checked')).map(s => s.value);

    // Filter products
    let filteredProducts = allProducts.filter(product => {
        const price = parseInt(product.dataset.price);
        const category = product.dataset.category;
        const size = product.dataset.size;

        const priceInRange = price >= priceMin && price <= priceMax;
        const categoryMatch = selectedCategories.length === 0 || selectedCategories.includes(category);
        const sizeMatch = selectedSizes.length === 0 || selectedSizes.includes(size);

        return priceInRange && categoryMatch && sizeMatch;
    });

    // Apply sorting
    const sortOption = document.getElementById('sort-filter').value;
    if (sortOption === 'price-asc') {
        filteredProducts.sort((a, b) => parseInt(a.dataset.price) - parseInt(b.dataset.price));
    } else if (sortOption === 'price-desc') {
        filteredProducts.sort((a, b) => parseInt(b.dataset.price) - parseInt(a.dataset.price));
    } else if (sortOption === 'default') {
        filteredProducts.sort((a, b) => parseInt(b.dataset.popularity) - parseInt(a.dataset.popularity));
    }

    // Update category title if only one category is selected
    const categoryTitle = document.getElementById('category-title');
    if (selectedCategories.length === 1) {
        categoryTitle.textContent = selectedCategories[0].toUpperCase();
    } else {
        categoryTitle.textContent = 'TẤT CẢ SẢN PHẨM';
    }

    // Reset to first page when applying new filters
    currentPage = 1;
    
    // Update product display
    updateProductDisplay(filteredProducts);
}

function updateProductDisplay(filteredProducts) {
    const grid = document.getElementById('all-products-grid');
    grid.innerHTML = '';
    
    const totalProducts = filteredProducts.length;
    const totalPages = Math.ceil(totalProducts / productsPerPage);
    
    // Calculate the range of products to display
    const start = (currentPage - 1) * productsPerPage;
    const end = Math.min(start + productsPerPage, totalProducts);
    
    // Display products for current page
    if (totalProducts > 0) {
        for (let i = start; i < end; i++) {
            const productClone = filteredProducts[i].cloneNode(true);
            
            // Reattach click event for the cloned product
            const productId = filteredProducts[i].dataset.id;
            productClone.onclick = function() {
                goToProductDetail(productId);
            };
            
            grid.appendChild(productClone);
        }
    } else {
        // Display "no products" message
        const noProductsDiv = document.createElement('div');
        noProductsDiv.className = 'no-products';
        noProductsDiv.textContent = 'Không có sản phẩm phù hợp với bộ lọc';
        grid.appendChild(noProductsDiv);
    }
    
    // Update pagination
    updatePagination(totalProducts, totalPages);
}

function updatePagination(total, totalPages) {
    const paginationContainer = document.querySelector('.pagination');
    const pageNumbersContainer = document.getElementById('page-numbers');
    const prevButton = document.getElementById('prev-page');
    const nextButton = document.getElementById('next-page');
    const productCountDisplay = document.getElementById('product-count');
    
    // Clear page numbers
    pageNumbersContainer.innerHTML = '';
    
    // Hide pagination if there's only one page or no products
    if (totalPages <= 1) {
        paginationContainer.style.display = 'none';
    } else {
        paginationContainer.style.display = 'flex';
        
        // Create page number buttons
        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement('button');
            button.textContent = i;
            button.className = i === currentPage ? 'active' : '';
            button.onclick = () => {
                currentPage = i;
                applyFilter();
            };
            pageNumbersContainer.appendChild(button);
        }
    }
    
    // Update prev/next buttons
    prevButton.disabled = currentPage === 1 || total === 0;
    nextButton.disabled = currentPage === totalPages || total === 0;
    
    // Update product count display
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
    applyFilter();
}

function goToProductDetail(id) {
    window.location.href = `/product/${id}`;
}