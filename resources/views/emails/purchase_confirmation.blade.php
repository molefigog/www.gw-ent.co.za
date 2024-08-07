<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="{{ asset('frontend/font/icomoon/style.css') }}">

    <title>{{ $buyer->name }}</title>
    <style>
        /* Add your inline styles here */
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            margin: 20px;
        }

        .card {
            border: 1px solid #cccccc50;
            border-radius: 5px;
            overflow: hidden;
        }

        .card-body {
            padding: 12px;
        }

        .text-center {
            text-align: center;
        }

        .hr {
            border: 0;
            height: 1px;
            background: #ccc;
            margin: 15px 0;
        }
    </style>

</head>

<body>
    @php
        $setting = App\Models\Setting::orderBy('created_at', 'desc')
            ->select('site', 'image', 'logo', 'favicon', 'description')
            ->first();
    @endphp
    <div class="container">
        <div class="card">
            <div class="card-body">
                <p class="text-center">Dear {{ $buyer->name }},</p>
                <p class="text-center">You paid Using Paypal <i class="icon-paypal"></i></p>
                <p class="text-center">Thank you for your purchase!</p>

                <p class="text-center">You have paid ${{ $payment_gross }}</p>
                <p class="text-center">Payment Status: {{ $payment_status }}</p>
                <p class="text-center">Transaction ID: {{ $txn_id }}</p>
                <hr class="hr">
                <p class="text-center">Item Details:</p>
                <p class="text-center">Artist: {{ $productRow->artist }}</p>
                <p class="text-center">Title: {{ $productRow->title }}</p>
                <p class="text-center">You have paid R{{ $productRow->amount }}</p>

                <p class="text-center">Date: <i class="icon-calendar"></i>{{ now()->format('d M Y H:i') }}</p>
                <hr class="hr">

                <p class="text-center">Thank you for using our service!</p>
                <p class="text-center">{{ $setting->site ?? config('app.name') }}</p>
            </div>
        </div>
    </div>

</body>

</html>
