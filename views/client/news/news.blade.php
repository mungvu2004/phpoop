@extends('client.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/client/css/news.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endpush

@section('content')
<div class="news-container">
    <div id="news-list" class="news-list"></div>
    <div id="news-detail" class="news-detail" style="display: none;"></div>
</div>
@endsection

@push('scripts')
    <script src="{{ file_url('assets/client/js/news.js') }}"></script>
@endpush