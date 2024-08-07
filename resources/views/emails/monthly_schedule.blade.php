<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} - Music Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #2681e2de;
            color: #fff;
            padding: 10px;
            text-align: center;
            font-size: 1.5em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .total-section {
            margin-top: 20px;
            text-align: center;
        }

        img.logo {
            width: 150px;
            display: block;
            margin: 20px auto;
        }

        hr {
            margin: 30px 0;
            border: 0;
            border-top: 1px solid #ddd;
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
            <div class="card-header">
                Music Report for {{ $user->name }}
            </div>
            @if ($songs->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Song</th>
                            <th>MD</th>
                            <th>Amount</th>
                            <th>Total</th>
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
                                <td>{{ $index + 1 }} {{ $song->title }}</td>
                                <td>{{ $song->md }}</td>
                                <td>{{ $song->amount }}</td>
                                <td>{{ $total }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="total-section">
                    <h2>{{ $user->name }}, you have <strong>R{{ $grandTotal }}</strong> this {{ $currentMonth }}
                        {{ $currentYear }}</h2>
                    <p>{{ $setting->site ?? config('app.name') }}</p>
                    <img src="{{ \Storage::url($setting->logo) }}" alt="" class="logo" />
                </div>
                <hr>
            @else
                <p>No songs found.</p>
            @endif
        </div>
    </div>
</body>

</html>
