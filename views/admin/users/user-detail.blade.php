@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{file_url('assets/admin/user.css')}}">
@endpush

@section('content')
<pre>{{print_r($detailUser)}}</pre>
    <main class="main">
        <div class="main-title">
            <h1 class="title-h1"></h1>
        </div>
    </main>
@endsection