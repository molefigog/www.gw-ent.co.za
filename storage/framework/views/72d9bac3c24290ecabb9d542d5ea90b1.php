<?php
    $apiKey = '863c6f17965b59a056305e51';
    $baseCurrency = 'ZAR';
    $targetCurrency = 'USD';
    $amount = $music->amount; // Assuming $music->amount contains the ZAR amount you want to convert

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



<style>
    /* Basic loader style */
.loader2 {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8);
    z-index: 9999;
}

.spinner2 {
    border: 8px solid #f3f3f3;
    border-radius: 50%;
    border-top: 8px solid #3498db;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loader2 p {
    margin-top: 10px;
    font-size: 18px;
    color: #333;
}
</style>
<?php $__env->startSection('content'); ?>
    <div class="container mt-5">
        <div class="row">
            <div class="card-img-wrapper col-md-6 ">
                <img src="<?php echo e(asset("storage/$music->image")); ?>" class="img-fluid" alt="Product Image">
            </div>
            <div class="col-md-6">
                <h2><?php echo e($music->title ?? '-'); ?></h2>
                <p class="text-muted"><?php echo e($music->description ?? '-'); ?></p>
                <P> Size: <?php echo e($music->size ?? '-'); ?>MB Duration:
                    <span><?php echo e($music->duration ?? '-'); ?></span>
                </P>
                <h3 class="text-success">Price R<?php echo e($music->amount); ?></h3>
                <p>
                    <strong>Downloads <span class="text-success"><?php echo e($music->downloads); ?></span> </strong> Genre <span
                        class="text-success"><?php echo e($music->genre ? $music->genre->title : '-'); ?></span>
                </p>
                <hr>
                <p class="text-center">Payment Gateways</p>
                <hr>
                <?php if($music->amount == 0): ?>
                    <div id="wrap">
                        <form action="<?php echo e(route('mp3.download', ['mp3' => $music->id])); ?>" method="get">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="music_id" value="<?php echo e($music->id); ?>">
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                <span class="circle2"><i class="icon-download"></i></span>
                                <span class="title2 gee">Download</span>

                            </button>
                        </form>
                    </div>
                <?php else: ?>
                    <nav class="text-center">
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">

                            <a class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" href="#nav-home"
                                role="tab" aria-controls="nav-home" aria-selected="true"><img
                                    src="<?php echo e(asset('assets/vcl1.png')); ?>" alt="" style="width: 24px; height: 24px;">
                                M-Pesa</a>
                            

                            <a class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" href="#nav-contact" role="tab"
                                aria-controls="nav-contact" aria-selected="false"><i class="icon-paypal"></i> Paypal</a>
                        </div>
                    </nav>

                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                            aria-labelledby="nav-home-tab">
                            <div class="justify-content-center">
                                <hr>
                                <div id="wrap">
                                    
                                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('payment', ['music' => $music]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3343005034-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                                </div>
                            </div>
                        </div>

                        


                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                            <div class="text-center">
                                <h6 class="text-center"></h6>
                                <?php if($music->amount == 0): ?>
                                    <p> </p>
                                <?php else: ?>
                                    <div id="wrap">
                                        <form id="buyNowForm" action="<?php echo e($Paypal); ?>" method="post">
                                            <input type="hidden" name="cmd" value="_xclick">
                                            <input type="hidden" name="amount" value="<?php echo e($amount); ?>">
                                            <input type="hidden" name="currency_code" value="<?php echo e($currency); ?>">
                                            <input type="hidden" name="business" value="<?php echo e($PAYPAL_ID); ?>">
                                            <input type="hidden" name="custom" value="<?php echo e($userId); ?>">
                                            <input type="hidden" name="item_name" value="<?php echo e($music->title); ?>">
                                            <input type="hidden" name="item_number" value="<?php echo e($music->id); ?>">
                                            <input type="hidden" name="return" value="<?php echo e(route('success')); ?>">
                                            <input type="hidden" name="cancel_return" value="<?php echo e(url('cancel')); ?>">
                                            <input type="hidden" name="notify_url" value="<?php echo e(url('ipn')); ?>">
                                            <button type="submit" class="btn btn-outline-info btn-sm"
                                                title="secure online payment with paypal"><i class="fab fa-cc-paypal"></i>
                                                Buy
                                                $<?php echo e($amount); ?></button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <hr>
                <div id="wrapper">
                    <audio preload="auto" controls>
                        <source src="<?php echo e(asset("storage/demos/$music->demo")); ?>">
                    </audio>

                </div>

                <div class="dropup-center dropup">

                    <a class="btn btn-outline-info btn-sm" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        share to <i class="ti-sharethis"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <div class="d-flex justify-content-evenly">
                            <?php echo $shareButtons; ?>

                            <a href="#" class="" onclick="copyToClipboard()"><i class="fas fa-copy"></i></a>
                        </div>
                    </ul>
                </div>
                <hr>



            </div>
        </div>

    </div>


    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('comment-section', ['musicId' => $music->id]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3343005034-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
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
    <meta property="og:type" content="music.song">
<?php $__env->stopSection(); ?>


<?php $__env->startPush('aplayer'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/audioplayer.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('player'); ?>
    <script src="<?php echo e(asset('assets/js/audioplayer.js')); ?>"></script>
    <script>
        $(function() {
            $('audio').audioPlayer();
        });
    </script>
 <script>


    function copyToClipboard() {
        const url = '<?php echo e($url1); ?>'; // Replace with your actual URL
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

    
    <script>
        document.getElementById('buyNowForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission

            var form = this;
            var userId = document.getElementsByName('custom')[0].value;

            if (!userId) {
                var intendedUrl = '<?php echo e(route('msingle.slug', ['slug' => urlencode($music->slug)])); ?>';
                window.sessionStorage.setItem('intended_url', intendedUrl);

                // Open the modal instead of redirecting to login
                var modalElement = document.querySelector('.bs-example-modal-center');
                var bootstrapModal = new bootstrap.Modal(modalElement);
                bootstrapModal.show();
            } else {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(route('check-music-file')); ?>',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        musicId: '<?php echo e($music->id); ?>'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            form.submit(); // Submit the form if the file exists
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Music File Not found',
                        });
                    },
                });
            }
        });
    </script>

<?php $__env->stopPush(); ?>
<?php $__env->startPush('mpesa'); ?>
    
    <script>
        $(document).ready(function() {
            $('#paymentForm').submit(function(e) {
                e.preventDefault();

                let form = this;

                proceedWithPayment(form);
            });

            function proceedWithPayment(form) {
                Swal.fire({
                    title: 'Processing',
                    html: 'Please wait...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    },
                });

                $.ajax({
                    type: 'POST',
                    url: $(form).attr('action'),
                    data: $(form).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log('Payment response:', response); // Add logging here
                        Swal.close();

                        if (response.status === 'success') {
                            window.location.href = response.download_url;
                        } else {
                            Swal.fire({
                                icon: response.status,
                                title: response.status.charAt(0).toUpperCase() + response.status
                                    .slice(1),
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Payment error:', xhr, status, error); // Add logging here
                        Swal.close();

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to make the API request',
                        });
                    },
                });
            }
        });
    </script>



    <script>
        document.getElementById('showAlert').addEventListener('click', function() {
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
                                $('.info').html('Received Amount: ' + response
                                    .receivedAmount +
                                    '<br>From Number: ' + response.fromNumber);
                                $('.message').html(
                                    '<p class="text-success">Success it worked</p>');
                            } else {
                                $('.info').html('Invalid OTP');
                                $('.message').html(
                                    '<p class="text-danger">Enter Valid OTP</p>');
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/msingle.blade.php ENDPATH**/ ?>