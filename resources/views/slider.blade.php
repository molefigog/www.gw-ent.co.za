    @php
           $genres = App\Models\Genre::withCount('music')
            ->latest()
            ->paginate(10) // You might want to adjust the pagination as needed
            ->withQueryString();
    @endphp

    <div class="owl-carousel owl-theme">
        @foreach ($genres as $genre)
        <div class="item">
            <img style="height: 300px; width:300px;" src="{{ asset("storage/$genre->image") }}"
                alt="{{ $genre->title }}">
            <div class="carousel-caption">
                <h5>
                    <a class="btn btn-primary w-100" href="{{ route('songs-by-genre', urlencode($genre->title)) }}">{{
                        $genre->title ?? '-' }}</a>
                </h5>
                <h3 >=>
                    <span class="btn btn-success btn-sm btn-outline">{{ $genre->music_count ?? 0 }} Songs</span>
                    <=
                </h3>
            </div>
        </div>
        @endforeach
    </div>
