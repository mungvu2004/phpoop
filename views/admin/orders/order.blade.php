@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/admin/order.css') }}">
@endpush
@push('scripts')
    <script src="{{ file_url('assets/js/order.js') }}"></script>
@endpush

@section('content')
    {{-- <pre>{{print_r($orders)}}</pre> --}}
    {{-- <pre>{{ $currentPage}}</pre>
    <pre>{{ $totalPage}}</pre> --}}
    
    <main class="main">
        <div class="main-title">
            <h1 class="title-h1"></h1>
        </div>
        <div class="sort">
           <a href="{{ route_url('admin/order/?sort=quick&sort_by=total_price') }}">Total price</a>
           <a href="{{ route_url('admin/order/?sort=quick&sort_by=created_at') }}">Ngày</a>
           <a href="{{ route_url('admin/order/?sort=quick&sort_by=status') }}">TT</a>
           <a href="{{ route_url('admin/order/') }}">Reset</a>
        </div>
        <div class="main-item">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Ngày tạo</th>
                        <th>Mã giảm giá</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody class="table-content">
                    @php
                        $i = 1;
                    @endphp
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td>{{ $order['username'] }}</td>
                            <td>{{ $order['created_at'] }}</td>
                            <td>{{ $order['coupon_code'] ?? 'NULL'}}</td>
                            <td>{{ $order['total_price'] }}</td>
                            <td >
                                <p class="status-color">{{ $order['status'] }}</p>
                            </td>
                            <td>
                                <a href="{{route_url('admin/order/edit/' . $order['id'])}}">Sửa</a>
                                <form action="/admin/order/delete/{{$order['id']}}" method="POST">
                                    <button type="submit" class="btn-delete">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
@endsection