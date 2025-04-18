@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/payment-methods.css') }}">
@endpush

@section('content')
    <div class="payment-methods-container">
        <h1 class="payment-title">Phương thức thanh toán</h1>
        
        <div class="payment-icons">
            <img src="{{ file_url('storage/Badge/payment.png') }}" alt="Phương thức thanh toán" class="payment-methods-image">
        </div>
        
        <div class="payment-description">
            <p>Khách hàng có thể tham khảo các phương thức thanh toán sau đây và lựa chọn áp dụng phương thức phù hợp:</p>
            <ul class="payment-methods-list">
                <li>- Cách 1: Thanh toán khi nhận hàng (COD – giao hàng và thu tiền tận nơi).</li>
                <li>- Cách 2: Thanh toán qua ví điện tử VNPay.</li>
            </ul>
            <p>PureWare sẽ xác nhận việc đặt hàng với Quý khách bằng hình thức nhắn tin qua số điện thoại hoặc gửi thông tin qua email Quý khách cung cấp.</p>
        </div>
    </div>
@endsection