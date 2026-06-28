@extends('frontend.frontend.client.client-master')
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
    <div class="row w-100 g-4 mt-0">
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
                                                                <strong>{{ __('Status:') }}</strong>
                                                                <x-status.main-order-status :status="$refundedOrder->status"/>
                                                            </div>
                                                        </div>
                                                        @if($refundedOrder->add_fee == 1)
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
                                                        <div class="customer__details__author__item__header__right">
                                                            <span >
                                                                <button type="button"
                                                                    class="btn btn-danger py-2 px-4 rounded me-2 btn-change"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#ChangeGatewayModal"
                                                                    data-refund_id="{{ $refundedOrder->id }}">
                                                                    {{ __("Change Gateway") }}
                                                                </button>
                                                                    
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="customer__details__author__item__inner border_top_1 top_15">
                                                    <div class="customer__account__details">
                                                      
                                                       
                                                        <div class="customer__account__details__item">
                                                            <div class="customer__account__details__item__flex">
                                                                <input type="hidden" name="gateway_id" id="gateway_id" value={{$refundedOrder->gateway_id}}>
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
    @include('frontend.frontend.client.pages.order.refunded-list.change-gateway-modal')
   
@endsection
@section('scripts')

    <script>
        (function(){
            "use strict";
            $(document).ready(function(){

                $('#gateway_method_for_change').on('change', function () {
                    var fields = $(this).find('option:selected').data('fields');
                    var  id=$(this).val();
                    var gateway_id=$('#gateway_id').val();
                    var container = $('#method-fields-for-change');
                    var present_gateway=$('#present_field').val();
                    container.empty();

                    if(id!=gateway_id)
                    {
                        $('#method-fields').addClass('d-none');
                        if (Array.isArray(fields)) {
                            $.each(fields, function (index, field) {
        
                                container.append(`
                                    <div class="mb-2">
                                        <label class="form-label">${field}</label>
                                        <input type="text" name="${field}" value="" class="form-control" placeholder="${field}" required>
                                    </div>
                                `);
                            });
                        }
                    }  
                    else if(id == gateway_id)
                    {
                        
                        $('#method-fields').removeClass('d-none');
                    }
                        
                   
                });


                  // When refund button is clicked, set values in the modal
            $(document).on('click', '.btn-change', function () {

                let refund_id = $(this).data('refund_id');

                $('#ChangeGatewayModal #refund_id').val(refund_id);
            });

            $('#change_gateway_details').on('click', function () 
            {
            
                var refund_id = $('#refund_id').val();
                var gateway_method=$('#gateway_method_for_change').val();
                var method_fields_for_change = {};
                $('#method-fields-for-change input').each(function () {
                    var name = $(this).attr('name');
                    var value = $(this).val();
                    method_fields_for_change[name] = value;
                });
                var method_fields = {};
                $('#method-fields input').each(function () {
                    var name = $(this).attr('name');
                    var value = $(this).val();
                    method_fields[name] = value;
                });



               
                $.ajax({
                            method: 'post',
                            url: "{{ route('client.refund.gateway.edit') }}",
                            data: {
                                refund_id:refund_id,
                                gateway_method:gateway_method,
                                method_fields:method_fields,
                                method_fields_for_change : method_fields_for_change

                            },
                            success: function(res) 
                            {
                                if (res.status == 'success') 
                                {
                                    toastr.success(res.message);
                                    $('#ChangeGatewayModal').modal('hide');
                                    location.reload();
                                    
                                  
                                }
                                else if (res.status == 'validation_error')
                                {
                                    let errorMessage = "";
                                    $.each(res.errors, function (key, value) {
                                        
                                        
                                        $('.error-change').text(value[0]);
                                    });
                                
                                
                                    
                                }
                                else
                                {
                                     toastr.error(res.message);
                                }
                                
                            },
                            error:function(xhr)
                            {
                                if (xhr.status === 422) { 
                                    let errors = xhr.responseJSON.errors;
                                    let errorMessage = "";
                                    $.each(errors, function (key, value) {
                                        errorMessage += value[0] + "<br>";
                                    });
                                    toastr.error(errorMessage);
                                } else {
                                    toastr.error("An error occurred. Please try again.");
                                }
                            }
                    });
            });

                   
            });
         })(jQuery);
    </script>
@endsection

