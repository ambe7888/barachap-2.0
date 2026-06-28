<div class="col-xl-3 col-lg-3">
    <div class="dashboard__card bg__white padding-20 radius-10">
        <h2 class="dashboard__card__header__title mb-3"><?php echo e(__('Add New Slider')); ?></h2>
        <form action="<?php echo e(route('admin.slider.add')); ?>" method="POST" class="new_language_form">
            <?php echo csrf_field(); ?>
            <div class="form__input__flex">
                <div class="form-group">
                    <label for="image"><?php echo e(__('Upload Background Image')); ?></label>
                    <div class="media-upload-btn-wrapper">
                        <div class="img-wrap"></div>
                        <input type="hidden" name="image">
                        <button type="button" class="btn btn-info media_upload_form_btn"
                                data-btntitle="<?php echo e(__('Select Image')); ?>"
                                data-modaltitle="<?php echo e(__('Upload Image')); ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#media_upload_modal">
                            <?php echo e(__('Upload Slider Image')); ?>

                        </button>
                    </div>
                </div>

                <div class="form__input__single">
                    <label for="direction" class="form__input__single__label"><?php echo e(__('Type')); ?></label>
                    <select name="type" id="type" class="form__control radius-5">
                        <option value=""><?php echo e(__('Select Type')); ?></option>
                        <option value="service"><?php echo e(__('Service')); ?></option>
                        <option value="category"><?php echo e(__("Category")); ?></option>
                        <option value="offer"><?php echo e(__("Offer")); ?></option>
                    </select>
                </div>

                <!-- Service Section -->
                <div class="form__input__single" id="service-section">
                    <label for="identity_service_id" class="form__input__single__label"><?php echo e(__('Service')); ?></label>
                    <select name="identity_service_id" id="identity_service_id" class="form__control radius-5">
                        <option value=""><?php echo e(__('Select Service')); ?></option>
                        <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($service->id); ?>"><?php echo e($service->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Category Section -->
                <div class="form__input__single" id="category-section">
                    <label for="identity_category_id" class="form__input__single__label"><?php echo e(__('Category')); ?></label>
                    <select name="identity_category_id" id="identity_category_id" class="form__control radius-5">
                        <option value=""><?php echo e(__('Select Category')); ?></option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"> <?php echo e($category->name); ?> </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form__input__single" id="offer-section">
                    <label for="identity_offer_id" class="form__input__single__label"><?php echo e(__('Offer')); ?></label>
                    <select name="identity_offer_id" id="identity_offer_id" class="form__control radius-5">
                        <option value=""><?php echo e(__('Select Offer')); ?></option>
                        <?php $__currentLoopData = $offers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($offer->id); ?>"> <?php echo e($offer->title); ?> </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form__input__single">
                    <label for="status" class="form__input__single__label"><?php echo e(__('Status')); ?></label>
                    <select name="status" id="status" class="form__control radius-5">
                        <option value="1"><?php echo e(__('Publish')); ?></option>
                        <option value="0"><?php echo e(__("Unpublished")); ?></option>
                    </select>
                </div>
            </div>
            <div class="btn_wrapper mt-4">
                <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5"><?php echo e(__('Submit')); ?></button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/pages/slider/add-slider-new.blade.php ENDPATH**/ ?>