@php
$apiKey = '863c6f17965b59a056305e51';
$baseCurrency = 'ZAR';
$targetCurrency = 'USD';
$amount = $beat->amount; // Assuming $music->amount contains the ZAR amount you want to convert

// Make API request to get the exchange rate
$apiUrl = "https://open.er-api.com/v6/latest/{$baseCurrency}?apikey={$apiKey}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if ($data && isset($data['rates'][$targetCurrency])) {
    // Perform the conversion
    $exchangeRate = $data['rates'][$targetCurrency];
    $convertedAmount = $amount * $exchangeRate;

    // Round the converted amount to 2 decimal places (you can adjust this as needed)
    $convertedAmount = round($convertedAmount, 2);

    // Assign the converted amount to $amount
    $amount = $convertedAmount;
} else {
    // Handle the case where the exchange rate data is not available
    $amount = 'Failed to retrieve exchange rate data.';
}


@endphp


@extends('layouts.master')

@section('content')
    
    <div class="container mt-5">
        <div class="row">
            <div class="card-img-wrapper col-md-6 ">
                <img src="{{ $beat->image ? \Storage::url($beat->image) : '' }}" class="img-fluid" alt="Product Image">
            </div>
            <div class="col-md-6">
                <h2>{{ $beat->title ?? '-' }}</h2>
                <p class="text-muted">{{ $beat->description ?? '-' }}</p>
                <P> Size: {{ $beat->size ?? '-' }}MB Duration:
                    <span>{{ $beat->duration ?? '-' }}</span>
                </P>
                @if ($beat->used == 1)
                <h3 class="text-muted">Sold </h3>
                @else
                <h3 class="text-success">Price R{{ $beat->amount }} </h3>
                @endif
                <p>
                    Genre <span
                        class="text-success">{{ $beat->genre ? $beat->genre->title : '-' }}</span>
                </p>
                <nav class="text-center">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home" role="tab"
                            aria-controls="nav-home" aria-selected="true"><img src="{{ asset('assets/vcl1.png') }}" alt=""
                                style="width: 24px; height: 24px;"> M-Pesa</a>
                        <a class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile" role="tab"
                            aria-controls="nav-profile" aria-selected="false"><i class="icon-account_balance_wallet"></i>
                            Wallet</a>
    
                        <a class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" href="#nav-contact" role="tab"
                            aria-controls="nav-contact" aria-selected="false"><i class="icon-paypal"></i> Paypal</a>
                    </div>
                </nav>
    
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="justify-content-center">
                            <hr>
                            <div id="wrap">
                                <form id="paymentForm" class="text-center" action="{{ route('beat.pay') }}" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">M-pesa</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"> <img src="{{ asset('assets/vcl1.png') }}"
                                                        alt="" style="width: 24px; height: 24px;"> </i></div>
                                            </div>
                                            <input type="text" class="form-control col-6" name="mobileNumber" value=""
                                            placeholder="Enter mpesa number" pattern="5\d{7}"
                                            title="Please enter 8 digits starting with 5" maxlength="8" required>
                                            <input type="hidden" name="amount" value="{{ $beat->amount }}">
                                            {{-- <input type="hidden" name="amount" value="{{ $music->amount }}"> --}}
                                            <input type="hidden" name="client_reference"
                                                value="{{ $beat->id }} {{ $beat->title ?? '-' }}">
                                            <input type="hidden" name="beatId"value="{{ $beat->id }}">
                                            @if ($beat->used == 1)
                                            &nbsp; <button type="submit" disabled class="btn btn-outline-danger btn-sm">
                                                <span class="circle2"><img src="{{ asset('assets/vcl1.png') }}" alt=""
                                                        style="width: 24px; height: 24px;"> </i></span>
                                                <span class="title2 gee">Sold</span>
    
                                            </button>
                                           @else
                                            &nbsp; <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <span class="circle2"><img src="{{ asset('assets/vcl1.png') }}" alt=""
                                                        style="width: 24px; height: 24px;"> </i></span>
                                                <span class="title2 gee">Pay M{{ $beat->amount }}</span>
    
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
    
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <br>
                        <div class="text-center">
                            @if ($beat->used == 1)
                            <div id="wrap">
                                <form action="" method="post">
                                    @csrf
                                    <input type="hidden" name="music_id" value="{{ $beat->id }}">
                                    <button type="button" disabled class="btn buy-button2">
                                        <span class="circle2"><i class="icon-download"></i></span>
                                        <span class="title2 gee">SOLD</span>
    
                                    </button>
                                </form>
                            </div>
                            @else
                            <div id="wrap"> 
                                <div class="text-center"><a href="#information" id="showAlert">INSTRUCTIONS</a></div>
                                <form id="complete-order" action="{{ route('beat-order') }}" method="post">
                                    @csrf
                                    <div class="text-center">
                                        <div class="info"></div>
                                        <div class="message"></div>
                                    </div>
                                    <input type="hidden" name="beat_id" value="{{ $beat->id }}">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP"
                                            aria-label="Enter OTP" aria-describedby="button-addon2" required>
                                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buy R{{
                                            $beat->amount }}</button>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
    
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <div class="text-center">
                            <h6 class="text-center"></h6>
                            @if ($beat->used == 1)
                            <p> </p>
                            @else
                            <div id="wrap">
                                <form id="buyNowForm" action="{{ $Paypal }}" method="post">
                                    <input type="hidden" name="cmd" value="_xclick">
                                    <input type="hidden" name="amount" value="{{ $amount }}">
                                    <input type="hidden" name="currency_code" value="{{ $currency }}">
                                    <input type="hidden" name="business" value="{{ $PAYPAL_ID }}">
    
                                    <input type="hidden" name="custom" value="{{ $userId }}">
    
                                    <input type="hidden" name="item_name" value="{{ $beat->title }}">
                                    <input type="hidden" name="item_number" value="{{ $beat->id }}">
                                    <input type="hidden" name="return" value="{{ route('success') }}">
                                    <input type="hidden" name="cancel_return" value="{{ url('cancel') }}">
                                    <input type="hidden" name="notify_url" value="{{ url('ipn') }}">
    
                                    <button type="submit" class="btn buy-button2"
                                        title="secure online payment with paypal"><i class="icon-paypal"></i> Buy
                                        ${{ $amount }}</button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                {{-- <div class="mb-3">
                    @if ($beat->used == 1)
                        <div id="wrap">
                            <button type="submit" class="btn buy-button2" onclick="showSoldOutNotification()">
                                <span class="circle2"><i class="icon-download"></i></span>
                                <span class="title2 gee">Sold</span>
                            </button>
                        </div>
                    @else
                        <div id="wrap">
                            <form action="{{ route('buy-beat') }}" method="post">
                                @csrf
                                <input type="hidden" name="beat_id" value="{{ $beat->id }}">
                                <button type="submit" class="btn buy-button2">
                                    <span class="circle2"><i class="fa fa-shopping-cart"></i></span>
                                    <span class="title2 gee">Buy R{{ $beat->amount }}</span>

                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="mb-3">
                    @if ($beat->used == 1)
                       <p>---</p> 
                    @else
                        <div id="wrap">
                            <form id="buyNowForm" action="{{$Paypal}}" method="post">
                                <input type="hidden" name="cmd" value="_xclick">
                                <input type="hidden" name="amount" value="{{$amount}}">
                                <input type="hidden" name="currency_code" value="{{$currency}}">
                                <input type="hidden" name="business" value="{{$PAYPAL_ID }}">
                                
                                <input type="hidden" name="custom" value="{{$userId}}">
    
                                <input type="hidden" name="item_name" value="{{$beat->title}}">
                                <input type="hidden" name="item_number" value="{{$beat->id}}">
                                <input type="hidden" name="return" value="{{ route('success') }}">
                                <input type="hidden" name="cancel_return" value="{{ url('cancel') }}">
                                <input type="hidden" name="notify_url" value="{{ url('ipn') }}">
    
                                <button type="submit" class="btn buy-button2" title="secure online payment with paypal"><i class="icon-paypal"></i> Buy ${{$amount}}</button>
                            </form>
                        </div>
                    @endif
                </div> --}}
                <div id="wrapper">
                    <audio preload="auto" controls>
                        <source src="{{ \Storage::url($beat->demo) }}">
                    </audio>
                   
                </div>

                <div>
                    <h5>share to <i class="icon-share2"></i></h5>
                    {!! $shareButtons !!}
                </div>
                <hr>
            </div>
        </div>
    </div>
    <br>
@endsection
@section('head')
    <title>{{ $metaTags['title'] }}</title>
    <meta name="description" content="{{ $metaTags['description'] }}">
    <meta property="og:title" content="{{ $metaTags['title'] }}">
    <meta property="og:image" content="{{ $metaTags['image'] }}">
    <meta property="og:description" content="{{ $metaTags['description'] }}">
    <meta property="og:url" content="{{ $metaTags['url'] }}" />
    <meta name="keywords" content="{{ $metaTags['keywords'] }}">
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="{{ $metaTags['title'] }}" />
    <meta name="twitter:description" content="{{ $metaTags['description'] }}" />
    <meta name="twitter:image" content="{{ $metaTags['image'] }}" />
    <meta property="fb:app_id" content="337031642040584" />
    <meta property="og:type" content="beat.song">
@endsection

@push('ghead')
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MT3JSPQW');
    </script>
    <!-- End Google Tag Manager -->
@endpush

@push('gbody')
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MT3JSPQW" height="0" width="0"
            style="display:none;visibility:hidden">
        </iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@endpush

@push('aplayer')
<link rel="stylesheet" href="{{ asset('frontend/css/audioplayer.css') }}">
@endpush

@push('player')
    <script src="{{ asset('frontend/js/audioplayer.js') }}"></script>
    <script>
        $(function() {
            $('audio').audioPlayer();
        });
    </script>
    <script>
        document.getElementById('buyNowForm').addEventListener('submit', function(event) {
            var userId = document.getElementsByName('custom')[0].value;
    
            // Check if userId is null
            if (!userId) {
                var intendedUrl = '{{ route('msingle.slug', ['slug' => urlencode($beat->slug)]) }}';
                window.sessionStorage.setItem('intended_url', intendedUrl);
    
                // Redirect to login page
                window.location.href = '{{ route('login') }}';
                event.preventDefault(); // Prevent the form from being submitted
            }
            // If userId is not null, the form will be submitted as usual
        });
    </script>
@endpush
@push('mpesa')
<script>
    $(document).ready(function () {
        $('#paymentForm').submit(function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Processing',
                html: 'Please wait...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
            });
            $.ajax({
                type: 'POST',
                url: '{{ route('beat.pay') }}',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    Swal.close();

                    Swal.fire({
                        icon: response.status,
                        title: response.status.charAt(0).toUpperCase() + response.status.slice(1),
                        text: response.message,
                    }).then(function () {
                        if (response.status === 'success') {
                            window.location.href = response.download_url;
                        }
                    });
                },
                error: function (xhr, status, error) {
                    Swal.close();

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to make the API request',
                    });
                },
            });
        });
    });
</script>
<script>
    // Add an event listener to the link
    document.getElementById('showAlert').addEventListener('click', function() {
      // Display SweetAlert2 when the link is clicked
      Swal.fire({
        title: 'INSTRUCTIONS',
        text: 'Send payment via M-Pesa to 59073443. You\'ll receive an OTP on your phone; use it to finalize the download payment.',
        icon: 'info',
        confirmButtonText: 'Dismiss'
      });
    });
  </script>
    {{-- <script>
        $(document).ready(function() {
            var delayTimer;
    
            $('#otp').on('input', function() {
                var otp = $(this).val();
    
                clearTimeout(delayTimer);
    
                delayTimer = setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('check-otp') }}',
                        data: {
                            _token: $('input[name="_token"]').val(),
                            otp: otp
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.info').html('Received Amount: ' + response.receivedAmount +
                                    '<br>From Number: ' + response.fromNumber);
                            } else {
                                $('.info').html('Invalid OTP');
                            }
                        },
                        error: function() {
                            $('.info').html('Error checking OTP');
                        }
                    });
                }, 500);
            });
        });
    </script> --}}
    <script>
        $(document).ready(function() {
            var delayTimer;
    
            $('#otp').on('input', function() {
                var otp = $(this).val();
    
                clearTimeout(delayTimer);
    
                delayTimer = setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('check-otp') }}',
                        data: {
                            _token: $('input[name="_token"]').val(),
                            otp: otp
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.info').html('Received Amount: ' + response.receivedAmount +
                                    '<br>From Number: ' + response.fromNumber);
                            } else {
                                $('.info').html('Invalid OTP');
                            }
                        },
                        error: function() {
                            $('.info').html('Error checking OTP');
                        }
                    });
                }, 500);
            });
        });
    </script>
@endpush

