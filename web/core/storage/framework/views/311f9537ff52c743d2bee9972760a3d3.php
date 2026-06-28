<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Payment Gateway')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <?php if (isset($component)) { $__componentOriginalbc1bcd20222d67be5eb46ea1d22a74fa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbc1bcd20222d67be5eb46ea1d22a74fa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.css','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('media.css'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbc1bcd20222d67be5eb46ea1d22a74fa)): ?>
<?php $attributes = $__attributesOriginalbc1bcd20222d67be5eb46ea1d22a74fa; ?>
<?php unset($__attributesOriginalbc1bcd20222d67be5eb46ea1d22a74fa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbc1bcd20222d67be5eb46ea1d22a74fa)): ?>
<?php $component = $__componentOriginalbc1bcd20222d67be5eb46ea1d22a74fa; ?>
<?php unset($__componentOriginalbc1bcd20222d67be5eb46ea1d22a74fa); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12 mt-0">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <h2 class="dashboard__card__header__title mb-3"><?php echo e(__('Payment Gateway')); ?></h2>
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
                <form action="<?php echo e(route('admin.payment.settings.update')); ?>" method="POST" enctype="multipart/form-data">
                     <?php echo csrf_field(); ?>
                        <?php if(!empty($gateway->description)): ?>
                            <div class="payment-notice alert alert-warning">
                                <p><?php echo e($gateway->description); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($cod) && $cod): ?>
                            <input type="hidden" name="gateway_name" value="cash_on_delivery">
                           <?php if (isset($component)) { $__componentOriginal724296dd5f04fa5f1379f3e209f82dd5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal724296dd5f04fa5f1379f3e209f82dd5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.fields.switcher','data' => ['value' => ''.e(get_static_option('cash_on_delivery')).'','name' => 'cash_on_delivery','label' => ''.e(__('Enable Cash On Delivery')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('fields.switcher'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['value' => ''.e(get_static_option('cash_on_delivery')).'','name' => 'cash_on_delivery','label' => ''.e(__('Enable Cash On Delivery')).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal724296dd5f04fa5f1379f3e209f82dd5)): ?>
<?php $attributes = $__attributesOriginal724296dd5f04fa5f1379f3e209f82dd5; ?>
<?php unset($__attributesOriginal724296dd5f04fa5f1379f3e209f82dd5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal724296dd5f04fa5f1379f3e209f82dd5)): ?>
<?php $component = $__componentOriginal724296dd5f04fa5f1379f3e209f82dd5; ?>
<?php unset($__componentOriginal724296dd5f04fa5f1379f3e209f82dd5); ?>
<?php endif; ?>
                        <?php else: ?>
                            <input type="hidden" name="gateway_name" value="<?php echo e($gateway->name); ?>">
                            <div class="form__input__single">
                                <label  for="instamojo_gateway"><strong><?php echo e(__('Enable/Disable '. ucfirst($gateway->name))); ?></strong></label>
                                <input type="hidden" name="<?php echo e($gateway->name); ?>_gateway">
                                <div class="switch_box style_7">
                                    <input type="checkbox" name="<?php echo e($gateway->name); ?>_gateway"  <?php if($gateway->status === 1 ): ?> checked <?php endif; ?>>
                                    <label></label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="instamojo_test_mode"><strong><?php echo e(__("Enable Test Mode"." ".ucfirst($gateway->name))); ?></strong></label>
                                <div class="switch_box style_7">
                                    <input type="checkbox" name="<?php echo e($gateway->name); ?>_test_mode"  <?php if($gateway->test_mode === 1 ): ?> checked <?php endif; ?>>
                                    <label></label>
                                </div>
                            </div>

                            <?php if (isset($component)) { $__componentOriginal7cd49cd4c5e01bbab6a7701cb52759f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7cd49cd4c5e01bbab6a7701cb52759f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.edit-media-upload-image','data' => ['label' => ''.e(__(ucfirst($gateway->name).' '.'Logo')).'','name' => ''.e($gateway->name.'_logo').'','value' => $gateway->image]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('media.edit-media-upload-image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['label' => ''.e(__(ucfirst($gateway->name).' '.'Logo')).'','name' => ''.e($gateway->name.'_logo').'','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($gateway->image)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7cd49cd4c5e01bbab6a7701cb52759f5)): ?>
<?php $attributes = $__attributesOriginal7cd49cd4c5e01bbab6a7701cb52759f5; ?>
<?php unset($__attributesOriginal7cd49cd4c5e01bbab6a7701cb52759f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7cd49cd4c5e01bbab6a7701cb52759f5)): ?>
<?php $component = $__componentOriginal7cd49cd4c5e01bbab6a7701cb52759f5; ?>
<?php unset($__componentOriginal7cd49cd4c5e01bbab6a7701cb52759f5); ?>
<?php endif; ?>
                            <?php
                                $credentials = !empty($gateway->credentials) ? json_decode($gateway->credentials) : [];
                            ?>
                            <?php $__currentLoopData = $credentials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cre_name =>  $cre_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group">
                                    <label><?php echo e(str_replace('_', ' ' , ucwords($cre_name))); ?></label>
                                    <input type="text" name="<?php echo e($gateway->name.'_'.$cre_name); ?>"
                                           value="<?php echo e($cre_value); ?>"
                                           class="form-control">
                                    <?php if($gateway->name == 'paytabs'): ?>
                                        <?php if($cre_name == 'region'): ?>
                                            <small class="text-secondary" style="font-size: 13px">GLOBAL,
                                                ARE, EGY, SAU,
                                                OMN, JOR</small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                     <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5 mt-3"><?php echo e(__('Update Changes')); ?></button>
                </form>
            </div>
        </div>
   </div>
    <?php if (isset($component)) { $__componentOriginal0a0c44ec0e77c6e781a03c2fda86fc75 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0a0c44ec0e77c6e781a03c2fda86fc75 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.markup','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('media.markup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0a0c44ec0e77c6e781a03c2fda86fc75)): ?>
<?php $attributes = $__attributesOriginal0a0c44ec0e77c6e781a03c2fda86fc75; ?>
<?php unset($__attributesOriginal0a0c44ec0e77c6e781a03c2fda86fc75); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0a0c44ec0e77c6e781a03c2fda86fc75)): ?>
<?php $component = $__componentOriginal0a0c44ec0e77c6e781a03c2fda86fc75; ?>
<?php unset($__componentOriginal0a0c44ec0e77c6e781a03c2fda86fc75); ?>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <?php if (isset($component)) { $__componentOriginal9c9e2f22010721f1a8a11abf87b15b5e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9c9e2f22010721f1a8a11abf87b15b5e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.media.js','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('media.js'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9c9e2f22010721f1a8a11abf87b15b5e)): ?>
<?php $attributes = $__attributesOriginal9c9e2f22010721f1a8a11abf87b15b5e; ?>
<?php unset($__attributesOriginal9c9e2f22010721f1a8a11abf87b15b5e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9c9e2f22010721f1a8a11abf87b15b5e)): ?>
<?php $component = $__componentOriginal9c9e2f22010721f1a8a11abf87b15b5e; ?>
<?php unset($__componentOriginal9c9e2f22010721f1a8a11abf87b15b5e); ?>
<?php endif; ?>
    <script>
        (function ($) {
            "use strict";
            $(document).ready(function ($) {
                $('.summernote').summernote({
                    height: 200,
                    codemirror: {
                        theme: 'monokai'
                    },
                    callbacks: {
                        onChange: function (contents, $editable) {
                            $(this).prev('input').val(contents);
                        }
                    }
                });
                if ($('.summernote').length > 0) {
                    $('.summernote').each(function (index, value) {
                        $(this).summernote('code', $(this).data('content'));
                    });
                }
            });
        })(jQuery);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\barachap\web\core\Modules/PaymentGateways\resources/views/backend/payment_settings.blade.php ENDPATH**/ ?>