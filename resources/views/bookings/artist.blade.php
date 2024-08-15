@extends('layouts.master')

@section('content')
    <div class="">
        <h2>Bookings for {{ $artist->name }}</h2>

        @if (is_null($booking))
            <p>No bookings found for this artist.</p>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="invoice-title">
                                        <h3>
                                            <img src="{{ asset('storage/' . $booking->image) }}" alt="artist image" height="100">
                                        </h3>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6 text-start">
                                            <address>
                                                <strong>Info:</strong><br>
                                                Artist: {{ $booking->artist }}<br>
                                                @if(isset($booking->bank[0]))
                                                    Bank: {{ $booking->bank[0]['name'] }}<br>
                                                    Account: {{ $booking->bank[0]['acc_number'] }}<br>
                                                @endif
                                                M-pesa: {{ $booking->mpesa }}
                                            </address>
                                        </div>

                                        <div class="col-6 text-start">
                                            <address>
                                                <strong>Contact:</strong><br>
                                                @foreach($booking->contact as $contact)
                                                    Tel: {{ $contact['tell'] }}<br>
                                                    Tel: {{ $contact['tell2'] }}<br>
                                                    Email: {{ $contact['email'] }}<br>
                                                @endforeach
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Local Pricing Table -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h4>Local Pricing</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <td><strong>Price</strong></td>
                                                    <td class="text-center"><strong>Duration/Time</strong></td>
                                                    <td class="text-end"><strong>Transport</strong></td>
                                                    <td class="text-end"><strong>Total</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $localTotalPrice = 0;
                                                    $localTransportCost = isset($booking->transport[0]['local']) ? $booking->transport[0]['local'] : 0;
                                                @endphp
                                                @foreach($booking->pricing as $price)
                                                    @php
                                                        $localTotalPrice += $price['amount'];
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $price['amount'] }}</td>
                                                        <td class="text-center">{{ $price['duration'] }}</td>
                                                        <td class="text-end">{{ $localTransportCost }}</td>
                                                        <td class="text-end">
                                                            @php
                                                                $total = $price['amount'] + $localTransportCost;
                                                            @endphp
                                                            {{ number_format($total, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- International Pricing Table -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h4>International Pricing</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <td><strong>Price</strong></td>
                                                    <td class="text-center"><strong>Duration/Time</strong></td>
                                                    <td class="text-end"><strong>Transport</strong></td>
                                                    <td class="text-end"><strong>Total</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $internationalTotalPrice = 0;
                                                    $internationalTransportCost = isset($booking->transport[0]['south_africa']) ? $booking->transport[0]['south_africa'] : 0;
                                                @endphp
                                                @foreach($booking->int_pricing as $int_price)
                                                    @php
                                                        $internationalTotalPrice += $int_price['amount'];
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $int_price['amount'] }}</td>
                                                        <td class="text-center">{{ $int_price['duration'] }}</td>
                                                        <td class="text-end">{{ $internationalTransportCost }}</td>
                                                        <td class="text-end">
                                                            @php
                                                                $total = $int_price['amount'] + $internationalTransportCost;
                                                            @endphp
                                                            {{ number_format($total, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="flex justify-content-center mt-3">
                                        @foreach($booking->contact as $contact)
                                            <a class="btn btn-outline-info btn-sm mx-2" href="mailto:{{ $contact['email'] }}"><i class="fas fa-envelope"></i> Email</a>
                                            <a class="btn btn-outline-info btn-sm mx-2" href="tel:{{ $contact['tell'] }}"><i class="fas fa-phone-square-alt"></i> Call</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div> <!-- end row -->

                            <!-- PDF Download Button -->
                            <div class="text-right mt-4">
                                <a href="{{ route('generate.pdf', ['artistId' => $artist->id]) }}" class="btn btn-primary"><i class="fa fa-print"></i>  Download PDF</a>
                            </div>
                        </div>
                    </div>
                </div> <!-- end col -->
            </div>
        @endif
    </div>

@endsection
