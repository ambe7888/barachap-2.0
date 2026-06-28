@extends('backend.admin-master')
@section('site-title')
    {{__('Sub Order Details')}}
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('Sub Order Details') }}</h4>
                        </div>
                    </div>
                </div>
                <x-validation.error/>
                <div class="tableStyle_three">
                    <div class="table_wrapper custom_Table">
                      <div class="dashboard__body">
                        <div class="dashboard__inner">

                           @if(optional($suborder->subOrderAddons)->count() > 0)
                              <div class="mt-2">
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

                          
                        <div class="mt-2">
                            <h4 class="dashboard__inner__item__header__title mt-4">{{ __('Sub Orders Locations Details') }}</h4>
                            <!-- Table Design One -->
                            <div class="tableStyle_one mt-4">
                                <div class="table_wrapper table-responsive">
                                    <!-- Table -->
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>{{ __('Id') }}</th>
                                            <th>{{ __('Sub Order Id') }}</th>
                                            <th>{{ __('Location') }}</th>
                                            <th>{{ __('Post Code') }}</th>
                                            <th>{{ __('Address') }}</th>
                                            <th>{{ __('Phone') }}</th>
                                            <th>{{ __('Emergency Phone') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                      
                                            <tr>
                                                <td>{{ $suborder->subOrderLocations?->id }}</td>
                                                <td>{{ $suborder->subOrderLocations?->sub_order_id }}</td>
                                                <td>
                                                    <div class="table_customer">
                                                        <div class="table_customer__flex">
                                                            <div class="table_customer__contents">
                                                                <h6 class="table_customer__title">{{ __('State:') }} {{ $suborder->subOrderLocations?->state?->state }} </h6>
                                                                <h6 class="table_customer__title">{{ __('City:') }} {{ $suborder->subOrderLocations?->city?->city }}</h6>
                                                                <h6 class="table_customer__title">{{ __('Area:') }} {{ $suborder->subOrderLocations?->area?->area }}</h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $suborder->subOrderLocations?->post_code }}</td>
                                                <td>{{ $suborder->subOrderLocations?->address }}</td>
                                                <td>{{ $suborder->subOrderLocations?->phone }}</td>
                                                <td>{{ $suborder->subOrderLocations?->emergency_phone }}</td>
                                            </tr>
                                       
                                        </tbody>
                                    </table>
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
