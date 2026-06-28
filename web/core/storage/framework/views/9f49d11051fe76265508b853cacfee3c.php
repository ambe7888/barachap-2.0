<!-- Country Edit Modal -->
<div class="modal fade" id="editStateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal_xl__fixed">
        <div class="popup_contents modal-content">
            <div class="popup_contents__header">
                <div class="popup_contents__header__flex">
                    <div class="popup_contents__header__contents">
                        <h2 class="popup_contents__header__title"><?php echo e(__('Edit State')); ?></h2>
                    </div>
                    <div class="popup_contents__header__close" data-bs-dismiss="modal">
                        <span class="popup_contents__close popup_close"> <i class="fas fa-times"></i> </span>
                    </div>
                </div>
            </div>
            <div class="popup_contents__body">
                <form action="<?php echo e(route('admin.state.edit')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="state_id" id="state_id" value="">
                    <div class="form__input__single">
                        <label for="title" class="label-title"><?php echo e(__('State')); ?></label>
                        <input type="text" name="edit_state" id="edit_state" value="<?php echo e(old('state')); ?>" placeholder="<?php echo e(__('Enter state')); ?>" class="form-control" >
                    </div>
                    <div class="popup_contents__footer flex_btn justify-content-end profile-border-top">
                        <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5 update_country"><?php echo e(__('Submit')); ?></button>
                        <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_danger radius-5" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\Modules/CountryManage\resources/views/state/edit-modal.blade.php ENDPATH**/ ?>