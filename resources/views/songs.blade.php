@extends('layouts.master')

@section('content')
    <h1 class="mt-4 mb-3 text-center">Genre</h1>
    <div class="row row-cols-md-3 row-cols-sm-2">
        @forelse ($genres as $genre)
            <div class="col mb-4">
                <div class="card">

                    <div class="card-img-wrapper">
                        <img src="{{ $genre->image ? \Storage::url($genre->image) : '' }}" style="height: 96px; width:96px;"
                            class="card-img-top" alt="{{ $genre->title }}" class="genre-image">
                    </div>

                    <div class="ribbon-2">{{ $genre->music_count ?? 0 }} Songs</div>
                    <div class="card-body">
                        <h5 class="card-title text-center" style="font-size: 12px; font-weight: 600;">
                            <span style="padding: 0px 10px;">
                                {{ $genre->title ?? '-' }}</span>
                        </h5>
                        <div class="card-text text-center">
                            <a class="btn buy-button"
                                href="{{ route('songs-by-genre', urlencode($genre->title)) }}">View</a>
                        </div>
                        <div class="card-hide">
                            <p class="text-dark card-text" style="font-size: 12px; font-weight: 600;">
                                {{ $genre->music_count ?? 0 }} Songs</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            ..
        @endforelse
    </div>
    <div class="text-center">{{ $music->links() }}</div>
@endsection

@section('head')
    <title>{{ $metaTags['title'] }}</title>
    <meta name="description" content="{{ $metaTags['description'] }}">
    <meta property="og:title" content="{{ $metaTags['title'] }}">
    <meta property="og:image" content="{{ $metaTags['image'] }}">
    <meta property="og:description" content="{{ $metaTags['description'] }}">
    <!-- Additional meta tags as needed -->
@endsection

@push('ghead')
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-MT3JSPQW');
    </script>
    <!-- End Google Tag Manager -->
@endpush

@push('gbody')
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MT3JSPQW" height="0" width="0"
            style="display:none;visibility:hidden">
        </iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
@endpush
