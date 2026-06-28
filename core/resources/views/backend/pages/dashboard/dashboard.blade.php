@extends('backend.admin-master')
@section('site-title')
    {{__('Dashboard')}}
@endsection
@section('style')
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
@endsection
@section('content')
    <div class="dashboard__body posPadding">
        <div class="dashboard__inner">
            <div class="dashboard__inner__item">
                <div class="dashboard__inner__item__flex">
                    <div class="dashboard__inner__item__left bodyItemPadding">
                        <div class="dashboard__inner__header">
                            <div class="dashboard__inner__header__flex">
                                <div class="dashboard__inner__header__left">
                                    <h4 class="dashboard__inner__header__title"> <strong id="greeting"></strong>, {{ Auth::guard('admin')->user()->name }} </h4>
                                    <p class="dashboard__inner__header__para">{{ __('Manage your dashboard here') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="dashboard_promo">
                            <div class="row g-4 mt-2">
                                @foreach($dashboardData as $item)
                                    <div class="col-xxl-2 col-xl-3 col-sm-6">
                                        <div class="dashboard_promo__single style_02 bg__white radius-10 padding-20">
                                            <span class="dashboard_promo__single__subtitle d-flex justify-content-between align-items-center">
                                                <span>
                                                {{ $item['title'] ?? '' }}
                                                 </span>
                                                @if(isset($item['route']))
                                                    <a href="{{ isset($item['params']) ? route($item['route'], $item['params']) : route($item['route']) }}">
                                                        <i class="las la-arrow-right fs-3 font-weight-600"></i>
                                                    </a>
                                                @endif
                                            </span>
                                            <h4 class="dashboard_promo__single__price mt-2">{{ $item['value'] ?? 0 }}</h4>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="row g-4 mt-1">
                            <div class="col-xl-6 col-lg-6">
                                <div class="dashboard__card bg__white radius-10 p-3">
                                    <div class="dashboard__card__header">
                                        <div class="dashboard__card__header__flex">
                                            <div class="dashboard__card__header__left">
                                                <h5 class="dashboard__card__header__title">{{ __('Customers') }}
                                                    <p>{{ __('Total Users:') }} {{ $total_user }}</p>
                                                </h5>
                                            </div>
                                            <div class="dashboard__card__header__right">
                                                <select id="timeIntervalSelect" class="select2_activation">
                                                    @foreach(['This Week','Last Week','This Month','Last Month','This Year','Last Year'] as $key => $option)
                                                        <option value="{{ $key }}">{{ $option }}</option>
                                                    @endforeach
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
                                                <h5 class="dashboard__card__header__title">{{ __('Services') }}
                                                    <p>{{ __('Total Services:') }} {{ $total_services }}</p>
                                                </h5>
                                            </div>
                                            <div class="dashboard__card__header__right">
                                                <select id="serviceTimeIntervalSelect" class="select2_activation">
                                                    @foreach(['This Week','Last Week','This Month','Last Month','This Year','Last Year'] as $key => $option)
                                                        <option value="{{ $key }}">{{ $option }}</option>
                                                    @endforeach
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
                                    <h5 class="dashboard__card__header__title">{{ __('Recent Users') }}</h5>
                                    <div class="dashboard__card__inner border_top_1">
                                        <div class="dashboard__inventory__table custom_table">
                                            @if($recent_users->count() > 0)
                                                <table>
                                                    <thead>
                                                    <tr>
                                                        <th>{{ __('ID') }}</th>
                                                        <th>{{ __('User') }}</th>
                                                        <th>{{ __('Created On') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($recent_users as $user)
                                                        <tr class="table_row">
                                                            <td><span class="order_id">{{ $user->id }}</span></td>
                                                            <td>
                                                                <div class="table_customer">
                                                                    <div class="table_customer__flex">
                                                                        <div class="table_customer__thumb">
                                                                            @if(!empty($user->image))
                                                                                {!! render_image_markup_by_attachment_id($user->image) !!}
                                                                            @else
                                                                                <img src="{{ asset('assets/frontend/img/static/user-no-image.webp') }}" alt="No Image">
                                                                            @endif
                                                                        </div>
                                                                        <div class="table_customer__contents">
                                                                            @if(trim($user->fullname) !== "")
                                                                                <h6 class="table_customer__title">{{ $user->fullname }}</h6>
                                                                            @else
                                                                                <h6 class="table_customer__title">{{ $user->email }}</h6>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><span class="table_date">{{ $user->created_at->format('d M Y') }}</span></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span class="text-center text-danger">{{ __('No recent users found') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="dashboard__card bg__white radius-10 p-3">
                                    <h5 class="dashboard__card__header__title">{{ __('Recent Service') }}</h5>
                                    <div class="dashboard__card__inner border_top_1">
                                        <div class="dashboard__inventory__table custom_table">
                                            @if($recent_services->count() > 0)
                                            <table>
                                                <thead>
                                                <tr>
                                                    <th>{{ __('ID') }}</th>
                                                    <th>{{ __('Title') }}</th>
                                                    <th>{{ __('Image') }}</th>
                                                    <th>{{ __('Details') }}</th>
                                                    <th>{{ __('Created On') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($recent_services as $service)
                                                    <tr class="table_row">
                                                        <td><span class="order_id">{{ $service->id }}</span></td>
                                                        <td>
                                                            <a href="{{ route('admin.service.details', $service->id) }}">
                                                            <span class="order_id">{{ $service->title }}</span>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span class="order_id">
                                                                {!! render_image_markup_by_attachment_id($service->image) !!}
                                                            </span>
                                                            </td>
                                                        <td>
                                                            <a href="{{ route('admin.service.details', $service->id) }}" class="cmnBtn btn_5 btn_bg_info btnIcon radius-5">
                                                                <i class="las la-eye"></i>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <span class="table_date">{{ $service->created_at->format('d M Y') }}</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            @else
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <span class="text-center text-danger">{{ __('No recent services found') }}</span>
                                                </div>
                                            @endif
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
                                                <h5 class="dashboard__card__header__title">{{ __('Revenue') }}</h5>
                                            </div>
                                            <div class="dashboard__card__header__right">
                                                <select id="totalIncomeIntervalSelectAll" class="select2_activation">
                                                    @foreach(['Today', 'Yesterday', 'This Week','Last Week','This Month','Last Month','This Year','Last Year'] as $key => $option)
                                                        <option value="{{ $key }}">{{ $option }}</option>
                                                    @endforeach
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
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            let currentTime = new Date().getHours();
            let morningGreeting = "{{ __('Good Morning') }}";
            let afternoonGreeting = "{{ __('Good Afternoon') }}";
            let eveningGreeting = "{{ __('Good Evening') }}";
            if (currentTime >= 0 && currentTime < 12) {
                $('#greeting').text(morningGreeting);
            } else if (currentTime >= 12 && currentTime < 18) {
                $('#greeting').text(afternoonGreeting);
            } else {
                $('#greeting').text(eveningGreeting);
            }
        });
    </script>
    @include('backend.pages.dashboard.line-graph-js')
    @include('backend.pages.dashboard.total-income-graph-js')
@endsection
