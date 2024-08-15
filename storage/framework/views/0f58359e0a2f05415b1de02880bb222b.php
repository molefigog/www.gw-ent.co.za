<?php $__env->startSection('content'); ?>
<br>
<hr>
<div class="row">
    <?php $__currentLoopData = $albums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $album): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?php echo e($album->name); ?></h5>
                    <p class="card-text">Released on: <?php echo e($album->release_date); ?></p>
                </div>
                <div class="card-footer">
                    <iframe src="https://open.spotify.com/embed/album/<?php echo e($album->id); ?>" width="100%" height="80" frameborder="0" allowtransparency="true" allow="encrypted-media"></iframe>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/songs/index.blade.php ENDPATH**/ ?>