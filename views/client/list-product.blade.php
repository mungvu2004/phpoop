@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/list-product.css') }}">
@endpush

@section('content')
<div class="product-page-container">
    <div class="filter-sidebar">
        <h3>BỘ LỌC</h3>

        <div class="filter-group">
            <h4>DANH MỤC</h4>
            @foreach (['Áo thun', 'Áo sơ mi', 'Quần âu', 'Áo khoác', 'Áo len'] as $cat)
                <label>
                    <input type="checkbox" name="category" value="{{ $cat }}" onchange="applyFilter()">
                    {{ $cat }}
                </label>
            @endforeach
        </div>

        <div class="filter-group">
            <h4>GIÁ</h4>
            <div class="range-slider">
                <input type="range" id="price-min" min="0" max="1000000" step="10000" value="0" oninput="updatePriceRange()">
                <input type="range" id="price-max" min="0" max="1000000" step="10000" value="1000000" oninput="updatePriceRange()">
            </div>
            <div class="price-label">
                <span id="price-min-display">0</span> – <span id="price-max-display">1,000,000</span> VNĐ
            </div>
        </div>

        

        <div class="filter-group">
            <h4>KÍCH THƯỚC</h4>
            @foreach (['S', 'M', 'L', 'XL', 'XXL'] as $size)
                <label>
                    <input type="checkbox" name="size" value="{{ $size }}" onchange="applyFilter()">
                    {{ $size }}
                </label>
            @endforeach
        </div>

        <button onclick="applyFilter()">ÁP DỤNG BỘ LỌC</button>
    </div>

    <div class="product-content">
        <div class="product-header">
            <h2 id="category-title">TẤT CẢ SẢN PHẨM</h2>
            <div class="sort-bar">
                <span id="product-count">Hiển thị 1–9 trên {{ count($products) }} sản phẩm</span>
                <select id="sort-filter" onchange="applyFilter()">
                    <option value="default">PHỔ BIẾN NHẤT</option>
                    <option value="price-asc">GIÁ: THẤP ĐẾN CAO</option>
                    <option value="price-desc">GIÁ: CAO ĐẾN THẤP</option>
                </select>
            </div>
        </div>

        <div class="product-grid" id="all-products-grid">
            @foreach ($products as $item)
                <div class="product-card"
                     data-category="{{ $item['category'] }}"
                     data-price="{{ $item['price'] }}"
                     data-color="{{ $item['color'] }}"
                     data-size="{{ $item['size'] }}"
                     data-popularity="{{ $item['popularity'] }}"
                     onclick="goToProductDetail('{{ $item['id'] }}')">
                    <img src="{{ file_url($item['image_url']) }}" alt="{{ $item['name'] }}">
                    <h3>{{ $item['name'] }}</h3>
                    <div class="price">
                        {{ number_format($item['price'], 0, ',', '.') }} VNĐ
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pagination">
            <button id="prev-page" onclick="changePage(-1)">TRƯỚC</button>
            <div id="page-numbers"></div>
            <button id="next-page" onclick="changePage(1)">SAU</button>
        </div>
    </div>
</div>

<script>
    let currentPage = 1;
    const productsPerPage = 9;
    let allProducts = [];

    document.addEventListener("DOMContentLoaded", () => {
        allProducts = Array.from(document.querySelectorAll('.product-card'));
        // Hiển thị tất cả sản phẩm ban đầu mà không áp dụng bộ lọc
        updateProductGrid(allProducts);
    });

    function updatePriceRange() {
        const priceMinInput = document.getElementById('price-min');
        const priceMaxInput = document.getElementById('price-max');
        let priceMin = parseInt(priceMinInput.value);
        let priceMax = parseInt(priceMaxInput.value);

        // Đảm bảo priceMin luôn nhỏ hơn priceMax
        if (priceMin > priceMax) {
            [priceMin, priceMax] = [priceMax, priceMin];
            priceMinInput.value = priceMin;
            priceMaxInput.value = priceMax;
        }

        // Cập nhật hiển thị giá
        document.getElementById('price-min-display').textContent = priceMin.toLocaleString();
        document.getElementById('price-max-display').textContent = priceMax.toLocaleString();

        applyFilter();
    }

    function applyFilter() {
        const priceMin = parseInt(document.getElementById('price-min').value) || 0;
        const priceMax = parseInt(document.getElementById('price-max').value) || 1000000;

        const selectedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked')).map(c => c.value);
        const selectedColors = Array.from(document.querySelectorAll('input[name="color"]:checked')).map(c => c.value);
        const selectedSizes = Array.from(document.querySelectorAll('input[name="size"]:checked')).map(c => c.value);

        let filtered = allProducts.filter(p => {
            const price = parseInt(p.dataset.price);
            const priceMatch = price >= priceMin && price <= priceMax;
            const categoryMatch = selectedCategories.length === 0 || selectedCategories.includes(p.dataset.category);
            const colorMatch = selectedColors.length === 0 || selectedColors.includes(p.dataset.color);
            const sizeMatch = selectedSizes.length === 0 || selectedSizes.includes(p.dataset.size);
            return priceMatch && categoryMatch && colorMatch && sizeMatch;
        });

        const sortFilter = document.getElementById('sort-filter').value;
        if (sortFilter === 'price-asc') {
            filtered.sort((a, b) => parseInt(a.dataset.price) - parseInt(b.dataset.price));
        } else if (sortFilter === 'price-desc') {
            filtered.sort((a, b) => parseInt(b.dataset.price) - parseInt(a.dataset.price));
        } else if (sortFilter === 'default') {
            filtered.sort((a, b) => parseInt(b.dataset.popularity) - parseInt(a.dataset.popularity));
        }

        updateProductGrid(filtered);
    }

    function updateProductGrid(filtered) {
        const grid = document.getElementById('all-products-grid');
        grid.innerHTML = '';
        filtered.forEach(p => p.style.display = 'none');

        const totalPages = Math.ceil(filtered.length / productsPerPage);
        const start = (currentPage - 1) * productsPerPage;
        const end = start + productsPerPage;

        filtered.slice(start, end).forEach(p => {
            p.style.display = 'block';
            grid.appendChild(p);
        });

        updatePagination(filtered.length, totalPages);
    }

    function updatePagination(total, pages) {
        const pageWrapper = document.getElementById('page-numbers');
        pageWrapper.innerHTML = '';
         for (let i = 1; i <= pages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = (i === currentPage) ? 'active' : '';
            btn.onclick = () => {
                currentPage = i;
                applyFilter();
            };
            pageWrapper.appendChild(btn);
        }

        document.getElementById('prev-page').disabled = currentPage === 1;
        document.getElementById('next-page').disabled = currentPage === pages;
        document.getElementById('product-count').textContent = `Hiển thị ${(currentPage - 1) * productsPerPage + 1}–${Math.min(currentPage * productsPerPage, total)} trên ${total} sản phẩm`;
    }

    function changePage(direction) {
        currentPage += direction;
        applyFilter();
    }

    function goToProductDetail(id) {
        window.location.href = `/product/${id}`;
    }
</script><script>
    let currentPage = 1;
    const productsPerPage = 9;
    let allProducts = [];

    document.addEventListener("DOMContentLoaded", () => {
        allProducts = Array.from(document.querySelectorAll('.product-card'));
        // Hiển thị tất cả sản phẩm ban đầu mà không áp dụng bộ lọc
        updateProductGrid(allProducts);
    });

    function updatePriceRange() {
        const priceMinInput = document.getElementById('price-min');
        const priceMaxInput = document.getElementById('price-max');
        let priceMin = parseInt(priceMinInput.value);
        let priceMax = parseInt(priceMaxInput.value);

        // Đảm bảo priceMin luôn nhỏ hơn priceMax
        if (priceMin > priceMax) {
            [priceMin, priceMax] = [priceMax, priceMin];
            priceMinInput.value = priceMin;
            priceMaxInput.value = priceMax;
        }

        // Cập nhật hiển thị giá
        document.getElementById('price-min-display').textContent = priceMin.toLocaleString();
        document.getElementById('price-max-display').textContent = priceMax.toLocaleString();

        applyFilter();
    }

    function applyFilter() {
        const priceMin = parseInt(document.getElementById('price-min').value) || 0;
        const priceMax = parseInt(document.getElementById('price-max').value) || 1000000;

        const selectedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked')).map(c => c.value);
        const selectedColors = Array.from(document.querySelectorAll('input[name="color"]:checked')).map(c => c.value);
        const selectedSizes = Array.from(document.querySelectorAll('input[name="size"]:checked')).map(c => c.value);

        let filtered = allProducts.filter(p => {
            const price = parseInt(p.dataset.price);
            const priceMatch = price >= priceMin && price <= priceMax;
            const categoryMatch = selectedCategories.length === 0 || selectedCategories.includes(p.dataset.category);
            const colorMatch = selectedColors.length === 0 || selectedColors.includes(p.dataset.color);
            const sizeMatch = selectedSizes.length === 0 || selectedSizes.includes(p.dataset.size);
            return priceMatch && categoryMatch && colorMatch && sizeMatch;
        });

        const sortFilter = document.getElementById('sort-filter').value;
        if (sortFilter === 'price-asc') {
            filtered.sort((a, b) => parseInt(a.dataset.price) - parseInt(b.dataset.price));
        } else if (sortFilter === 'price-desc') {
            filtered.sort((a, b) => parseInt(b.dataset.price) - parseInt(a.dataset.price));
        } else if (sortFilter === 'default') {
            filtered.sort((a, b) => parseInt(b.dataset.popularity) - parseInt(a.dataset.popularity));
        }

        updateProductGrid(filtered);
    }

    function updateProductGrid(filtered) {
        const grid = document.getElementById('all-products-grid');
        grid.innerHTML = '';
        filtered.forEach(p => p.style.display = 'none');

        const totalPages = Math.ceil(filtered.length / productsPerPage);
        const start = (currentPage - 1) * productsPerPage;
        const end = start + productsPerPage;

        filtered.slice(start, end).forEach(p => {
            p.style.display = 'block';
            grid.appendChild(p);
        });

        updatePagination(filtered.length, totalPages);
    }

    function updatePagination(total, pages) {
        const pageWrapper = document.getElementById('page-numbers');
        pageWrapper.innerHTML = '';
         for (let i = 1; i <= pages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = (i === currentPage) ? 'active' : '';
            btn.onclick = () => {
                currentPage = i;
                applyFilter();
            };
            pageWrapper.appendChild(btn);
        }

        document.getElementById('prev-page').disabled = currentPage === 1;
        document.getElementById('next-page').disabled = currentPage === pages;
        document.getElementById('product-count').textContent = `Hiển thị ${(currentPage - 1) * productsPerPage + 1}–${Math.min(currentPage * productsPerPage, total)} trên ${total} sản phẩm`;
    }

    function changePage(direction) {
        currentPage += direction;
        applyFilter();
    }

    function goToProductDetail(id) {
        window.location.href = `/product/${id}`;
    }
</script>
@endsection