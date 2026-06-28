<!-- Country Edit Modal -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal_xl__fixed">
        <div class="popup_contents modal-content">
            <div class="popup_contents__header">
                <div class="popup_contents__header__flex">
                    <div class="popup_contents__header__contents">
                        <h2 class="popup_contents__header__title"><?php echo e(__('Add New City')); ?></h2>
                    </div>
                    <div class="popup_contents__header__close" data-bs-dismiss="modal">
                        <span class="popup_contents__close popup_close"> <i class="fas fa-times"></i> </span>
                    </div>
                </div>
            </div>
            <div class="popup_contents__body">
                <form action="<?php echo e(route('admin.city.all')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form__input__single">
                        <?php if (isset($component)) { $__componentOriginal2497cd08ed4b80389f11a0f1101e9ba2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2497cd08ed4b80389f11a0f1101e9ba2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.text','data' => ['title' => __('City'),'type' => __('text'),'name' => 'city','id' => 'city','placeholder' => __('Enter city name')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('form.text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('City')),'type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('text')),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('city'),'id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('city'),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Enter city name'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2497cd08ed4b80389f11a0f1101e9ba2)): ?>
<?php $attributes = $__attributesOriginal2497cd08ed4b80389f11a0f1101e9ba2; ?>
<?php unset($__attributesOriginal2497cd08ed4b80389f11a0f1101e9ba2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2497cd08ed4b80389f11a0f1101e9ba2)): ?>
<?php $component = $__componentOriginal2497cd08ed4b80389f11a0f1101e9ba2; ?>
<?php unset($__componentOriginal2497cd08ed4b80389f11a0f1101e9ba2); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginalc58ff44542cd86b35422d3687e2397b6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc58ff44542cd86b35422d3687e2397b6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.select2-country-dropdown','data' => ['title' => __('Select State'),'name' => 'state','id' => 'state','allData' => $all_states]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('form.select2-country-dropdown'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Select State')),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('state'),'id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('state'),'allData' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($all_states)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc58ff44542cd86b35422d3687e2397b6)): ?>
<?php $attributes = $__attributesOriginalc58ff44542cd86b35422d3687e2397b6; ?>
<?php unset($__attributesOriginalc58ff44542cd86b35422d3687e2397b6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc58ff44542cd86b35422d3687e2397b6)): ?>
<?php $component = $__componentOriginalc58ff44542cd86b35422d3687e2397b6; ?>
<?php unset($__componentOriginalc58ff44542cd86b35422d3687e2397b6); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal1f61620c990fe8cc258019f51dea44d4 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1f61620c990fe8cc258019f51dea44d4 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.active-inactive','data' => ['title' => __('Select Status'),'info' => __('If you select inactive the services will off for the state')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('form.active-inactive'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Select Status')),'info' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('If you select inactive the services will off for the state'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1f61620c990fe8cc258019f51dea44d4)): ?>
<?php $attributes = $__attributesOriginal1f61620c990fe8cc258019f51dea44d4; ?>
<?php unset($__attributesOriginal1f61620c990fe8cc258019f51dea44d4); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1f61620c990fe8cc258019f51dea44d4)): ?>
<?php $component = $__componentOriginal1f61620c990fe8cc258019f51dea44d4; ?>
<?php unset($__componentOriginal1f61620c990fe8cc258019f51dea44d4); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale171feb651a2e3ceea9024202e75ec25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale171feb651a2e3ceea9024202e75ec25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.timezone','data' => ['title' => __('Select Timezone'),'name' => 'timezone','id' => 'timezone','class' => 'form-control timezone_select2_add']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('form.timezone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Select Timezone')),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('timezone'),'id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('timezone'),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('form-control timezone_select2_add')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale171feb651a2e3ceea9024202e75ec25)): ?>
<?php $attributes = $__attributesOriginale171feb651a2e3ceea9024202e75ec25; ?>
<?php unset($__attributesOriginale171feb651a2e3ceea9024202e75ec25); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale171feb651a2e3ceea9024202e75ec25)): ?>
<?php $component = $__componentOriginale171feb651a2e3ceea9024202e75ec25; ?>
<?php unset($__componentOriginale171feb651a2e3ceea9024202e75ec25); ?>
<?php endif; ?>
                    </div>
                    <div class="popup_contents__footer flex_btn justify-content-end profile-border-top">
                        <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5"><?php echo e(__('Submit')); ?></button>
                        <a href="javascript:void(0)" class="cmnBtn btn_5 btn_bg_danger radius-5" data-bs-dismiss="modal"><?php echo e(__('Cancel')); ?></a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\Modules/CountryManage\resources/views/city/add-modal.blade.php ENDPATH**/ ?>