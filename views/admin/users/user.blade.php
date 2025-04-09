@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{file_url('assets/admin/user.css')}}">
@endpush
@push('scripts')
    <script src="{{ file_url('assets/js/confirm.js') }}"></script>
    <script src="{{ file_url('assets/js/user.js') }}"></script>
@endpush
@section('content')
    {{-- <pre>{{ print_r($users) }}</pre> --}}
    <main class="main">
        <div class="main-title">
            <h1 class="title-h1"></h1>
        </div>
        <div class="main-user">
            <ul class="user-title">
                <li>Tên</li>
                <li>tên đăng nhâp</li>
                <li>email</li>
                <li>quyền truy cập</li>
                <li>trạng thái</li>
                <li>
                    Hoạt động
                </li>
            </ul>
            @foreach ($users as $user)
            <ul class="user-item">
                <li>{{$user['recipient_name'] ?? 'NULL'}}</li>
                <li>{{$user['username']}}</li>
                <li>{{$user['email']}}</li>
                <li>{{$user['role']}}</li>
                @php
                        $is = $user['is_active'];
                        $active = '';
                        if($is == 1) 
                        {
                            $active = 'Activated';
                        } else {
                            $active = 'Deactivated';
                        }
                    @endphp
                <li class="active">{{$active}}</li>
                <li class="item-button">
                    <a href="/admin/contact/edit/{{$user['id']}}" class="btn-detail">Chi tiết</a>
                    <form action="/admin/contact/delete/{{$user['id']}}" method="post">
                        <button class="btn-delete" type="submit">Xóa</button>
                    </form>
                </li>
            </ul>
            @endforeach
        </div>
    </main>
@endsection