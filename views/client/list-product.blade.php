@extends('client.layouts.main')
@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/list-product.css') }}">
@endpush

@section('content')
<div class="category-container">
    @php
        // Nhóm sản phẩm theo danh mục
        $groupedProducts = [];
        if (isset($products) && is_array($products)) {
            foreach ($products as $item) {
                $categoryId = isset($item['category_id']) ? (int)$item['category_id'] : 0;
                if ($categoryId > 0) { // Chỉ nhóm nếu category_id hợp lệ
                    if (!isset($groupedProducts[$categoryId])) {
                        $groupedProducts[$categoryId] = [];
                    }
                    $groupedProducts[$categoryId][] = $item;
                }
            }
        }

        // Tạo ánh xạ tên danh mục
        $categoryNames = [];
        if (isset($category) && is_array($category)) {
            foreach ($category as $cat) {
                $categoryNames[$cat['id']] = $cat['name'];
            }
        } else {
            // Nếu $category không tồn tại, có thể gán giá trị mặc định
            $categoryNames = [
                1 => 'Áo thun',
                2 => 'Áo sơ mi',
                3 => 'Quần âu',
                4 => 'Áo khoác',
                5 => 'Áo len',
            ];
        }

        // Đảm bảo danh mục đầu tiên là "Áo thun" nếu category_id = 1
        if (!isset($categoryNames[1]) || $categoryNames[1] !== 'Áo thun') {
            $categoryNames[1] = 'Áo thun';
        }
    @endphp

    <!-- Products Grid -->
    <div class="products-main">
        @if (!empty($groupedProducts))
            @foreach ($groupedProducts as $categoryId => $items)
                @php
                    // Đảm bảo categoryName và categorySlug duy nhất
                    $categoryName = isset($categoryNames[$categoryId]) ? $categoryNames[$categoryId] : 'Unknown Category';
                    $categorySlug = strtolower(str_replace(' ', '-', $categoryName)) . '-' . $categoryId;
                @endphp

                <div class="category-section" id="{{ $categorySlug }}">
                    <h2>{{ $categoryName }}</h2>
                    <div class="products-grid" id="{{ $categorySlug }}-products">
                        @foreach ($items as $index => $item)
                            @php
                                $imgPath = $item['image_url'];
                                $defaultImg = file_url('storage/uploads/users/error.png');
                                $imgSrc = file_exists($imgPath) ? file_url($imgPath) : $defaultImg;
                                $isHidden = $index >= 3 ? 'hidden' : '';
                            @endphp
                            <div class="product-item {{ $isHidden }}">
                                <img src="{{ $imgSrc }}" alt="{{ $item['name'] }}">
                                <h3>{{ $item['name'] }}</h3>
                                <p>{{ number_format($item['price'], 0, ',', '.') }} VNĐ</p>
                            </div>
                        @endforeach
                    </div>
                    @if (count($items) > 3)
                        <button class="learn-more" onclick="showMore('{{ $categorySlug }}-products')">Learn More</button>
                    @endif
                </div>
            @endforeach
        @else
            <p>Không có sản phẩm nào để hiển thị.</p>
        @endif
    </div>
</div>

<script>
    function showMore(categoryId) {
        const productsGrid = document.getElementById(categoryId);
        if (productsGrid) {
            const hiddenItems = productsGrid.querySelectorAll('.product-item.hidden');
            hiddenItems.forEach(item => {
                item.classList.remove('hidden');
            });
            const button = productsGrid.nextElementSibling;
            if (button) {
                button.style.display = 'none';
            }
        }
    }
</script>
@endsection