@php
$apiKey = '863c6f17965b59a056305e51';
$baseCurrency = 'ZAR';
$targetCurrency = 'USD';
$amount = '100';

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

    $convertedAmount = round($convertedAmount, 2);

    $amount = $convertedAmount;
} else {
    $amount = 'Failed to retrieve exchange rate data.';
}

@endphp

@extends('layouts.master')

@section('content')
@auth
<style>
    #text {
        font-weight: bold;
        font-size: 12px;
        animation-name: blink;
        animation-duration: 3s;
        animation-iteration-count: infinite;
    }

    @keyframes blink {
        0% {
            color: pink
        }

        50% {
            color: black;
        }

        100% {
            color: pink;
        }
    }
</style>
<div class="container mt-4">
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <a href="{{ url('edit-profile') }}">
                            <img src="{{ \Storage::url(auth()->user()->avatar ?? 'default_avatar.png') }}" alt=""
                                class="img-fluid rounded-circle" width="100" height="100">
                            <div class="ms-3">
                                <p class="text-muted">{{ Auth::user()->name }}</p>
                            </div>
                        </a>
                        {{-- <div class="ms-3">
                            <p class="mb-0">Available Balance M {{ Auth::user()->balance }}</p>
                        </div> --}}
                        @if (Auth::user()->upload_status == 1)
                        <div class="text-center ms-3">
                            <a class="btn btn-primary btn-smtext-center" href="{{ url('/all-music/create') }}"><i
                                    class="icon-upload"></i> Upload</a>
                        </div>
                        <hr>
                        <br>
                        @endif
                    </div>
                    <nav class="text-center">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home"
                                role="tab" aria-controls="nav-home" aria-selected="true">Balance</a>
                            <a class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile" role="tab"
                                aria-controls="nav-profile" aria-selected="false">M-Pesa</a>
                            <a class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" href="#nav-contact" role="tab"
                                aria-controls="nav-contact" aria-selected="false">Paypal</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            <div class="text-center">
                                <p class="mb-0">Account Holder {{ Auth::user()->name }}</p>
                                {{-- <p class="mb-0">Available Balance M {{ Auth::user()->balance }}</p>
                                <p>This the money you have Deposited into your Acount using M-pesa or Paypal</p> --}}
                            </div>
                            @if (Auth::user()->upload_status == 1)
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="card">
                                        <h6 class="text-center">account Wallet</h6>

                                       @if ($songs && $songs->count() > 0)
                                        <table border="1">
                                            <thead>
                                                <tr>
                                                    <th class=""><i class="icon-library_music text-muted"></i><span
                                                            class="card-text"> Tracks</span></th>
                                                    <th><i class="icon-cloud-download text-muted"></i><span
                                                            class="card-text"> Downloads</span></th>
                                                    <th><i class="icon-monetization_on text-muted"></i><span
                                                            class="card-text"> Price</span></th>
                                                    <th><i class="icon-drag_handle text-muted"></i><span
                                                            class="card-text"> Total</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @php
                                                $grandTotal = 0;
                                                @endphp

                                                @foreach ($songs as $index => $song)
                                                @php
                                                $total = $song->md * $song->amount;
                                                $grandTotal += $total;
                                                @endphp
                                                <tr>
                                                    <td class="">{{ $index + 1 }}
                                                        {{ $song->title }}</td>
                                                    <td>{{ $song->md }}</td>
                                                    <td>{{ $song->amount }}</td>
                                                    <td>{{ $total }}</td>
                                                </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        <P class="text-center">
                                        </P>
                                        <hr class="">
                                        <h6 class="text-center">{{ Auth::user()->name }} You have <strong>
                                                R{{ $grandTotal }} </strong>
                                            <script>
                                                var currentDate = new Date();
                                                            var currentMonthIndex = currentDate.getMonth();
                                                            var currentYear = currentDate.getFullYear();

                                                            // Array of month names
                                                            var monthNames = [
                                                                "January", "February", "March", "April", "May", "June",
                                                                "July", "August", "September", "October", "November", "December"
                                                            ];

                                                            var currentMonthName = monthNames[currentMonthIndex];

                                                            document.write("this " + currentMonthName + " " + currentYear);
                                            </script>
                                        </h6>
                                        <p class="text-muted text-center">
                                            md -> monthly downloads
                                            <br>
                                            md x amount = total


                                        </p>
                                        <hr>
                                        @else
                                        <p>No songs found.</p>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                            <div class="justify-content-center">
                                <h6 class="text-center">Activate your upload status</h6>
                                <p class="text-center">Note!! You must subscribe first to be able to upload and sell
                                    your music with us.</p>
                                <form id="paymentForm" class="text-center" action="{{ route('upload.status') }}"
                                    method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">M-pesa</label>
                                        <div class="input-group mb-2">
                                            @if (Auth::user()->upload_status == 0)
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"> <img src="{{ asset('assets/vcl1.png') }}"
                                                        alt="" style="width: 24px; height: 24px;"> </i></div>
                                            </div>
                                            <input type="number" class="form-control" name="mobileNumber" value=""
                                                placeholder="mpesa number" maxlength="8" required>
                                            <input type="hidden" name="amount" value="{{ $upload_fee }}">
                                            <input type="hidden" name="client_reference" value="uploading Fee">
                                            <input type="hidden" name="userId" value="{{ Auth::user()->id }}">



                                            &nbsp; <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <span class="circle2"><img src="{{ asset('assets/vcl1.png') }}" alt=""
                                                        style="width: 24px; height: 24px;">
                                                    </i></span>
                                                <span class="title2 gee">Pay M{{ $upload_fee }}</span>
                                            </button>
                                            @else
                                            <div class="alert alert-success justify-content-center" role="alert">
                                                <p class="text-center" id="text">Upload Activated! </p>
                                            </div>

                                            @endif

                                        </div>
                                    </div>
                                </form>

                            </div>
                            {{-- <div class="text-center">
                                <p>Note: You can use M-Pesa transaction ID you have received from M-Pesa if you paid
                                    before
                                    registering the account.</p>
                                <br>
                                <form method="POST" action="{{ route('process-top-up') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="transaction_id" class="text-center">Enter M-Pesa Transaction
                                            ID:</label>
                                        <div class="col-lg-4 mx-auto">
                                            <input type="text" name="transaction_id" placeholder="eg. 0853A5TXNBR4"
                                                class="form-control" id="transaction_id" required maxlength="12">
                                        </div>
                                    </div>
                                    <div class="ms_upload_btn">
                                        <button class="btn buy-button" type="submit">TOP UP</button>
                                    </div>
                                </form>
                                <hr>
                                <p>Use <a class=""
                                        href="https://play.google.com/store/apps/details?id=com.vodafone.mpesa.ls&hl=en_US&pli=1">M-Pesa
                                        App</a> or *200#.</p>
                                <hr>
                                <p>On M-PESA App click send money >> 59073443 >> amount >> pin.</p>
                                <p>Upon a successful payment, you shall automatically receive an SMS from GW confirming
                                    your
                                    payment</p>
                            </div> --}}
                        </div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <div class="text-center">
                                <h6 class="text-center">Activate your upload status</h6>
                                <p>Note!! You must subscribe first to be able to upload and sell your music with us.</p>
                                <form id="activate" action="{{ $Paypal }}" method="post"
                                    onsubmit="return checkUploadStatus()">
                                    <input type="hidden" name="cmd" value="_xclick">
                                    <input type="hidden" name="amount" value="{{ $amount }}">
                                    <input type="hidden" name="currency_code" value="{{ $currency }}">
                                    <input type="hidden" name="business" value="{{ $PAYPAL_ID }}">

                                    <input type="hidden" name="custom" value="{{ Auth::user()->id }}">

                                    <input type="hidden" name="item_name" value="Registration Fee">
                                    <input type="hidden" name="item_number" value="5907">
                                    <input type="hidden" name="return" value="{{ route('success2') }}">
                                    <input type="hidden" name="cancel_return" value="{{ url('cancel') }}">
                                    <input type="hidden" name="notify_url" value="{{ url('ipn') }}">

                                    @if (Auth::user()->upload_status == 0)
                                    <button class="btn btn-primary btn-sm" type="submit"><i class="icon-paypal"></i> Pay
                                        R100</button>
                                    @else
                                    <button class="btn btn-primary btn-sm" type="button" disabled><i
                                            class="icon-paypal"></i>Upload Activated</button>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>

@endsection

@push('upload_status')
<script>
    function checkUploadStatus() {
            // Check if upload_status is "0"
            var uploadStatus = "{{ Auth::user()->upload_status }}";
            if (uploadStatus === "0") {
                // Allow form submission
                return true;
            } else {
                // Display Notyf alert or perform other actions if needed
                var notyf = new Notyf({
                    position: {
                        x: 'right',
                        y: 'top',
                    },
                    dismissible: true,
                    duration: 7000,
                });
                notyf.alert('Your upload status is already activated');

                // Prevent form submission
                return false;
            }
        }

        document.getElementById("activate").addEventListener("submit", function(event) {
            if (!checkUploadStatus()) {
                event.preventDefault(); // Prevent the form from submitting
            }
        });
</script>
@endpush
@endauth
@push('mpesa')
<script>
    $(document).ready(function() {
            $('#paymentForm').submit(function(e) {
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
                    url: '{{ route('upload.status') }}',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();

                        Swal.fire({
                            icon: response.status,
                            title: response.status.charAt(0).toUpperCase() + response
                                .status.slice(1),
                            text: response.message,
                        }).then(function() {
                            if (response.status === 'success') {
                                location.reload();
                            }
                        });
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
            });
        });
</script>
@endpush