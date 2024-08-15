


<div class="col-sm-6 col-md-4 col-xl-3">
    

    <div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="POST" action="<?php echo e(route('login')); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="input-group has-validation mb-3">
                            <span class="input-group-text"><i class="ion ion-md-mail"></i></span>
                            <div class="form-floating">
                              <input type="email"  name="login" class="form-control" id="floatingInputGroup2" value="<?php echo e(old('email')); ?>" placeholder="Email" required autocomplete="login" autofocus>
                              <label for="floatingInputGroup2">Email address</label>
                            </div>
                            <?php $__errorArgs = ['login'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($message); ?></strong>
                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="input-group has-validation mb-3">
                            <span class="input-group-text"><i class="ion ion-ios-lock"></i></span>
                            <div class="form-floating">
                              <input type="password" name="password" class="form-control" id="password2" placeholder="Email" required autocomplete="login" autofocus>
                              <label for="floatingInputGroup2">Password</label>
                            </div>
                            <span class="input-group-text"><i id="togglePassword1" class="ion ion-md-eye"></i></span>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback">
                                <strong><?php echo e($message); ?></strong>
                            </div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Remember Me checkbox -->
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="basic_checkbox_1"
                                <?php echo e(old('remember') ? 'checked' : ''); ?>>
                            
                            <label class="form-check-label" for="rememberMe">Remember Me</label>
                        </div>

                        <div class="row mb-3">
                            <div class="col text-end">
                                <?php if(Route::has('password.request')): ?>
                                    <a class="text-muted" href="<?php echo e(route('password.request')); ?>">
                                        <i class="mdi mdi-lock"></i> <?php echo e(__('Forgot Your Password?')); ?>

                                    </a>
                                <?php endif; ?>
                            </div>
                            <div class="col text-end">
                                <span>or</span>
                                <a href="<?php echo e(url('/admin/register')); ?>">sign up for an account</a>
                            </div>
                        </div>

                        <!-- Login button -->
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <hr>
                    <a href="<?php echo e(route('google.redirect')); ?>" class="btn btn-primary btn-sm text-center"><i class="fab fa-google"></i>  Login with Google </a>
                    
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>

<?php if(auth()->guard()->check()): ?>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('cashout');

$__html = app('livewire')->mount($__name, $__params, 'lw-2629361830-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<?php endif; ?>
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Register</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('register2');

$__html = app('livewire')->mount($__name, $__params, 'lw-2629361830-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>
            <div class="modal-footer">


            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('modal'); ?>
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            var passwordInput = document.getElementById('password2');
            var icon = document.getElementById('togglePassword');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('ion ion-md-eye');
                icon.classList.add('ion ion-md-eye-off');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('ion ion-md-eye-off');
                icon.classList.add('on ion-md-eye');
            }
        });
    </script>
    <script>
        document.getElementById('togglePassword1').addEventListener('click', function() {
            var passwordInput = document.getElementById('password2');
            var icon = document.getElementById('togglePassword1');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('ion ion-md-eye');
                icon.classList.add('ion ion-md-eye-off');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('ion ion-md-eye-off');
                icon.classList.add('on ion-md-eye');
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/layouts/modal.blade.php ENDPATH**/ ?>