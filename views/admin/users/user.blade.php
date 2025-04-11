@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/admin/user.css') }}">
@endpush

@push('scripts')
    <script src="{{ file_url('assets/js/confirm.js') }}"></script>
    <script src="{{ file_url('assets/js/user.js') }}"></script>
@endpush

@section('content')
    <main class="main">
        <div class="main-title">
            <h1 class="title-h1"></h1>
        </div>
        <section class="main-user">
            <ul class="user-title">
                <li>Tên</li>
                <li>Tên đăng nhập</li>
                <li>Email</li>
                <li>Quyền truy cập</li>
                <li>Trạng thái</li>
                <li>Hoạt động</li>
            </ul>
            @foreach ($users as $user)
                <ul class="user-item">
                    <li>{{ $user['recipient_name'] ?? 'Chưa có' }}</li>
                    <li>{{ $user['username'] }}</li>
                    <li>{{ $user['email'] }}</li>
                    <li>{{ $user['role'] }}</li>
                    <li class="active">
                        <p>{{ $user['is_active'] == 1 ? 'Kích hoạt' : 'Vô hiệu' }}</p>
                    </li>
                    <li class="item-button">
                        <a href="/admin/contact/edit/{{ $user['id'] }}" class="btn-detail">Chi tiết</a>
                        <form action="/admin/contact/delete/{{ $user['id'] }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                            <input type="hidden" name="_method" value="DELETE">
                            <button class="btn-delete" type="submit">Xóa</button>
                        </form>
                    </li>
                </ul>
            @endforeach
        </section>
    </main>
@endsection