<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="author" content="gog">
    <meta name="MobileOptimized" content="320">
    <meta property="og:site_name" content="gw-ent" />
    <!-- App favicon_url -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <?php echo $__env->yieldContent('head'); ?>

    <!-- Bootstrap Css -->
    <link href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>" id="bootstrap-style" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="<?php echo e(asset('assets/css/icons.min.css')); ?>" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="<?php echo e(asset('assets/css/app.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <?php echo $__env->yieldPushContent('pondcss'); ?>
    <?php if($setting && $setting->favicon_url): ?>
        <link rel="icon" type="image/png" href="<?php echo e($setting->favicon_url); ?>">
    <?php endif; ?>
    <?php if($setting && $setting->favicon_url): ?>
        <link rel="apple-touch-icon" href="<?php echo e($setting->favicon_url); ?>">
    <?php endif; ?>
    <?php if($setting && $setting->favicon_url): ?>
        <meta name="msapplication-TileImage" content="<?php echo e($setting->favicon_url); ?>">
    <?php endif; ?>
    <?php if($setting && $setting->favicon_url): ?>
        <link rel="shortcut icon" href="<?php echo e($setting->favicon_url); ?>">
    <?php endif; ?>
    <?php echo $__env->yieldPushContent('aplayer'); ?>
    <style>
        .dropdown-menu {
            --bs-dropdown-bg: #36394cf2;
        }
        #page-topbar {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    z-index: 1002;
    background-color: #353e53;
}
.custom-spinner {
    width: 1rem;
    height: 1rem;
    border-width: 0.15em;
}
    </style>
      <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>



<body data-topbar="dark" data-bs-theme="dark"data-layout="horizontal">
    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid text-center">
                    <?php echo $__env->yieldContent('content'); ?>
                    <?php echo $__env->make('layouts.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php echo $__env->yieldContent('audio'); ?>

            <footer class="footer">
                <div class="container-fluid">
                    <p class="text center">Select Songs By Artist</p>
                    <ul class="nav">
                        <?php $__currentLoopData = $artists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $artist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $musicCount = DB::table('music_user')
                                    ->where('user_id', $artist->id)
                                    ->count();
                            ?>

                            <?php if($musicCount > 0): ?>
                                <li class="nav-item text-uppercase">
                                    <div class="p-2">
                                        <a class="btn btn-outline-info btn-sm text-truncate"
                                            href="<?php echo e(url('artist', str_replace(' ', '_', $artist->name))); ?>"
                                            style="max-width: 150px;">
                                            <?php echo e($artist->name); ?>

                                        </a>
                                    </div>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <div class="row">
                        <div class="col-12">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script> All rights reserved | <?php echo e($setting->site); ?> <span
                                class="d-none d-sm-inline-block"> |
                                Help <a href="mailto:<?php echo e($admin->email); ?>" target="_blank">Desk</a></span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    <div class="right-bar">
        <div data-simplebar class="h-100">
            <div class="rightbar-title px-3 py-4">
                <a href="javascript:void(0);" class="right-bar-toggle float-end">
                    <i class="mdi mdi-close noti-icon"></i>
                </a>
                <h5 class="m-0">Settings</h5>
            </div>
            <!-- Settings -->
            <hr class="mt-0" />

            <div class="p-4">

                <div class="form-check form-switch mb-3">
                    <input type="checkbox" class="form-check-input theme-choice" id="light-mode-switch" checked />
                    <label class="form-check-label" for="light-mode-switch">Light Mode</label>
                </div>
                <div class="form-check form-switch mb-3">
                    <input type="checkbox" class="form-check-input theme-choice" id="dark-mode-switch"
                        data-bsStyle="<?php echo e(asset('assets/css/bootstrap-dark.min.css')); ?>"
                        data-appStyle="<?php echo e(asset('assets/css/app-dark.min.css')); ?>" />
                    <label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
                </div>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('search-bar');

$__html = app('livewire')->mount($__name, $__params, 'lw-3733701469-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            </div>

        </div> <!-- end slimscroll-menu-->
    </div>




    <script src="<?php echo e(asset('assets/libs/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/metismenu/metisMenu.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/simplebar/simplebar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/libs/node-waves/waves.min.js')); ?>"></script>
    <!-- Peity chart-->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="<?php echo e(asset('assets/libs/peity/jquery.peity.min.js')); ?>"></script>
    <script>
        const BootstrapMin = "<?php echo e(asset('assets/css/bootstrap.min.css')); ?>";
        const AppCss = "<?php echo e(asset('assets/css/app.min.css')); ?>";
        const BootstrapRtl = "<?php echo e(asset('assets/css/bootstrap-rtl.min.css')); ?>";
        const AppRtl = "<?php echo e(asset('assets/css/app-rtl.min.css')); ?>";
    </script>
    <script src="<?php echo e(asset('assets/js/app.js')); ?>"></script>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

    <?php echo $__env->yieldPushContent('player'); ?>

    <?php echo $__env->yieldPushContent('modal'); ?>
    <?php echo $__env->yieldPushContent('pal'); ?>
    <?php echo $__env->yieldPushContent('upload_status'); ?>
    <?php echo $__env->yieldPushContent('updated'); ?>
    <?php echo $__env->yieldPushContent('mpesa'); ?>
    
    <script>
        window.addEventListener('success2', event => {

            showNotification2('downloading....');
        })

        function showNotification2(message) {
            var notyf = new Notyf({
                duration: 9000, // Duration in milliseconds
                dismissible: true,
                position: {
                    x: 'center', // Position on the x-axis
                    y: 'top', // Position on the y-axis
                }
            });
            notyf.success(message);
        }
</script>
<script>
        window.addEventListener('paymentFailed', event => {

            showNotification1('Failed to make the API request!');
        })

        function showNotification1(message) {
            var notyf = new Notyf({
                duration: 9000, // Duration in milliseconds
                dismissible: true,
                position: {
                    x: 'center', // Position on the x-axis
                    y: 'top', // Position on the y-axis
                }
            });
            notyf.error(message);
        }
</script>


</body>

</html>
<?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/layouts/app.blade.php ENDPATH**/ ?>