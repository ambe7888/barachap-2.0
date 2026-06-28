<div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-4">
    <div class="customer__details__author__item padding-20 radius-10">
        <div class="customer__details__author__item__header">
            <div class="customer__details__author__item__header__flex">
                <div class="customer__details__author__item__header__left">
                 
                    <h4 class="customer__details__author__item__title">{{ __('Sub Order Management') }}</h4>
                </div>
            </div>
        </div>
        <div class="customer__details__author__item__inner border_top_1 top_15">
            <div class="customer__account__details">
                    <p class="text-muted mb-3">
                        @if($suborder->status == 4)
                           {{ __("You canceled your order.") }}    
                        @elseif($suborder->status == 2)
                           {{ __("You complete your order.Now you can submit your review.") }} 
                        @elseif($suborder->status == 6)
                            {{__("You sent refund request.From here,you can submit your review")}}   
                        @else
                            {{ __("You can manage your order from here.") }}       
                        @endif
                        
                    </p>
               
                   <div class="customer__account__details__item mt-5">
                        <div class="customer__account__details">
                            @if($suborder->status == 0 || $suborder->status == 1)
                                
                                <button type="button"
                                    class="btn btn-danger py-2 px-4 rounded me-2 btn-cancel"
                                    data-bs-toggle="modal"
                                    data-bs-target="#CancelFormModal"
                                    data-sub_order_id="{{ $suborder->id }}"
                                    data-order_id="{{ $suborder->order_id }}">
                                {{ __("Cancel Order") }}
                                </button>
                            @endif   
                            
                            @if($order_complete_request && $order_complete_request->status == '0')
                                <button type="button" data-id="{{$suborder->id}}" data-order_id="{{$suborder->order_id}}" class="btn btn-primary py-2 px-4 rounded btn-accept">
                                    {{__("Complete Order")}}
                                </button>

                                <button type="button"
                                        class="btn btn-danger py-2 px-4 rounded ms-2 btn-decline"
                                        data-bs-toggle="modal"
                                        data-bs-target="#DeclineFormModal"
                                        data-sub_order_id="{{ $suborder->id }}"
                                        data-order_id="{{ $suborder->order_id }}">
                                    {{ __("Decline Order") }}
                                </button>

                               
                            @elseif($order_complete_request && $order_complete_request->status == '1')  
                                <button type="button"
                                        class="btn btn-primary py-2 px-4 rounded me-2 btn-review"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ReviewFormModal"
                                        data-sub_order_id="{{ $suborder->id }}"
                                        data-order_id="{{ $suborder->order_id }}"
                                        data-service_id="{{ $suborder->service_id}}">
                                        {{ __("Submit Review") }}
                                </button>
                                
                                @if($order_payment_status == 'complete'  && $suborder->status == '2')
                                    <button type="button"
                                        class="btn btn-danger py-2 px-4 rounded me-2 btn-refund"
                                        data-bs-toggle="modal"
                                        data-bs-target="#RefundRequestFormModal"
                                        data-sub_order_id="{{ $suborder->id }}"
                                        data-order_id="{{ $suborder->order_id }}">
                                        {{ __("Send Refund Request") }}
                                    </button>
                                @endif    
                            @endif    
                        </div>
                    </div>  
            </div>
        </div>
    </div>
</div>






