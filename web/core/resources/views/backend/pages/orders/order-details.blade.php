@extends('backend.admin-master')
@section('site-title')
    {{__('Order Details')}}
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
                            <h4 class="dashboard__inner__header__title">{{ __('Provider Order Details') }}</h4>
                        </div>
                        <div class="dashboard__inner__header__right">
                            <div class="d-flex text-right w-100 mt-3">
                                <input class="form__control string_search" name="string_search" id="string_search" placeholder="{{ __('Search') }}">
                            </div>
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
                                      @include('backend.pages.orders.order-details-step-01')
                                      @include('backend.pages.orders.order-details-step-02')
                                      @include('backend.pages.orders.order-details-step-03')
                                    </div>
                                </div>
                            </div>
                                @if($order->job_post_id === null)
                                     @include('backend.pages.orders.sub-order-details')
                                @endif
                        </div>
                       </div>
                     </div>
                 </div>
            </div>
        </div>
    </div>
    @include('backend.pages.orders.sub-order-status-modal')
@endsection
@section('scripts')
    <script type="text/javascript">
        (function(){
            "use strict";
            $(document).ready(function(){


                    $(document).on('click', '#status_id', function () { 
                        let status_id=$('#status_id').val();
                       
                        if(status_id == 4)
                        {
                            $(".apply_cancellation_policy_div").removeClass('d-none');
                        }
                        else
                        {
                            $(".apply_cancellation_policy_div").addClass('d-none');
                        }
                    });
                    //sub order status change
                    $(document).on('click', '.sub_order_status_change_modal', function () {
                        let el = $(this);
                        let sub_order_id = el.data('sub_order_id');
                        let form = $('#SubOrderStatusChangeModal');
                        form.find('#sub_order_id').val(sub_order_id);
                        
                    });
                  });
         })(jQuery);
    </script>
@endsection

