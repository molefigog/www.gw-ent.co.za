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
$Paypal = config('paypal.paypal_url');
$PAYPAL_ID = config('paypal.paypal_id');
$currency = config('paypal.paypal_currency');
$upload_fee = '100';
$user = auth()->user();
$songs = $user->musics;
@endphp

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
<div class="max-w-4xl mx-auto flex justify-center items-center h-full">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        @if (Auth::user()->upload_status == 1)

        <h6 class="text-center">account Wallet</h6>

        @if ($songs && $songs->count() > 0)
        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center">
                            Tracks
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center">
                            MD
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center">
                            Price
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center">
                            Total
                        </div>
                    </th>
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
                <tr class="bg-white border-b">
                    
                    <td class="px-6 py-4">
                        {{ $index + 1 }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $song->title }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $song->md }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $song->amount }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $total }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
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
        <p class="text-center">No songs found.</p>
        @endif
        @endif
    </div>
</div>

@endauth