@extends('client.layouts.main')

@section("content")
    <div class="category">
        <div class="category_title">
            <div class="div">
                <h1>Top categories</h1>
                <p>Explore our Popular Categories</p>
            </div>
            <input type="button" value="All Category">
        </div>
        <div class="category_content">
            @foreach($categories as $category)
                <div class="category_item">
                    <img src="{{ file_url('assets/imgb2/' . $category['icon']) }}" alt="{{ $category->name }}">
                    <h2>{{ $category['name'] }}</h3>
                    <p>28 Cours</p>
                </div>
            @endforeach
        </div>
    </div>
    <div class="banner1">
        <img src="{{ file_url('assets/imgb2/BANNER.png')}}" alt="">
    </div>
    <div class="banner2">
        <img src="{{ file_url('assets/imgb2/Frame55.png')}}" alt="">
    </div>
    <div class="banner2">
        <img src="{{ file_url('assets/imgb2/BANNER-2.png')}}" alt="">
    </div>
@endsection
