@extends('frontend.frontend.client.client-master')
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
                        <div class="d-flex text-right w-100 mt-3">
                            <div >
                                @if($order->payment_status != 'complete' && $order->payment_gateway != 'cash_on_delivery' && $order->payment_gateway != 'manual_payment')
                                   <a href="#/"
                                        class="btn btn-primary"
                                        data-order_id="{{ $order->id }}" data-bs-toggle="modal"
                                        data-bs-target="#paymentGatewayModal">{{ __('Pay Now') }}
                                    </a>
                                @endif
                            </div>
                            <div>

                            </div>
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
                                @include('frontend.frontend.client.pages.order.order-details-step-01')
                                @include('frontend.frontend.client.pages.order.order-details-step-02')
                                </div>
                            </div>
                        </div>
                            @if($order->job_post_id === null)
                                @include('frontend.frontend.client.pages.order.sub-order-list')
                            @endif
                    </div>
                </div>
                </div>
            </div>
        </div>
   </div>
     @include('frontend.frontend.client.pages.order.payment_gateway.gateway-markup')
@endsection
@section('scripts')
    @include('frontend.frontend.client.pages.order.payment_gateway.payment-gateway-js')
    <script type="text/javascript">
        (function(){
            "use strict";
            $(document).ready(function(){

                $('#apply_coupon_code').on('click',function()
                {

                    let selected=$('#coupon_code option:selected');
                    let order_id=$('#order_id').val();
                    let coupon_code=selected.data('code');
                    let discount_type=selected.data('type');

                     $.ajax({
                        method: 'post',
                        url: "{{ route('client.order.total.after-discount') }}",
                        data: {
                            order_id:order_id,
                            coupon_code:coupon_code,
                            coupon_type:discount_type,
                        },
                        success: function(res) {

                            if (res.status == 'success') {
                                $("#discount").text(res.discount);
                                $("#total_after_discount").text(res.data);
                            }
                            else
                            {
                                toaster.error(res.msg);
                            }
                        }
                    });

                });


            });
        })(jQuery);
    </script>
@endsection

