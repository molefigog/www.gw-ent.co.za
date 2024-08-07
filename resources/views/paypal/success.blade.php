@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="card">
        
        @if (!empty($payment_id))
        <h5 class="card-header text-success text-center">Payment is Successfully</h5>
        <div class="card-body">
            <h4></h4>
            <h5 class="card-title text-center">Payment Information</h5>
            <p class="card-text"><b>Reference Number:</b> {{ $payment_id }}</p>
            <p class="card-text"><b>Transaction ID:</b> {{ $txn_id }}</p>
            <p class="card-text"><b>Paid Amount USD:</b> ${{ $payment_gross }}</p>
            <p class="card-text"><b>Payment Status:</b> {{ $payment_status }}</p>

            <h4 class="text-center">Product Information</h4>
            <p class="card-text"><b>Name:</b> {{ $productRow->title }}</p>
            <p class="card-text"><b>Price ZAR:</b> R{{ $productRow->amount }}</p>
            <p class="card-text"><b>User Name:</b> {{ $productUser->name }}</p>
        @else
            <h1 class="text-danger">Your Payment has Failed</h1>
        @endif

      <a href="{{ url('purchased-musics') }}" class="btn buy-button">Downloads</a>
    </div>
    
  </div>
</div>
  @endsection