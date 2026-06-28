<?php if(session()->has('msg')): ?>
    <div class="alert alert-<?php echo e(session('type')); ?>">
        <?php echo purify_html(session('msg')); ?>

    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\barachap\Installable-file\Admin Panel\core\resources\views/components/msg/response-message.blade.php ENDPATH**/ ?>