<a tabindex="0" class="cmnBtn btn_5 btn_bg_danger radius-5 btnIcon swal_delete_all_lang_data_button">
 <?php if(isset($type)): ?> <i class="las la-trash"></i> <?php else: ?> <i class="las la-trash"></i> <?php endif; ?>
</a>
<form method='post' action='<?php echo e($url); ?>' class="d-none">
<input type='hidden' name='_token' value='<?php echo e(csrf_token()); ?>'>
<br>
<button type="submit" class="swal_form_submit_btn d-none"></button>
 </form>
<?php /**PATH C:\xampp\htdocs\barachap\core\resources\views/components/languages/delete-popover-all-lang.blade.php ENDPATH**/ ?>