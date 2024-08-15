@php

    $user = auth()->user();
    $songs = $user->musics;
@endphp

@auth

<div class="max-w-4xl mx-auto flex justify-center items-center h-full">
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <h6 class="text-center">Account Wallet</h6>

        @if ($songs && $songs->count() > 0)
            <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                <thead class="divide-y divide-gray-200 dark:divide-white/5">
                    <tr>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 fi-table-header-cell-title">
                            #
                        </th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 fi-table-header-cell-title">
                            <div class="flex items-center">
                                Tracks
                            </div>
                        </th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 fi-table-header-cell-title">
                            <div class="flex items-center">
                                MD
                            </div>
                        </th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 fi-table-header-cell-title">
                            <div class="flex items-center">
                                Price
                            </div>
                        </th>
                        <th class="fi-ta-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 fi-table-header-cell-title">
                            <div class="flex items-center">
                                Total
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                    @php
                        $grandTotal = 0;
                    @endphp

                    @foreach ($songs as $index => $song)
                        @php
                            $total = $song->md * $song->amount;
                            $grandTotal += $total;
                        @endphp
                        <tr class="bg-gray-50 dark:bg-white/5">
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-title">
                                {{ $index + 1 }}
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-title">
                                {{ $song->title }}
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-title">
                                {{ $song->md }}
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-title">
                                {{ $song->amount }}
                            </td>
                            <td class="fi-ta-cell p-0 first-of-type:ps-1 last-of-type:pe-1 sm:first-of-type:ps-3 sm:last-of-type:pe-3 fi-table-cell-title">
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

                    var monthNames = [
                        "January", "February", "March", "April", "May", "June",
                        "July", "August", "September", "October", "November", "December"
                    ];

                    var currentMonthName = monthNames[currentMonthIndex];

                    document.write("this " + currentMonthName + " " + currentYear);
                </script>
            </h6>
            <p>
                md -> monthly downloads
                <br>
                md x amount = total
            </p>
            <hr>
        @else
            <p class="text-center">No songs found.</p>
        @endif
    </div>
</div>





@endauth
