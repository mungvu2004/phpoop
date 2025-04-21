@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                    <h2 class="mt-3">Thanh toán thành công!</h2>
                    <p class="lead">Cảm ơn bạn đã thanh toán đơn hàng.</p>
                    <div class="mt-4">
                        <p><strong>Mã đơn hàng:</strong> {{ $order_id }}</p>
                        <p><strong>Số tiền:</strong> {{ number_format($amount, 0, ',', '.') }} VNĐ</p>
                    </div>
                    <div class="mt-4">
                        <a href="/orders" class="btn btn-primary">Xem đơn hàng</a>
                        <a href="/" class="btn btn-outline-primary ml-2">Về trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 