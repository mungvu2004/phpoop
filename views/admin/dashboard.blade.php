@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{ file_url('assets/admin/dashboard.css') }}">
@endpush
@push('scripts')
<script src="{{ file_url('assets/js/chart.js') }}"></script>
@endpush
@section('content')
    <main class="main">
        <div class="main-title">
            <h1 class="title-h1"></h1>
        </div>
        <div class="main-total">
            <div class="total">
                <div class="total-item">
                    <div class="title-item">
                        <h3>Total User</h3>
                        <span>{{ $users }}.00</span>
                    </div>
                    <div class="icon-item" style="background: rgba(130, 128, 255, 0.3);">
                        <i class="bi bi-people-fill" style="color: rgb(130, 128, 255)"></i>
                    </div>
                </div>
                <div class="total-item"></div>
            </div>
            <div class="total">
                <div class="total-item">
                    <div class="title-item">
                        <h3>Total Order</h3>
                        <span>{{ $orders }}.00</span>
                    </div>
                    <div class="icon-item" style="background: rgba(254, 197, 61, 0.3);">
                        <i class="bi bi-box-fill" style="color: rgb(254, 197, 61)"></i>
                    </div>
                </div>
                <div class="total-item"></div>
            </div>
            <div class="total">
                <div class="total-item">
                    <div class="title-item">
                        <h3>Total Pay</h3>
                        <span>{{ $payments }}.00</span>
                    </div>
                    <div class="icon-item" style="background: rgba(74, 217, 145, 0.3)">
                        <i class="bi bi-credit-card-2-back-fill" style="color: rgb(74, 217, 145)"></i>
                    </div>
                </div>
                <div class="total-item"></div>
            </div>
            <div class="total">
                <div class="total-item">
                    <div class="title-item">
                        <h3>Total Review</h3>
                        <span>{{ $reviews }}.00</span>
                    </div>
                    <div class="icon-item" style="background: rgba(255, 144, 102, 0.3)">
                        <i class="bi bi-chat-dots-fill" style="color: rgb(255, 144, 102)"></i>
                    </div>
                </div>
                <div class="total-item"></div>
            </div>
            
        </div>
        <div class="main-chart">
            <div class="chart-title">
                <h1>Sale Chart</h1>
                <select id="month">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <canvas id="saleChart"
                data-sale='@json($sale)'
                data-days="{{ cal_days_in_month(CAL_GREGORIAN, date('n'), date('Y')) }}"
            ></canvas>
        </div>
        <div class="main-detail">
            <h1>Order Details</h1>
            <div class="flex-table">
                <div class="flex-header">
                    <div class="flex-cell">Product Name</div>
                    <div class="flex-cell">Location</div>
                    <div class="flex-cell">Date - Time</div>
                    <div class="flex-cell">Piece</div>
                    <div class="flex-cell">Amount</div>
                    <div class="flex-cell">Status</div>
                </div>
                @foreach($allOrder as $item)

                    <div class="flex-row">
                        <div class="flex-cell">{{ $item['username'] }}</div>
                        <div class="flex-cell">{{ $item['shipping_address'] }}</div>
                        <div class="flex-cell">{{ $item['created_at'] }}</div>
                        <div class="flex-cell">{{ $item['total_price'] }}</div>
                        <div class="flex-cell">{{ isset($item['coupon_code']) ? $item['coupon_code'] : 'NULL' }}</div>
                        <div class="flex-cell">{{ $item['status'] }}</div>
                    </div>
                @endforeach
            </div>
            
        </div>
    </main>
@endsection