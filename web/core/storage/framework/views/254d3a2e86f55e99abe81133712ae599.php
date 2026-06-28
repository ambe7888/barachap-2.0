<table class="dataTablesExample">
    <thead>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-service-bulk-delete')): ?>
        <th class="no-sort">
            <div class="mark-all-checkbox">
                <input type="checkbox" class="all-checkbox">
            </div>
        </th>
    <?php endif; ?>
    <th><?php echo e(__('ID')); ?></th>
    <th><?php echo e(__('Image')); ?></th>
    <th><?php echo e(__('Title')); ?></th>
    <th><?php echo e(__('Category')); ?></th>
    <th><?php echo e(__('Price')); ?></th>
    <th><?php echo e(__('Crate Date')); ?></th>
    <th><?php echo e(__('Publishing Status')); ?></th>
    <th>
        <?php echo e(__('Status')); ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-service-approved')): ?>
        <span><?php if (isset($component)) { $__componentOriginalecfb555f0580a45a0f52a5378e37cc45 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalecfb555f0580a45a0f52a5378e37cc45 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.all-status-change','data' => ['url' => route('admin.service.user.all.approved')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.all-status-change'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.service.user.all.approved'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalecfb555f0580a45a0f52a5378e37cc45)): ?>
<?php $attributes = $__attributesOriginalecfb555f0580a45a0f52a5378e37cc45; ?>
<?php unset($__attributesOriginalecfb555f0580a45a0f52a5378e37cc45); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalecfb555f0580a45a0f52a5378e37cc45)): ?>
<?php $component = $__componentOriginalecfb555f0580a45a0f52a5378e37cc45; ?>
<?php unset($__componentOriginalecfb555f0580a45a0f52a5378e37cc45); ?>
<?php endif; ?></span>
        <?php endif; ?>
    </th>
    <th><?php echo e(__('Action')); ?></th>
    </thead>
    <tbody>
    <?php $__currentLoopData = $all_services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-service-bulk-delete')): ?>
                <td>
                    <?php if (isset($component)) { $__componentOriginal2f3acf431e4ef3aaad9c59423cc34c19 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2f3acf431e4ef3aaad9c59423cc34c19 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.bulk-action.bulk-delete-checkbox','data' => ['id' => $data->id]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('bulk-action.bulk-delete-checkbox'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data->id)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2f3acf431e4ef3aaad9c59423cc34c19)): ?>
<?php $attributes = $__attributesOriginal2f3acf431e4ef3aaad9c59423cc34c19; ?>
<?php unset($__attributesOriginal2f3acf431e4ef3aaad9c59423cc34c19); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2f3acf431e4ef3aaad9c59423cc34c19)): ?>
<?php $component = $__componentOriginal2f3acf431e4ef3aaad9c59423cc34c19; ?>
<?php unset($__componentOriginal2f3acf431e4ef3aaad9c59423cc34c19); ?>
<?php endif; ?>
                </td>
            <?php endif; ?>
            <td><?php echo e($data->id); ?></td>
            <td> <?php echo render_image_markup_by_attachment_id($data->image,'','thumb'); ?></td>
            <td><?php echo e($data->title); ?></td>
            <td><?php echo e(optional($data->category)->name); ?></td>

            <td>
                <?php if($data->discount_price > 0): ?>
                    <span class="discount-price"> <?php echo e(float_amount_with_currency_symbol($data->discount_price)); ?> </span>
                <?php else: ?>
                    <?php echo e(float_amount_with_currency_symbol($data->price)); ?>

                <?php endif; ?>
            </td>

            <td>
                <strong class="subCap"><?php echo e($data->created_at->diffForHumans()); ?></strong>
            </td>
            <!--published -->
            <td>
                <?php if($data->is_published === 1): ?>
                    <span class="alert alert-success custom_status_style"><?php echo e(__('Published')); ?></span>
                <?php else: ?>
                    <span class="alert alert-warning custom_status_style"><?php echo e(__('Unpublished')); ?></span>
                <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-service-published-status-change')): ?>
                <span class="my-2">
                    <?php if (isset($component)) { $__componentOriginal6154b1f241075f2d648e8b9bd5a15063 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6154b1f241075f2d648e8b9bd5a15063 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.admin-services-published-change','data' => ['url' => route('admin.service.published.status.change',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.admin-services-published-change'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.service.published.status.change',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6154b1f241075f2d648e8b9bd5a15063)): ?>
<?php $attributes = $__attributesOriginal6154b1f241075f2d648e8b9bd5a15063; ?>
<?php unset($__attributesOriginal6154b1f241075f2d648e8b9bd5a15063); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6154b1f241075f2d648e8b9bd5a15063)): ?>
<?php $component = $__componentOriginal6154b1f241075f2d648e8b9bd5a15063; ?>
<?php unset($__componentOriginal6154b1f241075f2d648e8b9bd5a15063); ?>
<?php endif; ?>
                </span>
                <?php endif; ?>

            </td>

            <!--status -->
            <td>
                <?php if($data->status==1): ?>
                    <span class="alert alert-success"><?php echo e(__('Approved')); ?></span>
                <?php else: ?>
                    <span class="alert alert-warning"><?php echo e(__('Pending')); ?></span>
                <?php endif; ?>

               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-service-status-change')): ?>
                <span class="my-2"><?php if (isset($component)) { $__componentOriginal086f7010becd4d657cdb856682d3d79f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal086f7010becd4d657cdb856682d3d79f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.status-change','data' => ['url' => route('admin.service.status.change',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.status-change'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.service.status.change',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal086f7010becd4d657cdb856682d3d79f)): ?>
<?php $attributes = $__attributesOriginal086f7010becd4d657cdb856682d3d79f; ?>
<?php unset($__attributesOriginal086f7010becd4d657cdb856682d3d79f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal086f7010becd4d657cdb856682d3d79f)): ?>
<?php $component = $__componentOriginal086f7010becd4d657cdb856682d3d79f; ?>
<?php unset($__componentOriginal086f7010becd4d657cdb856682d3d79f); ?>
<?php endif; ?></span>
               <?php endif; ?>

            </td>
            <!--Action -->
            <td class="p-1">
               
                <div class="multiple_action_buttons_new d-flex gap-2 flex-wrap">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-service-status-change')): ?>
                        <?php if($data->is_featured === 1): ?>
                             <?php if (isset($component)) { $__componentOriginal86b128640ac38601cbc41eade75f6714 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal86b128640ac38601cbc41eade75f6714 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.make-features-added','data' => ['url' => route('admin.service.make.featured',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.make-features-added'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.service.make.featured',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal86b128640ac38601cbc41eade75f6714)): ?>
<?php $attributes = $__attributesOriginal86b128640ac38601cbc41eade75f6714; ?>
<?php unset($__attributesOriginal86b128640ac38601cbc41eade75f6714); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal86b128640ac38601cbc41eade75f6714)): ?>
<?php $component = $__componentOriginal86b128640ac38601cbc41eade75f6714; ?>
<?php unset($__componentOriginal86b128640ac38601cbc41eade75f6714); ?>
<?php endif; ?>
                        <?php else: ?>
                             <?php if (isset($component)) { $__componentOriginale6729b33dd8f96b134ec9f27218e68e5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale6729b33dd8f96b134ec9f27218e68e5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.make-features','data' => ['url' => route('admin.service.make.featured',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.make-features'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.service.make.featured',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale6729b33dd8f96b134ec9f27218e68e5)): ?>
<?php $attributes = $__attributesOriginale6729b33dd8f96b134ec9f27218e68e5; ?>
<?php unset($__attributesOriginale6729b33dd8f96b134ec9f27218e68e5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale6729b33dd8f96b134ec9f27218e68e5)): ?>
<?php $component = $__componentOriginale6729b33dd8f96b134ec9f27218e68e5; ?>
<?php unset($__componentOriginale6729b33dd8f96b134ec9f27218e68e5); ?>
<?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal768f8f40d03d4d53d956d4ea52baca68 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768f8f40d03d4d53d956d4ea52baca68 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.view-icon','data' => ['url' => route('admin.service.details',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon.view-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.service.details',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal768f8f40d03d4d53d956d4ea52baca68)): ?>
<?php $attributes = $__attributesOriginal768f8f40d03d4d53d956d4ea52baca68; ?>
<?php unset($__attributesOriginal768f8f40d03d4d53d956d4ea52baca68); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal768f8f40d03d4d53d956d4ea52baca68)): ?>
<?php $component = $__componentOriginal768f8f40d03d4d53d956d4ea52baca68; ?>
<?php unset($__componentOriginal768f8f40d03d4d53d956d4ea52baca68); ?>
<?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-service-delete')): ?>
                        <?php if (isset($component)) { $__componentOriginal7973b0ce98592c79f9209abd6e46a09b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7973b0ce98592c79f9209abd6e46a09b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.popup.delete-popup','data' => ['url' => route('admin.service.delete',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('popup.delete-popup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.service.delete',$data->id))]); ?>
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
                    <?php endif; ?>
                </div>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<div class="custom_pagination mt-5 d-flex justify-content-end">
    <?php echo e($all_services->links()); ?>

</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/pages/services/search-service.blade.php ENDPATH**/ ?>