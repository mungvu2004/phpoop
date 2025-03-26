@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/admin/product.css') }}">
@endpush

@section('content')
    <main class="main">
        <div class="main-title">
            <h1 class="title-h1"></h1>
        </div>

        <div class="main-product">
            @php
                $products = $product['data']
            @endphp
            @foreach($products as $item)
                <div class="product-item">
                    @php
                        $img = file_exists('storage/uploads' . $item['image_url']);
                    @endphp 
                    @if($img) <img src="{{ $img }}" alt="">
                    @else <img src="{{ file_url("storage/uploads/users/download.jpg") }}" alt="">
                    @endif
                    <div class="item-tp">
                        <h3>{{ $item['name'] }}</h3>
                        <span>{{ $item['price'] }}</span>
                        
                    </div>
                </div>
            @endforeach
        </div>
        <pre>{{ print_r($product) }}</pre>
    </main>
@endsection