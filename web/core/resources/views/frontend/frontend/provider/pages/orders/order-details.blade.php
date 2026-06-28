@extends('frontend.frontend.provider.provider-master')
@section('site-title')
    {{__('Order Details')}}
@endsection
@section('style')
   <style>
       .table_customer.provider_wrapper {
           border-bottom: 1px solid #d1d1d1;
       }

       .content{
           max-width: calc(100% - 385px) ;
       }
   </style>
@endsection
@section('content')
    <div class="content px-4 w-100">
        <div class="dashboard__card bg__white padding-20 radius-10">
                <div class="dashboard__inner__header">
                    <div class="dashboard__inner__header__flex">
                        <div class="dashboard__inner__header__left">
                            <h4 class="dashboard__inner__header__title">{{ __('Order Details') }}</h4>
                        </div>
                        <div class="dashboard__inner__header__right">

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
                                      @include('frontend.frontend.provider.pages.orders.order-details-step-01')
                                      @include('frontend.frontend.provider.pages.orders.order-details-step-02')
                                      @include('frontend.frontend.provider.pages.orders.order-details-step-03')
                                    </div>
                                </div>
                            </div>
                                @if($order->job_post_id === null)
                                     @include('frontend.frontend.provider.pages.orders.sub-order-details')
                                @endif
                        </div>
                       </div>
                     </div>
                 </div>
            </div>
    </div>
    @include('frontend.frontend.provider.pages.orders.sub-order-status-modal-for-accept')
    @include('frontend.frontend.provider.pages.orders.sub-order-status-modal-for-cancel')
@endsection
@section('scripts')
    <script type="text/javascript">
        (function(){
            "use strict";
            $(document).ready(function(){
                let currentSubOrderStatus;
                //sub order status change to accept/decline
                $(document).on('click', '.sub_order_status_change_modal_for_accept', function () {
                    let el = $(this);
                    let sub_order_id = el.data('sub_order_id');
                    let order_id = el.data('order_id');
                    let form = $('#SubOrderStatusChangeModalForAccept');
                    form.find('#sub_order_id').val(sub_order_id);
                    form.find('#order_id').val(order_id);
                });

                //sub order status change to cancel
                $(document).on('click', '.sub_order_status_change_modal_for_cancel', function () {
                    let el = $(this);
                    let sub_order_id = el.data('sub_order_id');
                    let order_id = el.data('order_id');
                    let form = $('#SubOrderStatusChangeModalForCancel');
                    form.find('#sub_order_id').val(sub_order_id);
                    form.find('#order_id').val(order_id);
                });


            });

              // completion request send
              $(document).on('click','.swal_send_complete_request',function(e){
                    e.preventDefault();
                    Swal.fire({
                        title: '{{__("Are you sure to send complete request? Once you done you can not revert this !!")}}',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: "{{ __('Yes, send it!') }}"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $(this).next().find('.swal_form_submit_btn').trigger('click');
                        }
                    });
                });

         })(jQuery);



    </script>
@endsection

