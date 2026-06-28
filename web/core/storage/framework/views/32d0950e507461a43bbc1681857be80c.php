<?php $__env->startSection('title', __('Order Cancellation Policy')); ?>
<?php $__env->startSection('content'); ?>
    <div class="dashboard__body">
        <div class="row">
            <div class="col-lg-6">
                <div class="customMarkup__single">
                    <div class="customMarkup__single__item">
                        <div class="customMarkup__single__item__flex">
                            <h4 class="customMarkup__single__title"><?php echo e(__('Order Cancellation Policy')); ?></h4>
                        </div>
                        <?php if (isset($component)) { $__componentOriginal4bb59b834d778ff0cb72af5a473e2885 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4bb59b834d778ff0cb72af5a473e2885 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.validation.error','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('validation.error'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4bb59b834d778ff0cb72af5a473e2885)): ?>
<?php $attributes = $__attributesOriginal4bb59b834d778ff0cb72af5a473e2885; ?>
<?php unset($__attributesOriginal4bb59b834d778ff0cb72af5a473e2885); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4bb59b834d778ff0cb72af5a473e2885)): ?>
<?php $component = $__componentOriginal4bb59b834d778ff0cb72af5a473e2885; ?>
<?php unset($__componentOriginal4bb59b834d778ff0cb72af5a473e2885); ?>
<?php endif; ?>
                        <div class="customMarkup__single__inner mt-4">
                            <form action="<?php echo e(route('admin.order.cancellation-policy')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="form__input__single">
                                    <label class="form__input__single__label"><?php echo e(__('Fine Type')); ?> <span class="text-danger"></label>
                                    <select id="fine_type" name="fine_type" class="form-control">
                                        <option <?php echo e($cancellationPolicy?->fine_type == 'flat' ? 'selected' : ''); ?> value="flat"> <?php echo e(__('Flat')); ?> </option>
                                        <option <?php echo e($cancellationPolicy?->fine_type == 'percentage' ? 'selected' : ''); ?> value="percentage"> <?php echo e(__('Percentage')); ?> </option>
                                    </select>
                                </div>
                                <div class="form__input__single">
                                    <label class="form__input__single__label"><?php echo e(__('Amount')); ?> <span class="text-danger">*</span></label>
                                    <input type="number" class="form__control radius-5" name="amount" id="amount" value="<?php echo e($cancellationPolicy?->amount); ?>" placeholder="<?php echo e(__('Add Amount')); ?>" step="0.01">
                                </div>

                                <div class="form__input__single">
                                    <label class="form__input__single__label"><?php echo e(__('Available Type')); ?> <span class="text-danger"></label>
                                    <select id="available_type" name="available_type" class="form-control">
                                        <option <?php echo e($cancellationPolicy?->available_type == 'always' ? 'selected' : ''); ?> value="always"> <?php echo e(__('Always')); ?> </option>
                                        <option <?php echo e($cancellationPolicy?->available_type == 'certain_time' ? 'selected' : ''); ?> value="certain_time"> <?php echo e(__('Certain Time')); ?> </option>
                                    </select>
                                </div>

                                <div class="form__input__single d-none" id="div_time_in_min">
                                    <label class="form__input__single__label"><?php echo e(__('Time')); ?> <span class="text-danger">*</span></label>
                                    <input type="number" class="form__control radius-5 " name="time_in_min" id="time_in_min" value="<?php echo e($cancellationPolicy?->time_in_min); ?>" placeholder="<?php echo e(__('Add Time in minute')); ?>" step="0.01">
                                </div>
                                 <!-- Description -->
                                <div class="form__input__single mt-3">
                                    <label class="form__input__single__label"><?php echo e(__('Description')); ?> <span class="text-danger"></label>
                                    <div class="input-form input-form2">
                                        <textarea class="textarea--form" name="description" placeholder="<?php echo e(__('Type Description')); ?>" rows="8" cols="8"><?php echo e($cancellationPolicy?->description); ?></textarea>
                                    </div>
                                </div>
                                
                               
                                <div class="btn_wrapper mt-4">
                                    <button type="submit" id="update" class="cmnBtn btn_5 btn_bg_blue radius-5 update_info"><?php echo e(__('Update Setting')); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script src="<?php echo e(asset('assets/backend/js/select2.min.js')); ?>"></script>    
    <script type="text/javascript">
        (function(){
            "use strict";
            $(document).ready(function(){

                     let available_types = $("#available_type").val();
                     if(available_types == 'certain_time'){
                         $('#div_time_in_min').removeClass("d-none");
                     }
                     else
                     {
                            $('#div_time_in_min').addClass("d-none");
                     }    
                    //sub order status change
                    $(document).on('click', '#available_type', function () {
                        let value = $(this).val();
                        if(value == 'certain_time'){
                            $('#div_time_in_min').removeClass("d-none");

                        }else
                        {
                            $('#div_time_in_min').addClass("d-none");
                        }
                    });  
            });
         })(jQuery);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/pages/orders/order_cancellation_policy/add.blade.php ENDPATH**/ ?>