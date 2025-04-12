@extends('client.layouts.main')
@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/list-product.css') }}">
@endpush

@section('content')
<div class="category-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <span>Home</span> / <span class="active">Shop</span>
    </div>

    <div class="category-main">
        <!-- Category Title and Products Count -->
        <div class="category-header">
            <h1>All Products</h1>
            <p>Showing 1-9 of 24 products</p>
        </div>

        <!-- Category Content -->
        <div class="category-content">
            <!-- Left Sidebar - Filters -->
            <div class="filters-sidebar">
                <div class="filter-section">
                    <h3>Filters</h3>
                </div>

                <!-- Price Filter -->
                <div class="filter-section">
                    <h4>Price</h4>
                    <div class="price-slider">
                        <input type="range" min="0" max="200" value="150" class="price-range">
                    </div>
                    <div class="price-range-labels">
                        <span>$0</span>
                        <span>$200</span>
                    </div>
                </div>

                <!-- Color Filter -->
                <div class="filter-section">
                    <h4>Colors</h4>
                    <div class="color-options">
                        <div class="color-option red"></div>
                        <div class="color-option orange"></div>
                        <div class="color-option yellow"></div>
                        <div class="color-option green"></div>
                        <div class="color-option blue"></div>
                        <div class="color-option purple"></div>
                        <div class="color-option pink"></div>
                        <div class="color-option black"></div>
                        <div class="color-option white"></div>
                    </div>
                </div>

                <!-- Size Filter -->
                <div class="filter-section">
                    <h4>Size</h4>
                    <div class="size-options">
                        <div class="size-option">XS</div>
                        <div class="size-option">S</div>
                        <div class="size-option">M</div>
                        <div class="size-option">L</div>
                        <div class="size-option">XL</div>
                        <div class="size-option">XXL</div>
                    </div>
                </div>

                <!-- Style Filter -->
                <div class="filter-section">
                    <h4>Dress Style</h4>
                    <div class="checkbox-option">
                        <input type="checkbox" id="casual">
                        <label for="casual">Casual</label>
                    </div>
                    <div class="checkbox-option">
                        <input type="checkbox" id="formal">
                        <label for="formal">Formal</label>
                    </div>
                </div>

                <!-- Clear Filter Button -->
                <div class="filter-actions">
                    <button class="clear-filters">Clear All Filters</button>
                </div>
            </div>

            <!-- Right Side - Products Grid -->
            
        </div>
    </div>
</div>
@endsection
