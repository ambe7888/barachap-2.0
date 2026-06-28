<div  class="tab-pane fade step active show" id="listing-info" role="tabpanel" aria-labelledby="listing-info-tab">
    <div class="row">
        <div class="col-lg-8">
            <!-- Title -->
            <div class="form__input__single">
                <label class="form__input__single__label"><?php echo e(__('Title')); ?> <span class="text-danger">*</span></label>
                <input type="text" class="form__control radius-5" name="title" id="title" value="<?php echo e(old('title')); ?>" placeholder="<?php echo e(__('Add title')); ?>">
            </div>

            <div class="form__input__single mt-2">
                <div class="input-form input-form2 permalink_label">
                    <label for="title" class="form__input__single__label text-dark"> <?php echo e(__('Permalink')); ?>  <span class="text-danger">*</span>  </label>
                    <span id="slug_show" class="display-inline"></span>
                    <span id="slug_edit" class="display-inline d-inline">
                    <button class="btn btn-warning btn-sm slug_edit_button">  <i class="las la-edit"></i> </button>
                    <input class="listing_slug form__control radius-5" name="slug" value="<?php echo e(old('slug')); ?>" id="slug" style="display: none" type="text">
                    <button class="btn btn-info btn-sm slug_update_button mt-2" style="display: none"><?php echo e(__('Update')); ?></button>
               </span>
                </div>
            </div>

            <div class="d-flex justify-content-between gap-3 flex-wrap mt-3">
                <div class="form__input__single">
                    <label class="form__input__single__label"><?php echo e(__('Category')); ?>  <span class="text-danger">*</span> </label>
                    <select name="category_id" id="category" class="select-itms select2_activation">
                        <option value=""><?php echo e(__('Select Category')); ?></option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form__input__single" id="sub_category">
                    <label for="subcategory" class="form__input__single__label"> <?php echo e(__('Sub Category')); ?> </label>
                    <select  name="sub_category_id" id="subcategory" class="subcategory select2_activation">
                        <option value=""><?php echo e(__('Select Sub Category')); ?></option>
                    </select>
                </div>
                <div class="form__input__single child_category_wrapper">
                    <label for="child_category" class="form__input__single__label"> <?php echo e(__('Child Category')); ?> </label>
                    <select  name="child_category_id" id="child_category" class="select2_activation">
                        <option value=""><?php echo e(__('Select Child Category')); ?></option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div class="form__input__single mt-3">
                <label class="form__input__single__label"><?php echo e(__('Description')); ?> <span class="text-danger">*</span> <span class="text-danger"><?php echo e(__('minimum 150 characters.')); ?></span> </label>
                <div class="input-form input-form2">
                    <textarea class="textarea--form" name="description" placeholder="<?php echo e(__('Type Description')); ?>" rows="8" cols="8"><?php echo e(old('description')); ?></textarea>
                </div>
            </div>

            <div class="d-flex">
                <!-- video url -->
                <div class="form__input__single">
                    <label class="form__input__single__label"><?php echo e(__('Video Url')); ?> </label>
                    <div class="input-form input-form2">
                        <input type="text" class="form__control radius-5" name="video_url" id="video_url" value="<?php echo e(old('video_url')); ?>" placeholder="<?php echo e(__('youtube url')); ?>">
                    </div>
                    <small class="text-danger video_url_design"><?php echo e(__('Example:')); ?> https://www.youtube.com/watch?v=IcM8_Llgxf4&t=1s </small>
                </div>

                <!-- featured services -->
                <div class="form__input__single mt-4 mx-3">
                    <div class="checkBox">
                        <label class="is_featured form__input__single__label d-flex gap-2">
                            <input class="checkBox__input effectBorder" type="checkbox" name="is_featured" id="is_featured" value="">
                            <?php echo e(__('Is Featured')); ?>

                        </label>
                    </div>
                </div>
            </div>
            
            <div class="form__input__single mt-3">
                <input type="checkbox" class="form-check-input" id="choose_staff" name="disable_staff">
                <label class="form-check-label" for="choose_staff">Client can choose staff</label>
            </div>
                
           
            <div class="form__input__single mt-3 d-none" id="choose_staff_option">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="any_staff" id="any_staff1" value="option1" checked>
                    <label class="form-check-label" for="any_staff">
                        Client can choose allocated staff only
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="any_staff" id="any_staff2" value="option2" checked>
                    <label class="form-check-label" for="any_staff">
                        Client can choose all staff
                    </label>
                </div>
            </div>
            <div class="form__input__single d-none" id="select_staff">
                <label for="mySelect" class="form__input__single__label"><?php echo e(__('Select Staff')); ?></label>
                <select name="staffs[]" id="mySelect" multiple="multiple" style="width: 300px;">
                    <option value=""><?php echo e(__('Select Service')); ?></option>
                    <?php $__currentLoopData = $staffs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $staff): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>        
                        <option value="<?php echo e($staff->id); ?>"><?php echo e($staff->first_name); ?> <?php echo e($staff->last_name); ?></option>       
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>     
            </div>
        </div>

        <!--2nd step -->
        <div class="col-lg-4">
            <!-- Price -->
            <div class="col-lg-12 col-md-12 mt-4">
                <div class="form__input__single position-relative">
                    <label class="infoTitle"><?php echo e(__('Price')); ?> <span class="text-danger">*</span></label>
                    <div class="input-form input-form2">
                        <input type="number" class="form__control radius-5" name="price" id="price" value="<?php echo e(old('price')); ?>" placeholder="<?php echo e(__('0.00')); ?>">
                    </div>
                </div>
            </div>

            <!-- Discount Price -->
            <div class="col-lg-12 col-md-12 mt-4">
                <div class="form__input__single position-relative">
                    <label class="infoTitle"><?php echo e(__('Discount Price')); ?> <span class="text-danger">*</span></label>
                    <div class="input-form input-form2">
                        <input type="number" class="form__control radius-5" name="discount_price" id="discount_price" value="<?php echo e(old('discount_price')); ?>" placeholder="<?php echo e(__('0.00')); ?>">
                    </div>
                </div>
            </div>

            <!-- Service Unit -->
            <div class="col-lg-12 col-md-12 mt-4">
                <div class="form__input__single position-relative">
                    <label class="infoTitle"><?php echo e(__('Unit')); ?></label>
                    <div class="input-form input-form2">
                        <input type="text" class="form__control radius-5" name="unit" id="unit" value="<?php echo e(old('unit')); ?>" placeholder="<?php echo e(__('e.g., kg, gm, m, L,hr, km/h, m²')); ?>">
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 mt-4">
                
            </div>


            <div class="col-lg-12 mt-3">
                <div class="upload-img">
                    <div class="media-upload-btn-wrapper">
                        <div class="img-wrap">
                            <img src="<?php echo e(asset('assets/frontend/img/gallery/single-image-upload.png')); ?>" alt="images" class="w-100">
                        </div>
                        <input type="hidden" name="service_image">
                        <button type="button" class="btn btn-info media_upload_form_btn"
                                data-btntitle="<?php echo e(__('Select Image')); ?>"
                                data-modaltitle="<?php echo e(__('Upload Image')); ?>"
                                data-bs-toggle="modal"
                                data-bs-target="#media_upload_modal">
                            <?php echo e(__('Upload Main Image')); ?>

                        </button>
                        <small><?php echo e(__('image format: jpg,jpeg,png,gif,webp')); ?></small> <br>
                        <small><?php echo e(__('recommended size 810x450')); ?></small>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 mt-3">
                <div class="upload-img">
                    <div class="media-upload-btn-wrapper">
                        <div class="img-wrap">
                            <img src="<?php echo e(asset('assets/frontend/img/gallery/uploadeImg.png')); ?>" alt="images" class="w-100">
                        </div>
                        <input type="hidden" name="gallery_images">
                        <button type="button" class="btn btn-info media_upload_form_btn"
                                data-btntitle="<?php echo e(__('Select Image')); ?>"
                                data-modaltitle="<?php echo e(__('Upload Image')); ?>"
                                data-mulitple="true"
                                data-bs-toggle="modal"
                                data-bs-target="#media_upload_modal">
                            <?php echo e(__('Upload Gallery Images')); ?>

                        </button>
                        <small><?php echo e(__('image format: jpg,jpeg,png,gif,webp')); ?></small> <br>
                        <small><?php echo e(__('recommended size 810x450')); ?></small>
                    </div>
                </div>
            </div>

            <!-- start previous / next buttons -->
            <div  class="col-lg-12 mt-5">
                <div class="btn_wrapper d-flex justify-content-end gap-3">
                    <button class="cmnBtn btn_5 btn_bg_blue radius-5" id="nextBtn" type="button"><?php echo e(__('Next')); ?></button>
                </div>
            </div>
        </div>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/pages/services/admin/service-general-info.blade.php ENDPATH**/ ?>