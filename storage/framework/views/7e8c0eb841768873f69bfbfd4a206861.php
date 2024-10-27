<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>

    <meta charset="utf-8">
    <meta name="author" content="gog">
    <meta name="MobileOptimized" content="320">
    <meta property="og:site_name" content="gw-ent" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="canonical" href="<?php echo e(config('app.url')); ?>">
    <?php echo $__env->yieldContent('head'); ?>

    <!-- Bootstrap Css -->
    <link href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>" id="bootstrap-style" rel="stylesheet" type="text/css">
    <!-- Icons Css -->
    <link href="<?php echo e(asset('assets/css/icons.min.css')); ?>" rel="stylesheet" type="text/css">
    <!-- App Css-->
    <link href="<?php echo e(asset('assets/css/app.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css">
    <!-- Scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
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
    </style>
<?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>


</head>



<body data-topbar="dark"data-layout="horizontal">
    <!-- Begin page -->
    <div id="layout-wrapper">

        <?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="main-content">


            <hr>

            <div class="page-content">
                <div class="container-fluid text-center">
                    <div class="d-block d-sm-none">
                        <?php echo $__env->yieldContent('search'); ?>
                       </div>

                    <?php echo e($slot); ?>

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
                            </script><?php echo e($setting->site); ?><span class="d-none d-sm-inline-block"> | <i
                                    class="mdi mdi-heart text-danger"></i> Help <a href="mailto:<?php echo e($admin->email); ?>"
                                    target="_blank">Desk</a></span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- Right Sidebar -->
    

    <?php echo $__env->yieldPushContent('modals'); ?>

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
    
    <?php echo $__env->yieldPushContent('player'); ?>

    <?php echo $__env->yieldPushContent('modal'); ?>
    <?php echo $__env->yieldPushContent('pal'); ?>
    <?php echo $__env->yieldPushContent('upload_status'); ?>
    <?php echo $__env->yieldPushContent('updated'); ?>
    <?php echo $__env->yieldPushContent('mpesa'); ?>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>

</html>
<?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/layouts/master1.blade.php ENDPATH**/ ?>