<div>
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


<style>
    section {
        /* background-color: hsl(228, 33%, 97%); */
        height: 100vh;
        display: flex;
        padding: 0 2%;
        flex-direction: column;
        justify-content: center;
        gap: 10px;
    }

    .comment-box {
        display: flex;
        background-color: rgb(255 255 255 / 7%);
        font-size: 14px;
        gap: 20px;
        padding: 8px;
        border-radius: 10px;
        background-clip: padding-box;
        box-shadow: inset 0 0 0 3px rgba(255, 255, 255, 0.13);
    }

    .ddd {
        background-color: rgba(255, 255, 255, 0.205);
        border-radius: 6px;
        padding: 4px;
    }

    .comment-reply {
        display: flex;
    }

    .comment-reply .vline {
        background-color: hsl(223, 19%, 93%);
        width: 5px;
        margin: 0 50px;
    }

    .reply-col {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .comment-count {
        background-color: hsl(228, 33%, 97%);
        color: hsl(238, 40%, 52%);
        font-weight: 500;
        padding: 10px 8px;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        gap: 13px;
        align-self: flex-start;
        align-items: center;
    }

    .comment {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .comment-head {
        display: flex;
        justify-content: space-between;
    }


    .comment-head .dname {
        font-weight: 500;
        color: hsl(212, 24%, 26%);
    }

    .comment-head .name {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .comment-head .trailing {
        display: flex;
        gap: 5px;
        align-items: center;
    }

    .comment-body {
        line-height: 22px;
        padding: 0px 39px;
        margin-top: -26px;
    }

    .comment-text {
        background-color: rgba(255, 255, 255, 0.178);
        padding: 25px 20px;
        display: flex;
        gap: 20px;
        align-items: flex-start;
        border-radius: 10px;
    }

    #text {
        font-weight: bold;
        font-size: 12px;
        animation-name: blink;
        animation-duration: 4s;
        animation-iteration-count: infinite;
    }
    @keyframes blink {
        0% {
            color: rgb(47, 43, 247)
        }
        50% {
            color: rgb(20, 211, 45);
        }
        100% {
            color: rgb(255, 255, 255);
        }
    }

    textarea {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
    }

    .you {
        padding: 3px 8px;
        background-color: hsl(238, 40%, 52%);
        color: white;
        font-weight: 500;
        border-radius: 3px;
        font-size: 13px;
    }

    .delete {
        margin-right: 10px;
    }

    .reply-to {
        color: hsl(238, 40%, 52%);
        font-weight: 500;
    }
</style>

    <div class="container mt-5">
        <div class="row">
            <div class="card-img-wrapper col-md-6 ">
                <img src="{{ asset("storage/$music->image") }}" class="img-fluid" alt="Product Image">
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
                @if ($music->amount == 0)
                    <div id="wrap">
                        <form action="{{ route('mp3.download', ['mp3' => $music->id]) }}" method="get">
                            @csrf
                            <input type="hidden" name="music_id" value="{{ $music->id }}">
                            <button type="submit" class="btn buy-button2">
                                <span class="circle2"><i class="icon-download"></i></span>
                                <span class="title2 gee">Download</span>

                            </button>
                        </form>
                    </div>
                @else
                    <nav class="text-center">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">

                            <a class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home"
                                role="tab" aria-controls="nav-home" aria-selected="true"><img
                                    src="{{ asset('assets/vcl1.png') }}" alt="" style="width: 24px; height: 24px;">
                                M-Pesa</a>
                            <a class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile" role="tab"
                                aria-controls="nav-profile" aria-selected="false"><i
                                    class="icon-account_balance_wallet"></i>
                                Wallet</a>

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
                                    <form id="paymentForm" class="text-center" action="{{ route('m-pesa') }}"
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
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
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
                        </div>


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
                                            <button type="submit" class="btn buy-button2" title="secure online payment with paypal"><i class="icon-paypal"></i> Buy ${{ $amount }}</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                <div id="wrapper">
                    <audio preload="auto" controls>
                        <source src="{{ asset("storage/demos/$music->demo") }}">
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


    @livewire('comment-section', ['musicId' => $music->id])
    <br>

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
        document.getElementById('buyNowForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission

            var form = this;
            var userId = document.getElementsByName('custom')[0].value;

            if (!userId) {
                var intendedUrl = '{{ route('msingle.slug', ['slug' => urlencode($music->slug)]) }}';
                window.sessionStorage.setItem('intended_url', intendedUrl);
                window.location.href = '{{ route('login') }}';
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

        function proceedWithPayment(form) {
            Swal.fire({
                title: 'Processing',
                html: 'Please wait...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                },
            });

            $.ajax({
                type: 'POST',
                url: $(form).attr('action'),
                data: $(form).serialize(),
                dataType: 'json',
                success: function(response) {
                    Swal.close();

                    if (response.status === 'success') {
                        window.location.href = response.download_url;
                    } else {
                        Swal.fire({
                            icon: response.status,
                            title: response.status.charAt(0).toUpperCase() + response.status.slice(1),
                            text: response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.close();

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to make the API request',
                    });
                },
            });
        }
    });
</script>

    <script>
        document.getElementById('showAlert').addEventListener('click', function() {
            Swal.fire({
                title: 'INSTRUCTIONS',
                text: 'Send payment via M-Pesa to 59073443. You\'ll receive an OTP on your phone; use it to finalize the download payment.',
                icon: 'info',
                confirmButtonText: 'Dismiss'
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
@endpush

</div>
