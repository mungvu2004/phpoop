@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/cart.css') }}">
@endpush


@section('content')
<div class="cart-container">
    <h1 class="cart-title">YOUR CART</h1>
    
    <div class="cart-content">
        <div class="cart-items">
            @foreach($cartItems as $item)
            <div class="cart-item">
                <div class="item-image">
                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                </div>
                <div class="item-details">
                    <h3 class="item-name">{{ $item->product->name }}</h3>
                    <div class="item-options">
                        <div class="item-option">Size: {{ $item->size }}</div>
                        <div class="item-option">Color: {{ $item->color }}</div>
                    </div>
                    <div class="item-price">${{ number_format($item->price, 0) }}</div>
                </div>
                <div class="item-actions">
                    <div class="quantity-control">
                        <button class="quantity-btn decrease" data-id="{{ $item->id }}">−</button>
                        <input type="text" class="quantity-input" value="{{ $item->quantity }}" readonly>
                        <button class="quantity-btn increase" data-id="{{ $item->id }}">+</button>
                    </div>
                    <button class="remove-item" data-id="{{ $item->id }}">
                        <span class="remove-icon">×</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="order-summary">
            <h2 class="summary-title">Order Summary</h2>
            
            <div class="summary-row">
                <div class="summary-label">Subtotal</div>
                <div class="summary-value">${{ number_format($subtotal, 0) }}</div>
            </div>
            
            <div class="summary-row discount">
                <div class="summary-label">Discount (-20%)</div>
                <div class="summary-value">-${{ number_format($discount, 0) }}</div>
            </div>
            
            <div class="summary-row">
                <div class="summary-label">Delivery Fee</div>
                <div class="summary-value">${{ number_format($deliveryFee, 0) }}</div>
            </div>
            
            <div class="summary-row total">
                <div class="summary-label">Total</div>
                <div class="summary-value">${{ number_format($total, 0) }}</div>
            </div>
            
            <div class="promo-code">
                <div class="promo-input">
                    <span class="promo-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.5 8.33334V11.6667C17.5 13.5 16.8333 14.1667 15 14.1667H12.5V5.83334H15C16.8333 5.83334 17.5 6.50001 17.5 8.33334Z" stroke="#888888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.5 8.33334H15" stroke="#888888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.5 11.6667H15" stroke="#888888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M2.5 5.83334H12.5V14.1667H5C3.33333 14.1667 2.5 13.3333 2.5 11.6667V5.83334Z" stroke="#888888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 17.5L7.5 14.1667" stroke="#888888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 5.83334V2.5" stroke="#888888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.33333 5.83334V2.5" stroke="#888888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <input type="text" placeholder="Add promo code">
                </div>
                <button class="apply-btn">Apply</button>
            </div>
            
            <button class="checkout-btn">
                Go to Checkout
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.5 13.5L15 9L10.5 4.5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3 9H15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('Client/cart.js') }}"></script>
@endsection

