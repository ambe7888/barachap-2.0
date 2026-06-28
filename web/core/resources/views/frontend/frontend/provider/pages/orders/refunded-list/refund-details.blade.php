@extends('frontend.frontend.provider.provider-master')
@section('site-title')
    {{__('Refund Details')}}
@endsection
@section('style')
   <style>
    
       .table_customer.provider_wrapper {
           border-bottom: 1px solid #d1d1d1;
       }
   </style>
@endsection
@section('content')
    <div class="row g-4 mt-0">
        <div class="col-xl-12 col-lg-12">
            <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('Refund Details') }}</h4>
                        </div>
                    </div>
                </div>
                <x-validation.error/>
                <div class="tableStyle_three">
                    <div class="table_wrapper custom_Table">
                      <div class="dashboard__body">
                        <div class="dashboard__inner">
                            <div class="customer__details mt-4">
                                <div class="customer__details__author">
                                    <div class="row">
                                        <div class="col-xl-6  col-md-6">
                                            <div class="customer__details__author__item padding-20 radius-10">
                                                <div class="customer__details__author__item__header">
                                                    <div class="customer__details__author__item__header__flex">
                                                        <div class="customer__details__author__item__header__left">
                                                            <h4 class="customer__details__author__item__title">{{ __('Refund Details') }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="customer__details__author__item__inner border_top_1 top_15">
                                                    <div class="customer__account__details">
                                                        <div class="customer__account__details__item">
                                                            <div class="customer__account__details__item__flex">
                                                                <strong>{{ __('Order ID:') }}</strong>
                                                                <span>{{ $refundedOrder->order?->id }}</span>
                                                            </div>
                                                        </div> 
                                                        <div class="customer__account__details__item">
                                                            <div class="customer__account__details__item__flex">
                                                                <strong>{{ __('Sub Order ID:') }}</strong>
                                                                <span>{{ $refundedOrder->sub_order_id }}</span>
                                                            </div>
                                                        </div> 
                                                        <div class="customer__account__details__item">
                                                            <div class="customer__account__details__item__flex">
                                                                <strong>{{ __('Client Name:') }}</strong>
                                                                <span>{{ $refundedOrder->user?->first_name }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="customer__account__details__item">
                                                            <div class="customer__account__details__item__flex">
                                                                <strong>{{ __('Client Email:') }}</strong>
                                                                <span>{{ $refundedOrder->user?->email }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="customer__account__details__item">
                                                            <div class="customer__account__details__item__flex">
                                                                <strong>{{ __('Status:') }}</strong>
                                                                <x-status.main-order-status :status="$refundedOrder->status"/>
                                                            </div>
                                                        </div>
                                                        @if($refundedOrder->refund_source == 0)
                                                            <div class="customer__account__details__item">
                                                                <div class="customer__account__details__item__flex">
                                                                    <strong>{{ __('Fine Type') }}</strong>
                                                                    <span>{{ $refundedOrder->fine_type }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="customer__account__details__item">
                                                                <div class="customer__account__details__item__flex">
                                                                    <strong>{{ __('Fine Amount') }}</strong>
                                                                    <span>{{ $refundedOrder->fine_amount }}</span>
                                                                </div>
                                                            </div>
                                                        @endif    
                                                       
                                                        <div class="customer__account__details__item">
                                                            <div class="customer__account__details__item__flex">
                                                                <strong>{{ __('Amount:') }}</strong>
                                                                <span>{{ $refundedOrder->amount }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6  col-md-6">
                                            <div class="customer__details__author__item padding-20 radius-10">
                                                <div class="customer__details__author__item__header">
                                                    <div class="customer__details__author__item__header__flex">
                                                        <div class="customer__details__author__item__header__left">
                                                            <h4 class="customer__details__author__item__title">{{ __('Refund Gateway Details') }}</h4>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="customer__details__author__item__inner border_top_1 top_15">
                                                    <div class="customer__account__details">
                                                      
                                                       
                                                        <div class="customer__account__details__item">
                                                            <div class="customer__account__details__item__flex">
                                                                <strong>{{ __('Gateway Name:') }}</strong>
                                                                <span>{{ $refundedOrder->gateway_name ?? "N/A" }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="customer__account__details__item">
                                                            <div class="customer__account__details__item__flex">
                                                                <strong>{{ __('Gateway Fields:') }}</strong>
                                                                <div class="d-flex flex-column">
                                                                    @if($refundedOrder->gateway_field)
                                                                        @foreach($refundedOrder->gateway_field as $key=>$value)
                                                                            <span>{{$key}}{{__(": ")  }}</strong> {{ $value ?? "N/A" }}</span>
                                                                        @endforeach
                                                                    @endif
                                                                                               
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
                       </div>
                     </div>
                 </div>
            </div>
        </div>
    </div>
   
@endsection
@section('scripts')
   
    <script type="text/javascript">
        (function(){
            "use strict";
            $(document).ready(function(){
                   
                       

            });
         })(jQuery);
    </script>
@endsection

