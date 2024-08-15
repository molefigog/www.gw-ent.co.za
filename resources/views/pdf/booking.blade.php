<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $artist->name }}</title>

    <style>
     body {
    position: relative;
    font-family: Arial, sans-serif;
    margin: 20px;
}

body::before {
    content: "";
    position: absolute;
    top: 78;
    left: 110;
    width: 40%;
    height: 40%;
    background: url('{{ public_path('storage/' . $booking->image) }}') no-repeat center center;
    background-size: cover;
    opacity: 0.3; /* 60% opacity */
    filter: grayscale(100%); /* Black-and-white filter */
    z-index: -8; /* Position it behind the content */
}

        .invoice-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table, .table th, .table td {
            border: 1px solid black;
        }
        .table th, .table td {
            padding: 8px;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .table-info {
        background-color: #e9ecef;
    }




    </style>
</head>
<body>

    <div class="invoice-title">
        <h6>{{ $artist->name }}</h6>
        <hr>
        {{-- <img src="{{ asset('storage/' . $booking->image) }}" alt="artist image" height="100"> --}}

    </div>

    <table style="border-collapse: collapse; width: 100%;">
        <thead class="table-info">
            <tr>
                <th style="text-align: left; padding: 8px; width: 50%;">Info</th>
                <th style="text-align: left; padding: 8px; width: 50%;">Contact</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 8px;">
                    Artist: {{ $booking->artist }}<br>
                    @if(isset($booking->bank[0]))
                        Bank: {{ $booking->bank[0]['name'] }}<br>
                        Account: {{ $booking->bank[0]['acc_number'] }}<br>
                    @endif
                    M-pesa: {{ $booking->mpesa }}
                </td>
                <td style="padding: 8px;">
                    @foreach($booking->contact as $contact)
                        Tel: {{ $contact['tell'] }}<br>
                        Tel: {{ $contact['tell2'] }}<br>
                        Email: {{ $contact['email'] }}<br>
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>





    <!-- Local Pricing Table -->
    <h3>Local Pricing</h3>
    <table class="table">
        <thead class="table-info">
            <tr>
                <th>Price</th>
                <th>Duration/Time</th>
                <th>Transport</th>
                <th>Total</th>
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
                    <td>{{ $price['duration'] }}</td>
                    <td>{{ $localTransportCost }}</td>
                    <td class="text-right">
                        @php
                            $total = $price['amount'] + $localTransportCost;
                        @endphp
                        {{ number_format($total, 2) }}
                    </td>
                </tr>
            @endforeach
            {{-- <tr>
                <td colspan="3" class="text-right"><strong>Total Price (Local):</strong></td>
                <td class="text-right">
                    @php
                        $localGrandTotal = $localTotalPrice + $localTransportCost;
                    @endphp
                    {{ number_format($localGrandTotal, 2) }}
                </td>
            </tr> --}}
        </tbody>
    </table>

    <!-- International Pricing Table -->
    <h3>International Pricing</h3>
    <table class="table">
        <thead class="table-info">
            <tr>
                <th>Price</th>
                <th>Duration/Time</th>
                <th>Transport</th>
                <th>Total</th>
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
                    <td>{{ $int_price['duration'] }}</td>
                    <td>{{ $internationalTransportCost }}</td>
                    <td class="text-right">
                        @php
                            $total = $int_price['amount'] + $internationalTransportCost;
                        @endphp
                        {{ number_format($total, 2) }}
                    </td>
                </tr>
            @endforeach
            {{-- <tr>
                <td colspan="3" class="text-right"><strong>Total Price (International):</strong></td>
                <td class="text-right">
                    @php
                        $internationalGrandTotal = $internationalTotalPrice + $internationalTransportCost;
                    @endphp
                    {{ number_format($internationalGrandTotal, 2) }}
                </td>
            </tr> --}}
        </tbody>
    </table>

</body>
</html>
