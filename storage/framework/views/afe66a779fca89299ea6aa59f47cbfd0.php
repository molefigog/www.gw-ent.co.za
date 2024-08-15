

<?php $__env->startSection('content'); ?>
    <div class="container mt-5">

        <form id="complete-order" action="<?php echo e(route('getDl')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <div class="text-center">
                <div class="info"></div>
                <div class="message"></div>
            </div>

            <div class="input-group mb-3">
                <input type="text" class="form-control" name="otp" id="otp" placeholder="Enter OTP"
                    aria-label="Enter OTP" aria-describedby="button-addon2" required>
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2">
                    download</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('mpesa'); ?>
<script>
    $(document).ready(function() {
        var delayTimer;

        $('#otp').on('input', function() {
            var otp = $(this).val();

            clearTimeout(delayTimer);

            delayTimer = setTimeout(function() {
                $.ajax({
                    type: 'POST',
                    url: '<?php echo e(route('otpdownloads')); ?>',
                    data: {
                        _token: $('input[name="_token"]').val(),
                        otp: otp
                    },
                    success: function(response) {
                        if (response.success) {
                            $('.info').html('Title: ' + response
                                .otp +
                                '<br>From Number: ' + response.fromNumber);
                            $('.message').html(
                                '<p class="text-success">Available</p>');
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

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/otpdownloads.blade.php ENDPATH**/ ?>