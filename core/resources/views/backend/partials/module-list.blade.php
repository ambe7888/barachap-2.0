<!-- Support Ticket Module -->


@canany(['coupon-settings', 'coupons-new', 'coupon-list', 'coupon-edit-add', 'coupon-delete-add'])
    <li class="dashboard__bottom__list__item @if (request()->is(['admin/coupons', 'admin/coupons/*'])) active @endif">
        <a href="{{ route('admin.coupon.all') }}">
            <i class="las la-percentage"></i>
            <span>{{ __('Coupon Manage') }}</span>
        </a>
    </li>
@endcanany

@canany(['tax-settings', 'tax-list', 'tax-new', 'tax-edit-add'])
<li class="dashboard__bottom__list__item has-children @if(request()->is('admin/tax/*')) active open @endif">
    <a href="javascript:void(0)"><i class="las la-file-invoice-dollar"></i>
        <span class="icon_title">{{ __('Tax Settings') }}</span>
    </a>
    <ul class="submenu" style="@if(request()->is('admin/tax/*')) display:block; @endif">
        <li class="dashboard__bottom__list__item @if(request()->is('admin/tax/settings')) selected @endif">
            <a href="{{ route('admin.tax.settings') }}">{{ __('Tax Manage Settings') }}</a>
        </li>

            @if (get_static_option('tax_system') == 'advance_tax_system')
                <li class="dashboard__bottom__list__item @if(request()->is('admin/tax/settings')) selected @endif">
                    <a href="{{ route('admin.tax.tax-class') }}">{{ __('Tax Class') }}</a>
                </li>
            @endif

         @if (get_static_option('tax_system') == 'zone_wise_tax_system')
                <li class="dashboard__bottom__list__item @if(request()->is('admin/tax/state')) selected @endif">
                    <a href="{{ route('admin.tax.state.all') }}">{{ __('State Tax') }}</a>
                </li>
                <li class="dashboard__bottom__list__item @if(request()->is('admin/tax/city')) selected @endif">
                    <a href="{{ route('admin.tax.city.all') }}">{{ __('City Tax') }}</a>
                </li>
            @endif

    </ul>
</li>
@endcanany

<!-- Admin Manage Role Module -->
@can('admin-role-manage')
<li  class="dashboard__bottom__list__item has-children @if (request()->is('admin/manage*')) active open show @endif">
    <a href="javascript:void(0)"> <i class="las la-user-cog"></i> {{ __('Admin Role Manage') }} </a>
    <ul class="submenu">
        <li class="dashboard__bottom__list__item @if (request()->is('admin/manage/create/new-admin')) selected @endif">
            <a href="{{ route('admin.create') }}"> {{ __('Add New Admin') }} </a>
        </li>
        <li class="dashboard__bottom__list__item @if (request()->is('admin/manage/all-admins')) selected @endif">
            <a href="{{ route('admin.all') }}"> {{ __('All Admins') }} </a>
        </li>
        <li class="dashboard__bottom__list__item @if (request()->is('admin/manage/permission/role/all')) selected @endif">
            <a href="{{ route('admin.role.create') }}"> {{ __('All Roles') }} </a>
        </li>
    </ul>
</li>
@endcan

<!-- Country Manage Module -->
@canany(['state-list', 'state-csv-file-import', 'city-list', 'city-csv-file-import', 'area-list', 'area-csv-file-import'])
<li class="dashboard__bottom__list__item has-children @if(request()->is('admin/location/*')) active open @endif">
    <a href="javascript:void(0)"><i class="las la-globe"></i>
        <span class="icon_title">{{ __('Location Manage') }}</span>
    </a>
    <ul class="submenu" style="@if(request()->is('admin/location/*')) display:block; @endif">
        @can('state-list')
        <li class="dashboard__bottom__list__item @if(request()->is('admin/location/state/all-state')) selected @endif">
            <a href="{{ route('admin.state.all') }}">{{ __('All States') }}</a>
        </li>
        @endcan
        @can('state-csv-file-import')
        <li class="dashboard__bottom__list__item @if(request()->is('admin/location/state/csv/import')) selected @endif">
            <a href="{{ route('admin.state.import.csv.settings') }}">{{ __('Import State') }}</a>
        </li>
        @endcan
        @can('city-list')
        <li class="dashboard__bottom__list__item @if(request()->is('admin/location/city/all-city')) selected @endif">
            <a href="{{ route('admin.city.all') }}">{{ __('All Cities') }}</a>
        </li>
        @endcan
        @can('city-csv-file-import')
        <li class="dashboard__bottom__list__item @if(request()->is('admin/location/city/csv/import')) selected @endif">
            <a href="{{ route('admin.city.import.csv.settings') }}">{{ __('Import Cities') }}</a>
        </li>
        @endcan
        @can('area-list')
        <li class="dashboard__bottom__list__item @if(request()->is('admin/location/area/all-area')) selected @endif">
            <a href="{{ route('admin.area.all') }}">{{ __('All Areas') }}</a>
        </li>
        @endcan
        @can('area-csv-file-import')
        <li class="dashboard__bottom__list__item @if(request()->is('admin/location/area/csv/import')) selected @endif">
            <a href="{{ route('admin.area.import.csv.settings') }}">{{ __('Import Areas') }}</a>
        </li>
        @endcan
    </ul>
</li>
@endcanany


<!-- Integration Module -->
@can('integration-list')
<li class="dashboard__bottom__list__item @if(request()->is('admin/integrations-manage*')) active @endif">
    <a href="{{route('admin.integration')}}"><i class="las la-puzzle-piece"></i>
        <span class="icon_title">{{ __('Integration') }}</span>
    </a>
</li>
@endcan


<!-- Support Ticket Module -->
@canany(['department-list', 'support-ticket-list'])
<li class="dashboard__bottom__list__item has-children @if(request()->is('admin/support-ticket/*')) active open @endif">
    <a href="javascript:void(0)"><i class="las la-headset"></i>
        <span class="icon_title">{{ __('Support') }}</span>
    </a>
    <ul class="submenu" style="@if(request()->is('admin/support-ticket/*')) display:block; @endif">
        @can('department-list')
        <li class="dashboard__bottom__list__item @if(request()->is('admin/support-ticket/department')) selected @endif">
            <a href="{{ route('admin.department') }}">{{ __('Department') }}</a>
        </li>
        @endcan
        @can('support-ticket-list')
            <li class="dashboard__bottom__list__item @if(request()->is('admin/support-ticket/tickets')) selected @endif">
                <a href="{{ route('admin.ticket') }}">{{ __('Support Ticket') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany

<!-- Pages Module -->
<li class="dashboard__bottom__list__item has-children @if(request()->is('admin/plugin-manage/*')) active open @endif">
    <a href="javascript:void(0)"><i class="las la-plug"></i>
        <span class="icon_title">{{ __('Plugins Manage') }}</span>
    </a>
    <ul class="submenu" style="@if(request()->is('admin/plugin-manage/*')) display:block; @endif">
        @can('plugins-list')
            <li class="dashboard__bottom__list__item @if(request()->is('admin/plugin-manage/all')) selected @endif">
                <a href="{{ route('admin.plugin.manage.all') }}">{{ __('All Plugins') }}</a>
            </li>
        @endcan
        @can('plugins-add')
            <li class="dashboard__bottom__list__item @if(request()->is('admin/plugin-manage/new')) selected @endif">
                <a href="{{ route('admin.plugin.manage.new') }}">{{ __('Add New Plugin') }}</a>
            </li>
        @endcan
    </ul>
</li>

@can('payment-currency-settings')
<li class="dashboard__bottom__list__item has-children @if(request()->is('admin/payment-settings/*') || request()->is('admin/payment-gateway/currency-settings')) active open @endif">
    <a href="javascript:void(0)"><i class="las la-money-check-alt"></i>
        <span class="icon_title">{{ __('Payment Gateway') }}</span>
    </a>
    <ul class="submenu" style="@if(request()->is('admin/payment-settings/*') || request()->is('admin/payment-gateway/currency-settings')) display:block; @endif">
        @can('payment-currency-settings')
            <li class="dashboard__bottom__list__item @if(request()->is('admin/payment-gateway/currency-settings')) selected @endif">
                <a href="{{ route('admin.payment.gateway.currency.settings') }}">{{ __('Currency Settings') }}</a>
            </li>
        @endcan
        @php
            $payment_gateways = \Modules\PaymentGateways\app\Models\PaymentGateway::pluck('name');
        @endphp
        @foreach($payment_gateways ?? [] as $gateway)
            <li class="dashboard__bottom__list__item @if(request()->is("admin/payment-settings/payment/{$gateway}")) selected @endif">
                <a class="text-capitalize" href="{{ route("admin.payment.settings.{$gateway}") }}">{{ __($gateway) }}</a>
            </li>
        @endforeach

        <li class="dashboard__bottom__list__item @if(request()->is("admin/payment-settings/payment/cash-on-delivery")) selected @endif">
            <a class="text-capitalize" href="{{ route("admin.payment.settings.cod") }}">{{ __('cash on delivery') }}</a>
        </li>

    </ul>
</li>
@endcan

@can('live-chat-settings')
    <li class="dashboard__bottom__list__item @if(request()->routeIs('admin.pusher.settings')) active @endif">
        <a href="{{route('admin.pusher.settings')}}"><i class="lab la-rocketchat"></i>
            <span class="icon_title">{{ __('Chat Settings') }}</span>
        </a>
    </li>
@endcan

@can('sms-gateway-settings')
    <li class="dashboard__bottom__list__item @if(request()->routeIs('admin.sms.gateway.settings') || request()->is('admin/sms-gateway-settings/view')) active @endif">
        <a href="{{route('admin.sms.gateway.settings')}}"><i class="las la-sms"></i>
            <span class="icon_title">{{ __('SMS Gateway') }}</span>
        </a>
    </li>
@endcan

<!-- Render all module route start -->
@php
    $all_modules_route = (new \App\Helpers\ModuleMetaData())->getAllExternalMenu() ?? [];

@endphp
@foreach($all_modules_route as $index => $externalMenu)
    @php
        $flag = false;
        $activeRoutes = array_column((array) $externalMenu, 'route');
    @endphp

    @foreach ($externalMenu as $key => $individual_menu_item)
        @php
            $convert_to_array = (array) $individual_menu_item;
            $convert_to_array['label'] = __($convert_to_array['label']);
            if (array_key_exists('permissions', $convert_to_array) && !is_array($convert_to_array['permissions'])) {
                $convert_to_array['permissions'] = [$convert_to_array['permissions']];
            }
            $routeName = $convert_to_array['route'];
            $icon = array_key_exists('icon', $convert_to_array) ? $convert_to_array['icon'] : '';
        @endphp
        @if(count($externalMenu) > 1)
            @if($key === 0)
                <li class="dashboard__bottom__list__item has-children @if(in_array(\Request::route()->getName(), $activeRoutes)) active open @endif">
                    @endif

                    @if(empty($convert_to_array['parent']) && !$flag)
                        @php
                            $flag = true;
                        @endphp
                        <a href="javascript:void(0)">
                            <i class="{{$icon}}"></i>
                            <span class="icon_title">{{ $convert_to_array['label'] }} <span class="badge bg-danger">{{ __('Plugin') }}</span> </span>
                        </a>
                        <ul class="submenu" style=" @if(in_array(\Request::route()->getName(), $activeRoutes)) display:block; @endif">
                            @endif
                            @if($key !== 0 && $flag)
                                <li class="dashboard__bottom__list__item  @if(request()->routeIs($routeName) == $routeName) selected @endif">
                                    <a href="{{ route($routeName) }}">{{ $convert_to_array['label'] }}</a>
                                </li>
                            @endif
                            @if($key === count($externalMenu)-1)
                        </ul>
                </li>
            @endif
        @else
            <li class="dashboard__bottom__list__item @if(request()->routeIs($routeName)) active open @endif">
                <a href="{{ route($routeName) }}">  <i class="{{$icon}}"></i>  {{ $convert_to_array['label'] }} <span class="badge bg-danger">{{ __('Plugin') }}</span> </a>
            </li>
        @endif
    @endforeach
@endforeach
<!-- Render all module route end -->
