<table class="DataTable_activation">
    <thead>
    <tr>
        <th><?php echo e(__('ID')); ?></th>
        <th><?php echo e(__('Title')); ?></th>
        <th><?php echo e(__('Budget')); ?></th>
        <th><?php echo e(__('view')); ?></th>
        <th><?php echo e(__('Date & Time')); ?></th>
        <th><?php echo e(__('Status')); ?></th>
        <th><?php echo e(__('Published Date')); ?></th>
        <th><?php echo e(__('Publishing Status')); ?></th>
        <th><?php echo e(__('Action')); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $all_jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($job->id); ?></td>
            <td><?php echo e($job->title); ?></td>
            <td><?php echo e(float_amount_with_currency_symbol($job->budget)); ?></td>
            <td><?php echo e($job->view); ?></td>
            <td>
                <div class="table_customer">
                    <div class="table_customer__flex">
                        <div class="table_customer__contents">
                            <h6 class="table_customer__title"><?php echo e(__('Date:')); ?> <?php echo e(\Carbon\Carbon::parse($job->date)->format('d M Y')); ?> </h6>
                            <h6 class="table_customer__title"><?php echo e(__('Time:')); ?> <?php echo e(\Carbon\Carbon::parse($job->time)->format('H:i:s')); ?></h6>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <?php if (isset($component)) { $__componentOriginal03379f522cfceba10901e2e1e89a2bd7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal03379f522cfceba10901e2e1e89a2bd7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.table.active-inactive','data' => ['status' => $job->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.table.active-inactive'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($job->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal03379f522cfceba10901e2e1e89a2bd7)): ?>
<?php $attributes = $__attributesOriginal03379f522cfceba10901e2e1e89a2bd7; ?>
<?php unset($__attributesOriginal03379f522cfceba10901e2e1e89a2bd7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal03379f522cfceba10901e2e1e89a2bd7)): ?>
<?php $component = $__componentOriginal03379f522cfceba10901e2e1e89a2bd7; ?>
<?php unset($__componentOriginal03379f522cfceba10901e2e1e89a2bd7); ?>
<?php endif; ?>
                <?php if (isset($component)) { $__componentOriginaled49183813b6264fe02b2283042511dd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaled49183813b6264fe02b2283042511dd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.table.status-change','data' => ['url' => route('admin.jobs.status',$job->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.table.status-change'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.jobs.status',$job->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaled49183813b6264fe02b2283042511dd)): ?>
<?php $attributes = $__attributesOriginaled49183813b6264fe02b2283042511dd; ?>
<?php unset($__attributesOriginaled49183813b6264fe02b2283042511dd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaled49183813b6264fe02b2283042511dd)): ?>
<?php $component = $__componentOriginaled49183813b6264fe02b2283042511dd; ?>
<?php unset($__componentOriginaled49183813b6264fe02b2283042511dd); ?>
<?php endif; ?>
            </td>
            <td>
                <?php echo e($job->published_at ? \Carbon\Carbon::parse($job->published_at)->format('F j, Y') : __('No date yet')); ?>

            </td>
            <td>
                <?php if($job->is_published === 1): ?>
                    <span class="alert alert-success"><?php echo e(__('Published')); ?></span>
                <?php else: ?>
                    <span class="alert alert-warning"><?php echo e(__('Unpublished')); ?></span>
                <?php endif; ?>
                 <span class="my-2"><?php if (isset($component)) { $__componentOriginal6154b1f241075f2d648e8b9bd5a15063 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6154b1f241075f2d648e8b9bd5a15063 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.admin-services-published-change','data' => ['url' => route('admin.job.published.status.change',$job->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.admin-services-published-change'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.job.published.status.change',$job->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6154b1f241075f2d648e8b9bd5a15063)): ?>
<?php $attributes = $__attributesOriginal6154b1f241075f2d648e8b9bd5a15063; ?>
<?php unset($__attributesOriginal6154b1f241075f2d648e8b9bd5a15063); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6154b1f241075f2d648e8b9bd5a15063)): ?>
<?php $component = $__componentOriginal6154b1f241075f2d648e8b9bd5a15063; ?>
<?php unset($__componentOriginal6154b1f241075f2d648e8b9bd5a15063); ?>
<?php endif; ?></span>
            </td>

            <td class="actions">
                <a class="cmnBtn btn_5 btn_bg_info radius-5" href="<?php echo e(route('admin.jobs.offers.all', $job->id)); ?>">
                    <?php echo e(__('Job Offers')); ?>

                </a>
                <?php if (isset($component)) { $__componentOriginal768f8f40d03d4d53d956d4ea52baca68 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768f8f40d03d4d53d956d4ea52baca68 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.view-icon','data' => ['url' => route('admin.jobs.details',$job->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon.view-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.jobs.details',$job->id))]); ?>
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
                <?php if (isset($component)) { $__componentOriginal7973b0ce98592c79f9209abd6e46a09b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7973b0ce98592c79f9209abd6e46a09b = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.popup.delete-popup','data' => ['url' => route('admin.jobs.delete',$job->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('popup.delete-popup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.jobs.delete',$job->id))]); ?>
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
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<div class="custom_pagination mt-5 d-flex justify-content-end">
    <?php echo e($all_jobs->links()); ?>

</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\Modules/JobPost\resources/views/backend/jobs/search-result.blade.php ENDPATH**/ ?>