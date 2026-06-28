<div class="tab-pane fade step" id="location" role="tabpanel" aria-labelledby="location-tab">
    <div class="row">
        <div class="col-12">

            <div class="single-settings">
                <h5 class="input-title"> <?php echo e(__('Whats Included This Package')); ?> </h5>
                <div class="append-additional-includes">
                    <div class="single-dashboard-input what-include-element">
                        <div class="single-info-input margin-top-20">
                            <label><?php echo e(__('Title')); ?><span class="text-danger">*</span></label>
                            <input class="form-control" type="text" name="include_service_title[]" placeholder="<?php echo e(__('Service title')); ?>">
                        </div>
                        <div class="single-info-input margin-top-20">
                            <label><?php echo e(__('Description')); ?></label>
                            <textarea class="form-control" name="include_service_description[]" cols="20" rows="5" placeholder="<?php echo e(__('Description')); ?>"></textarea>
                        </div>
                    </div>
                </div>
                <div class="btn-wrapper margin-top-20">
                    <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_blue radius-5 add-what-includes"> <?php echo e(__('Add More')); ?> </a>
                </div>
            </div>


            <div class="single-settings margin-top-40">
                <h5 class="input-title"> <?php echo e(__('Whats Service Excludes')); ?> </h5>
                <div class="append-excludes">
                    <div class="single-dashboard-input excludes">
                        <div class="single-info-input margin-top-20">
                            <input class="form-control" type="text" name="excludes_title[]" placeholder="<?php echo e(__('Type Here')); ?>">
                        </div>
                        <div class="single-info-input margin-top-20">
                            <textarea class="form-control" name="excludes_description[]" cols="20" rows="5" placeholder="<?php echo e(__('Description')); ?>"></textarea>
                        </div>
                    </div>
                </div>
                <div class="btn-wrapper margin-top-20">
                    <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_blue radius-5 add-excludes"> <?php echo e(__('Add More')); ?> </a>
                </div>
            </div>


            <div class="single-settings margin-top-40">
                <h5 class="input-title"> <?php echo e(__('Add Addons Services')); ?> </h5>
                <div class="append-addons-services">
                    <div class="single-dashboard-input addons-services">
                        <div class="single-info-input margin-top-20">
                            <label><?php echo e(__('Title')); ?></label>
                            <input class="form-control" type="text" name="addons_service_title[]" placeholder="<?php echo e(__('Addon Service title')); ?>">
                        </div>
                        <div class="single-info-input margin-top-20">
                            <label><?php echo e(__('Price')); ?></label>
                            <input class="form-control numeric-value" type="number" step="0.01" name="addons_service_price[]" placeholder="<?php echo e(__('Add Price')); ?>">
                        </div>
                        <div class="single-info-input margin-top-20">
                            <textarea class="form-control" name="addons_service_description[]" cols="20" rows="5" placeholder="<?php echo e(__('Description')); ?>"></textarea>
                        </div>
                    </div>
                </div>
                <div class="btn-wrapper margin-top-20">
                    <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_blue radius-5 add-addons-services"> <?php echo e(__('Add More')); ?> </a>
                </div>
            </div>

            <div class="single-settings margin-top-40 faq_show_hide">
                <h5 class="input-title"> <?php echo e(__('Faqs')); ?> </h5>
                <div class="append-faqs">
                    <div class="single-dashboard-input faqs">
                        <div class="single-info-input margin-top-20">
                            <input class="form-control" type="text" name="faqs_title[]" placeholder="<?php echo e(__('Faq Title')); ?>">
                        </div>
                        <div class="single-info-input margin-top-20">
                            <textarea class="form-control" name="faqs_description[]" cols="20" rows="5" placeholder="<?php echo e(__('Faq Description')); ?>"></textarea>
                        </div>
                    </div>
                </div>
                <div class="btn-wrapper margin-top-20">
                    <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_blue radius-5 add-faqs"> <?php echo e(__('Add More')); ?> </a>
                </div>
            </div>


            <!-- start previous / next buttons -->
            <div  class="col-lg-12 mt-5">
                <div class="btn_wrapper d-flex justify-content-end gap-3">
                    <button class="cmnBtn btn_5 btn_bg_info radius-5" id="prevBtn" type="button"><?php echo e(__('Previous')); ?></button>
                    <button class="cmnBtn btn_5 btn_bg_success radius-5" id="submitBtn" type="submit"><?php echo e(__('Submit')); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/pages/services/admin/service-include.blade.php ENDPATH**/ ?>