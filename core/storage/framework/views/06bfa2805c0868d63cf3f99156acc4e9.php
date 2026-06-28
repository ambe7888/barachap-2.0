<table class="dataTablesExample">
    <thead>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dynamic-page-bulk-delete')): ?>
    <th class="no-sort">
        <div class="mark-all-checkbox">
            <input type="checkbox" class="all-checkbox">
        </div>
    </th>
    <?php endif; ?>
    <th><?php echo e(__('ID')); ?></th>
    <th><?php echo e(__('Title')); ?></th>
    <th><?php echo e(__('Date')); ?></th>
    <th><?php echo e(__('Status')); ?></th>
    <th><?php echo e(__('Action')); ?></th>
    </thead>
    <tbody>
    <?php $__currentLoopData = $all_pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dynamic-page-bulk-delete')): ?>
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
            <td><?php echo e($data->title ?? __('Untitled')); ?>

                <?php if($data->id == get_static_option('home_page')): ?>
                    <strong class="text-primary">-<?php echo e(__('Home Page')); ?></strong>
                <?php endif; ?>
                <?php if($data->id == get_static_option('blog_page')): ?>
                    <strong class="text-info">-<?php echo e(__('Blog Page')); ?></strong>
                <?php endif; ?>
            </td>
            <td><?php echo e($data->created_at->diffForHumans()); ?></td>
            <td><?php if (isset($component)) { $__componentOriginald32980183f909ae865e35d62f3139e1d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald32980183f909ae865e35d62f3139e1d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.status-span','data' => ['status' => $data->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.status-span'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald32980183f909ae865e35d62f3139e1d)): ?>
<?php $attributes = $__attributesOriginald32980183f909ae865e35d62f3139e1d; ?>
<?php unset($__attributesOriginald32980183f909ae865e35d62f3139e1d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald32980183f909ae865e35d62f3139e1d)): ?>
<?php $component = $__componentOriginald32980183f909ae865e35d62f3139e1d; ?>
<?php unset($__componentOriginald32980183f909ae865e35d62f3139e1d); ?>
<?php endif; ?></td>
            <td>
                <?php if($data->id != get_static_option('home_page') && $data->id != get_static_option('blog_page')): ?>
                    <?php if (isset($component)) { $__componentOriginal0d086c76bb3005df8f0c13f4bb5ba4b8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal0d086c76bb3005df8f0c13f4bb5ba4b8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.languages.delete-popover-all-lang','data' => ['url' => route('admin.page.delete.lang.all',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('languages.delete-popover-all-lang'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.page.delete.lang.all',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal0d086c76bb3005df8f0c13f4bb5ba4b8)): ?>
<?php $attributes = $__attributesOriginal0d086c76bb3005df8f0c13f4bb5ba4b8; ?>
<?php unset($__attributesOriginal0d086c76bb3005df8f0c13f4bb5ba4b8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal0d086c76bb3005df8f0c13f4bb5ba4b8)): ?>
<?php $component = $__componentOriginal0d086c76bb3005df8f0c13f4bb5ba4b8; ?>
<?php unset($__componentOriginal0d086c76bb3005df8f0c13f4bb5ba4b8); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginal768f8f40d03d4d53d956d4ea52baca68 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768f8f40d03d4d53d956d4ea52baca68 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.view-icon','data' => ['url' => route('frontend.dynamic.page',$data->slug)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon.view-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('frontend.dynamic.page',$data->slug))]); ?>
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
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dynamic-page-edit')): ?>
                    <?php if (isset($component)) { $__componentOriginal95a57cf8b726360d66c2d339617390c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal95a57cf8b726360d66c2d339617390c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.edit-icon','data' => ['url' => route('admin.page.edit',$data->id).'?lang='.$default_lang]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon.edit-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.page.edit',$data->id).'?lang='.$default_lang)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal95a57cf8b726360d66c2d339617390c3)): ?>
<?php $attributes = $__attributesOriginal95a57cf8b726360d66c2d339617390c3; ?>
<?php unset($__attributesOriginal95a57cf8b726360d66c2d339617390c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal95a57cf8b726360d66c2d339617390c3)): ?>
<?php $component = $__componentOriginal95a57cf8b726360d66c2d339617390c3; ?>
<?php unset($__componentOriginal95a57cf8b726360d66c2d339617390c3); ?>
<?php endif; ?>
                    <?php if(!empty($data->page_builder_status)): ?>
                        <a href="<?php echo e(route('admin.dynamic.page.builder',['type' =>'dynamic-page','id' => $data->id])); ?>" target="_blank" class="cmnBtn btn_5 btn_bg_secondary radius-5"><?php echo e(__('Open Page Builder')); ?></a>
                    <?php endif; ?>
               <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<div class="custom_pagination mt-5 d-flex justify-content-end">
    <?php echo e($all_pages->links()); ?>

</div>
<?php /**PATH C:\xampp\htdocs\barachap\core\resources\views/backend/pages/page/search-page.blade.php ENDPATH**/ ?>