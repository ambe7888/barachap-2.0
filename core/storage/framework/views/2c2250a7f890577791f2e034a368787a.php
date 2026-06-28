<?php $__env->startSection('site-title'); ?>
    <?php echo e(__('Dashboard')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('style'); ?>
    <style>
        .order_id img{
            width: 50px !important;
        }
        .table_customer__thumb img {
            width: 60px;
            height: 60px;
        }
        .dashboard__card {
            height: 97%!important;
        }

       #sales_pipeline {
             width: 100%!important;
             height: 350px!important;
         }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="dashboard__body posPadding">
        <div class="dashboard__inner">
            <div class="dashboard__inner__item">
                <div class="dashboard__inner__item__flex">
                    <div class="dashboard__inner__item__left bodyItemPadding">
                        <div class="dashboard__inner__header">
                            <div class="dashboard__inner__header__flex">
                                <div class="dashboard__inner__header__left">
                                    <h4 class="dashboard__inner__header__title"> <strong id="greeting"></strong>, <?php echo e(Auth::guard('admin')->user()->name); ?> </h4>
                                    <p class="dashboard__inner__header__para"><?php echo e(__('Manage your dashboard here')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard_promo">
                            <div class="row g-4 mt-2">
                                <?php $__currentLoopData = $dashboardData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-xxl-2 col-xl-3 col-sm-6">
                                        <div class="dashboard_promo__single style_02 bg__white radius-10 padding-20">
                                            <span class="dashboard_promo__single__subtitle d-flex justify-content-between align-items-center">
                                                <span>
                                                <?php echo e($item['title'] ?? ''); ?>

                                                 </span>
                                                <?php if(isset($item['route'])): ?>
                                                    <a href="<?php echo e(isset($item['params']) ? route($item['route'], $item['params']) : route($item['route'])); ?>">
                                                        <i class="las la-arrow-right fs-3 font-weight-600"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </span>
                                            <h4 class="dashboard_promo__single__price mt-2"><?php echo e($item['value'] ?? 0); ?></h4>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-xl-6 col-lg-6">
                                <div class="dashboard__card bg__white radius-10 p-3">
                                    <div class="dashboard__card__header">
                                        <div class="dashboard__card__header__flex">
                                            <div class="dashboard__card__header__left">
                                                <h5 class="dashboard__card__header__title"><?php echo e(__('Customers')); ?>

                                                    <p><?php echo e(__('Total Users:')); ?> <?php echo e($total_user); ?></p>
                                                </h5>
                                            </div>
                                            <div class="dashboard__card__header__right">
                                                <select id="timeIntervalSelect" class="select2_activation">
                                                    <?php $__currentLoopData = ['This Week','Last Week','This Month','Last Month','This Year','Last Year']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>"><?php echo e($option); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart__item__inner mt-4">
                                        <canvas id="lineChartCustomer"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <div class="dashboard__card bg__white padding-20 radius-10">
                                    <div class="dashboard__card__header">
                                        <div class="dashboard__card__header__flex">
                                            <div class="dashboard__card__header__left">
                                                <h5 class="dashboard__card__header__title"><?php echo e(__('Services')); ?>

                                                    <p><?php echo e(__('Total Services:')); ?> <?php echo e($total_services); ?></p>
                                                </h5>
                                            </div>
                                            <div class="dashboard__card__header__right">
                                                <select id="serviceTimeIntervalSelect" class="select2_activation">
                                                    <?php $__currentLoopData = ['This Week','Last Week','This Month','Last Month','This Year','Last Year']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>"><?php echo e($option); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chart__item__inner mt-4">
                                        <canvas id="lineChartListings"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-lg-6">
                                <div class="dashboard__card bg__white radius-10 p-3">
                                    <h5 class="dashboard__card__header__title"><?php echo e(__('Recent Users')); ?></h5>
                                    <div class="dashboard__card__inner border_top_1">
                                        <div class="dashboard__inventory__table custom_table">
                                            <?php if($recent_users->count() > 0): ?>
                                                <table>
                                                    <thead>
                                                    <tr>
                                                        <th><?php echo e(__('ID')); ?></th>
                                                        <th><?php echo e(__('User')); ?></th>
                                                        <th><?php echo e(__('Created On')); ?></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php $__currentLoopData = $recent_users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr class="table_row">
                                                            <td><span class="order_id"><?php echo e($user->id); ?></span></td>
                                                            <td>
                                                                <div class="table_customer">
                                                                    <div class="table_customer__flex">
                                                                        <div class="table_customer__thumb">
                                                                            <?php if(!empty($user->image)): ?>
                                                                                <?php echo render_image_markup_by_attachment_id($user->image); ?>

                                                                            <?php else: ?>
                                                                                <img src="<?php echo e(asset('assets/frontend/img/static/user-no-image.webp')); ?>" alt="No Image">
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        <div class="table_customer__contents">
                                                                            <?php if(trim($user->fullname) !== ""): ?>
                                                                                <h6 class="table_customer__title"><?php echo e($user->fullname); ?></h6>
                                                                            <?php else: ?>
                                                                                <h6 class="table_customer__title"><?php echo e($user->email); ?></h6>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><span class="table_date"><?php echo e($user->created_at->format('d M Y')); ?></span></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            <?php else: ?>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span class="text-center text-danger"><?php echo e(__('No recent users found')); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="dashboard__card bg__white radius-10 p-3">
                                    <h5 class="dashboard__card__header__title"><?php echo e(__('Recent Service')); ?></h5>
                                    <div class="dashboard__card__inner border_top_1">
                                        <div class="dashboard__inventory__table custom_table">
                                            <?php if($recent_services->count() > 0): ?>
                                            <table>
                                                <thead>
                                                <tr>
                                                    <th><?php echo e(__('ID')); ?></th>
                                                    <th><?php echo e(__('Title')); ?></th>
                                                    <th><?php echo e(__('Image')); ?></th>
                                                    <th><?php echo e(__('Details')); ?></th>
                                                    <th><?php echo e(__('Created On')); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $__currentLoopData = $recent_services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr class="table_row">
                                                        <td><span class="order_id"><?php echo e($service->id); ?></span></td>
                                                        <td>
                                                            <a href="<?php echo e(route('admin.service.details', $service->id)); ?>">
                                                            <span class="order_id"><?php echo e($service->title); ?></span>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span class="order_id">
                                                                <?php echo render_image_markup_by_attachment_id($service->image); ?>

                                                            </span>
                                                            </td>
                                                        <td>
                                                            <a href="<?php echo e(route('admin.service.details', $service->id)); ?>" class="cmnBtn btn_5 btn_bg_info btnIcon radius-5">
                                                                <i class="las la-eye"></i>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span class="table_date"><?php echo e($service->created_at->format('d M Y')); ?></span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tbody>
                                            </table>
                                            <?php else: ?>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span class="text-center text-danger"><?php echo e(__('No recent services found')); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row g-4 mt-1">
                            <div class="col-xl-6 col-lg-6">
                                <div class="dashboard__card bg__white radius-10 p-3">
                                    <div class="dashboard__card__header">
                                        <div class="dashboard__card__header__flex">
                                            <div class="dashboard__card__header__left">
                                                <h5 class="dashboard__card__header__title"><?php echo e(__('Revenue')); ?></h5>
                                            </div>
                                            <div class="dashboard__card__header__right">
                                                <select id="totalIncomeIntervalSelectAll" class="select2_activation">
                                                    <?php $__currentLoopData = ['Today', 'Yesterday', 'This Week','Last Week','This Month','Last Month','This Year','Last Year']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($key); ?>"><?php echo e($option); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="chart__item__inner mt-4">
                                            <div class="chart__item__inner mt-4">
                                                <div class="sales_pipeline_chart">
                                                    <div id="sales_pipeline"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function () {
            let currentTime = new Date().getHours();
            let morningGreeting = "<?php echo e(__('Good Morning')); ?>";
            let afternoonGreeting = "<?php echo e(__('Good Afternoon')); ?>";
            let eveningGreeting = "<?php echo e(__('Good Evening')); ?>";
            if (currentTime >= 0 && currentTime < 12) {
                $('#greeting').text(morningGreeting);
            } else if (currentTime >= 12 && currentTime < 18) {
                $('#greeting').text(afternoonGreeting);
            } else {
                $('#greeting').text(eveningGreeting);
            }
        });
    </script>
    <?php echo $__env->make('backend.pages.dashboard.line-graph-js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('backend.pages.dashboard.total-income-graph-js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\barachap\core\resources\views/backend/pages/dashboard/dashboard.blade.php ENDPATH**/ ?>