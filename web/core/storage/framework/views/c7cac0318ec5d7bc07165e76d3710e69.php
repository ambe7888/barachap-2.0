<script>
    (function ($) {
        "use strict";
        $(document).ready(function() {

            // add what new include
            $(".add-what-includes").on('click',function(){
                let  total_element = $(".what-include-element").length;
                let max = 15;
                if(total_element < max ){
                    $(".append-additional-includes").append(
                        '<div class="single-dashboard-input what-include-element">\
                            <div class="single-info-input margin-top-20">\
                                <label><?php echo e(__('Title')); ?> <span class="text-danger">*</span> </label>\
                                    <input class="form-control" type="text" name="include_service_title[]" placeholder="<?php echo e(__('Service title')); ?>">\
                                </div>\
                                <div class="single-info-input margin-top-20">\
                                    <label><?php echo e(__('Description')); ?></label>\
                                    <textarea class="form-control" name="include_service_description[]" cols="20" rows="5" placeholder="<?php echo e(__('Description')); ?>"></textarea>\
                                </div>\
                                <span class="btn btn-danger remove-include"><i class="las la-times"></i></span>\
                        </div>'
                    );
                }
            });


            // remove include service
            $(document).on('click', ".remove-include", function() {
                $(this).closest('.what-include-element').remove();
            });


            // add benifits
            $(document).on('click', ".add-excludes", function() {
                let  total_element = $(".excludes").length;
                let max = 5;
                if(total_element < max ){
                    $(".append-excludes").append(
                        '<div class="single-dashboard-input excludes">\
                            <div class="single-info-input margin-top-20">\
                                <input class="form-control" type="text" name="excludes_title[]" placeholder="<?php echo e(__('Type Here')); ?>">\
                                </div>\
                                <div class="single-info-input margin-top-20">\
                                    <textarea class="form-control" name="excludes_description[]" cols="20" rows="5" placeholder="<?php echo e(__('Description')); ?>"></textarea>\
                                </div>\
                                <span class="btn btn-danger remove-excludes"><i class="las la-times"></i></span>\
                            </div>');
                }
            });

            // remove benifits
            $(document).on('click', ".remove-excludes", function() {
                $(this).closest('.excludes').remove();
            });

            // add additional service
            $(document).on('click', ".add-addons-services", function() {
                let  total_element = $(".addons-services").length;
                let max = 5;
                if(total_element < max ){
                    $(".append-addons-services").append(
                        '<div class="single-dashboard-input addons-services">\
                            <div class="single-info-input margin-top-20">\
                                <label><?php echo e(__('Title')); ?></label>\
                                    <input class="form-control" type="text" name="addons_service_title[]" placeholder="<?php echo e(__('Addon Service title')); ?>">\
                                </div>\
                                <div class="single-info-input margin-top-20">\
                                    <label><?php echo e(__('Unit Price')); ?></label>\
                                    <input class="form-control numeric-value" type="text" name="addons_service_price[]" placeholder="<?php echo e(__('Add Price')); ?>">\
                                </div>\
                                <div class="single-info-input margin-top-20">\
                                    <textarea class="form-control" name="addons_service_description[]" cols="20" rows="5" placeholder="<?php echo e(__('Description')); ?>"></textarea>\
                                </div>\
                                <span class="btn btn-danger remove-service"><i class="las la-times"></i></span>\
                        </div>');
                }
            });

            // remove additional service
            $(document).on('click', ".remove-service", function() {
                $(this).closest('.addons-services').remove();
            })



            // add faqs
            $(".add-faqs").on('click',function(){
                let  total_element = $(".faqs").length;
                let max = 15;
                if(total_element < max ){
                    $(".append-faqs").append(
                        '<div class="single-dashboard-input faqs">\
                            <div class="single-info-input margin-top-20">\
                                <input class="form-control" type="text" name="faqs_title[]" placeholder="<?php echo e(__('Faq Title')); ?>">\
                                </div>\
                                <div class="single-info-input margin-top-20">\
                                    <textarea class="form-control" name="faqs_description[]" cols="20" rows="5" placeholder="<?php echo e(__('Faq Description')); ?>"></textarea>\
                                </div>\
                                <span class="btn btn-danger remove-faqs"><i class="las la-times"></i></span>\
                            </div>');
                }
            })

            // remove faqs
            $(document).on('click', ".remove-faqs", function() {
                $(this).closest('.faqs').remove();
            })

            //total price
            $(document).on("change", ".include-price", function() {
                var sum = 0;
                $(".include-price").each(function() {
                    if(isNaN($(this).val())){
                        alert('Please Enter Numeric Value only')
                    }else{
                        sum += +$(this).val();
                    }
                });
                $("#service_total_price").val(sum);
            });

            //include quantity
            $(document).on("change", ".numeric-value", function() {
                if(isNaN($(this).val())){
                    alert('Please Enter Numeric Value only')
                }
            });
        });
    })(jQuery)
</script>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/pages/services/admin/service-add-more-option-js.blade.php ENDPATH**/ ?>