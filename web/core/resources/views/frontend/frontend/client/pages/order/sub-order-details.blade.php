@extends('frontend.frontend.client.client-master')
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
                    @include('frontend.frontend.client.pages.order.sub-order-details-step-01')
                    @include('frontend.frontend.client.pages.order.sub-order-details-step-02')
                    @include('frontend.frontend.client.pages.order.sub-order-details-step-03')
                    @include('frontend.frontend.client.pages.order.sub-order-details-step-04')
                    @include('frontend.frontend.client.pages.order.sub-order-details-step-05')
                    @include('frontend.frontend.client.pages.order.sub-order-details-step-06')
                </div>
               
            </div>
        </div>
    </div>
    @include('frontend.frontend.client.pages.order.cancel-modal')
    @include('frontend.frontend.client.pages.order.decline-modal')
    @include('frontend.frontend.client.pages.order.send-refund-request-modal')
    @include('frontend.frontend.client.pages.order.review-modal')

@endsection

@section('scripts')
    <script>
 
       
        $(document).ready(function(){
          

            $(document).on('click', '.btn-accept', function (e) {
                   
                e.preventDefault();
                let id = $(this).data('id');
                let order_id = $(this).data('order_id');
                let url = "{{ route('client.order.complete.request.approve') }}";
                let data = {
                    sub_order_id: id,
                    order_id: order_id,
                    _token: "{{ csrf_token() }}"
                };
                $.post(url, data, function(response) {
                    if (response.status === 'success') {
                        alert(response.msg);
                        location.reload();
                    } else {
                        alert(response.msg);
                    }
                });
            });

           
            // When Decline button is clicked, set values in the modal
            $(document).on('click', '.btn-decline', function () {
                let sub_order_id = $(this).data('sub_order_id');
                let order_id = $(this).data('order_id');

                $('#DeclineFormModal #sub_order_id').val(sub_order_id);
                $('#DeclineFormModal #order_id').val(order_id);
            });

            $('#send_decline_request').on('click', function () 
            {
            
                var order_id = $('#order_id').val();
                var sub_order_id = $('#sub_order_id').val();
                var reason = $('#decline_reason').val();
               
                $.ajax({
                            method: 'post',
                            url: "{{ route('client.order.complete.request.decline') }}",
                            data: {
                                order_id:order_id,
                                sub_order_id:sub_order_id,
                                decline_reason:reason,
                                
                            },
                            success: function(res) 
                            {
                                if (res.status == 'success') 
                                {
                                    toastr.success("{{__('Success')}}");
                                    $("#brand_id").val("").trigger("change");
                                    $("#sub_order_id").val("").trigger("change");
                                    $('#decline_reason').val('');
                                    $('#DeclineFormModal').modal('hide');
                                    location.reload();
                                    
                                  
                                }
                                else if (res.status == 'validation_error')
                                {
                                    let errorMessage = "";
                                    $.each(res.errors, function (key, value) {
                                        
                                        
                                        $('.error-car').text(value[0]);
                                    });
                                
                                
                                    
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


            $('#gateway_method').on('change', function() {
                var fields = $(this).find('option:selected').data('fields');
                var container = $('#method-fields');
                container.empty();

                if (Array.isArray(fields)) {
                    $.each(fields, function(index, field) {
                        container.append(`
                            <div class="mb-2">
                                <label class="form-label">${field}</label>
                                <input type="text" name="${field}" class="form-control" placeholder="${field}" required>
                            </div>
                        `);
                    });
                }
            });


             // When Cancel button is clicked, set values in the modal
            $(document).on('click', '.btn-cancel', function () {
                let sub_order_id = $(this).data('sub_order_id');
                let order_id = $(this).data('order_id');

                $('#CancelFormModal #sub_order_id_for_cancel').val(sub_order_id);
                $('#CancelFormModal #order_id_for_cancel').val(order_id);
            });

            $('#send_cancel_request').on('click', function () 
            {
            
                var order_id = $('#order_id_for_cancel').val();
                var sub_order_id = $('#sub_order_id_for_cancel').val();
                var reason = $('#cancel_reason').val();
                var gateway_method=$('#gateway_method').val();
                 var method_fields = {};
                $('#method-fields input').each(function () {
                    var name = $(this).attr('name');
                    var value = $(this).val();
                    method_fields[name] = value;
                });



               
                $.ajax({
                            method: 'post',
                            url: "{{ route('client.order.cancel.request') }}",
                            data: {
                                order_id_for_cancel:order_id,
                                sub_order_id_for_cancel:sub_order_id,
                                cancel_reason:reason,
                                gateway_method:gateway_method,
                                method_fields:method_fields

                                
                            },
                            success: function(res) 
                            {
                                if (res.status == 'success') 
                                {
                                    toastr.success("{{__('Success')}}");
                                    $("#order_id_for_cancel").val("").trigger("change");
                                    $("#sub_order_id_for_cancel").val("").trigger("change");
                                    $('#cancel_reason').val('');
                                    $('#CancelFormModal').modal('hide');
                                    location.reload();
                                    
                                  
                                }
                                else if (res.status == 'validation_error')
                                {
                                    let errorMessage = "";
                                    $.each(res.errors, function (key, value) {
                                        
                                        
                                        $('.error-cancel').text(value[0]);
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


            $('#gateway_method_for_refund').on('change', function() {
                var fields = $(this).find('option:selected').data('fields');
                var container = $('#method-fields-for-refund');
                container.empty();

                if (Array.isArray(fields)) {
                    $.each(fields, function(index, field) {
                        container.append(`
                            <div class="mb-2">
                                <label class="form-label">${field}</label>
                                <input type="text" name="${field}" class="form-control" placeholder="${field}" required>
                            </div>
                        `);
                    });
                }
            });


             // When refund button is clicked, set values in the modal
            $(document).on('click', '.btn-refund', function () {
                let sub_order_id = $(this).data('sub_order_id');
                let order_id = $(this).data('order_id');

                $('#RefundRequestFormModal #sub_order_id_for_refund').val(sub_order_id);
                $('#RefundRequestFormModal #order_id_for_refund').val(order_id);
            });

            $('#send_refund_request').on('click', function () 
            {
            
                var order_id = $('#order_id_for_refund').val();
                var sub_order_id = $('#sub_order_id_for_refund').val();
                var reason = $('#refund_reason').val();
                var gateway_method=$('#gateway_method_for_refund').val();
                 var method_fields = {};
                $('#method-fields-for-refund input').each(function () {
                    var name = $(this).attr('name');
                    var value = $(this).val();
                    method_fields[name] = value;
                });



               
                $.ajax({
                            method: 'post',
                            url: "{{ route('client.refund.request.send') }}",
                            data: {
                                order_id_for_refund:order_id,
                                sub_order_id_for_refund:sub_order_id,
                                refund_reason:reason,
                                gateway_method_for_refund:gateway_method,
                                method_fields:method_fields

                                
                            },
                            success: function(res) 
                            {
                                if (res.status == 'success') 
                                {
                                    toastr.success("{{__('Success')}}");
                                    $("#order_id_for_refund").val("").trigger("change");
                                    $("#sub_order_id_for_refund").val("").trigger("change");
                                    $('#refund_reason').val('');
                                    $('#RefundRequestFormModal').modal('hide');
                                    location.reload();
                                    
                                  
                                }
                                else if (res.status == 'validation_error')
                                {
                                    let errorMessage = "";
                                    $.each(res.errors, function (key, value) {
                                        
                                        
                                        $('.error-refund').text(value[0]);
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


            $('.star').on('click', function () {
                var rating = $(this).data('value');
                $('#ratingInput').val(rating);

               
                $('.star').each(function (index) {
                    if (index < rating) {
                        $(this).addClass('selected');
                    }
                    else {
                        $(this).removeClass('selected');
                    }
                });
                
            });


            // When Review button is clicked, set values in the modal
            $(document).on('click', '.btn-review', function () {
                let sub_order_id = $(this).data('sub_order_id');
                let order_id = $(this).data('order_id');
                let service_id=$(this).data('service_id');

                $('#ReviewFormModal #sub_order_id_for_review').val(sub_order_id);
                $('#ReviewFormModal #order_id_for_review').val(order_id);
                $('#ReviewFormModal #service_id').val(service_id);

                let rating=$('#ratingInput').val();
                $('.star').each(function (index) {
                    if (index < rating) {
                        $(this).addClass('selected');
                    }
                });

            });

            $('#send_review').on('click', function () 
            {
            
                var order_id = $('#order_id_for_review').val();
                var sub_order_id = $('#sub_order_id_for_review').val();
                var service_id= $('#service_id').val();
                var review = $('#review').val();
                var star= $('#ratingInput').val();
               
                $.ajax({
                            method: 'post',
                            url: "{{ route('client.review.send') }}",
                            data: {
                                order_id:order_id,
                                sub_order_id:sub_order_id,
                                service_id:service_id,
                                review:review,
                                rating:star
                                
                            },
                            success: function(res) 
                            {
                                if (res.status == 'success') 
                                {
                                    toastr.success("{{__('Success')}}");
                                    $("#order_id_for_review").val("").trigger("change");
                                    $("#sub_order_id_review").val("").trigger("change");
                                    $('#review').val('');
                                    $('#ReviewFormModal').modal('hide');
                                    location.reload();
                                    
                                  
                                }
                                else if (res.status == 'validation_error')
                                {
                                    let errorMessage = "";
                                    $.each(res.errors, function (key, value) {
                                        
                                        
                                        $('.error-review').text(value[0]);
                                    });
                                
                                
                                    
                                }
                                else{
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
              

                
    </script>    
@endsection
