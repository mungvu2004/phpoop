@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{file_url("assets/client/account.css")}}">
@endpush
@push("scripts")
    <script src="{{file_url('assets/js/client/account.js')}}"></script>
@endpush
@section('content')

<div class="card">
    <form id="addressForm" action="/account/update" method="POST" enctype="multipart/form-data">
        <div class="image-upload">
            <div class="image-preview" id="imagePreview">
                <!-- Hiển thị ảnh từ database nếu có -->
                @if(isset($userDetail[0]['image_url']) && $userDetail[0]['image_url'])
                    <img src="{{ file_url($userDetail[0]['image_url']) }}" alt="Ảnh địa chỉ" id="previewImage">
                @else
                    <!-- Placeholder khi không có ảnh -->
                    <img src="" alt="Ảnh xem trước" id="previewImage" style="display: none;">
                @endif
            </div>
            <label class="block mt-3 text-center">
                <input type="file" id="image_url" name="image_url" accept="image/*" class="hidden">
                <div class="file-input-label">
                    <i class="fas fa-camera mr-2"></i> Đổi ảnh
                </div>
            </label>
        </div>
        <div class="section-title">Thông tin liên hệ</div>
        <div class="grid-section">
            <div class="form-group">
                <i class="fas fa-tag"></i>
                <input type="text" id="address_name" name="address_name" value="{{$userDetail[0]['address_name']}}" placeholder=" ">
                <label for="address_name">Tên địa chỉ</label>
            </div>
            <div class="form-group">
                <i class="fas fa-user"></i>
                <input type="text" id="recipient_name" name="recipient_name" value="{{$userDetail[0]['recipient_name']}}" placeholder=" ">
                <label for="recipient_name">Tên người nhận</label>
            </div>
            <div class="form-group">
                <i class="fas fa-phone"></i>
                <input type="tel" id="phone_number" name="phone_number" autocomplete="off" value="{{$userDetail[0]['phone_number']}}" placeholder=" ">
                <label for="phone_number">Số điện thoại</label>
            </div>
        </div>
        <div class="section-title">Chi tiết địa chỉ</div>
        <div class="grid-section">
            <div class="form-group">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" id="address_line1" name="address_line1" autocomplete="off" value="{{$userDetail[0]['address_line1']}}" placeholder=" ">
                <label for="address_line1">Địa chỉ 1</label>
            </div>
            <div class="form-group">
                <i class="fas fa-city"></i>
                <select id="city" name="city">
                    <option value="">{{$userDetail[0]['city']}}</option>
                </select>                
                <label for="city">Thành phố</label>
            </div>
            <div class="form-group">
                <i class="fas fa-globe"></i>
                <input type="text" id="country" name="country" autocomplete="off" value="Việt Nam" placeholder=" ">
                <label for="country">Quốc gia</label>
            </div>
        </div>
        <div class="section-title">Tùy chọn</div>
        <div class="checkbox-group">
            <input type="checkbox" id="is_default" name="is_default" checked>
            <label for="is_default" class="text-sm text-gray-900 font-medium">Đặt làm mặc định</label>
        </div>
        <button type="submit" class="submit-btn"><i class="fas fa-save"></i> Cập nhật</button>
    </form>
</div>
@endsection