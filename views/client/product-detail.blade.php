@extends('client.layouts.main')

@push('product-detail')
    <link rel="stylesheet" href="{{ file_url('assets/client/product-detail.css') }}">
@endpush

@section('content')
<div class="product-detail">
    <div class="product-gallery">
        <div class="product-thumbnails">
            <img src="/path/to/thumb1.jpg" alt="Thumbnail 1">
            <img src="/path/to/thumb2.jpg" alt="Thumbnail 2">
            <img src="/path/to/thumb3.jpg" alt="Thumbnail 3">
        </div>
        <div class="product-main-image">
            <img src="/path/to/main-image.jpg" alt="Main Product Image">
        </div>
    </div>

    <div class="product-info">
        <h1 class="product-title">ONE LIFE GRAPHIC T-SHIRT</h1>
        <div class="product-rating">★★★★★ 4.5 (120 reviews)</div>
        <div class="product-price">
            <span class="new-price">$200</span>
            <span class="old-price">$300</span>
        </div>
        
        <div class="product-colors">
            <span class="color-option" style="background-color: black;"></span>
            <span class="color-option" style="background-color: blue;"></span>
            <span class="color-option" style="background-color: gray;"></span>
        </div>
        
        <div class="product-size">
            <button class="size-option">S</button>
            <button class="size-option">M</button>
            <button class="size-option">L</button>
            <button class="size-option">XL</button>
        </div>
        
        <button class="add-to-cart">ADD TO CART</button>
    </div>
</div>

<div class="reviews">
    <h2>All Reviews</h2>
    <div class="review">
        <p class="review-text">"Great quality, very comfortable!"</p>
        <div class="review-rating">★★★★★</div>
    </div>
    <div class="review">
        <p class="review-text">"Nice fit, but delivery was late."</p>
        <div class="review-rating">★★★★☆</div>
    </div>
</div>

<div class="suggested-products">
    <h2>You Might Also Like</h2>
    <div class="product-suggestions">
        <div class="suggestion-item">
            <img src="/path/to/similar1.jpg" alt="Similar Product 1">
            <p>Product Name 1</p>
            <span>$50</span>
        </div>
        <div class="suggestion-item">
            <img src="/path/to/similar2.jpg" alt="Similar Product 2">
            <p>Product Name 2</p>
            <span>$60</span>
        </div>
    </div>
</div>
@endsection
