<div class="form-group">
    <label><?php echo e($label); ?></label>
    <label class="switch <?php echo e($class ?? ''); ?>">
        <input type="checkbox" name="<?php echo e($name); ?>" <?php if(isset($setValue)): ?> value="<?php echo e($setValue); ?>" <?php endif; ?> <?php if(!empty($value)): ?> checked <?php endif; ?>>
        <span class="slider onff"></span>
    </label>
    <?php if(isset($info)): ?>
        <small class="info-text d-block mt-2 <?php echo e($infoClass ?? ''); ?>"><?php echo e($info); ?></small>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/components/fields/switcher.blade.php ENDPATH**/ ?>