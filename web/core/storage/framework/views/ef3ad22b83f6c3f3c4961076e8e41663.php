<?php if(session()->has('msg')): ?>
    <div class="mt-3 alert alert_margin alert_<?php echo e(session('type')); ?> alert_dismissible fade show" role="alert">
        <?php echo purify_html(session('msg')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/components/msg/success.blade.php ENDPATH**/ ?>