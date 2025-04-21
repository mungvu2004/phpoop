@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/payment.css') }}">
@endpush

@push('scripts')
    <script src="{{ file_url('assets/client/js/vnpay.js') }}"></script>
@endpush

@section('content')
    <div class="payment-container">
        <div class="payment-header">
            <h1>Thanh toán qua VNPay</h1>
            <p>Vui lòng kiểm tra thông tin đơn hàng trước khi thanh toán</p>
        </div>

        <div class="payment-content">
            <div class="order-summary">
                <h2>Thông tin đơn hàng</h2>
                <div class="summary-item">
                    <span class="label">Mã đơn hàng:</span>
                    <span class="value" id="order-id">{{ $order_id }}</span>
                </div>
                <div class="summary-item">
                    <span class="label">Tổng tiền:</span>
                    <span class="value" id="total-amount">{{ number_format($amount, 0) }} ₫</span>
                </div>
            </div>

            <div class="payment-methods">
                <h2>Chọn phương thức thanh toán</h2>
                <div class="payment-options">
                    <div class="payment-option">
                        <input type="radio" name="payment_type" value="ATM" id="atm" checked>
                        <label for="atm">
                            <img src="{{ file_url('assets/client/images/atm.png') }}" alt="ATM">
                            <span>Thẻ ATM</span>
                        </label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_type" value="VISA" id="visa">
                        <label for="visa">
                            <img src="{{ file_url('assets/client/images/visa.png') }}" alt="VISA">
                            <span>Thẻ VISA</span>
                        </label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" name="payment_type" value="QR" id="qr">
                        <label for="qr">
                            <img src="{{ file_url('assets/client/images/qr.png') }}" alt="QR">
                            <span>Quét mã QR</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="payment-actions">
                <button class="btn btn-secondary" id="cancel-payment">Hủy</button>
                <button class="btn btn-primary" id="proceed-payment">Tiến hành thanh toán</button>
            </div>
        </div>
    </div>
@endsection 