<div class="dashboard__left dashboard-left-content">
    <div class="dashboard__left__main">
        <div class="dashboard__left__close close-bars"><i class="fa-solid fa-times"></i></div>
        <div class="dashboard__top">
            <div class="dashboard__top__logo mb-4">
                <a href="{{route('admin.dashboard')}}" class="dashboard-logo-style">
                    @if(get_static_option('site_admin_dark_mode') == 'on')
                        {!! render_image_markup_by_attachment_id(get_static_option('site_white_logo')) !!}
                    @else
                        {!! render_image_markup_by_attachment_id(get_static_option('site_logo')) !!}
                    @endif
                </a>
            </div>
        </div>

        <div class="dashboard__bottom">
            <div class="dashboard__bottom__search mb-3">
                <input class="form--control  w-100" type="text" placeholder="{{ __('Search here') }}" id="search_sidebarList">
            </div>
            <ul class="dashboard__bottom__list dashboard-list">

                @can('admin-dashboard')
                    <li class="dashboard__bottom__list__item @if(request()->is('admin/dashboard')) active @endif">
                        <a href="{{route('admin.dashboard')}}"><i class="lab la-accessible-icon"></i>
                            <span class="icon_title">{{ __('Dashboard') }}</span>
                        </a>
                    </li>
                @endcan

                <!--Admin service manage -->
                @canany(['user-service-list','user-deleteService-list','admin-service-list','admin-service-location','service-create-page-settings','report-reason-list', 'service-report-list'])
                    <li  class="dashboard__bottom__list__item has-children @if (request()->is('admin/service/*')
                     || request()->is('admin/services/user-all-services')|| request()->is('admin/services/all') || request()->is('admin/services/admin-service-location') || request()->is('admin/page-settings/service-create-page/settings')) active open show
                    @endif">
                        <a href="javascript:void(0)"> <i class="las la-th-list"></i> {{ __('Service Manage') }} </a>
                        <ul class="submenu">
                            @can('user-service-list')
                                <li class="dashboard__bottom__list__item @if (request()->is('admin/services/user-all-services')) selected @endif">
                                    <a href="{{ route('admin.user.all.services') }}"> {{ __('All Provider Services') }} </a>
                                </li>
                            @endcan
                            @can('user-deleteService-list')
                                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.service.restore'])) selected @endif">
                                    <a href="{{ route('admin.service.restore') }}"> {{ __('Trash List') }} </a>
                                </li>
                            @endcan
                            @can('admin-service-list')
                                <li class="dashboard__bottom__list__item @if (request()->is('admin/services/all')
                                || request()->is('admin/services/add') ||
                                request()->is('admin/services/admin-edit-service/*')) selected @endif">
                                    <a href="{{ route('admin.all.services') }}"> {{ __('Admin All Services') }} </a>
                                </li>
                           @endcan
                           @can('admin-service-location')
                                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.service.location.page'])) selected @endif">
                                    <a href="{{ route('admin.service.location.page') }}"> {{ __('Admin All Services Location') }} </a>
                                </li>
                          @endcan
                          @can('service-create-page-settings')
                                <li class="dashboard__bottom__list__item @if(request()->is('admin/page-settings/service-create-page/settings')) selected @endif">
                                    <a href="{{ route('admin.service.create.settings') }}">{{ __('Service Create Settings') }}</a>
                                </li>
                          @endcan
                           <!--Admin service schedule manage -->
                            @can('admin-service-list')
                                <li class="dashboard__bottom__list__item @if(request()->is('admin/service/schedule/list')) selected @endif">
                                    <a href="{{ route('admin.schedule.all') }}"> {{ __('Admin Schedule Manage') }} </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['job-list', 'job-status-change', 'job-bulk-delete'])
                    <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/job/*')) active open @endif">
                        <a href="javascript:void(0)"><i class="las la-briefcase"></i>
                            <span class="icon_title">{{ __('Jobs') }}</span>
                        </a>
                        <ul class="submenu" style="@if(request()->is('admin/job/*')) display:block; @endif">
                                <li class="dashboard__bottom__list__item @if(request()->is('admin/job/all')) selected @endif">
                                    <a href="{{ route('admin.jobs.all') }}">{{ __('All Jobs') }}</a>
                                </li>
                                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.job.restore'])) selected @endif">
                                    <a href="{{ route('admin.job.restore') }}"> {{ __('Trash List') }} </a>
                                </li>
                                <li class="dashboard__bottom__list__item @if(request()->is('admin/job/settings')) selected @endif">
                                    <a href="{{ route('admin.job.settings') }}">{{ __('Job Settings') }}</a>
                                </li>
                        </ul>
                    </li>
                @endcanany

               <!--Admin order manage -->
                <li  class="dashboard__bottom__list__item has-children @if (request()->is('admin/orders/*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="las la-bars"></i> {{ __('All Orders Manage') }} </a>
                    <ul class="submenu">
                        @can('user-service-list')
                        <li class="dashboard__bottom__list__item
                        @if (request()->is('admin/orders/user/all-orders')
                        || request()->is('admin/orders/user/details/*')
                        || request()->is('admin/orders/user/sub-order/details/*')) selected
                        @endif">
                            <a href="{{ route('admin.user.all.order') }}"> {{ __('All Provider Orders') }} </a>
                        </li>
                        @endcan
                        @can('admin-service-list')
                        <li class="dashboard__bottom__list__item
                            @if(Route::currentRouteName() == 'admin.service.all.orders'
                            || request()->is('admin/orders/details/*') || request()->is('admin/orders/sub-order/details/*')  ) selected
                             @endif
                             ">
                            <a href="{{ route('admin.service.all.orders') }}"> {{ __('All Admin  Orders') }} </a>
                        </li>
                       @endcan
                       @can('admin-service-list')
                       <li class="dashboard__bottom__list__item
                           @if(Route::currentRouteName() == 'admin.redunded-order.list') selected
                            @endif
                            ">
                           <a href="{{ route('admin.redunded-order.list') }}"> {{ __('All Refunded Orders') }} </a>
                       </li>
                      @endcan
                        @can('admin-service-list')
                            <li class="dashboard__bottom__list__item @if(request()->is('admin/orders/settings')) selected @endif">
                                <a href="{{ route('admin.order.settings') }}">{{ __('Order Settings') }}</a>
                            </li>
                        @endcan
                        @can('admin-service-list')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/orders/order-cancellation-policy')) selected @endif">
                            <a href="{{ route('admin.order.cancellation-policy') }}">{{ __('Oder Cancellation Policy') }}</a>
                        </li>
                    @endcan
                    </ul>
                </li>


               @canany(['user-list', 'user-deactivated-list', 'user-verify-status', 'user-add'])
                <li  class="dashboard__bottom__list__item has-children
                @if (request()->is('admin/user*')
                    || request()->is('admin/user/profile/*'))
                    active open show
                  @endif">
                    <a href="javascript:void(0)"> <i class="las la-user-circle"></i> {{ __('User Manage') }} </a>
                    <ul class="submenu">
                        @can('user-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.user.all'])) selected @endif">
                                <a href="{{ route('admin.user.all') }}"> {{ __('All Users') }} </a>
                            </li>
                        @endcan
                        @can('user-deactivated-list')
                           <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.user.restore'])) selected @endif">
                                <a href="{{ route('admin.user.restore') }}"> {{ __('Trash List') }} </a>
                            </li>
                        @endcan
                        @can('user-verify-status')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.user.verification.request'])) selected @endif">
                                <a href="{{ route('admin.user.verification.request') }}">
                                    {{ __('Identity Verify Requests') }} </a>
                            </li>
                        @endcan
                        @can('user-add')
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.user.add'])) selected @endif">
                            <a href="{{ route('admin.user.add') }}">
                                {{ __('Add New User') }} </a>
                        </li>
                        @endcan
                    </ul>
                </li>
               @endcanany

               @canany(['user-list', 'user-deactivated-list', 'user-verify-status', 'user-add'])
                <li  class="dashboard__bottom__list__item has-children @if (request()->is('admin/staff*')) active open show @endif">
                    <a href="javascript:void(0)"> <i class="las la-user-circle"></i> {{ __('Admin Staffs Manage') }} </a>
                    <ul class="submenu">
                        @can('user-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.staff.all'])) selected @endif">
                                <a href="{{ route('admin.staff.all') }}"> {{ __('Admin All Staffs') }} </a>
                            </li>
                        @endcan
                        @can('user-add')
                        <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.staff.add'])) selected @endif">
                            <a href="{{ route('admin.staff.add') }}">
                                {{ __('Add New Staff') }} </a>
                        </li>
                        @endcan
                    </ul>
                </li>
               @endcanany

               @canany(['category-list', 'category-add'])
                <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/category/*')) active open @endif">
                    <a href="javascript:void(0)"><i class="las la-th-list"></i>
                        <span class="icon_title">{{ __('Categories') }}</span>
                    </a>
                    <ul class="submenu" style="@if(request()->is('admin/category/*')) display:block; @endif">
                        @can('category-list')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/category/index')) selected @endif">
                            <a href="{{ route('admin.category') }}">{{ __('All Category') }}</a>
                        </li>
                        @endcan
                       @can('category-add')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/category/add-new-category')) selected @endif">
                            <a href="{{ route('admin.category.new') }}">{{ __('Add New Category') }}</a>
                        </li>
                        @endcan
                    </ul>
                </li>
               @endcanany

              @canany(['subcategory-list', 'subcategory-add'])
                <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/subcategory/*')) active open @endif">
                    <a href="javascript:void(0)"><i class="las la-th-list"></i>
                        <span class="icon_title">{{ __('Subcategories') }}</span>
                    </a>
                    <ul class="submenu" style="@if(request()->is('admin/subcategory/*')) display:block; @endif">
                        @can('subcategory-list')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/subcategory/index')) selected @endif">
                            <a href="{{ route('admin.subcategory') }}">{{ __('All Subcategories') }}</a>
                        </li>
                        @endcan
                        @can('subcategory-add')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/subcategory/add-new-subcategory')) selected @endif">
                            <a href="{{ route('admin.subcategory.new') }}">{{ __('Add New Subcategory') }}</a>
                        </li>
                       @endcan
                    </ul>
                  </li>
                @endcanany

                    <!-- Child Categories Manage -->
                    @canany(['child-category-list', 'child-category-add'])
                        <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/child-category/*')) active open @endif">
                            <a href="javascript:void(0)">
                                <i class="las la-th-list"></i>
                                <span class="icon_title">{{ __('Child Categories') }}</span>
                            </a>
                            <ul class="submenu" style="@if(request()->is('admin/child-category/*')) display:block; @endif">
                                @can('child-category-list')
                                    <li class="dashboard__bottom__list__item @if(request()->is('admin/child-category/index')) selected @endif">
                                        <a href="{{ route('admin.child.category') }}">{{ __('All Child Categories') }}</a>
                                    </li>
                                @endcan
                                @can('child-category-add')
                                    <li class="dashboard__bottom__list__item @if(request()->is('admin/child-category/add-new-child-category')) selected @endif">
                                        <a href="{{ route('admin.child.category.new') }}">{{ __('Add New Child Category') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                    @can('slider-settings', 'slider-list')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/slider/*')) active @endif">
                            <a href="{{ route('admin.slider.add') }}"><i class="las la-sliders-h"></i>{{ __('Slider Settings') }}</a>
                        </li>
                    @endcan


                @canany(['offer-list', 'offer-add'])
                    <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/offer*')) active open @endif">
                        <a href="javascript:void(0)">
                            <i class="las la-paste"></i>
                            <span class="icon_title">{{ __('Offers') }}</span>
                        </a>
                        <ul class="submenu" style="@if(request()->is('admin/offer/*')) display:block; @endif">
                            @can('offer-list')
                                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.offer.all'])) selected @endif">
                                    <a href="{{ route('admin.offer.all') }}">{{ __('All Offers') }}</a>
                                </li>
                            @endcan
                            @can('offer-add')
                                <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.offer.add'])) selected @endif">
                                    <a href="{{ route('admin.offer.add') }}">{{ __('Add New Offer') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                    <!-- Pages Manage -->
                    @canany(['dynamic-page-list', 'dynamic-page-add'])
                        <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/dynamic-page*')) active open @endif">
                            <a href="javascript:void(0)">
                                <i class="las la-paste"></i>
                                <span class="icon_title">{{ __('Pages') }}</span>
                            </a>
                            <ul class="submenu" style="@if(request()->is('admin/dynamic-page/*')) display:block; @endif">
                                @can('dynamic-page-list')
                                    <li class="dashboard__bottom__list__item @if(request()->is('admin/dynamic-page/all')) selected @endif">
                                        <a href="{{ route('admin.page') }}">{{ __('All Pages') }}</a>
                                    </li>
                                @endcan
                                @can('dynamic-page-add')
                                    <li class="dashboard__bottom__list__item @if(request()->is('admin/dynamic-page/new')) selected @endif">
                                        <a href="{{ route('admin.page.new') }}">{{ __('Add New Page') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                @include('backend.partials.module-list')

                @canany('report-reason-list', 'report-reason-edit', 'report-reason-delete', 'report-reason-bulk-delete')
                    <li class="dashboard__bottom__list__item @if(request()->routeIs('admin.report.reason.all')) active @endif">
                        <a href="{{ route('admin.report.reason.all') }}"> <i class="las la-question-circle"></i> {{ __('Reasons') }} </a>
                    </li>
                @endcanany
                 <!-- Refund Manage -->
               @canany('refund-payment-gateway-list', 'refund-settings-view', 'refund-payment-gateway-add', 'refund-payment-gateway-edit', 'refund-payment-status-change', 'refund-payment-gateway-delete', 'refund-list', 'refund-status-change', 'refund-fee-settings-view')
               <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/refund*')) active open @endif">
                   <a href="javascript:void(0)">
                       <i class="las la-paste"></i>
                       <span class="icon_title">{{ __('Refund Gateway') }}</span>
                   </a>
                   <ul class="submenu @if(request()->is('admin/refund/*')) d-block @endif">
                       @can('refund-payment-gateway-add')
                       <li class="dashboard__bottom__list__item @if(request()->routeIs(['admin.refund.gateway'])) selected @endif">
                           <a href="{{ route('admin.refund.gateway') }}">{{ __('Refund Payment Gateway') }}</a>
                       </li>
                       @endcan
                   </ul>
               </li>
               @endcanany

                @can('notifications-list')
                    <li class="dashboard__bottom__list__item @if(request()->is('admin/admin-commission/all')) active @endif">
                        <a href="{{ route('admin.commission.all') }}" ><i class="las la-money-check-alt"></i>{{ __('Admin Commission') }}</a>
                    </li>
                @endcan

                    <!-- Withdraw Manage -->
                    @canany('withdraw-payment-gateway-list', 'withdraw-settings-view', 'withdraw-payment-gateway-add', 'withdraw-payment-gateway-edit', 'withdraw-payment-status-change', 'withdraw-payment-gateway-delete', 'withdraw-list', 'withdraw-status-change', 'withdraw-fee-settings-view')
                    <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/withdraw*')) active open @endif">
                        <a href="javascript:void(0)">
                            <i class="las la-paste"></i>
                            <span class="icon_title">{{ __('Withdraw') }}</span>
                        </a>
                        <ul class="submenu" style="@if(request()->is('admin/withdraw/*')) display:block; @endif">
                            @can('withdraw-settings-view')
                            <li class="dashboard__bottom__list__item @if(request()->routeIs(['admin.withdraw.settings'])) selected @endif">
                                <a href="{{ route('admin.withdraw.settings') }}">{{ __('Withdraw Settings') }}</a>
                            </li>
                            @endcan
                            @can('withdraw-payment-gateway-add')
                            <li class="dashboard__bottom__list__item @if(request()->routeIs(['admin.withdraw.gateway'])) selected @endif">
                                <a href="{{ route('admin.withdraw.gateway') }}">{{ __('Withdraw Payment Gateway') }}</a>
                            </li>
                            @endcan
                            @can('withdraw-list')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.withdraw.request'])) selected @endif">
                                <a href="{{ route('admin.withdraw.request') }}">{{ __('Withdraw Request') }}</a>
                            </li>
                            @endcan
                            @can('withdraw-fee-settings-view')
                            <li class="dashboard__bottom__list__item @if (request()->routeIs(['admin.withdraw.fee.settings'])) selected @endif">
                                <a href="{{ route('admin.withdraw.fee.settings') }}">
                                    {{ __('Withdraw Fee Settings') }}
                                </a>
                            </li>
                           @endcan
                        </ul>
                    </li>
                    @endcanany


                @can('notifications-list')
                    <li class="dashboard__bottom__list__item @if(request()->is('admin/notification/*')) active @endif">
                        <a href="{{ route('admin.notification.all') }}"><i class="las la-bell"></i>{{ __('All Notification') }}</a>
                    </li>
                    <li class="dashboard__bottom__list__item @if(request()->is('admin/firebase/settings*')) active @endif">
                        <a href="{{ route('admin.firebase.settings') }}"><i class="las la-bell"></i>{{ __('Firebase Settings') }}</a>
                    </li>
                @endcan




              @can('google-map-settings')
                <li class="dashboard__bottom__list__item @if(request()->is('admin/map-settings/*')) active @endif">
                    <a href="{{ route('admin.map.settings.page') }}"><i class="las la-map"></i>{{ __('Google Map Settings') }}</a>
                </li>
               @endcan

                    <!-- Appearance Settings -->
                    @canany([
                       'color-settings', 'typography-settings',
                        'typography-single-settings', 'font-add-settings', 'custom-font-delete', 'custom-font-status-change',
                        'media-upload', 'media-upload-delete', '404-page-settings', 'maintains-page-settings'
                    ])
                        <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/appearance-settings/*')) active open @endif">
                            <a href="javascript:void(0)">
                                <i class="las la-cogs"></i>
                                <span class="icon_title">{{ __('Appearance Settings') }}</span>
                            </a>
                            <ul class="submenu" style="@if(request()->is('admin/appearance-settings/*')) display:block; @endif">
                                @can('media-upload')
                                    <li class="dashboard__bottom__list__item @if(request()->is('admin/appearance-settings/media-upload/page')) selected @endif">
                                        <a href="{{ route('admin.upload.media.images.page') }}">{{ __('Media Images Manage') }}</a>
                                    </li>
                                @endcan
                                @can('404-page-settings')
                                    <li class="dashboard__bottom__list__item @if(request()->is('admin/appearance-settings/404-page-manage')) selected @endif">
                                        <a href="{{ route('admin.404.page.settings') }}">{{ __('404 Page Manage') }}</a>
                                    </li>
                                @endcan
                                @can('maintains-page-settings')
                                    <li class="dashboard__bottom__list__item @if(request()->is('admin/appearance-settings/maintains-page')) selected @endif">
                                        <a href="{{ route('admin.maintains.page.settings') }}">{{ __('Maintain Page Manage') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany([
                            'login-register-page-settings', 'user-public-profile-page-settings'
                        ])
                        <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/page-settings/admin-login-page/settings')) active open @endif">
                            <a href="javascript:void(0)">
                                <i class="las la-file-alt"></i>
                                <span class="icon_title">{{ __('Page Settings') }}</span>
                            </a>
                            <ul class="submenu" style="@if(request()->is('admin/page-settings/admin-login-page/settings')) display:block; @endif">

                                @can('user-public-profile-page-settings')
                                    <li class="dashboard__bottom__list__item @if(request()->is('admin/page-settings/admin-login-page/settings')) selected @endif">
                                        <a href="{{ route('admin.login.page.settings') }}">{{ __('Admin Login Page Settings') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany


                    @canany(['smtp-settings'])
                    <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/email-settings/*')) active open @endif">
                    <a href="javascript:void(0)"><i class="las la-envelope"></i>
                        <span class="icon_title">{{ __('Email Settings') }}</span>
                    </a>
                        <ul class="submenu" style="@if(request()->is('admin/email-settings/*')) display:block; @endif">
                            <li class="dashboard__bottom__list__item @if(request()->is('admin/email-settings/smtp')) selected @endif">
                                <a href="{{ route('admin.email.smtp.settings') }}">{{ __('SMTP Settings') }}</a>
                            </li>
                            <li class="dashboard__bottom__list__item @if(request()->is('admin/email-settings/all-email-templates')) selected @endif">
                                <a href="{{ route('admin.email.template.all') }}">{{ __('All Email Templates') }}</a>
                            </li>
                        </ul>
                    </li>
                    @endcanany

                 @canany(['site-identity-settings', 'basic-settings', 'seo-settings', 'scripts-settings',  'sitemap-settings', 'gdpr-settings', 'license-setting', 'software-update-setting', 'cache-settings', 'database-upgrade-setting'
                          ])
                <li class="dashboard__bottom__list__item has-children @if(request()->is('admin/general-settings/*')) active open @endif">
                    <a href="javascript:void(0)"><i class="las la-cog"></i>
                        <span class="icon_title">{{ __('General Settings') }}</span>
                    </a>
                    <ul class="submenu" style="@if(request()->is('admin/general-settings/*')) display:block; @endif">
                       @can('site-identity-settings')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/general-settings/site-identity')) selected @endif">
                            <a href="{{ route('admin.general.site.identity') }}">{{ __('Site Identity') }}</a>
                        </li>
                        @endcan
                        @can('basic-settings')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/general-settings/basic-settings')) selected @endif">
                            <a href="{{ route('admin.general.basic.settings') }}">{{ __('Basic Settings') }}</a>
                        </li>
                       @endcan
                      @can('license-settings')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/general-settings/license-setting')) selected @endif">
                            <a href="{{ route('admin.general.license.settings') }}">{{ __('Licence Settings') }}</a>
                        </li>
                       @endcan
                      @can('software-update-settings')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/general-settings/software-update-setting')) selected @endif">
                            <a href="{{ route('admin.general.software.update.settings') }}">{{ __('Check Update') }}</a>
                        </li>
                     @endcan
                     @can('cache-settings')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/general-settings/cache-settings')) selected @endif">
                            <a href="{{ route('admin.general.cache.settings') }}">{{ __('Cache Settings') }}</a>
                        </li>
                     @endcan
                      @can('database-upgrade-settings')
                        <li class="dashboard__bottom__list__item @if(request()->is('admin/general-settings/database-upgrade')) selected @endif">
                            <a href="{{ route('admin.general.database.upgrade') }}">{{ __('Database Upgrade') }}</a>
                        </li>
                     @endcan
                    </ul>
                </li>
               @endcanany

                @can('languages-list')
                    <li class="dashboard__bottom__list__item @if(request()->is('admin/languages/*') || request()->is('admin/languages')) active @endif">
                        <a href="{{ route('admin.languages') }}"><i class="las la-language"></i> <span class="icon_title">{{ __('Languages') }}</span></a>
                    </li>
                @endcan

                <li class="dashboard__bottom__list__item">
                    <a href="{{ route('admin.logout') }}"> <i class="las la-sign-out-alt"></i> <span class="icon_title">{{ __('Log Out') }}</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>







