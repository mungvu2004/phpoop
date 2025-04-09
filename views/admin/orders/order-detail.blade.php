@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/admin/order-detail.css') }}">
@endpush
@push('scripts')
    <script src="{{ file_url('assets/js/order-detail.js') }}"></script>
@endpush

@section('content')
    <pre>{{print_r($orderDetail)}}</pre>
    @php
        $i = 1;
        $defaultImg = 'storage/uploads/users/error.png'
    @endphp
    <main class="main">
        <div class="main-title">
            <h1 class="title-h1"></h1>
            <button class="edit-order">Sửa</button>
        </div>
        <div class="edit-order">
            <form action="/admin/order/upload/{{ $orderDeatil[0]['order_id'] }}" method="post" >
                
            </form>
        </div>
        <div class="user-name">
            <ul class="title">
                <li>Tên</li>
                <li>địa chỉ</li>
                <li>Trạng thái</li>
                <li>Phương thức thanh toán</li>
                <li>Phương thức giao hàng</li>
            </ul>
            <ul class="user-tt">
                <li>{{$orderDetail[0]['username']}}</li>
                <li>{{$orderDetail[0]['shipping_address']}}</li>
                <li>{{$orderDetail[0]['status']}}</li>
                <li>{{$orderDetail[0]['payment_method']}}</li>
                <li>{{$orderDetail[0]['shipping_method']}}</li>
            </ul>
        </div>
        <div class="order-detail">
            @foreach ($orderDetail as $item)
                <div class="detail-content">
                    <div class="detail-product">
                        @php
                            $imgPath = file_exists($item['image_url']) ? $item['image_url'] : $defaultImg;
                        @endphp
                        <img src="{{file_url($imgPath)}}" alt="{{$item['product_name']}}">
                        <p>{{ $item['product_name'] }}</p>
                        <p>Đơn giá: <span>{{ number_format($item['price'], 0, ',', '.') }} VND</span></p>
                        <p>Số lượng: {{ $item['quantity'] }}</p>
                    </div>
                    <div class="detail-tt">
                        <p>Địa chỉ: {{$item['shipping_address']}}</p>
                        <p>Tổng tiền: <span>{{ number_format($item['subtotal'], 0, ',', '.') }} VND</span></p>
                    </div>

                </div>
            @endforeach
        </div>

    </main>
@endsection