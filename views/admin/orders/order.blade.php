@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/admin/order.css') }}">
@endpush
@push('scripts')
    <script src="{{ file_url('assets/js/order.js') }}"></script>
@endpush

@section('content')
    {{-- <pre>{{print_r($orders)}}</pre>
    <pre>{{ $currentPage}}</pre>
    <pre>{{ $totalPage}}</pre> --}}
    <main class="main">
        <div class="main-title">
            <h1 class="title-h1"></h1>
        </div>
        <div class="sort">

        </div>
        <div class="main-item">
            <div class="item-title">
                <ul>
                    <li>ID</li>
                    <li>NAME</li>
                    <li>address</li>
                    <li>date</li>
                    <li>type</li>
                    <li>status</li>
                </ul>
            </div>
            <div class="item-content">
                
                    @foreach ($orders as $order)
                    <ul>
                        <li>{{ $order['user_id'] }}</li>
                        <li>{{ $order['coupon_id'] }}</li>
                        <li>{{ $order['shipping_address'] }}</li>
                        <li>{{ $order['created_at'] }}</li>
                        <li>{{ $order['total_price'] }}</li>
                        <li>{{ $order['status'] }}</li>
                        </ul>
                    @endforeach
                
            </div>
        </div>
    </main>
@endsection