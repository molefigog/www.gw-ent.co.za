<div>

    <style>
        .card-body {
            height: 24px;
            /* Adjust this height as needed */
            padding: 0px 0px;
            z-index: 12;
        }

        .card-text {
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            /* background: linear-gradient(to right,
                    rgb(243 240 240 / 30%),
                    rgb(74 156 167 / 14%)); */
        }

        .card {
            overflow: hidden;
        }

        .cardfooter .social-icons {
            display: flex;
            justify-content: space-evenly;
            /* background: linear-gradient(to top,
                    rgb(250, 250, 250),
                    rgba(74, 156, 167, 0.418)); */
        }

        .image-filter:hover {
            transform: scale(1.1);
        }

        .image-filter {
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        .cardfooter .social-icons a {
            margin: 0 2px;
            padding: 0px 0px;
        }

        .info-solid {
            background: linear-gradient(to top, #ec4561, rgb(74 156 167 / 0%));
            margin-top: -36px;
            height: 40px;
            display: flex;
            justify-content: space-around;
            z-index: 12;
        }

        .icon-size {
            font-size: larger;
            cursor: pointer;
            color: #fffcfc;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        .icon-size i .dripicons-media-play {
            top: 0px;
            left: 0px;
        }

        .icon-size:hover {
            transform: scale(0.8);
        }

        .play-icon {
            position: relative;
            width: 40px;
            height: 40px;
            background-image: -webkit-linear-gradient(-57deg, rgb(255, 85, 62) 0%, rgb(255, 0, 101) 100%);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.3s ease, filter 0.3s ease;
            outline: 1px solid #ffffff;
            bottom: 8px;
            transform: scale(0.8);
        }

        .price {
            padding: 8px 0px;
        }



        .card-img-top {
            width: 100%;
            height: auto;
            aspect-ratio: 1;
            object-fit: cover;
        }

        @supports not (aspect-ratio: 1) {
            .card-img-top-container {
                width: 100%;
                padding-top: 100%;
                /* 1:1 Aspect Ratio */
                position: relative;
            }

            .card-img-top-container img {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
        }

        .spinner {
            position: fixed;
            top: 50%;
            left: 50%;
            z-index: 20;
            transform: translate(-50%, -50%);
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-left-color: #fffbfb;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes bar {
            0% {
                height: 10%;
            }

            50% {
                height: 100%;
            }

            100% {
                height: 10%;
            }
        }
    </style>
    <div class="row g-2">
        @forelse($allMusic as $music)
            <div wire:key="{{ $music->id }}" class="col-6 col-md-2">
                <div class="card h-100">
                    <img src="{{ asset("storage/$music->image") }}" class="card-img-top image-filter"
                        alt="{{ $music->image }}" />
                    <div class="info-solid relative">
                        <div class="play-icon track-list" data-id="{{ $music->id }}"role="button" tabindex="0"
                            onclick="fetchTrackData(this);">
                            <i class="dripicons-media-play icon-size"></i>
                        </div>
                        <a href="{{ route('msingle.slug', ['slug' => urlencode($music->slug)]) }}">
                            <h6 class="price">R{{ $music->amount ?? '-' }}.00</h6>
                        </a>
                        <div class="dropup-center dropup price">
                            <a class="text-dark" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>

                            <ul class="dropdown-menu">
                                {{-- <li><a class="dropdown-item" href="#">{{$music->duration}}</a></li>
                                <li><a class="dropdown-item" href="#">{{$music->size}}</a></li>
                                <li>
                                    <a class="dropdown-item" href="#">{{$music->likes}}/a>
                                </li> --}}
                            </ul>
                        </div>

                    </div>
                    <div class="card-body d-flex flex-column">
                        <a href="{{ route('msingle.slug', ['slug' => urlencode($music->slug)]) }}">
                            <h6 class="card-text">
                                <small>{{ $music->artist ?? '-' }}</small>
                            </h6>
                            <p class="card-text text-truncate">
                                <small>{{ $music->title ?? '-' }}</small>
                            </p>
                        </a>
                    </div>
                    @php
                        $baseUrl = config('app.url');
                        $url1 = "{$baseUrl}/msingle/{$music->slug}";
                        $shareButtons = \Share::page($url1, 'Check out this music: ' . $music->title)
                            ->facebook()
                            ->twitter()
                            ->whatsapp();
                    @endphp
                    <div class="cardfooter">
                        <div class="social-icons">
                            <a><i class="fas fa-eye"></i> </a>

                            <a><i class="fas fa-clock"></i> </a>
                            <div class="dropup-center dropup">

                                <a class="" href="#" role="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fas fa-share"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <div class="d-flex justify-content-evenly">
                                        {!! $shareButtons !!}
                                        <a href="#" class="" onclick="copyToClipboard()"><i
                                                class="fas fa-copy"></i></a>
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="spinner" class="spinner" style="display: none;"></div>
        @empty

            @lang('no_items_found')
        @endforelse
    </div>
    <br>
    <div class="flex justify-content-center ">
        <div class="pagination-sm">{{ $allMusic->links() }}</div>
    </div>
    @php
        $setting = App\Models\Setting::firstOrFail();
        $appName = config('app.name');
        $url = config('app.url');

        $title = $setting ? $setting->site : $appName;
        $image = asset("storage/$setting->image");
        $keywords = 'GW ENT, genius Works ent, KS, K Fire, K-Fire, Elliotgog, GOG';
    @endphp
    @section('head')
        <title>{{ $title }}</title>
        <meta name="description" content="{{ $setting->description }}">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:image" content="{{ $image }}">
        <meta property="og:description" content="{{ $setting->description }}">
        <meta property="og:url" content="{{ $url }}" />
        <link rel="canonical" href="{{ $url1 }}">
        <meta name="keywords" content="{{ $keywords }}">
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="{{ $title }}" />
        <meta name="twitter:description" content="{{ $setting->description }}" />
        <meta name="twitter:image" content="{{ $image }}" />
        <meta property="fb:app_id" content="337031642040584" />
    @endsection

    @push('player')
        <script src="{{ asset('assets/js/mediaelement-and-player.js') }}"></script>
        {{--
        <script>
            var trackPlaying = '',
                audioPlayer = document.getElementById('audio-player');

            audioPlayer.addEventListener("ended", function() {
                jQuery('.track-list').find('i').removeClass('dripicons-media-pause').addClass('dripicons-media-play');
            });

            audioPlayer.addEventListener("pause", function() {
                jQuery('.track-list').find('i').removeClass('dripicons-media-pause').addClass('dripicons-media-play');
            });

            function changeAudio(sourceUrl, posterUrl, trackTitle, trackSinger, playAudio = true) {
                var audio = $("#audio-player"),
                    clickEl = jQuery('[data-track="' + sourceUrl + '"]'),
                    playerId = audio.closest('.mejs__container').attr('id'),
                    playerObject = mejs.players[playerId];

                jQuery('.track-list').find('i').removeClass('dripicons-media-pause').addClass('dripicons-media-play');

                if (sourceUrl == trackPlaying) {
                    if (playerObject.paused) {
                        playerObject.play();
                        clickEl.find('i').removeClass('dripicons-media-play').addClass('dripicons-media-pause');
                    } else {
                        playerObject.pause();
                        clickEl.find('i').removeClass('dripicons-media-pause').addClass('dripicons-media-play');
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
                    jQuery(clickEl).find('i').removeClass('dripicons-media-play').addClass('dripicons-media-pause');
                }
            }

            function fetchTrackData(el) {
                var id = jQuery(el).attr('data-id');
                document.getElementById('spinner').style.display = 'block';
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/api/get_data',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        document.getElementById('spinner').style.display = 'none';
                        if (response.error) {
                            console.error('Error fetching track data:', response.error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error fetching track data: ' + response.error,
                            });
                        } else {
                            changeAudio(response.demo, response.image, response.title, response.artist);
                        }
                    },
                    error: function(xhr) {
                        document.getElementById('spinner').style.display = 'none';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch track data. Please try again later.',
                            footer: 'Status Code: ' + xhr.status
                        });
                        console.error('Failed to fetch track data:', xhr);
                    }
                });
            }

            function fetchTrackData2(el) {
                var id = jQuery(el).attr('data-id');

                // Show spinner
                document.getElementById('spinner').style.display = 'block';

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/api/get_data',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Hide spinner
                        document.getElementById('spinner').style.display = 'none';

                        if (response.error) {
                            console.error('Error fetching track data:', response.error);
                        } else {
                            changeAudio(response.demo, response.image, response.title, response.artist);
                        }
                    },
                    error: function(xhr) {
                        // Hide spinner
                        document.getElementById('spinner').style.display = 'none';

                        console.error('Failed to fetch track data:', xhr);
                    }
                });
            }
            jQuery(window).on('load', function() {
                var trackOnload = jQuery('#track-onload');

                if (trackOnload.length > 0) {
                    var audioTrack = trackOnload.attr('data-track'),
                        posterUrl = trackOnload.attr('data-poster'),
                        trackTitle = trackOnload.attr('data-title');
                    trackSinger = trackOnload.attr('data-singer');

                    setTimeout(function() {
                        changeAudio(audioTrack, posterUrl, trackTitle, trackSinger, false);
                    }, 500);
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const audioPlayer = document.getElementById('audio-player');
                const divToHide = document.querySelector('.toggle--player');

                if (!audioPlayer || !divToHide) {
                    console.error('Audio player or div to hide not found.');
                    return;
                }

                function hideDivIfNoAudio() {
                    if (!audioPlayer.paused || audioPlayer.currentTime > 0) {
                        divToHide.style.display = 'block';
                    } else {
                        divToHide.style.display = 'none';
                    }
                }

                function handleAudioEvents() {
                    hideDivIfNoAudio();
                }

                audioPlayer.addEventListener('loadeddata', handleAudioEvents);
                audioPlayer.addEventListener('play', handleAudioEvents);
                audioPlayer.addEventListener('pause', handleAudioEvents);
                audioPlayer.addEventListener('ended', handleAudioEvents);
                audioPlayer.addEventListener('timeupdate', handleAudioEvents);
                hideDivIfNoAudio();
            });
        </script> --}}
        <script>
            var trackPlaying = '',
                audioPlayer = document.getElementById('audio-player');

            audioPlayer.addEventListener("ended", function() {
                console.log("Audio ended.");
                jQuery('.track-list').find('i').removeClass('dripicons-media-pause').addClass('dripicons-media-play');
            });

            audioPlayer.addEventListener("pause", function() {
                console.log("Audio paused.");
                jQuery('.track-list').find('i').removeClass('dripicons-media-pause').addClass('dripicons-media-play');
            });

            function changeAudio(sourceUrl, posterUrl, trackTitle, trackSinger, playAudio = true) {
                var audio = $("#audio-player"),
                    clickEl = jQuery('[data-track="' + sourceUrl + '"]'),
                    playerId = audio.closest('.mejs__container').attr('id'),
                    playerObject = mejs.players[playerId];

                if (!playerObject) {
                    console.error('Player object not found for playerId:', playerId);
                    return;
                }

                jQuery('.track-list').find('i').removeClass('dripicons-media-pause').addClass('dripicons-media-play');

                if (sourceUrl == trackPlaying) {
                    if (playerObject.paused) {
                        playerObject.play();
                        clickEl.find('i').removeClass('dripicons-media-play').addClass('dripicons-media-pause');
                    } else {
                        playerObject.pause();
                        clickEl.find('i').removeClass('dripicons-media-pause').addClass('dripicons-media-play');
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
                    jQuery(clickEl).find('i').removeClass('dripicons-media-play').addClass('dripicons-media-pause');
                }
            }

            function fetchTrackData(el) {
                var id = jQuery(el).attr('data-id');
                document.getElementById('spinner').style.display = 'block';
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/api/get_data',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        document.getElementById('spinner').style.display = 'none';
                        if (response.error) {
                            console.error('Error fetching track data:', response.error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Error fetching track data: ' + response.error,
                            });
                        } else {
                            changeAudio(response.demo, response.image, response.title, response.artist);
                        }
                    },
                    error: function(xhr) {
                        document.getElementById('spinner').style.display = 'none';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to fetch track data. Please try again later.',
                            footer: 'Status Code: ' + xhr.status
                        });
                        console.error('Failed to fetch track data:', xhr);
                    }
                });
            }

            jQuery(window).on('load', function() {
                var trackOnload = jQuery('#track-onload');

                if (trackOnload.length > 0) {
                    var audioTrack = trackOnload.attr('data-track'),
                        posterUrl = trackOnload.attr('data-poster'),
                        trackTitle = trackOnload.attr('data-title'),
                        trackSinger = trackOnload.attr('data-singer');

                    setTimeout(function() {
                        changeAudio(audioTrack, posterUrl, trackTitle, trackSinger, false);
                    }, 500);
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                const audioPlayer = document.getElementById('audio-player');
                const divToHide = document.querySelector('.toggle--player');

                if (!audioPlayer || !divToHide) {
                    console.error('Audio player or div to hide not found.');
                    return;
                }

                function hideDivIfNoAudio() {
                    if (!audioPlayer.paused || audioPlayer.currentTime > 0) {
                        divToHide.style.display = 'block';
                    } else {
                        divToHide.style.display = 'none';
                    }
                }

                function handleAudioEvents() {
                    hideDivIfNoAudio();
                }

                audioPlayer.addEventListener('loadeddata', handleAudioEvents);
                audioPlayer.addEventListener('play', handleAudioEvents);
                audioPlayer.addEventListener('pause', handleAudioEvents);
                audioPlayer.addEventListener('ended', handleAudioEvents);
                audioPlayer.addEventListener('timeupdate', handleAudioEvents);
                hideDivIfNoAudio();
            });
        </script>
        <script>
            function copyToClipboard() {
                const url = '{{ $url1 }}';
                navigator.clipboard.writeText(url).then(() => {
                    alert('URL copied to clipboard!');
                }).catch(err => {
                    console.error('Could not copy text: ', err);
                });
            }
        </script>
    @endpush

    @push('aplayer')
        <link rel="stylesheet" href="{{ asset('assets/css/mediaelementplayer.css') }}">
    @endpush
    <br>
    @section('audio')
        <div class="toggle--player">
            <audio id="audio-player" preload="none" class="mejs__player" controls
                data-mejsoptions='{"defaultAudioHeight": "50", "alwaysShowControls": "true"}' style="max-width:100%;">
                <source src="" type="audio/mp3">
            </audio>
        </div>
    @endsection

    @section('search')
    @livewire('search-bar')
    @endsection
</div>
