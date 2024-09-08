<?php $__env->startSection('content'); ?>

    <div class="articles">
        <?php $__empty_1 = true; $__currentLoopData = $beats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $beat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="article-card">
            <div class="cover">
                <img src="<?php echo e($beat->image ? \Storage::url($beat->image) : ''); ?>" alt="Article 1 Cover Image">
                <div class="overlay">
                    <a href="#" class="play-button track-list" data-track="<?php echo e(\Storage::url($beat->demo)); ?>"
                        data-poster="<?php echo e($beat->image ? \Storage::url($beat->image) : ''); ?>" data-title="<?php echo e($beat->title ?? '-'); ?>" data-singer="<?php echo e($beat->artist ?? '-'); ?>">
                        <i class="icon-play"></i>
                    </a>
                </div>
            </div>

            <div class="details">
                <h6 class="artist"><?php echo e($beat->artist ?? '-'); ?></h6>
                <p class="card-text" id="product1Description">
                    <?php echo e($beat->title ?? '-'); ?>

                </p>

                <?php if($beat->amount == 0): ?>
                <a href="<?php echo e(route('beat.slug', ['slug' => urlencode($beat->slug)])); ?>" class="btn buy-button">Download</a>
                <?php else: ?>
                <a href="<?php echo e(route('beat.slug', ['slug' => urlencode($beat->slug)])); ?>" class="btn buy-button">Buy R<?php echo e($beat->amount); ?></a>
                <?php endif; ?>

            </div>
            <div class="songs-button"><a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"><i class="icon-ellipsis-v"></i></a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a class="dropdown-item" href="#"><span class="icon-line-plus"></span> Add to Queue</a>
                        <a class="dropdown-item" href="#"><span class="icon-beat"></span> Add to Playlist</a>
                        <a class="dropdown-item" href="#"><span class="icon-line-cloud-download"></span>
                            Download
                            Offline</a>
                        <a class="dropdown-item" href="#"><span class="icon-line-heart"></span> Love</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"><span class="icon-line-share"></span> Share</a>
                    </li>
                </ul>
            </div>
            <div class="details-left">

                <p class="texts">&nbsp;<?php echo e($beat->size); ?>MB</p>
                <p class="texts"><i class="icon-clock-o"></i>&nbsp;<?php echo e($beat->duration); ?></p>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

        <?php echo app('translator')->get('no_items_found'); ?>
        <?php endif; ?>

    </div>
    <div class="pagination"><?php echo e($beats->links()); ?></div>
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/beatz.blade.php ENDPATH**/ ?>