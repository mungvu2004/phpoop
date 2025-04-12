@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{file_url('assets/admin/coupon.css')}}">
@endpush

@push('scripts')
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ file_url('assets/js/coupon.js') }}"></script>
@endpush

@section('content')
<main class="main">
    <div class="main-title">
        <h1 class="title-h1"></h1>
        <button class="btn-coupon">Thêm mới</button>
    </div>
    <div class="coupon">
        @foreach ($coupons as $coupon)
        <div class="coupon-item">
            @php
                $code = str_split($coupon['code']);
                $logo = $code[0];
            @endphp
            <div class="item-logo">
                <p>{{$logo}}</p>
            </div>
            <div class="item-coupon">
                <div class="title">
                    <h3>{{$coupon['code']}}</h3>
                    <h4>{{ number_format($coupon['discount'], 0) }}%</h4>
                    <h5>{{$coupon['expiry_date']}}</h5>
                </div>
                <div class="is-active">
                    <label class="switch">
                        <input type="checkbox" id="toggleSwitch"
                            {{$coupon['is_active'] == 1 ? 'checked' : ''}}
                            data-id = {{$coupon['id']}}
                            data-status = {{$coupon['is_active']}}
                        >
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="form-coupon">
        <form action="/admin/coupon/create" method="POST" id="couponForm">
            <!-- Mã giảm giá -->
            <div class="form-group">
                <label for="code">Mã giảm giá <span class="required">*</span></label>
                <input type="text" name="code" id="code" class="form-control" placeholder="Nhập mã giảm giá (VD: SALE50)" required maxlength="50">
            </div>

            <!-- Giá trị giảm giá (theo phần trăm) -->
            <div class="form-group">
                <label for="discount">Phần trăm giảm giá <span class="required">*</span></label>
                <input type="number" name="discount" id="discount" class="form-control" placeholder="Nhập phần trăm giảm (VD: 10)" required step="0.01" min="0" max="100">
                <small class="form-text">Giá trị từ 0 đến 100%</small>
            </div>

            <!-- Ngày hết hạn -->
            <div class="form-group">
                <label for="expiry_date">Ngày hết hạn <span class="required">*</span></label>
                <input type="date" name="expiry_date" id="expiry_date" class="form-control" required>
            </div>

            <!-- Trạng thái kích hoạt -->
            <div class="form-group">
                <label for="is_active">Trạng thái</label>
                <label class="switch">
                    <input type="checkbox" name="is_active" id="is_active" value="1" checked>
                    <span class="slider"></span>
                </label>
                <span>Kích hoạt</span>
            </div>

            <!-- Nút gửi -->
            <button type="submit" class="btn btn-primary">Thêm mã giảm giá</button>
            <button type="button" class="btn btn-secondary">Hủy</button>
        </form>
    </div>
</main>
@endsection
