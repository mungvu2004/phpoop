@extends('admin.layouts.main')

@push('styles')
    <link rel="stylesheet" href="{{file_url('assets/admin/payment.css')}}">
@endpush

@section('content')
<main class="main">
    <div class="main-title">
        <h1 class="title-h1">Transaction History</h1>
    </div>
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Recipient Name</th>
                    <th>Order ID</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td data-label="ID">{{$i = 1}}</td>
                        <td data-label="Recipient Name">{{ $payment['recipient_name'] ?: '-' }}</td>
                        <td data-label="Order ID">{{ $payment['order_id'] }}</td>
                        <td data-label="Amount" class="amount">{{ number_format($payment['amount'], 0, ',', '.') }} VND</td>
                        <td data-label="Payment Method">{{ $payment['payment_method'] }}</td>
                        <td data-label="Status">
                            <span class="status status-{{ $payment['status'] }}">{{ $payment['status'] }}</span>
                        </td>
                        <td data-label="Created At">{{ date('d/m/Y H:i', strtotime($payment['created_at'])) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 24px; color: #64748b;">
                            No transactions found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</main>
@endsection