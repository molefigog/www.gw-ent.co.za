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

        /* .price {
            padding: 8px 0px;
        } */



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

        .dim {
            background: #ff2950a9;
            color: #fcfcfc;
            text-shadow: -1px -1px 0px var(--background),
                3px 3px 0px var(--background),
                6px 6px 0px #00000055;
        }
    </style>
    <br>
    <hr>

    
    <div id="accordion">
        <div class="accordion-item">
            <div class="accordion-item border rounded">
                <h2 class="accordion-header" id="headingMusic">
                    <button class="accordion-button fw-semibold" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseMusic" aria-expanded="true" aria-controls="collapseMusic">
                        All Music
                    </button>
                </h2>
                <hr>
                <div id="collapseMusic" class="accordion-collapse collapse show" aria-labelledby="headingMusic"
                    data-bs-parent="#accordion">
                    <div class="accordion-body">
                        <div class="row g-2">
                            <!--[if BLOCK]><![endif]--><?php if($allMusic->count() > 0): ?>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $allMusic; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $music): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div wire:key="<?php echo e($music->id); ?>" class="col-6 col-md-2">
                                        <div class="card h-100">
                                            <img src="<?php echo e($music->img); ?>" class="card-img-top image-filter"
                                                alt="<?php echo e($music->image); ?>" />
                                            <div class="info-solid relative">
                                                <div class="play-icon track-list" data-id="<?php echo e($music->id); ?>"
                                                    role="button" tabindex="0" onclick="fetchTrackData(this);">
                                                    <i class="dripicons-media-play icon-size"></i>
                                                </div>
                                                <!--[if BLOCK]><![endif]--><?php if($music->free): ?>
                                                    <form action="<?php echo e(route('mp3.download', ['mp3' => $music->id])); ?>"
                                                        method="get">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="music_id"
                                                            value="<?php echo e($music->id); ?>">
                                                        <button type="submit"
                                                            class="btn btn-outline-light waves-effect waves-light btn-sm dim"
                                                            style="height:28px;">
                                                            Download
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <a class="btn btn-outline-light waves-effect waves-light btn-sm dim"
                                                        style="height:28px;"
                                                        href="<?php echo e(route('msingle.slug', ['slug' => urlencode($music->slug)])); ?>">
                                                        <strong class="price"
                                                            style="padding: 0px 10px;">R<?php echo e($music->amount ?? '-'); ?>.00</strong>
                                                    </a>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <div class="dropup-center dropup price">
                                                    <a class="btn btn-outline-light waves-effect waves-light btn-sm dim my-6"
                                                        href="#" role="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>

                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#"><i
                                                                    class="far fa-clock"></i> <?php echo e($music->duration); ?></a>
                                                        </li>
                                                        <li><a class="dropdown-item" href="#"><i
                                                                    class="far fa-file-audio"></i>
                                                                <?php echo e($music->size); ?>MB</a></li>
                                                        <li>
                                                            <button style="font-size: 9px; margin-right: 4px;"
                                                                class="dropdown-item"
                                                                wire:click="incrementLikes(<?php echo e($music->id); ?>)">
                                                                <span style="color: #007bff;">
                                                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                                                    <?php echo e($music->likes); ?></span>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>

                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <a
                                                    href="<?php echo e(route('msingle.slug', ['slug' => urlencode($music->slug)])); ?>">
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
                                                $url = "{$baseUrl}/msingle/{$music->slug}";
                                                $shareButtons = \Share::page(
                                                    $url,
                                                    'Check out this music: ' . $music->title,
                                                )
                                                    ->facebook()
                                                    ->twitter()
                                                    ->whatsapp();
                                            ?>
                                            <div class="cardfooter">
                                                <div class="social-icons">

                                                    <div class="dropup-center dropup">

                                                        <a class="" href="#" role="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            share <i class="fas fa-share-alt"></i>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <div class="d-flex justify-content-evenly">
                                                                <?php echo $shareButtons; ?>

                                                                <a href="#" class=""
                                                                    onclick="copyToClipboard()"><i
                                                                        class="fas fa-copy"></i></a>
                                                            </div>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="spinner" class="spinner" style="display: none;"></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                                <p><?php echo app('translator')->get('no_items_found'); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="flex justify-content-center ">
            <div class="pagination-sm"><?php echo e($allMusic->links()); ?></div>
        </div>
        <hr>
        <div class="accordion-item">
            <div class="accordion-item border rounded">
                <h2 class="accordion-header" id="headingBeats">
                    <button class="accordion-button btn-success" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseBeats" aria-expanded="false" aria-controls="collapseBeats">
                        Beats
                    </button>
                </h2>
                <hr>
                <div id="collapseBeats" class="accordion-collapse collapse" aria-labelledby="headingBeats"
                    data-bs-parent="#accordion">
                    <div class="accordion-body">
                        <div class="row g-2">
                            <!--[if BLOCK]><![endif]--><?php if($beats->count() > 0): ?>
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $beats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $music): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div wire:key="<?php echo e($music->id); ?>" class="col-6 col-md-2">
                                        <div class="card h-100">
                                            <img src="<?php echo e($music->img); ?>" class="card-img-top image-filter"
                                                alt="<?php echo e($music->image); ?>" />
                                            <div class="info-solid relative">
                                                <div class="play-icon track-list" data-id="<?php echo e($music->id); ?>"
                                                    role="button" tabindex="0" onclick="fetchTrackData(this);">
                                                    <i class="dripicons-media-play icon-size"></i>
                                                </div>
                                                <!--[if BLOCK]><![endif]--><?php if($music->free): ?>
                                                    <form action="<?php echo e(route('mp3.download', ['mp3' => $music->id])); ?>"
                                                        method="get">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="music_id"
                                                            value="<?php echo e($music->id); ?>">
                                                        <button type="submit"
                                                            class="btn btn-outline-light waves-effect waves-light btn-sm dim"
                                                            style="height:28px;">
                                                            Download
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <a class="btn btn-outline-light waves-effect waves-light btn-sm dim"
                                                        style="height:28px;"
                                                        href="<?php echo e(route('msingle.slug', ['slug' => urlencode($music->slug)])); ?>">
                                                        <strong class="price"
                                                            style="padding: 0px 10px;">R<?php echo e($music->amount ?? '-'); ?>.00</strong>
                                                    </a>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                <div class="dropup-center dropup price">
                                                    <a class="btn btn-outline-light waves-effect waves-light btn-sm dim my-6"
                                                        href="#" role="button" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>

                                                    <ul class="dropdown-menu">
                                                        <li><a class="dropdown-item" href="#"><i
                                                                    class="far fa-clock"></i>
                                                                <?php echo e($music->duration); ?></a></li>
                                                        <li><a class="dropdown-item" href="#"><i
                                                                    class="far fa-file-audio"></i>
                                                                <?php echo e($music->size); ?>MB</a></li>
                                                        <li>
                                                            <button style="font-size: 9px; margin-right: 4px;"
                                                                class="dropdown-item"
                                                                wire:click="incrementLikes(<?php echo e($music->id); ?>)">
                                                                <span style="color: #007bff;">
                                                                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                                                                    <?php echo e($music->likes); ?></span>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <a
                                                    href="<?php echo e(route('msingle.slug', ['slug' => urlencode($music->slug)])); ?>">
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
                                                $url = "{$baseUrl}/msingle/{$music->slug}";
                                                $shareButtons = \Share::page(
                                                    $url,
                                                    'Check out this music: ' . $music->title,
                                                )
                                                    ->facebook()
                                                    ->twitter()
                                                    ->whatsapp();
                                            ?>
                                            <div class="cardfooter">
                                                <div class="social-icons">

                                                    <div class="dropup-center dropup">

                                                        <a class="" href="#" role="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            share <i class="fas fa-share-alt"></i>
                                                        </a>
                                                        <ul class="dropdown-menu">
                                                            <div class="d-flex justify-content-evenly">
                                                                <?php echo $shareButtons; ?>

                                                                <a href="#" class=""
                                                                    onclick="copyToClipboard()"><i
                                                                        class="fas fa-copy"></i></a>
                                                            </div>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="spinner" class="spinner" style="display: none;"></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            <?php else: ?>
                                <p><?php echo app('translator')->get('no_items_found'); ?></p>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex justify-content-center ">
            <div class="pagination-sm"><?php echo e($beats->links()); ?></div>
        </div>
    </div>



    <?php
        $setting = App\Models\Setting::firstOrFail();
        $appName = config('app.name');
        $home = config('app.url');

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
        <meta property="og:url" content="<?php echo e($home); ?>" />
        <link rel="canonical" href="<?php echo e($home); ?>">
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
                console.log("Changing audio to:", sourceUrl);
                var audio = $("#audio-player"),
                    clickEl = jQuery('[data-track="' + sourceUrl + '"]'),
                    playerId = audio.closest('.mejs__container').attr('id'),
                    playerObject = mejs.players[playerId];

                console.log("Clicked Element:", clickEl);
                console.log("Player Object:", playerObject);

                if (!playerObject) {
                    console.error('Player object not found for playerId:', playerId);
                    return;
                }

                jQuery('.track-list').find('i').removeClass('dripicons-media-pause').addClass('dripicons-media-play');

                if (sourceUrl == trackPlaying) {
                    if (playerObject.paused) {
                        playerObject.play();
                        clickEl.find('i').removeClass('dripicons-media-play').addClass('dripicons-media-pause');
                        console.log("Playing track.");
                    } else {
                        playerObject.pause();
                        clickEl.find('i').removeClass('dripicons-media-pause').addClass('dripicons-media-play');
                        console.log("Pausing track.");
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
                    console.log("Playing new track.");
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
                const url = '<?php echo e($url); ?>'; // Replace with your actual URL
                console.log('Attempting to copy:', url); // Debugging line
                navigator.clipboard.writeText(url).then(() => {
                    console.log('Copy successful'); // Debugging line
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'URL copied to clipboard!',
                        input: "text",
                        inputValue: url,
                        timer: 15000,
                        showConfirmButton: false
                    });
                }).catch(err => {
                    console.error('Could not copy text:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Could not copy URL!',
                    });
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

$__html = app('livewire')->mount($__name, $__params, 'lw-447617718-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <?php $__env->stopSection(); ?>
</div>
<?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/livewire/mmino.blade.php ENDPATH**/ ?>