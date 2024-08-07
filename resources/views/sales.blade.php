@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <h6 class="text-center">account Wallet</h6>

                        @if ($songs->count() > 0)
                            <table border="1">
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
                                            <td class="card-text">{{ $index + 1 }} {{ $song->title }}</td>
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
                            <h6 class="text-center">{{ Auth::user()->name }} You have <strong> R{{ $grandTotal }} </strong><script>
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
                              </script></h6>
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
    </div>
@endsection
