<div class="dashboard__inner__item__header padding-20">
    <div class="dashboard__inner__item__header__flex">
        <div class="dashboard__inner__item__header__left">
            <div class="dashboard_order__tab d-flex flex-wrap gap-3">
                <ul class="tabs">
                    <li class="<?php echo e($current_status === 'all' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.service.all.orders')); ?>"><?php echo e(__('All Orders')); ?> <span class="badge_notification"><?php echo e($total_orders); ?></span></a>
                    </li>
                    <li class="<?php echo e($current_status === '0' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.service.all.orders', ['status' => '0'])); ?>"><?php echo e(__('Pending')); ?> <span class="badge_notification"><?php echo e($total_pending_order); ?></span></a>
                    </li>
                    <li class="<?php echo e($current_status === '1' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.service.all.orders', ['status' => '1'])); ?>"><?php echo e(__('Completed')); ?> <span class="badge_notification"><?php echo e($total_completed_order); ?></span></a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="dashboard__inner__item__header__right">
            <div class="dashboard__inner__item__header__right__flex">
                <div class="dashboard__inner__item__header__right__item">
                    <div class="porduct_search">
                        <input type="text" class="form--control porduct_search__input radius-5 string_search" name="string_search" id="string_search" placeholder="<?php echo e(__('Search order')); ?>">
                        <button type="submit" class="porduct_search__icon"><i class="material-symbols-outlined">search</i> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/pages/orders/order-filter.blade.php ENDPATH**/ ?>