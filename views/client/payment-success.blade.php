@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/payment.css') }}">
@endpush

@section('content')
    <div class="payment-status-container">
        <div class="payment-status success">
            <div class="status-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 16.17L4.83 12L3.41 13.41L9 19L21 7L19.59 5.59L9 16.17Z" fill="#4CAF50"/>
                </svg>
            </div>
            <h1>Thanh toán thành công!</h1>
            <p>Cảm ơn bạn đã mua hàng. Đơn hàng của bạn sẽ được xử lý trong thời gian sớm nhất.</p>
            <div class="order-details">
                <p>Mã đơn hàng: <strong>{{ $order_id }}</strong></p>
                <p>Số tiền: <strong>{{ number_format($amount, 0) }} ₫</strong></p>
            </div>
            <div class="action-buttons">
                <a href="/orders" class="btn btn-primary">Xem đơn hàng</a>
                <a href="/" class="btn btn-secondary">Tiếp tục mua sắm</a>
            </div>
        </div>
    </div>
@endsection 