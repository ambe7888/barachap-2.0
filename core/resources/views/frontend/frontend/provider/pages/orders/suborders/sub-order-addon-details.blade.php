@extends('frontend.frontend.provider.provider-master')
@section('site-title')
    {{__('Sub Order Details')}}
@endsection
@section('style')
    <style>
        .star-rating {
            display: flex;
            flex-direction: column;
            font-size: 2rem;
            cursor: pointer;
        }
        .star {
            color: lightgray;
        }
        .star.selected {
            color: gold;
        }

    </style>
@endsection
@section('content')
    <div class="row g-4 w-100 mt-0 px-5">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('Sub Order Details') }}</h4>
                        </div>
                    </div>
                </div>

                <div class="tableStyle_three">
                    <div class="table_wrapper custom_Table">
                        <div class="dashboard__body">
                            <div class="dashboard__inner">

                                @if(optional($suborder->subOrderAddons)->count() > 0)
                                    <div class="mt-2 ">
                                        <h4 class="dashboard__inner__item__header__title mt-4">{{ __('Sub Orders Addons Details') }}</h4>
                                        <!-- Table Design One -->
                                        <div class="tableStyle_one mt-4">
                                            <div class="table_wrapper table-responsive">
                                                <!-- Table -->
                                                <table class="table">
                                                    <thead>
                                                    <tr>
                                                        <th>{{ __('Id') }}</th>
                                                        <th>{{ __('Sub Order Id') }}</th>
                                                        <th>{{ __('Title') }}</th>
                                                        <th>{{ __('Price') }}</th>
                                                        <th>{{ __('Quantity') }}</th>
                                                        <th>{{ __('Total') }}</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($suborder->subOrderAddons as $subOrder)
                                                        <tr>
                                                            <td>{{ $subOrder->id }}</td>
                                                            <td>{{ $subOrder->sub_order_id }}</td>
                                                            <td>{{ $subOrder->title }}</td>
                                                            <td>{{ float_amount_with_currency_symbol($subOrder->price) }}</td>
                                                            <td>{{ $subOrder->quantity }}</td>
                                                            <td>{{ float_amount_with_currency_symbol($subOrder->total) }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @include('frontend.frontend.provider.pages.orders.suborders.sub-order-details-step-01')
                    @include('frontend.frontend.provider.pages.orders.suborders.sub-order-details-step-02')
                    @include('frontend.frontend.provider.pages.orders.suborders.sub-order-details-step-03')
                    @include('frontend.frontend.provider.pages.orders.suborders.sub-order-details-step-04')
                    @include('frontend.frontend.provider.pages.orders.suborders.sub-order-details-step-05')
                </div>

            </div>
        </div>
    </div>


@endsection

@section('scripts')
    <script>

    </script>
@endsection
