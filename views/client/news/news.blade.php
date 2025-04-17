@extends('client.layouts.main')

@push('styles')
<link rel="stylesheet" href="{{ file_url('assets/client/news.css') }}">
@endpush

@section('content')
<div class="news-container">
    <h1><b>DANH SÁCH TIN TỨC</b></h1>
    <div id="news-list" class="news-list">
        <!-- Nội dung sẽ được thêm bởi JavaScript -->
    </div>
</div>

<div id="news-detail" class="news-detail" style="display: none;">
    <span class="back-btn" onclick="showNewsList()">← Quay lại danh sách</span>
    <h2 id="detail-title"></h2>
    <div id="detail-content"></div>
</div>

<script src="{{ file_url('assets/client/js/news.js') }}"></script>
@endsection