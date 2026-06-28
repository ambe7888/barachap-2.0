<table class="dataTablesExample">
    <thead>
    <th><?php echo e(__('ID')); ?></th>
    <th><?php echo e(__('Order Type')); ?></th>
    <th><?php echo e(__('Client Info')); ?></th>
    <th><?php echo e(__('Invoice Number')); ?></th>
    <th><?php echo e(__('Amount Details')); ?></th>
    <th><?php echo e(__('Payment Gateway')); ?></th>
    <th><?php echo e(__('Payment Status')); ?></th>
    <th><?php echo e(__('Order Status')); ?></th>
    <th><?php echo e(__('Crate Date')); ?></th>
    <th><?php echo e(__('Action')); ?></th>
    </thead>
    <tbody>
    <?php $__currentLoopData = $all_orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($data->id); ?></td>
            <td>
                <?php if(!empty($data->suborders)): ?>
                    <?php if($data->suborders[0]->job_post_id !== null): ?>
                        <span class="status_btn in_progress"><?php echo e(__('Job')); ?></span>
                    <?php else: ?>
                        <span class="status_btn completed"><?php echo e(__('Service')); ?></span>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <td>
                <div class="table_customer">
                    <div class="table_customer__flex">
                        <div class="table_customer__contents">
                            <h6 class="table_customer__title"><?php echo e(__('Name:')); ?> <?php echo e($data->client?->fullname); ?> </h6>
                            <h6 class="table_customer__title"><?php echo e(__('Email:')); ?> <?php echo e($data->client?->email); ?></h6>
                        </div>
                    </div>
                </div>
            </td>
            <td><?php echo e($data->invoice_number); ?></td>
            <td>
                <div class="table_customer">
                    <div class="table_customer__flex">
                        <div class="table_customer__contents">
                            <h6 class="table_customer__title"><?php echo e(__('Sub Total:')); ?> <?php echo e(float_amount_with_currency_symbol($data->sub_total)); ?> </h6>
                            <?php if($data->coupon_amount > 0): ?>
                                <h6 class="table_customer__title"> <?php echo e(__('Coupon Amount:')); ?>  <strong>-</strong> <?php echo e(float_amount_with_currency_symbol($data->coupon_amount)); ?> </h6>
                            <?php endif; ?>
                            <h6 class="table_customer__title"><?php echo e(__('Tax:')); ?> <strong>+</strong>  <?php echo e(float_amount_with_currency_symbol($data->tax)); ?></h6>
                            <h6 class="table_customer__title"><?php echo e(__('Total:')); ?> <?php echo e(float_amount_with_currency_symbol($data->total)); ?></h6>
                        </div>
                    </div>
                </div>
            </td>

            <!--payment status -->
            <td><span class="my-2"><?php echo e($data->payment_gateway); ?></span></td>
            <td>
                <span class="my-2"><?php if (isset($component)) { $__componentOriginalea75bea71273380f238b6b34c44fd6fe = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalea75bea71273380f238b6b34c44fd6fe = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.payment-status','data' => ['status' => $data->payment_status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.payment-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data->payment_status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalea75bea71273380f238b6b34c44fd6fe)): ?>
<?php $attributes = $__attributesOriginalea75bea71273380f238b6b34c44fd6fe; ?>
<?php unset($__attributesOriginalea75bea71273380f238b6b34c44fd6fe); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalea75bea71273380f238b6b34c44fd6fe)): ?>
<?php $component = $__componentOriginalea75bea71273380f238b6b34c44fd6fe; ?>
<?php unset($__componentOriginalea75bea71273380f238b6b34c44fd6fe); ?>
<?php endif; ?></span>
                <?php if($data->payment_gateway == 'manual_payment' && $data->payment_status == 'pending'): ?>
                    <span><?php if (isset($component)) { $__componentOriginal086f7010becd4d657cdb856682d3d79f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal086f7010becd4d657cdb856682d3d79f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.status-change','data' => ['url' => route('admin.order.change.status',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.status-change'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.order.change.status',$data->id))]); ?>
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
                    <a href="#" class="open-modal"
                       data-file-url="<?php echo e(asset('assets/uploads/manual-payment/'.$data->payment_attachment)); ?>"
                       data-file-name="<?php echo e($data->payment_attachment); ?>">
                        <i class="las la-file-alt"></i>
                    </a>
                <?php elseif($data->payment_status == 'pending'): ?>
                    <span><?php if (isset($component)) { $__componentOriginal086f7010becd4d657cdb856682d3d79f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal086f7010becd4d657cdb856682d3d79f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.status-change','data' => ['url' => route('admin.order.change.status',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.status-change'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.order.change.status',$data->id))]); ?>
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
            <td>
                <span class="my-2">
                    <?php if (isset($component)) { $__componentOriginal2709abd618c7f840ceb579b31ba995da = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2709abd618c7f840ceb579b31ba995da = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.status.main-order-status','data' => ['status' => $data->status]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('status.main-order-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2709abd618c7f840ceb579b31ba995da)): ?>
<?php $attributes = $__attributesOriginal2709abd618c7f840ceb579b31ba995da; ?>
<?php unset($__attributesOriginal2709abd618c7f840ceb579b31ba995da); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2709abd618c7f840ceb579b31ba995da)): ?>
<?php $component = $__componentOriginal2709abd618c7f840ceb579b31ba995da; ?>
<?php unset($__componentOriginal2709abd618c7f840ceb579b31ba995da); ?>
<?php endif; ?>
                </span>
            </td>
            <td> <strong class="subCap"><?php echo e($data->created_at->diffForHumans()); ?></strong></td>
            <!--Action -->
            <td>
                <?php if (isset($component)) { $__componentOriginal768f8f40d03d4d53d956d4ea52baca68 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal768f8f40d03d4d53d956d4ea52baca68 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.view-icon','data' => ['url' => route('admin.order.details',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon.view-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.order.details',$data->id))]); ?>
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

                <?php if (isset($component)) { $__componentOriginalcfe74fef1e01e8d4a7dcb56a2fb67fde = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcfe74fef1e01e8d4a7dcb56a2fb67fde = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.file-icon','data' => ['url' => route('admin.order.invoice.generate',$data->id)]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('icon.file-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['url' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('admin.order.invoice.generate',$data->id))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcfe74fef1e01e8d4a7dcb56a2fb67fde)): ?>
<?php $attributes = $__attributesOriginalcfe74fef1e01e8d4a7dcb56a2fb67fde; ?>
<?php unset($__attributesOriginalcfe74fef1e01e8d4a7dcb56a2fb67fde); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcfe74fef1e01e8d4a7dcb56a2fb67fde)): ?>
<?php $component = $__componentOriginalcfe74fef1e01e8d4a7dcb56a2fb67fde; ?>
<?php unset($__componentOriginalcfe74fef1e01e8d4a7dcb56a2fb67fde); ?>
<?php endif; ?>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<div class="custom_pagination mt-5 d-flex justify-content-end">
    <?php echo e($all_orders->links()); ?>

</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/pages/orders/user-orders/search-order.blade.php ENDPATH**/ ?>