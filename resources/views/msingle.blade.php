@php
    $apiKey = '863c6f17965b59a056305e51';
    $baseCurrency = 'ZAR';
    $targetCurrency = 'USD';
    $amount = $music->amount; // Assuming $music->amount contains the ZAR amount you want to convert

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
<style>
    /* Basic loader style */
    .loader2 {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        z-index: 9999;
    }

    .spinner2 {
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #3498db;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .loader2 p {
        margin-top: 10px;
        font-size: 18px;
        color: #333;
    }
</style>
@section('content')
@if ($music->publish)
    <div class="container mt-5">
        <div class="row">
            <div class="card-img-wrapper col-md-6 ">
                <img src="{{$music->img }}" class="img-fluid" alt="Product Image">
            </div>
            <div class="col-md-6">
                <h2>{{ $music->title ?? '-' }}</h2>
                <p class="text-muted">{{ $music->description ?? '-' }}</p>
                <P> Size: {{ $music->size ?? '-' }}MB Duration:
                    <span>{{ $music->duration ?? '-' }}</span>
                </P>
                <h3 class="text-success">Price R{{ $music->amount }}</h3>
                <p>
                    <strong>Downloads <span class="text-success">{{ $music->downloads }}</span> </strong> Genre <span
                        class="text-success">{{ $music->genre ? $music->genre->title : '-' }}</span>
                </p>
                <hr>
                <p class="text-center">Payment Gateways</p>
                <hr>
                @if ($music->free)
                    <div id="wrap">
                        <form action="{{ route('mp3.download', ['mp3' => $music->id]) }}" method="get">
                            @csrf
                            <input type="hidden" name="music_id" value="{{ $music->id }}">
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                <span class="circle2"><i class="icon-download"></i></span>
                                <span class="title2 gee">Download</span>

                            </button>
                        </form>
                    </div>
                @else
                @if ($music->sold)

                <button type="submit" class="btn btn-outline-success btn-sm">

                    <span class="title2 gee">Sold</span>

                </button>
                @else
                    <nav class="text-center">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">

                            <a class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home"
                                role="tab" aria-controls="nav-home" aria-selected="true"><img
                                    src="{{ asset('assets/vcl1.png') }}" alt="" style="width: 24px; height: 24px;">
                                M-Pesa</a>


                            <a class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" href="#nav-contact" role="tab"
                                aria-controls="nav-contact" aria-selected="false"><i class="icon-paypal"></i> Paypal</a>
                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            <div class="justify-content-center">
                                <hr>
                                <div id="wrap">
                                    {{-- <form id="paymentForm" class="text-center" action="{{ route('m-pesa') }}"
                                        method="post">
                                        @csrf
                                        <div class="mb-3">
                                            <p></p>
                                            <h6 id="text">Enter Your M-pesa Number To Buy This Song</h6>
                                            <p>Reka ka mpesa</p>
                                            <div class="input-group mb-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text"> <img
                                                            src="{{ asset('assets/vcl1.png') }}" alt=""
                                                            style="width: 24px; height: 24px;"> </i></div>
                                                </div>
                                                <input type="text" class="form-control col-6" name="mobileNumber"
                                                    value="" placeholder="Enter mpesa number" pattern="5\d{7}"
                                                    title="Please enter 8 digits starting with 5" maxlength="8" required>
                                                <input type="hidden" name="amount" value="{{ $music->amount }}">
                                                <input type="hidden" name="client_reference"
                                                    value="{{ $music->id }} {{ $music->title ?? '-' }}">
                                                <input type="hidden" name="musicId" value="{{ $music->id }}">

                                                &nbsp; <button type="submit" class="btn btn-outline-success btn-sm">
                                                    <span class="circle2"></span>
                                                    <span class="title2 gee">Pay M{{ $music->amount }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </form> --}}
                                    @livewire('payment', ['music' => $music])

                                </div>
                            </div>
                        </div>

                        {{-- <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <br>
                            <div class="text-center">
                                <div class="text-center"><a href="#information" id="showAlert">INSTRUCTIONS</a></div>
                                <form id="complete-order" action="{{ route('manual') }}" method="post">
                                    @csrf
                                    <div class="text-center">
                                        <div class="info"></div>
                                        <div class="message"></div>
                                    </div>
                                    <input type="hidden" name="music_id" value="{{ $music->id }}">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="otp" id="otp"
                                            placeholder="Enter OTP" aria-label="Enter OTP"
                                            aria-describedby="button-addon2" required>
                                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buy
                                            R{{ $music->amount }}</button>
                                    </div>
                                </form>
                            </div>
                        </div> --}}


                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <div class="text-center">
                                <h6 class="text-center"></h6>
                                @if ($music->amount == 0)
                                    <p> </p>
                                @else
                                    <div id="wrap">
                                        <form id="buyNowForm" action="{{ $Paypal }}" method="post">
                                            <input type="hidden" name="cmd" value="_xclick">
                                            <input type="hidden" name="amount" value="{{ $amount }}">
                                            <input type="hidden" name="currency_code" value="{{ $currency }}">
                                            <input type="hidden" name="business" value="{{ $PAYPAL_ID }}">
                                            <input type="hidden" name="custom" value="{{ $userId }}">
                                            <input type="hidden" name="item_name" value="{{ $music->title }}">
                                            <input type="hidden" name="item_number" value="{{ $music->id }}">
                                            <input type="hidden" name="return" value="{{ route('success') }}">
                                            <input type="hidden" name="cancel_return" value="{{ url('cancel') }}">
                                            <input type="hidden" name="notify_url" value="{{ url('ipn') }}">
                                            <button type="submit" class="btn btn-outline-info btn-sm"
                                                title="secure online payment with paypal"><i class="fab fa-cc-paypal"></i>
                                                Buy
                                                ${{ $amount }}</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                @endif
                {{-- @if (session('download_valid') && session('music_id') == $music->id)
                    <form action="{{ route('music.download', ['music' => $music->id]) }}" method="get">
                        @csrf
                        <input type="hidden" name="music_id" value="{{ $music->id }}">
                        <button type="submit" class="btn btn-outline-success btn-sm">
                            <span class="circle2"><i class="icon-download"></i></span>
                            <span class="title2 gee">Download</span>
                        </button>
                    </form>
                @else
                    <p>Download link is not available or expired.</p>
                    <button type="submit" disabled class="btn btn-outline-danger btn-sm">
                        <span class="circle2"><i class="icon-download"></i></span>
                        <span class="title2 gee">Download</span>
                    </button>
                @endif --}}
                <hr>
                <div id="wrapper">
                    <audio preload="auto" controls>
                        <source src="{{ asset("storage/demos/$music->demo") }}">
                    </audio>

                </div>

                <div class="dropup-center dropup">

                    <a class="btn btn-outline-info btn-sm" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        share to <i class="ti-sharethis"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <div class="d-flex justify-content-evenly">
                            {!! $shareButtons !!}
                            <a href="#" class="" onclick="copyToClipboard()"><i class="fas fa-copy"></i></a>
                        </div>
                    </ul>
                </div>
                <hr>



            </div>
        </div>

    </div>
@endif

    @livewire('comment-section', ['musicId' => $music->id])
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
    <meta property="og:type" content="music.song">
@endsection


@push('aplayer')
    <link rel="stylesheet" href="{{ asset('assets/css/audioplayer.css') }}">
@endpush

@push('player')
    <script src="{{ asset('assets/js/audioplayer.js') }}"></script>
    <script>
        $(function() {
            $('audio').audioPlayer();
        });
    </script>
    <script>
        function copyToClipboard() {
            const url = '{{ $url1 }}'; // Replace with your actual URL
            console.log('Attempting to copy:', url); // Debugging line
            navigator.clipboard.writeText(url).then(() => {
                console.log('Copy successful'); // Debugging line
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'URL copied to clipboard!',
                    input: "text",
                    inputValue: url,
                    timer: 15000,
                    showConfirmButton: false
                });
            }).catch(err => {
                console.error('Could not copy text:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Could not copy URL!',
                });
            });
        }
    </script>

    <script>
        document.getElementById('buyNowForm').addEventListener('submit', function(event) {
            event.preventDefault();

            var form = this;
            var userId = document.getElementsByName('custom')[0].value;

            if (!userId) {
                var intendedUrl = '{{ route('msingle.slug', ['slug' => urlencode($music->slug)]) }}';
                window.sessionStorage.setItem('intended_url', intendedUrl);

                // Open the modal instead of redirecting to login
                var modalElement = document.querySelector('.bs-example-modal-center');
                var bootstrapModal = new bootstrap.Modal(modalElement);
                bootstrapModal.show();
            } else {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('check-music-file') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        musicId: '{{ $music->id }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            form.submit(); // Submit the form if the file exists
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Music File Not found',
                        });
                    },
                });
            }
        });
    </script>
@endpush
@push('mpesa')

    <script>
        $(document).ready(function() {
            $('#paymentForm').submit(function(e) {
                e.preventDefault();

                let form = this;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('check-music-file') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        musicId: '{{ $music->id }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            proceedWithPayment(form);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Music File Not found',
                        });
                    },
                });
            });
        });
    </script>
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
                                $('.info').html('Received Amount: ' + response
                                    .receivedAmount +
                                    '<br>From Number: ' + response.fromNumber);
                                $('.message').html(
                                    '<p class="text-success">Success it worked</p>');
                            } else {
                                $('.info').html('Invalid OTP');
                                $('.message').html(
                                    '<p class="text-danger">Enter Valid OTP</p>');
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
    <script>
        document.getElementById('input_CustomerMSISDN').addEventListener('input', function() {
            const msisdnInput = this.value;
            const msisdnError = document.getElementById('msisdnError');

            // Check if the input starts with "5" and is 8 digits long
            if (/^5\d{7}$/.test(msisdnInput)) {
                msisdnError.style.display = 'none';
                this.setCustomValidity(''); // Clear custom validity message
            } else {
                msisdnError.style.display = 'block';
                this.setCustomValidity('Invalid'); // Set custom validity message to block form submission
            }
        });
    </script>
@endpush
