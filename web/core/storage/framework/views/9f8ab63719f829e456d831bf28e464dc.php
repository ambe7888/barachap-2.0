<!-- State Edit Modal -->
<div class="modal fade" id="editCityModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel"><?php echo e(__('Edit City')); ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('admin.city.edit')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="city_id" id="city_id" value="">
                <div class="modal-body">
                    <?php if (isset($component)) { $__componentOriginal2497cd08ed4b80389f11a0f1101e9ba2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2497cd08ed4b80389f11a0f1101e9ba2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.text','data' => ['title' => __('City'),'type' => __('text'),'name' => 'edit_city','id' => 'edit_city','placeholder' => __('Enter city name')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('form.text'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('City')),'type' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('text')),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('edit_city'),'id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('edit_city'),'placeholder' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Enter city name'))]); ?>
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
                    <div class="single-input">
                        <label class="label-title mt-3"><?php echo e(__('Select States')); ?></label>
                        <select name="edit_state" id="edit_state" class="form__control radius-5 country_select22">
                            <?php $__currentLoopData = $all_states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($data->id); ?>"><?php echo e($data->state); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <?php if (isset($component)) { $__componentOriginale171feb651a2e3ceea9024202e75ec25 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale171feb651a2e3ceea9024202e75ec25 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.form.timezone','data' => ['title' => __('Select Timezone'),'name' => 'edit_timezone','id' => 'edit_timezone','class' => 'form-control timezone_select2_edit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('form.timezone'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Select Timezone')),'name' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('edit_timezone'),'id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('edit_timezone'),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('form-control timezone_select2_edit')]); ?>
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
                <div class="modal-footer">
                    <button type="button" class="cmnBtn btn_5 btn_bg_danger radius-5" data-bs-dismiss="modal"><?php echo e(__('Close')); ?></button>
                    <button type="submit" class="cmnBtn btn_5 btn_bg_blue radius-5 edit_state"><?php echo e(__('Update')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\Modules/CountryManage\resources/views/city/edit-modal.blade.php ENDPATH**/ ?>