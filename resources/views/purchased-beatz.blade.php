@php
    // use Illuminate\Support\Facades\Auth;

    // // Get the currently authenticated user
    // $user = Auth::user();

    // Initialize the total count
    $totalBeatz = 0;

    // Check if the user is logged in
    if ($user) {
        // Count the items based on user_id and beat_id
        $totalBeatz = App\Models\Beatz::where('user_id', $user->id)->count();
    }
@endphp

@extends('layouts.master')

@section('content')
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
    <div class="justify-content-center">
        <h5 class="text-center">Hi <span>{{ Auth::user()->name }}</span> you have {{ $totalBeatz }}</h5><br>
        <p class="text-center" id="text">download links will expire in 30days!!</p>
    </div>
    <div class="articles">

        @forelse($purchasedbeats as $beat)
            <div class="article-card">
                <div class="cover">
                    <img src="{{ $beat->image ? \Storage::url($beat->image) : '' }}" alt="Article 1 Cover Image">
                    <div class="overlay">
                        <a href="#" class="play-button track-list" data-track="{{ \Storage::url($beat->demo) }}"
                            data-poster="{{ $beat->image ? \Storage::url($beat->image) : '' }}"
                            data-title="{{ $beat->title ?? '-' }}" data-singer="{{ $beat->artist ?? '-' }}">
                            <i class="icon-play"></i>
                        </a>
                    </div>
                </div>

                <div class="details">
                    <h6 class="artist">{{ $beat->artist ?? '-' }}</h6>
                    <p class="card-text" id="product1Description">
                        {{ $beat->title ?? '-' }}
                    </p>

                    <a href="{{ route('download-beat', ['beat_id' => $beat->id]) }}" class="btn buy-button">
                        <i class="icon-cloud-download"></i> DOWNLOAD
                    </a>

                </div>

                <div class="details-left">

                    <p class="texts">{{ $beat->size ?? '-' }}&nbsp;MB</p>
                    <p class="texts"><i class="icon-clock-o"></i>&nbsp;{{ $beat->duration }}</p>
                </div>
         
            </div>
            @empty
            <p class="text-center">No Downloads Found</p>
        @endforelse
        <div class="pagination">{{ $purchasedbeats->links('custom-pagination') }}</div>
    </div>
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
@push('player')
    <script src="{{ asset('frontend/js/mediaelement-and-player.js') }}"></script>

    <script>
        var trackPlaying = '',
            audioPlayer = document.getElementById('audio-player');

        audioPlayer.addEventListener("ended", function() {
            jQuery('.track-list').find('i').removeClass('icon-pause').addClass('icon-play');
        });

        audioPlayer.addEventListener("pause", function() {
            jQuery('.track-list').find('i').removeClass('icon-pause').addClass('icon-play');
        });

        function changeAudio(sourceUrl, posterUrl, trackTitle, trackSinger, playAudio = true) {
            var audio = $("#audio-player"),
                clickEl = jQuery('[data-track="' + sourceUrl + '"]'),
                playerId = audio.closest('.mejs__container').attr('id'),
                playerObject = mejs.players[playerId];

            jQuery('.track-list').find('i').removeClass('icon-pause').addClass('icon-play');

            if (sourceUrl == trackPlaying) {
                if (playerObject.paused) {
                    playerObject.play();
                    clickEl.find('i').removeClass('icon-play').addClass('icon-pause');
                } else {
                    playerObject.pause();
                    clickEl.find('i').removeClass('icon-pause').addClass('icon-play');
                }
                return true;
            }

            trackPlaying = sourceUrl;

            audio.attr('poster', posterUrl);
            audio.attr('title', trackTitle);

            jQuery('.mejs__layers').html('').html('<div class="mejs-track-artwork"><img src="' + posterUrl +
                '" alt="Track Poster" /></div><div class="mejs-track-details"><h3>' + trackTitle + '<br><span>' +
                trackSinger + '</span></h3></div>');

            if (sourceUrl != '') {
                playerObject.setSrc(sourceUrl);
            }
            playerObject.pause();
            playerObject.load();

            if (playAudio == true) {
                playerObject.play();
                jQuery(clickEl).find('i').removeClass('icon-play').addClass('icon-pause');
            }
        }

        jQuery('.track-list').on('click', function() {
            var audioTrack = jQuery(this).attr('data-track'), // Track url
                posterUrl = jQuery(this).attr('data-poster'), // Track Poster Image
                trackTitle = jQuery(this).attr('data-title'); // Track Title
            trackSinger = jQuery(this).attr('data-singer'); // Track Singer Name

            changeAudio(audioTrack, posterUrl, trackTitle, trackSinger);
            return false;
        });

        jQuery(window).on('load', function() {
            var trackOnload = jQuery('#track-onload');

            if (trackOnload.length > 0) {
                var audioTrack = trackOnload.attr('data-track'), // Track url
                    posterUrl = trackOnload.attr('data-poster'), // Track Poster Image
                    trackTitle = trackOnload.attr('data-title'); // Track Title
                trackSinger = trackOnload.attr('data-singer'); // Track Singer Name

                setTimeout(function() {
                    changeAudio(audioTrack, posterUrl, trackTitle, trackSinger, false);
                }, 500);
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const audioPlayer = document.getElementById('audio-player');

            function hideMediaPlayer() {
                if (!audioPlayer.paused || audioPlayer.src) {
                    audioPlayer.style.display = 'block';
                } else {
                    audioPlayer.style.display = 'none';
                }
            }

            audioPlayer.addEventListener('loadeddata', hideMediaPlayer);
            audioPlayer.addEventListener('pause', hideMediaPlayer);

            // Initial check after DOM loaded
            hideMediaPlayer();
        });
    </script>
@endpush

@push('aplayer')
    <link rel="stylesheet" href="{{ asset('frontend/css/mediaelementplayer.css') }}">
@endpush

@section('audio')
    <audio id="audio-player" preload="none" class="mejs__player" controls
        data-mejsoptions='{"defaultAudioHeight": "50", "alwaysShowControls": "true"}' style="max-width:100%;">
        <source src="" type="audio/mp3">
    </audio>
@endsection
