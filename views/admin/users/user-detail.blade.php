@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{file_url('assets/admin/user.css')}}">
@endpush
@push('scripts')
    <script src="{{ file_url('assets/js/confirm.js') }}"></script>
    <script src="{{ file_url('assets/js/user.js') }}"></script>
@endpush

@section('content')
{{-- <pre>{{print_r($detailUser)}}</pre>
<pre>{{print_r($user)}}</pre> --}}
    <main class="main">
        <div class="main-title">
            <h1 class="title-h1"></h1>
        </div>
        <div class="users-detail">
            <div class="user-logo">
                @php
                    $logo = $detailUser[0]['recipient_name'];
                    function getLastTwoInitials($name) {
                        if ($name === null) {
                            $name = "Mung Vu";
                        }
                        $name = trim($name);
                        if (empty($name)) {
                            return "";
                        }

                        $words = preg_split('/\s+/', $name); // Tách bằng mọi khoảng trắng, kể cả tab hoặc nhiều space
                        $wordCount = count($words);

                        if ($wordCount == 1) {
                            return strtolower(substr($words[0], 0, 1));
                        } elseif ($wordCount >= 2) {
                            $lastTwoWords = array_slice($words, -2);
                            $initials = "";
                            foreach ($lastTwoWords as $word) {
                                $initials .= strtolower(substr($word, 0, 1));
                            }
                            return $initials;
                        }

                        return "";
                    }

                @endphp
                <p>{{getLastTwoInitials($logo)}}</p>
            </div>
            <div class="user-detail">
                <h2>{{$detailUser[0]['recipient_name']}}</h2>
                <p>Số điện thoại: <span>{{$detailUser[0]['phone_number']}}</span></p>
                <p>Email: <span>{{$user['email']}}</span></p>
                <div class="user-address">
                    <table class="address">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Địa điểm</th>
                                <th>Số nhà, tên đường</th>
                                <th>Thành phố</th>
                                <th>Mặc định</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @foreach ($detailUser as $address)
                                <tr>
                                    <td>00{{$i++}}</td>
                                    <td>{{$address['address_name']}}</td>
                                    <td>{{$address['address_line1']}}</td>
                                    <td>{{$address['city']}}</td>
                                    @php
                                        $is = $address['is_default'];
                                        if($is === 1) {
                                            $active = 'Mặc định';
                                        } else {
                                            $active = 'NULL';
                                        }
                                    @endphp
                                    <td>{{$active}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
@endsection