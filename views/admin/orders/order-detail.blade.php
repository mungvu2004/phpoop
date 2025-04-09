@extends('admin.layouts.main')
@push('styles')
    <link rel="stylesheet" href="{{file_url('asset/admin/order-detail.css')}}">
@endpush
@push('scripts')
    <script src="{{file_url('assets/js/order-detail.js')}}"></script>
@endpush

@section('content')
    <pre>{{print_r($orderDetail)}}</pre>
@endsection