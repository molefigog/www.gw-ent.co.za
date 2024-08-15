<?php
$apiKey = '863c6f17965b59a056305e51';
$baseCurrency = 'ZAR';
$targetCurrency = 'USD';
$amount = $beat->amount; // Assuming $music->amount contains the ZAR amount you want to convert

// Make API request to get the exchange rate
$apiUrl = "https://open.er-api.com/v6/latest/{$baseCurrency}?apikey={$apiKey}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if ($data && isset($data['rates'][$targetCurrency])) {
    // Perform the conversion
    $exchangeRate = $data['rates'][$targetCurrency];
    $convertedAmount = $amount * $exchangeRate;

    // Round the converted amount to 2 decimal places (you can adjust this as needed)
    $convertedAmount = round($convertedAmount, 2);

    // Assign the converted amount to $amount
    $amount = $convertedAmount;
} else {
    // Handle the case where the exchange rate data is not available
    $amount = 'Failed to retrieve exchange rate data.';
}


?>




<?php $__env->startSection('content'); ?>
    
    <div class="container mt-5">
        <div class="row">
            <div class="card-img-wrapper col-md-6 ">
                <img src="<?php echo e($beat->image ? \Storage::url($beat->image) : ''); ?>" class="img-fluid" alt="Product Image">
            </div>
            <div class="col-md-6">
                <h2><?php echo e($beat->title ?? '-'); ?></h2>
                <p class="text-muted"><?php echo e($beat->description ?? '-'); ?></p>
                <P> Size: <?php echo e($beat->size ?? '-'); ?>MB Duration:
                    <span><?php echo e($beat->duration ?? '-'); ?></span>
                </P>
                <?php if($beat->used == 1): ?>
                <h3 class="text-muted">Sold </h3>
                <?php else: ?>
                <h3 class="text-success">Price R<?php echo e($beat->amount); ?> </h3>
                <?php endif; ?>
                <p>
                    Genre <span
                        class="text-success"><?php echo e($beat->genre ? $beat->genre->title : '-'); ?></span>
                </p>
                <nav class="text-center">
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home" role="tab"
                            aria-controls="nav-home" aria-selected="true"><img src="<?php echo e(asset('assets/vcl1.png')); ?>" alt=""
                                style="width: 24px; height: 24px;"> M-Pesa</a>
                        <a class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#nav-profile" role="tab"
                            aria-controls="nav-profile" aria-selected="false"><i class="icon-account_balance_wallet"></i>
                            Wallet</a>
    
                        <a class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" href="#nav-contact" role="tab"
                            aria-controls="nav-contact" aria-selected="false"><i class="icon-paypal"></i> Paypal</a>
                    </div>
                </nav>
    
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                        <div class="justify-content-center">
                            <hr>
                            <div id="wrap">
                                <form id="paymentForm" class="text-center" action="<?php echo e(route('beat.pay')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">M-pesa</label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"> <img src="<?php echo e(asset('assets/vcl1.png')); ?>"
                                                        alt="" style="width: 24px; height: 24px;"> </i></div>
                                            </div>
                                            <input type="text" class="form-control col-6" name="mobileNumber" value=""
                                            placeholder="Enter mpesa number" pattern="5\d{7}"
                                            title="Please enter 8 digits starting with 5" maxlength="8" required>
                                            <input type="hidden" name="amount" value="<?php echo e($beat->amount); ?>">
                                            
                                            <input type="hidden" name="client_reference"
                                                value="<?php echo e($beat->id); ?> <?php echo e($beat->title ?? '-'); ?>">
                                            <input type="hidden" name="beatId"value="<?php echo e($beat->id); ?>">
                                            <?php if($beat->used == 1): ?>
                                            &nbsp; <button type="submit" disabled class="btn btn-outline-danger btn-sm">
                                                <span class="circle2"><img src="<?php echo e(asset('assets/vcl1.png')); ?>" alt=""
                                                        style="width: 24px; height: 24px;"> </i></span>
                                                <span class="title2 gee">Sold</span>
    
                                            </button>
                                           <?php else: ?>
                                            &nbsp; <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <span class="circle2"><img src="<?php echo e(asset('assets/vcl1.png')); ?>" alt=""
                                                        style="width: 24px; height: 24px;"> </i></span>
                                                <span class="title2 gee">Pay M<?php echo e($beat->amount); ?></span>
    
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
    
                    <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                        <br>
                        <div class="text-center">
                            <?php if($beat->used == 1): ?>
                            <div id="wrap">
                                <form action="" method="post">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="music_id" value="<?php echo e($beat->id); ?>">
                                    <button type="button" disabled class="btn buy-button2">
                                        <span class="circle2"><i class="icon-download"></i></span>
                                        <span class="title2 gee">SOLD</span>
    
                                    </button>
                                </form>
                            </div>
                            <?php else: ?>
                            <div id="wrap"> 
                                <div class="text-center"><a href="#information" id="showAlert">INSTRUCTIONS</a></div>
                                <form id="complete-order" action="<?php echo e(route('beat-order')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <div class="text-center">
                                        <div class="info"></div>
                                        <div class="message"></div>
                                    </div>
                                    <input type="hidden" name="beat_id" value="<?php echo e($beat->id); ?>">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP"
                                            aria-label="Enter OTP" aria-describedby="button-addon2" required>
                                        <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Buy R<?php echo e($beat->amount); ?></button>
                                    </div>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
    
                    <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                        <div class="text-center">
                            <h6 class="text-center"></h6>
                            <?php if($beat->used == 1): ?>
                            <p> </p>
                            <?php else: ?>
                            <div id="wrap">
                                <form id="buyNowForm" action="<?php echo e($Paypal); ?>" method="post">
                                    <input type="hidden" name="cmd" value="_xclick">
                                    <input type="hidden" name="amount" value="<?php echo e($amount); ?>">
                                    <input type="hidden" name="currency_code" value="<?php echo e($currency); ?>">
                                    <input type="hidden" name="business" value="<?php echo e($PAYPAL_ID); ?>">
    
                                    <input type="hidden" name="custom" value="<?php echo e($userId); ?>">
    
                                    <input type="hidden" name="item_name" value="<?php echo e($beat->title); ?>">
                                    <input type="hidden" name="item_number" value="<?php echo e($beat->id); ?>">
                                    <input type="hidden" name="return" value="<?php echo e(route('success')); ?>">
                                    <input type="hidden" name="cancel_return" value="<?php echo e(url('cancel')); ?>">
                                    <input type="hidden" name="notify_url" value="<?php echo e(url('ipn')); ?>">
    
                                    <button type="submit" class="btn buy-button2"
                                        title="secure online payment with paypal"><i class="icon-paypal"></i> Buy
                                        $<?php echo e($amount); ?></button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div id="wrapper">
                    <audio preload="auto" controls>
                        <source src="<?php echo e(\Storage::url($beat->demo)); ?>">
                    </audio>
                   
                </div>

                <div>
                    <h5>share to <i class="icon-share2"></i></h5>
                    <?php echo $shareButtons; ?>

                </div>
                <hr>
            </div>
        </div>
    </div>
    <br>
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
    <meta property="og:type" content="beat.song">
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

<?php $__env->startPush('aplayer'); ?>
<link rel="stylesheet" href="<?php echo e(asset('frontend/css/audioplayer.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('player'); ?>
    <script src="<?php echo e(asset('frontend/js/audioplayer.js')); ?>"></script>
    <script>
        $(function() {
            $('audio').audioPlayer();
        });
    </script>
    <script>
        document.getElementById('buyNowForm').addEventListener('submit', function(event) {
            var userId = document.getElementsByName('custom')[0].value;
    
            // Check if userId is null
            if (!userId) {
                var intendedUrl = '<?php echo e(route('msingle.slug', ['slug' => urlencode($beat->slug)])); ?>';
                window.sessionStorage.setItem('intended_url', intendedUrl);
    
                // Redirect to login page
                window.location.href = '<?php echo e(route('login')); ?>';
                event.preventDefault(); // Prevent the form from being submitted
            }
            // If userId is not null, the form will be submitted as usual
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startPush('mpesa'); ?>
<script>
    $(document).ready(function () {
        $('#paymentForm').submit(function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Processing',
                html: 'Please wait...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                onBeforeOpen: () => {
                    Swal.showLoading();
                },
            });
            $.ajax({
                type: 'POST',
                url: '<?php echo e(route('beat.pay')); ?>',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    Swal.close();

                    Swal.fire({
                        icon: response.status,
                        title: response.status.charAt(0).toUpperCase() + response.status.slice(1),
                        text: response.message,
                    }).then(function () {
                        if (response.status === 'success') {
                            window.location.href = response.download_url;
                        }
                    });
                },
                error: function (xhr, status, error) {
                    Swal.close();

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to make the API request',
                    });
                },
            });
        });
    });
</script>
<script>
    // Add an event listener to the link
    document.getElementById('showAlert').addEventListener('click', function() {
      // Display SweetAlert2 when the link is clicked
      Swal.fire({
        title: 'INSTRUCTIONS',
        text: 'Send payment via M-Pesa to 59073443. You\'ll receive an OTP on your phone; use it to finalize the download payment.',
        icon: 'info',
        confirmButtonText: 'Dismiss'
      });
    });
  </script>
    
    <script>
        $(document).ready(function() {
            var delayTimer;
    
            $('#otp').on('input', function() {
                var otp = $(this).val();
    
                clearTimeout(delayTimer);
    
                delayTimer = setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: '<?php echo e(route('check-otp')); ?>',
                        data: {
                            _token: $('input[name="_token"]').val(),
                            otp: otp
                        },
                        success: function(response) {
                            if (response.success) {
                                $('.info').html('Received Amount: ' + response.receivedAmount +
                                    '<br>From Number: ' + response.fromNumber);
                            } else {
                                $('.info').html('Invalid OTP');
                            }
                        },
                        error: function() {
                            $('.info').html('Error checking OTP');
                        }
                    });
                }, 500);
            });
        });
    </script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/beat.blade.php ENDPATH**/ ?>