<div>
    <div class="app-search">
        <div class="position-relative">
            <input wire:model.live="search" type="text" class="form-control" placeholder="Search...">
            <span class="fa fa-search"></span>
        </div>
    </div>

    <!--[if BLOCK]><![endif]--><?php if(sizeof($songs) > 0): ?>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 show" aria-labelledby="page-header-notifications-dropdown" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(-20px, 72px);" data-popper-placement="bottom-end">
        <div class="p-3">
            <div class="row align-items-center">
                <div class="col">
                    
                </div>
            </div>
        </div>
        <div data-simplebar="init" style="max-height: 230px;"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: -17px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: auto; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px;">
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $songs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $song): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('msingle.slug', ['slug' => urlencode($song->slug)])); ?>" class="text-reset notification-item">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-xs">
                            
                            <img src="<?php echo e(asset("storage/$song->image")); ?>" class="rounded-circle"
                                            alt="<?php echo e($song->image); ?>" style="width: 40px; height: 40px;" />
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1"><?php echo e($song->artist); ?></h6>
                        <div class="font-size-12 text-muted">
                            <p class="mb-1"><?php echo e($song->title); ?></p>
                        </div>
                    </div>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
        </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 407px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: block; height: 129px;"></div></div></div>
        <div class="p-2 border-top">
            <div class="d-grid">
                <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                    View all
                </a>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

</div>
<?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/livewire/search-bar.blade.php ENDPATH**/ ?>