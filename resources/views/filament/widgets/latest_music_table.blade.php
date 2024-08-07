<!-- resources/views/filament/widgets/latest_music.blade.php -->

<div>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Monthly Downloads</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($songs as $song)
            <tr>
                <td>{{ $song->title }}</td>
                <td>{{ $song->md }}</td>
                <td>{{ $song->amount }}</td>
                <td>{{ $song->total }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>Total: {{ $grandTotal }}</div>
</div>