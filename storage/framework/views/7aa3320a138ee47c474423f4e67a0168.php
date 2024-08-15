<?php $__env->startSection('content'); ?>

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
        <?php $__empty_1 = true; $__currentLoopData = $musicCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $music): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div wire:key="<?php echo e($music->id); ?>" class="col-6 col-md-2">
                <div class="card h-100">
                    <img src="<?php echo e(asset("storage/$music->image")); ?>" class="card-img-top image-filter"
                        alt="<?php echo e($music->image); ?>" />
                    <div class="info-solid relative">
                        <div class="play-icon track-list" data-id="<?php echo e($music->id); ?>"role="button" tabindex="0"
                            onclick="fetchTrackData(this);">
                            <i class="dripicons-media-play icon-size"></i>
                        </div>
                        <a href="<?php echo e(route('msingle.slug', ['slug' => urlencode($music->slug)])); ?>">
                            <h6 class="price">R<?php echo e($music->amount ?? '-'); ?>.00</h6>
                        </a>
                        <div class="dropup-center dropup price">
                            <a class="text-dark" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </a>

                            <ul class="dropdown-menu">
                                
                            </ul>
                        </div>

                    </div>
                    <div class="card-body d-flex flex-column">
                        <a href="<?php echo e(route('msingle.slug', ['slug' => urlencode($music->slug)])); ?>">
                            <h6 class="card-text">
                                <small><?php echo e($music->artist ?? '-'); ?></small>
                            </h6>
                            <p class="card-text text-truncate">
                                <small><?php echo e($music->title ?? '-'); ?></small>
                            </p>
                        </a>
                    </div>
                    <?php
                        $baseUrl = config('app.url');
                        $url1 = "{$baseUrl}/msingle/{$music->slug}";
                        $shareButtons = \Share::page($url1, 'Check out this music: ' . $music->title)
                            ->facebook()
                            ->twitter()
                            ->whatsapp();
                    ?>
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
                                        <?php echo $shareButtons; ?>

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
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

            <?php echo app('translator')->get('no_items_found'); ?>
        <?php endif; ?>
    </div>
    <br>
    <div class="flex justify-content-center ">
        <div class="pagination-sm"><?php echo e($musicCollection->links()); ?></div>
    </div>
    <?php
        $setting = App\Models\Setting::firstOrFail();
        $appName = config('app.name');
        $url = config('app.url');

        $title = $setting ? $setting->site : $appName;
        $image = asset("storage/$setting->image");
        $keywords = 'GW ENT, genius Works ent, KS, K Fire, K-Fire, Elliotgog, GOG';
    ?>
    <?php $__env->startSection('head'); ?>
        <title><?php echo e($title); ?></title>
        <meta name="description" content="<?php echo e($setting->description); ?>">
        <meta property="og:title" content="<?php echo e($title); ?>">
        <meta property="og:image" content="<?php echo e($image); ?>">
        <meta property="og:description" content="<?php echo e($setting->description); ?>">
        <meta property="og:url" content="<?php echo e($url); ?>" />
        <link rel="canonical" href="<?php echo e($url1); ?>">
        <meta name="keywords" content="<?php echo e($keywords); ?>">
        <meta name="twitter:card" content="summary" />
        <meta name="twitter:title" content="<?php echo e($title); ?>" />
        <meta name="twitter:description" content="<?php echo e($setting->description); ?>" />
        <meta name="twitter:image" content="<?php echo e($image); ?>" />
        <meta property="fb:app_id" content="337031642040584" />
    <?php $__env->stopSection(); ?>

    <?php $__env->startPush('player'); ?>
        <script src="<?php echo e(asset('assets/js/mediaelement-and-player.js')); ?>"></script>
        
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
                const url = '<?php echo e($url1); ?>';
                navigator.clipboard.writeText(url).then(() => {
                    alert('URL copied to clipboard!');
                }).catch(err => {
                    console.error('Could not copy text: ', err);
                });
            }
        </script>
    <?php $__env->stopPush(); ?>

    <?php $__env->startPush('aplayer'); ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/mediaelementplayer.css')); ?>">
    <?php $__env->stopPush(); ?>
    <br>
    <?php $__env->startSection('audio'); ?>
        <div class="toggle--player">
            <audio id="audio-player" preload="none" class="mejs__player" controls
                data-mejsoptions='{"defaultAudioHeight": "50", "alwaysShowControls": "true"}' style="max-width:100%;">
                <source src="" type="audio/mp3">
            </audio>
        </div>
    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('search'); ?>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('search-bar');

$__html = app('livewire')->mount($__name, $__params, 'lw-3364446204-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php $__env->stopSection(); ?>
</div>

    <div class="pagination"><?php echo e($musicCollection->links()); ?></div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('head'); ?>
    <title><?php echo e($metaTags['title']); ?></title>
    <meta name="description" content="<?php echo e($metaTags['description']); ?>">
    <meta property="og:title" content="<?php echo e($metaTags['title']); ?>">
    <meta property="og:image" content="<?php echo e($metaTags['image']); ?>">
    <meta property="og:description" content="<?php echo e($metaTags['description']); ?>">
    <meta property="og:url" content="<?php echo e($metaTags['url']); ?>" />
    <meta name="keywords" content="<?php echo e($metaTags['keywords']); ?>">
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content="<?php echo e($metaTags['title']); ?>" />
    <meta name="twitter:description" content="<?php echo e($metaTags['description']); ?>" />
    <meta name="twitter:image" content="<?php echo e($metaTags['image']); ?>" />
    <meta property="fb:app_id" content="337031642040584" />
<?php $__env->stopSection(); ?>

<?php $__env->startPush('ghead'); ?>
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('gbody'); ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MT3JSPQW" height="0" width="0"
            style="display:none;visibility:hidden">
        </iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
<?php $__env->stopPush(); ?>

<?php $__env->startPush('recipe'); ?>
    <script type="application/ld+json">
    <?php echo json_encode($siteData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

    <?php echo json_encode($recipeData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>

</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('player'); ?>

    <script src="<?php echo e(asset('frontend/js/mediaelement-and-player.js')); ?>"></script>

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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('aplayer'); ?>
<link rel="stylesheet" href="<?php echo e(asset('frontend/css/mediaelementplayer.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('audio'); ?>
<audio id="audio-player" preload="none" class="mejs__player" controls
            data-mejsoptions='{"defaultAudioHeight": "50", "alwaysShowControls": "true"}' style="max-width:100%;">
            <source src="" type="audio/mp3">
        </audio>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/songs_by_genre.blade.php ENDPATH**/ ?>