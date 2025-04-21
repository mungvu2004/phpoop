@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-times-circle text-danger" style="font-size: 48px;"></i>
                    <h2 class="mt-3">Thanh toán thất bại!</h2>
                    <p class="lead">{{ $message }}</p>
                    <div class="mt-4">
                        <a href="/cart" class="btn btn-primary">Thử lại</a>
                        <a href="/" class="btn btn-outline-primary ml-2">Về trang chủ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 