<div class="dashboard__left dashboard-left-content">
    <div class="dashboard__left__main">
        <div class="dashboard__left__close close-bars"><i class="fa-solid fa-times"></i></div>
        <div class="dashboard__top">
            <div class="dashboard__top__logo mb-4">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="dashboard-logo-style">
                    <?php if(get_static_option('site_admin_dark_mode') == 'on'): ?>
                        <?php echo render_image_markup_by_attachment_id(get_static_option('site_white_logo')); ?>

                    <?php else: ?>
                        <?php echo render_image_markup_by_attachment_id(get_static_option('site_logo')); ?>

                    <?php endif; ?>
                </a>
            </div>
        </div>

        <div class="dashboard__bottom">
            <?php if(auth()->guard('admin')->check()): ?>
                <div class="sidebar-welcome-msg text-center py-2 px-3 mb-2" style="font-family: 'Poppins', sans-serif; font-size: 0.9rem; color: var(--text-secondary); font-weight: 600; border-bottom: 1px solid var(--border-color);">
                    <?php echo e(__('Bienvenue')); ?>, <?php echo e(auth()->guard('admin')->user()->name); ?>

                </div>
            <?php endif; ?>
            <div class="dashboard__bottom__search mb-3">
                <input class="form--control  w-100" type="text" placeholder="<?php echo e(__('Search here')); ?>" id="search_sidebarList">
            </div>
            <ul class="dashboard__bottom__list dashboard-list">

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin-dashboard')): ?>
                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/dashboard')): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('admin.dashboard')); ?>"><i class="lab la-accessible-icon"></i>
                            <span class="icon_title"><?php echo e(__('Dashboard')); ?></span>
                        </a>
                    </li>
                <?php endif; ?>

                <!--Admin service manage -->
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-service-list','user-deleteService-list','admin-service-list','admin-service-location','service-create-page-settings','report-reason-list', 'service-report-list'])): ?>
                    <li  class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/service/*')
                     || request()->is('admin/services/user-all-services')|| request()->is('admin/services/all') || request()->is('admin/services/admin-service-location') || request()->is('admin/page-settings/service-create-page/settings')): ?> active open show
                    <?php endif; ?>">
                        <a href="javascript:void(0)"> <i class="las la-th-list"></i> <?php echo e(__('Service Manage')); ?> </a>
                        <ul class="submenu">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-service-list')): ?>
                                <li class="dashboard__bottom__list__item <?php if(request()->is('admin/services/user-all-services')): ?> selected <?php endif; ?>">
                                    <a href="<?php echo e(route('admin.user.all.services')); ?>"> <?php echo e(__('All Provider Services')); ?> </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-deleteService-list')): ?>
                                <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.service.restore'])): ?> selected <?php endif; ?>">
                                    <a href="<?php echo e(route('admin.service.restore')); ?>"> <?php echo e(__('Trash List')); ?> </a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin-service-list')): ?>
                                <li class="dashboard__bottom__list__item <?php if(request()->is('admin/services/all')
                                || request()->is('admin/services/add') ||
                                request()->is('admin/services/admin-edit-service/*')): ?> selected <?php endif; ?>">
                                    <a href="<?php echo e(route('admin.all.services')); ?>"> <?php echo e(__('Admin All Services')); ?> </a>
                                </li>
                           <?php endif; ?>
                           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin-service-location')): ?>
                                <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.service.location.page'])): ?> selected <?php endif; ?>">
                                    <a href="<?php echo e(route('admin.service.location.page')); ?>"> <?php echo e(__('Admin All Services Location')); ?> </a>
                                </li>
                          <?php endif; ?>
                          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('service-create-page-settings')): ?>
                                <li class="dashboard__bottom__list__item <?php if(request()->is('admin/page-settings/service-create-page/settings')): ?> selected <?php endif; ?>">
                                    <a href="<?php echo e(route('admin.service.create.settings')); ?>"><?php echo e(__('Service Create Settings')); ?></a>
                                </li>
                          <?php endif; ?>
                           <!--Admin service schedule manage -->
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin-service-list')): ?>
                                <li class="dashboard__bottom__list__item <?php if(request()->is('admin/service/schedule/list')): ?> selected <?php endif; ?>">
                                    <a href="<?php echo e(route('admin.schedule.all')); ?>"> <?php echo e(__('Admin Schedule Manage')); ?> </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['job-list', 'job-status-change', 'job-bulk-delete'])): ?>
                    <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/job/*')): ?> active open <?php endif; ?>">
                        <a href="javascript:void(0)"><i class="las la-briefcase"></i>
                            <span class="icon_title"><?php echo e(__('Jobs')); ?></span>
                        </a>
                        <ul class="submenu" style="<?php if(request()->is('admin/job/*')): ?> display:block; <?php endif; ?>">
                                <li class="dashboard__bottom__list__item <?php if(request()->is('admin/job/all')): ?> selected <?php endif; ?>">
                                    <a href="<?php echo e(route('admin.jobs.all')); ?>"><?php echo e(__('All Jobs')); ?></a>
                                </li>
                                <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.job.restore'])): ?> selected <?php endif; ?>">
                                    <a href="<?php echo e(route('admin.job.restore')); ?>"> <?php echo e(__('Trash List')); ?> </a>
                                </li>
                                <li class="dashboard__bottom__list__item <?php if(request()->is('admin/job/settings')): ?> selected <?php endif; ?>">
                                    <a href="<?php echo e(route('admin.job.settings')); ?>"><?php echo e(__('Job Settings')); ?></a>
                                </li>
                        </ul>
                    </li>
                <?php endif; ?>

               <!--Admin order manage -->
                <li  class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/orders/*')): ?> active open show <?php endif; ?>">
                    <a href="javascript:void(0)"> <i class="las la-bars"></i> <?php echo e(__('All Orders Manage')); ?> </a>
                    <ul class="submenu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-service-list')): ?>
                        <li class="dashboard__bottom__list__item
                        <?php if(request()->is('admin/orders/user/all-orders')
                        || request()->is('admin/orders/user/details/*')
                        || request()->is('admin/orders/user/sub-order/details/*')): ?> selected
                        <?php endif; ?>">
                            <a href="<?php echo e(route('admin.user.all.order')); ?>"> <?php echo e(__('All Provider Orders')); ?> </a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin-service-list')): ?>
                        <li class="dashboard__bottom__list__item
                            <?php if(Route::currentRouteName() == 'admin.service.all.orders'
                            || request()->is('admin/orders/details/*') || request()->is('admin/orders/sub-order/details/*')  ): ?> selected
                             <?php endif; ?>
                             ">
                            <a href="<?php echo e(route('admin.service.all.orders')); ?>"> <?php echo e(__('All Admin  Orders')); ?> </a>
                        </li>
                       <?php endif; ?>
                       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin-service-list')): ?>
                       <li class="dashboard__bottom__list__item
                           <?php if(Route::currentRouteName() == 'admin.redunded-order.list'): ?> selected
                            <?php endif; ?>
                            ">
                           <a href="<?php echo e(route('admin.redunded-order.list')); ?>"> <?php echo e(__('All Refunded Orders')); ?> </a>
                       </li>
                      <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin-service-list')): ?>
                            <li class="dashboard__bottom__list__item <?php if(request()->is('admin/orders/settings')): ?> selected <?php endif; ?>">
                                <a href="<?php echo e(route('admin.order.settings')); ?>"><?php echo e(__('Order Settings')); ?></a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin-service-list')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/orders/order-cancellation-policy')): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.order.cancellation-policy')); ?>"><?php echo e(__('Oder Cancellation Policy')); ?></a>
                        </li>
                    <?php endif; ?>
                    </ul>
                </li>


               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-list', 'user-deactivated-list', 'user-verify-status', 'user-add'])): ?>
                <li  class="dashboard__bottom__list__item has-children
                <?php if(request()->is('admin/user*')
                    || request()->is('admin/user/profile/*')): ?>
                    active open show
                  <?php endif; ?>">
                    <a href="javascript:void(0)"> <i class="las la-user-circle"></i> <?php echo e(__('User Manage')); ?> </a>
                    <ul class="submenu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-list')): ?>
                            <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.user.all'])): ?> selected <?php endif; ?>">
                                <a href="<?php echo e(route('admin.user.all')); ?>"> <?php echo e(__('All Users')); ?> </a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-deactivated-list')): ?>
                           <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.user.restore'])): ?> selected <?php endif; ?>">
                                <a href="<?php echo e(route('admin.user.restore')); ?>"> <?php echo e(__('Trash List')); ?> </a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-verify-status')): ?>
                            <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.user.verification.request'])): ?> selected <?php endif; ?>">
                                <a href="<?php echo e(route('admin.user.verification.request')); ?>">
                                    <?php echo e(__('Identity Verify Requests')); ?> </a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-add')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.user.add'])): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.user.add')); ?>">
                                <?php echo e(__('Add New User')); ?> </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
               <?php endif; ?>

               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['user-list', 'user-deactivated-list', 'user-verify-status', 'user-add'])): ?>
                <li  class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/staff*')): ?> active open show <?php endif; ?>">
                    <a href="javascript:void(0)"> <i class="las la-user-circle"></i> <?php echo e(__('Admin Staffs Manage')); ?> </a>
                    <ul class="submenu">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-list')): ?>
                            <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.staff.all'])): ?> selected <?php endif; ?>">
                                <a href="<?php echo e(route('admin.staff.all')); ?>"> <?php echo e(__('Admin All Staffs')); ?> </a>
                            </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-add')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.staff.add'])): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.staff.add')); ?>">
                                <?php echo e(__('Add New Staff')); ?> </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
               <?php endif; ?>

               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['category-list', 'category-add'])): ?>
                <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/category/*')): ?> active open <?php endif; ?>">
                    <a href="javascript:void(0)"><i class="las la-th-list"></i>
                        <span class="icon_title"><?php echo e(__('Categories')); ?></span>
                    </a>
                    <ul class="submenu" style="<?php if(request()->is('admin/category/*')): ?> display:block; <?php endif; ?>">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-list')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/category/index')): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.category')); ?>"><?php echo e(__('All Category')); ?></a>
                        </li>
                        <?php endif; ?>
                       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-add')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/category/add-new-category')): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.category.new')); ?>"><?php echo e(__('Add New Category')); ?></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </li>
               <?php endif; ?>

              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['subcategory-list', 'subcategory-add'])): ?>
                <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/subcategory/*')): ?> active open <?php endif; ?>">
                    <a href="javascript:void(0)"><i class="las la-th-list"></i>
                        <span class="icon_title"><?php echo e(__('Subcategories')); ?></span>
                    </a>
                    <ul class="submenu" style="<?php if(request()->is('admin/subcategory/*')): ?> display:block; <?php endif; ?>">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subcategory-list')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/subcategory/index')): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.subcategory')); ?>"><?php echo e(__('All Subcategories')); ?></a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('subcategory-add')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/subcategory/add-new-subcategory')): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.subcategory.new')); ?>"><?php echo e(__('Add New Subcategory')); ?></a>
                        </li>
                       <?php endif; ?>
                    </ul>
                  </li>
                <?php endif; ?>

                    <!-- Child Categories Manage -->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['child-category-list', 'child-category-add'])): ?>
                        <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/child-category/*')): ?> active open <?php endif; ?>">
                            <a href="javascript:void(0)">
                                <i class="las la-th-list"></i>
                                <span class="icon_title"><?php echo e(__('Child Categories')); ?></span>
                            </a>
                            <ul class="submenu" style="<?php if(request()->is('admin/child-category/*')): ?> display:block; <?php endif; ?>">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('child-category-list')): ?>
                                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/child-category/index')): ?> selected <?php endif; ?>">
                                        <a href="<?php echo e(route('admin.child.category')); ?>"><?php echo e(__('All Child Categories')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('child-category-add')): ?>
                                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/child-category/add-new-child-category')): ?> selected <?php endif; ?>">
                                        <a href="<?php echo e(route('admin.child.category.new')); ?>"><?php echo e(__('Add New Child Category')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('slider-settings', 'slider-list')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/slider/*')): ?> active <?php endif; ?>">
                            <a href="<?php echo e(route('admin.slider.add')); ?>"><i class="las la-sliders-h"></i><?php echo e(__('Slider Settings')); ?></a>
                        </li>
                    <?php endif; ?>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['offer-list', 'offer-add'])): ?>
                    <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/offer*')): ?> active open <?php endif; ?>">
                        <a href="javascript:void(0)">
                            <i class="las la-paste"></i>
                            <span class="icon_title"><?php echo e(__('Offers')); ?></span>
                        </a>
                        <ul class="submenu" style="<?php if(request()->is('admin/offer/*')): ?> display:block; <?php endif; ?>">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('offer-list')): ?>
                                <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.offer.all'])): ?> selected <?php endif; ?>">
                                    <a href="<?php echo e(route('admin.offer.all')); ?>"><?php echo e(__('All Offers')); ?></a>
                                </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('offer-add')): ?>
                                <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.offer.add'])): ?> selected <?php endif; ?>">
                                    <a href="<?php echo e(route('admin.offer.add')); ?>"><?php echo e(__('Add New Offer')); ?></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                    <!-- Pages Manage -->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['dynamic-page-list', 'dynamic-page-add'])): ?>
                        <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/dynamic-page*')): ?> active open <?php endif; ?>">
                            <a href="javascript:void(0)">
                                <i class="las la-paste"></i>
                                <span class="icon_title"><?php echo e(__('Pages')); ?></span>
                            </a>
                            <ul class="submenu" style="<?php if(request()->is('admin/dynamic-page/*')): ?> display:block; <?php endif; ?>">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dynamic-page-list')): ?>
                                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/dynamic-page/all')): ?> selected <?php endif; ?>">
                                        <a href="<?php echo e(route('admin.page')); ?>"><?php echo e(__('All Pages')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('dynamic-page-add')): ?>
                                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/dynamic-page/new')): ?> selected <?php endif; ?>">
                                        <a href="<?php echo e(route('admin.page.new')); ?>"><?php echo e(__('Add New Page')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                <?php echo $__env->make('backend.partials.module-list', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('report-reason-list', 'report-reason-edit', 'report-reason-delete', 'report-reason-bulk-delete')): ?>
                    <li class="dashboard__bottom__list__item <?php if(request()->routeIs('admin.report.reason.all')): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('admin.report.reason.all')); ?>"> <i class="las la-question-circle"></i> <?php echo e(__('Reasons')); ?> </a>
                    </li>
                <?php endif; ?>
                 <!-- Refund Manage -->
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('refund-payment-gateway-list', 'refund-settings-view', 'refund-payment-gateway-add', 'refund-payment-gateway-edit', 'refund-payment-status-change', 'refund-payment-gateway-delete', 'refund-list', 'refund-status-change', 'refund-fee-settings-view')): ?>
               <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/refund*')): ?> active open <?php endif; ?>">
                   <a href="javascript:void(0)">
                       <i class="las la-paste"></i>
                       <span class="icon_title"><?php echo e(__('Refund Gateway')); ?></span>
                   </a>
                   <ul class="submenu <?php if(request()->is('admin/refund/*')): ?> d-block <?php endif; ?>">
                       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('refund-payment-gateway-add')): ?>
                       <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.refund.gateway'])): ?> selected <?php endif; ?>">
                           <a href="<?php echo e(route('admin.refund.gateway')); ?>"><?php echo e(__('Refund Payment Gateway')); ?></a>
                       </li>
                       <?php endif; ?>
                   </ul>
               </li>
               <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notifications-list')): ?>
                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/admin-commission/all')): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('admin.commission.all')); ?>" ><i class="las la-money-check-alt"></i><?php echo e(__('Admin Commission')); ?></a>
                    </li>
                <?php endif; ?>

                    <!-- Withdraw Manage -->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any('withdraw-payment-gateway-list', 'withdraw-settings-view', 'withdraw-payment-gateway-add', 'withdraw-payment-gateway-edit', 'withdraw-payment-status-change', 'withdraw-payment-gateway-delete', 'withdraw-list', 'withdraw-status-change', 'withdraw-fee-settings-view')): ?>
                    <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/withdraw*')): ?> active open <?php endif; ?>">
                        <a href="javascript:void(0)">
                            <i class="las la-paste"></i>
                            <span class="icon_title"><?php echo e(__('Withdraw')); ?></span>
                        </a>
                        <ul class="submenu" style="<?php if(request()->is('admin/withdraw/*')): ?> display:block; <?php endif; ?>">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('withdraw-settings-view')): ?>
                            <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.withdraw.settings'])): ?> selected <?php endif; ?>">
                                <a href="<?php echo e(route('admin.withdraw.settings')); ?>"><?php echo e(__('Withdraw Settings')); ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('withdraw-payment-gateway-add')): ?>
                            <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.withdraw.gateway'])): ?> selected <?php endif; ?>">
                                <a href="<?php echo e(route('admin.withdraw.gateway')); ?>"><?php echo e(__('Withdraw Payment Gateway')); ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('withdraw-list')): ?>
                            <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.withdraw.request'])): ?> selected <?php endif; ?>">
                                <a href="<?php echo e(route('admin.withdraw.request')); ?>"><?php echo e(__('Withdraw Request')); ?></a>
                            </li>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('withdraw-fee-settings-view')): ?>
                            <li class="dashboard__bottom__list__item <?php if(request()->routeIs(['admin.withdraw.fee.settings'])): ?> selected <?php endif; ?>">
                                <a href="<?php echo e(route('admin.withdraw.fee.settings')); ?>">
                                    <?php echo e(__('Withdraw Fee Settings')); ?>

                                </a>
                            </li>
                           <?php endif; ?>
                        </ul>
                    </li>
                    <?php endif; ?>


                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('notifications-list')): ?>
                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/notification/*')): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('admin.notification.all')); ?>"><i class="las la-bell"></i><?php echo e(__('All Notification')); ?></a>
                    </li>
                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/firebase/settings*')): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('admin.firebase.settings')); ?>"><i class="las la-bell"></i><?php echo e(__('Firebase Settings')); ?></a>
                    </li>
                <?php endif; ?>




              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('google-map-settings')): ?>
                <li class="dashboard__bottom__list__item <?php if(request()->is('admin/map-settings/*')): ?> active <?php endif; ?>">
                    <a href="<?php echo e(route('admin.map.settings.page')); ?>"><i class="las la-map"></i><?php echo e(__('Google Map Settings')); ?></a>
                </li>
               <?php endif; ?>

                    <!-- Appearance Settings -->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any([
                       'color-settings', 'typography-settings',
                        'typography-single-settings', 'font-add-settings', 'custom-font-delete', 'custom-font-status-change',
                        'media-upload', 'media-upload-delete', '404-page-settings', 'maintains-page-settings'
                    ])): ?>
                        <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/appearance-settings/*')): ?> active open <?php endif; ?>">
                            <a href="javascript:void(0)">
                                <i class="las la-cogs"></i>
                                <span class="icon_title"><?php echo e(__('Appearance Settings')); ?></span>
                            </a>
                            <ul class="submenu" style="<?php if(request()->is('admin/appearance-settings/*')): ?> display:block; <?php endif; ?>">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('media-upload')): ?>
                                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/appearance-settings/media-upload/page')): ?> selected <?php endif; ?>">
                                        <a href="<?php echo e(route('admin.upload.media.images.page')); ?>"><?php echo e(__('Media Images Manage')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('404-page-settings')): ?>
                                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/appearance-settings/404-page-manage')): ?> selected <?php endif; ?>">
                                        <a href="<?php echo e(route('admin.404.page.settings')); ?>"><?php echo e(__('404 Page Manage')); ?></a>
                                    </li>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('maintains-page-settings')): ?>
                                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/appearance-settings/maintains-page')): ?> selected <?php endif; ?>">
                                        <a href="<?php echo e(route('admin.maintains.page.settings')); ?>"><?php echo e(__('Maintain Page Manage')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any([
                            'login-register-page-settings', 'user-public-profile-page-settings'
                        ])): ?>
                        <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/page-settings/admin-login-page/settings')): ?> active open <?php endif; ?>">
                            <a href="javascript:void(0)">
                                <i class="las la-file-alt"></i>
                                <span class="icon_title"><?php echo e(__('Page Settings')); ?></span>
                            </a>
                            <ul class="submenu" style="<?php if(request()->is('admin/page-settings/admin-login-page/settings')): ?> display:block; <?php endif; ?>">

                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-public-profile-page-settings')): ?>
                                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/page-settings/admin-login-page/settings')): ?> selected <?php endif; ?>">
                                        <a href="<?php echo e(route('admin.login.page.settings')); ?>"><?php echo e(__('Admin Login Page Settings')); ?></a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                    <?php endif; ?>


                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['smtp-settings'])): ?>
                    <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/email-settings/*')): ?> active open <?php endif; ?>">
                    <a href="javascript:void(0)"><i class="las la-envelope"></i>
                        <span class="icon_title"><?php echo e(__('Email Settings')); ?></span>
                    </a>
                        <ul class="submenu" style="<?php if(request()->is('admin/email-settings/*')): ?> display:block; <?php endif; ?>">
                            <li class="dashboard__bottom__list__item <?php if(request()->is('admin/email-settings/smtp')): ?> selected <?php endif; ?>">
                                <a href="<?php echo e(route('admin.email.smtp.settings')); ?>"><?php echo e(__('SMTP Settings')); ?></a>
                            </li>
                            <li class="dashboard__bottom__list__item <?php if(request()->is('admin/email-settings/all-email-templates')): ?> selected <?php endif; ?>">
                                <a href="<?php echo e(route('admin.email.template.all')); ?>"><?php echo e(__('All Email Templates')); ?></a>
                            </li>
                        </ul>
                    </li>
                    <?php endif; ?>

                 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['site-identity-settings', 'basic-settings', 'seo-settings', 'scripts-settings',  'sitemap-settings', 'gdpr-settings', 'license-setting', 'software-update-setting', 'cache-settings', 'database-upgrade-setting'
                          ])): ?>
                <li class="dashboard__bottom__list__item has-children <?php if(request()->is('admin/general-settings/*')): ?> active open <?php endif; ?>">
                    <a href="javascript:void(0)"><i class="las la-cog"></i>
                        <span class="icon_title"><?php echo e(__('General Settings')); ?></span>
                    </a>
                    <ul class="submenu" style="<?php if(request()->is('admin/general-settings/*')): ?> display:block; <?php endif; ?>">
                       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('site-identity-settings')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/general-settings/site-identity')): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.general.site.identity')); ?>"><?php echo e(__('Site Identity')); ?></a>
                        </li>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('basic-settings')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/general-settings/basic-settings')): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.general.basic.settings')); ?>"><?php echo e(__('Basic Settings')); ?></a>
                        </li>
                       <?php endif; ?>
                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('license-settings')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/general-settings/license-setting')): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.general.license.settings')); ?>"><?php echo e(__('Licence Settings')); ?></a>
                        </li>
                       <?php endif; ?>
                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('software-update-settings')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/general-settings/software-update-setting')): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.general.software.update.settings')); ?>"><?php echo e(__('Check Update')); ?></a>
                        </li>
                     <?php endif; ?>
                     <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cache-settings')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/general-settings/cache-settings')): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.general.cache.settings')); ?>"><?php echo e(__('Cache Settings')); ?></a>
                        </li>
                     <?php endif; ?>
                      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('database-upgrade-settings')): ?>
                        <li class="dashboard__bottom__list__item <?php if(request()->is('admin/general-settings/database-upgrade')): ?> selected <?php endif; ?>">
                            <a href="<?php echo e(route('admin.general.database.upgrade')); ?>"><?php echo e(__('Database Upgrade')); ?></a>
                        </li>
                     <?php endif; ?>
                    </ul>
                </li>
               <?php endif; ?>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('languages-list')): ?>
                    <li class="dashboard__bottom__list__item <?php if(request()->is('admin/languages/*') || request()->is('admin/languages')): ?> active <?php endif; ?>">
                        <a href="<?php echo e(route('admin.languages')); ?>"><i class="las la-language"></i> <span class="icon_title"><?php echo e(__('Languages')); ?></span></a>
                    </li>
                <?php endif; ?>

                <li class="dashboard__bottom__list__item">
                    <a href="<?php echo e(route('admin.logout')); ?>"> <i class="las la-sign-out-alt"></i> <span class="icon_title"><?php echo e(__('Log Out')); ?></span></a>
                </li>
            </ul>
        </div>
    </div>
</div>







<?php /**PATH C:\xampp\htdocs\barachap\web\core\resources\views/backend/partials/sidebar.blade.php ENDPATH**/ ?>