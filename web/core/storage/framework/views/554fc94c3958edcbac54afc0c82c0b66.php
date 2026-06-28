<table class="table_activation">
    <thead>
        
    <tr>
        <th><?php echo e(__('ID')); ?></th>
        <th><?php echo e(__('Title')); ?></th>
        <th><?php echo e(__('Budget')); ?></th>
        <th><?php echo e(__('view')); ?></th>
    <th><?php echo e(__('Action')); ?></th>
    </tr>
    </thead>
    <tbody>
    <?php if($all_jobs->total() >=1): ?>
        <?php $__currentLoopData = $all_jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($job->id); ?></td>
                <td><?php echo e($job->title); ?></td>
                <td><?php echo e(float_amount_with_currency_symbol($job->budget)); ?></td>
                <td><?php echo e($job->view); ?></td>
                <td>
                    
                    <?php if (isset($component)) { $__componentOriginal8691a41893630c41896a3aaa81ae3cbf = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8691a41893630c41896a3aaa81ae3cbf = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.popup.restore-popup','data' => ['title' => __('Restore Service'),'url' => route('admin.job.restore',$job->id),'class' => 'cmnBtn btn_5 btn_bg_blue radius-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('popup.restore-popup'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Restore Service')),'url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.job.restore',$job->id)),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('cmnBtn btn_5 btn_bg_blue radius-5')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8691a41893630c41896a3aaa81ae3cbf)): ?>
<?php $attributes = $__attributesOriginal8691a41893630c41896a3aaa81ae3cbf; ?>
<?php unset($__attributesOriginal8691a41893630c41896a3aaa81ae3cbf); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8691a41893630c41896a3aaa81ae3cbf)): ?>
<?php $component = $__componentOriginal8691a41893630c41896a3aaa81ae3cbf; ?>
<?php unset($__componentOriginal8691a41893630c41896a3aaa81ae3cbf); ?>
<?php endif; ?>
                    <?php if(isset($isDemoMiddlewareIsEnabled)): ?>
                        <button class="btn btn-warning" onclick="alert('This is a demo website. You cannot delete permanently.');">
                            <?php echo e(__('Delete Permanently')); ?>

                        </button>  
                    <?php else: ?>  
                        <?php if (isset($component)) { $__componentOriginal1e6e26a797bbb09588e08319e6d7f310 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1e6e26a797bbb09588e08319e6d7f310 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.popup.delete','data' => ['title' => __('Delete Permanently'),'url' => route('admin.job.permanent.delete',$job->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('popup.delete'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Delete Permanently')),'url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.job.permanent.delete',$job->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1e6e26a797bbb09588e08319e6d7f310)): ?>
<?php $attributes = $__attributesOriginal1e6e26a797bbb09588e08319e6d7f310; ?>
<?php unset($__attributesOriginal1e6e26a797bbb09588e08319e6d7f310); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1e6e26a797bbb09588e08319e6d7f310)): ?>
<?php $component = $__componentOriginal1e6e26a797bbb09588e08319e6d7f310; ?>
<?php unset($__componentOriginal1e6e26a797bbb09588e08319e6d7f310); ?>
<?php endif; ?>           
                    <?php endif; ?>        
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php else: ?>
        <?php if (isset($component)) { $__componentOriginal299c0410dd55ce378949b38ffa493a39 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal299c0410dd55ce378949b38ffa493a39 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table.no-data-found','data' => ['colspan' => '5','class' => 'text-danger text-center py-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('table.no-data-found'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['colspan' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('5'),'class' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('text-danger text-center py-5')]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal299c0410dd55ce378949b38ffa493a39)): ?>
<?php $attributes = $__attributesOriginal299c0410dd55ce378949b38ffa493a39; ?>
<?php unset($__attributesOriginal299c0410dd55ce378949b38ffa493a39); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal299c0410dd55ce378949b38ffa493a39)): ?>
<?php $component = $__componentOriginal299c0410dd55ce378949b38ffa493a39; ?>
<?php unset($__componentOriginal299c0410dd55ce378949b38ffa493a39); ?>
<?php endif; ?>
    <?php endif; ?>
    </tbody>
</table>
<?php if (isset($component)) { $__componentOriginal0143df8887fb9686c5dbf1f1b0d7027f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0143df8887fb9686c5dbf1f1b0d7027f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.pagination.laravel-paginate','data' => ['allData' => $all_jobs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('pagination.laravel-paginate'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['allData' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($all_jobs)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0143df8887fb9686c5dbf1f1b0d7027f)): ?>
<?php $attributes = $__attributesOriginal0143df8887fb9686c5dbf1f1b0d7027f; ?>
<?php unset($__attributesOriginal0143df8887fb9686c5dbf1f1b0d7027f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0143df8887fb9686c5dbf1f1b0d7027f)): ?>
<?php $component = $__componentOriginal0143df8887fb9686c5dbf1f1b0d7027f; ?>
<?php unset($__componentOriginal0143df8887fb9686c5dbf1f1b0d7027f); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\Modules/JobPost\resources/views/backend/jobs/search-result-for-trash-jobs.blade.php ENDPATH**/ ?>