<div class="container mt-4">
    <h5><?php echo e($comments->count()); ?> Comments</h5>

    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div wire:key="<?php echo e($comment->id); ?>" class="card mb-3">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        
                        
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-0"><?php echo e($comment->user->name ?? 'Anonymous'); ?></h6>
                                <small class="text-muted"><?php echo e($comment->created_at->diffForHumans()); ?></small>
                            </div>
                            <!--[if BLOCK]><![endif]--><?php if(Auth::user() && $comment->user_id == Auth::user()->id): ?>
                                <div>
                                    
                                    
                                    <button class="btn btn-outline-danger btn-sm" wire:click="deleteComment(<?php echo e($comment->id); ?>)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <p class="mb-0"><?php echo e($comment->content); ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

    <?php if(Auth::user()): ?>
        <div class="card mt-3">
            <div class="card-body">
                <div class="d-flex align-items-start">
                    <div class="me-3">
                        <img style="width: 40px; height: 40px;" src="<?php echo e(asset('storage/' . Auth::user()->avatar)); ?>" alt="" class="rounded-circle" />
                    </div>
                    <div class="flex-grow-1">
                        <form wire:submit.prevent="addComment">
                            <div class="mb-3">
                                <textarea wire:model="newComment" id="comment-text-area" class="form-control" rows="4" placeholder="Add a comment"></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary btn-sm">Comment</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center">Login to comment</button>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div>
<?php /**PATH /home/gw-ent/htdocs/www.gw-ent.co.za/resources/views/livewire/comment-section.blade.php ENDPATH**/ ?>