<div class="table_wrapper custom_dataTable">
    <table class="dataTablesExample">
        <thead>
        <th><?php echo e(__('ID')); ?></th>
        <th><?php echo e(__('Identity')); ?></th>
        <th><?php echo e(__('Image')); ?></th>
        <th><?php echo e(__('Type')); ?></th>
        <th><?php echo e(__('Status')); ?></th>
        <th><?php echo e(__('Action')); ?></th>
        </thead>
        <tbody>
        <?php if($sliders->count() > 0): ?>
            <?php $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($data->id); ?></td>
                    <td><?php echo e($data->identity); ?></td>

                    <td><?php echo render_image_markup_by_attachment_id($data->image,'','thumb'); ?></td>

                    <td>
                        <?php if($data->type == 'service'): ?>
                            <a href="javascript:void(0)" class="alert alert-info"><?php echo e(__("Service")); ?></a>
                        <?php elseif($data->type == 'category'): ?>
                            <a href="javascript:void(0)" class="alert alert-info"><?php echo e(__("Category")); ?></a>
                        <?php elseif($data->type == 'offer'): ?>
                            <a href="javascript:void(0)" class="alert alert-info"><?php echo e(__("Offer")); ?></a>    
                        <?php endif; ?>
                    </td>

                    <td>
                        <?php if($data->status == 1): ?>
                            <a href="javascript:void(0)" class="alert alert-success"><?php echo e(__("Publish")); ?></a>
                        <?php elseif($data->status === 0): ?>
                            <a href="javascript:void(0)" class="alert alert-warning"><?php echo e(__("Unpublished")); ?></a>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (isset($component)) { $__componentOriginal7973b0ce98592c79f9209abd6e46a09b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7973b0ce98592c79f9209abd6e46a09b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.popup.delete-popup','data' => ['url' => route('admin.slider.delete',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('popup.delete-popup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.slider.delete',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7973b0ce98592c79f9209abd6e46a09b)): ?>
<?php $attributes = $__attributesOriginal7973b0ce98592c79f9209abd6e46a09b; ?>
<?php unset($__attributesOriginal7973b0ce98592c79f9209abd6e46a09b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7973b0ce98592c79f9209abd6e46a09b)): ?>
<?php $component = $__componentOriginal7973b0ce98592c79f9209abd6e46a09b; ?>
<?php unset($__componentOriginal7973b0ce98592c79f9209abd6e46a09b); ?>
<?php endif; ?>
                        <a href="<?php echo e(route('admin.slider.edit', $data->id)); ?>" class="cmnBtn btn_5 btn_bg_info radius-5"><?php echo e(__('Edit')); ?></a>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/pages/slider/all-slider.blade.php ENDPATH**/ ?>