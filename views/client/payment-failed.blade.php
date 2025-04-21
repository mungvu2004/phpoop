@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/payment.css') }}">
@endpush

@section('content')
    <div class="payment-status-container">
        <div class="payment-status failed">
            <div class="status-icon">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12L19 6.41Z" fill="#F44336"/>
                </svg>
            </div>
            <h1>Thanh toán thất bại!</h1>
            <p>Rất tiếc, quá trình thanh toán của bạn không thành công. Vui lòng thử lại hoặc liên hệ với chúng tôi để được hỗ trợ.</p>
            <div class="error-details">
                <p>Mã lỗi: <strong>{{ $error_code ?? 'Không xác định' }}</strong></p>
                <p>Thông báo: <strong>{{ $error_message ?? 'Có lỗi xảy ra trong quá trình thanh toán' }}</strong></p>
            </div>
            <div class="action-buttons">
                <a href="/cart" class="btn btn-primary">Thử lại</a>
                <a href="/contact" class="btn btn-secondary">Liên hệ hỗ trợ</a>
            </div>
        </div>
    </div>
@endsection 