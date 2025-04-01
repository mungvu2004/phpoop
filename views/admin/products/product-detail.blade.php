@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/admin/product.css') }}">
@endpush
@push('scripts')
    <script src="{{ file_url('assets/js/product.js') }}"></script>
@endpush
@section('content')
    <main class="main">
        <div class="main-title">
            <h1 class="title-h1"></h1>
        </div>
    </main>
@endsection