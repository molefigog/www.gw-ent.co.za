@extends('layouts.master')

@section('content')
    <div class="card">
             <h6 class="text-center">Payment History</h1>
                <div class="table-responsive text-nowrap">
                    @if ($payments->isEmpty())
                        <p>No payment history available.</p>
                    @else
                        <table class="table table-sm table-dark">
                            <thead>
                                <tr>
                                    <th>Ref</th>
                                    <th>Transaction ID</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Item Title</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                    <tr>
                                        <td>{{ $payment->item_number }}</td>
                                        <td>{{ $payment->txn_id }}</td>
                                        <td>${{ $payment->payment_gross }}</td>
                                        <td>{{ $payment->payment_status }}</td>
                
                                        {{-- Check if $payment->music exists --}}
                                        <td>
                                            @if ($payment->music)
                                                {{ $payment->music->title }}
                                            @else
                                               Joining Fee
                                            @endif
                                        </td>
                
                                        <td>{{ $payment->created_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                
    </div>
@endsection
