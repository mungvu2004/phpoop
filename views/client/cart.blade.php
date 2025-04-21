@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/cart.css') }}">
    <link rel="stylesheet" href="{{ file_url('assets/client/checkout.css') }}">
@endpush
@push('scripts')
    <script src="{{file_url('assets/client/js/cart.js')}}"></script>
    <script src="{{file_url('assets/js/notifi.js')}}"></script>
    <script src="{{file_url('assets/client/js/checkout.js')}}"></script>
@endpush

@section('content')
    <div class="cart-container">
        <div class="cart-content">
            <h1 class="cart-title">YOUR CART</h1>
            <div class="order-list" id="order-list">
                @foreach ($orders as $order)
                    <!-- Đơn hàng -->
                    <div class="order-card" data-order-id="{{ $order['order_id'] }}"
                        data-total="{{ $order['total_price'] ?? 0 }}" data-discount="{{ $order['discount'] ?? 0 }}"
                        data-shipping="{{ $order['shipping_fee'] ?? 0 }}">
                        <div class="order-header">
                            <div class="order-header-left">
                                <input type="checkbox" class="order-checkbox" data-order-id="{{ $order['order_id'] }}"
                                    data-status="{{$order['status']}}">
                                <div class="order-id">Đơn hàng #{{ $order['order_id'] }}</div>
                                <div class="order-date">{{$order['created_at']}}</div>
                            </div>
                            <div class="order-status status-{{ strtolower(str_replace(' ', '-', $order['status'])) }}">
                                {{ $order['status'] }}
                            </div>
                            <div class="order-actions">
                                <button class="btn btn-info details-btn" data-order-id="{{ $order['order_id'] }}">
                                    <i class="fa fa-eye"></i> Chi tiết
                                </button>
                            </div>
                        </div>

                        <div class="order-body" id="order-body-{{ $order['order_id'] }}">
                            <div class="order-summary">
                                <div class="summary-item">
                                    <div class="summary-label">Tổng số lượng</div>
                                    <div class="summary-value">{{ $order['items']['quantity'] }}</div>
                                </div>
                            </div>
                            <table class="product-list">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order['item'] as $item)
                                        <tr data-product-id="{{ $item['product_id'] }}">
                                            <td>
                                                <div class="product-info">
                                                    <div class="thumbnail-container">
                                                        <img src="{{ file_url($item['image_url']) }}"
                                                            alt="{{ $item['product_name'] }}" class="product-image">
                                                    </div>
                                                    <div class="product-name">{{ $item['product_name'] }}</div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($item['product_price'], 0) }} ₫</td>
                                            <td>
                                                <div class="quantity-control">
                                                    <button class="quantity-btn decrease-btn"
                                                        data-product-id="{{ $item['product_id'] }}">-</button>
                                                    <input type="text" class="quantity-input" value="{{ $item['quantity'] }}"
                                                        data-product-id="{{ $item['product_id'] }}"
                                                        data-order-detail-id="{{ $item['id'] }}" readonly>
                                                    <button class="quantity-btn increase-btn"
                                                        data-product-id="{{ $item['product_id'] }}">+</button>
                                                </div>
                                            </td>
                                            <td data-subtotal="{{ $item['product_price'] * $item['quantity'] }}">
                                                {{ number_format($item['product_price'] * $item['quantity'], 0) }} ₫
                                            </td>
                                            <td>
                                                <button class="btn btn-danger delete-product-btn notifis"
                                                    data-product-id="{{ $item['product_id'] }}"
                                                    data-order-detail-id="{{ $item['id'] }}"
                                                    onclick="return confirmDelete()">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h2 class="summary-title">Tổng đơn hàng</h2>
            <div class="summary-row">
                <div class="summary-label">Tổng tiền</div>
                <div class="summary-value" id="summary-total">0 ₫</div>
            </div>
            <div class="summary-row discount">
                <div class="summary-label">Giảm giá</div>
                <div class="summary-value" id="summary-discount"></div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Phí vận chuyển</div>
                <div class="summary-value" id="summary-shipping">0 ₫</div>
            </div>
            <div class="summary-row total">
                <div class="summary-label">Thành tiền</div>
                <div class="summary-value" id="summary-final">0 ₫</div>
            </div>
            <div class="promo-code">
                <div class="promo-input">
                    <span class="promo-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M17.5 8.33334V11.6667C17.5 13.5 16.8333 14.1667 15 14.1667H12.5V5.83334H15C16.8333 5.83334 17.5 6.50001 17.5 8.33334Z"
                                stroke="#888888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.5 8.33334H15" stroke="#888888" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M12.5 11.6667H15" stroke="#888888" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M2.5 5.83334H12.5V14.1667H5C3.33333 14.1667 2.5 13.3333 2.5 11.6667V5.83334Z"
                                stroke="#888888" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5 17.5L7.5 14.1667" stroke="#888888" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M5 5.83334V2.5" stroke="#888888" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M8.33333 5.83334V2.5" stroke="#888888" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <input class="coupon_name" value="" type="text" placeholder="Nhập mã giảm giá">
                </div>
                <button class="apply-btn">Áp dụng</button>
            </div>
            <button class="checkout-btn" id="checkout-btn" style="">
                Thanh toán
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.5 13.5L15 9L10.5 4.5" stroke="white" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M3 9H15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div class="checkout-modal" id="checkout-modal">
        <div class="checkout-modal-content">
            <div class="checkout-modal-header">
                <h2>Thanh toán</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="checkout-modal-body">
                <form id="checkout-form" action="/payment/process" method="POST">
                    <input type="hidden" name="order_ids" id="selected-order-ids" value="">
                    <input type="hidden" name="amount" id="total-amount" value="">
                    
                    <div class="form-section">
                        <h3>Phương thức vận chuyển</h3>
                        <div class="shipping-methods">
                            <label class="shipping-method">
                                <input type="radio" name="shipping_method" value="standard" checked>
                                <div class="method-content">
                                    <span class="method-name">Giao hàng tiêu chuẩn</span>
                                    <span class="method-price">20,000 ₫</span>
                                    <span class="method-time">3-5 ngày</span>
                                </div>
                            </label>
                            <label class="shipping-method">
                                <input type="radio" name="shipping_method" value="express">
                                <div class="method-content">
                                    <span class="method-name">Giao hàng nhanh</span>
                                    <span class="method-price">40,000 ₫</span>
                                    <span class="method-time">1-2 ngày</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3>Phương thức thanh toán</h3>
                        <div class="payment-methods">
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="cod" checked>
                                <div class="method-content">
                                    <span class="method-name">Thanh toán khi nhận hàng (COD)</span>
                                    <span class="method-icon">💵</span>
                                </div>
                            </label>
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="vnpay">
                                <div class="method-content">
                                    <span class="method-name">Thanh toán qua VNPAY</span>
                                    <img src="{{ file_url('assets/images/vnpay-logo.png') }}" alt="VNPay" class="method-icon" style="height: 30px;">
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary close-modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('checkout-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const paymentMethod = formData.get('payment_method');
    const orderIds = formData.get('order_ids');
    let amount = formData.get('amount');
    const shippingMethod = formData.get('shipping_method');
    
    // Đảm bảo amount là số
    amount = parseFloat(amount) || 0;
    
    // Debug
    console.log("Form data:", {
        payment_method: paymentMethod,
        order_ids: orderIds,
        amount: amount,
        shipping_method: shippingMethod
    });

    try {
        let response;
        if (paymentMethod === 'cod') {
            // Gửi request thanh toán COD
            response = await fetch('/payment/processCOD', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_ids: orderIds,
                    shipping_method: shippingMethod
                })
            });
        } else if (paymentMethod === 'vnpay') {
            // Gửi request thanh toán VNPAY
            response = await fetch('/payment/vnpay', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_ids: orderIds,
                    amount: amount,
                    shipping_method: shippingMethod
                })
            });
        }

        console.log("Response status:", response.status);
        const text = await response.text();
        console.log("Response text:", text);
        
        try {
            const data = JSON.parse(text);
            console.log("Parsed data:", data);
            
            if (data.status === 'success') {
                if (paymentMethod === 'cod') {
                    window.location.href = '/orders';
                } else if (paymentMethod === 'vnpay') {
                    window.location.href = data.payment_url;
                }
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        } catch (jsonError) {
            console.error("JSON parse error:", jsonError);
            alert("Lỗi xử lý phản hồi từ server");
        }
    } catch (error) {
        console.error('Fetch error:', error);
        alert('Có lỗi xảy ra khi xử lý thanh toán');
    }
});
</script>
@endpush