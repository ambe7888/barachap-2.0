<div class="modal fade w-100" id="paymentGatewayModal" tabindex="-1" aria-labelledby="paymentGatewayModalLabel" aria-hidden="true">
    <div class="modal-dialog ab">
        <form action="{{route('client.order.payment.process')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="paymentGatewayModalLabel">{{ $title ?? '' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="confirm-payment payment-border">
                        <div class="single-checkbox">
                            <div class="checkbox-inlines">
                                <label class="checkbox-label" for="check2">
                                    {!! \App\Helpers\PaymentGatewayRenderHelper::renderPaymentGatewayForForm() !!}
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="amount_details">
                        <div class="customer__account__details">
                            <input type="hidden" name="order_id" id="order_id" value="{{$order->id}}">
                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Sub Total:') }}</strong>
                                    <span>{{ float_amount_with_currency_symbol($order->sub_total)  }}</span>
                                </div>
                            </div>



                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Tax:') }}</strong>
                                    <span><strong>+</strong>{{ float_amount_with_currency_symbol($order->tax)  }}</span>
                                </div>
                            </div>
                             @if(!empty($order->coupon_code) && !empty($order->coupon_amount))
                                <div class="customer__account__details__item">
                                    <div class="customer__account__details__item__flex">
                                        <strong>{{ __('Coupon Code:') }}</strong>
                                        <span>{{ $order->coupon_code }}</span>
                                    </div>
                                </div>
                                <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Discount:') }}</strong>
                                    <span><strong>-</strong><span id="discount">{{float_amount_with_currency_symbol($order->coupon_amount)  }}</span></span>
                                </div>
                            </div>
                            @else
                                 <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Discount:') }}</strong>
                                    <span><strong>-</strong><span id="discount">{{float_amount_with_currency_symbol(0.00)  }}</span></span>
                                </div>
                            @endif

                            <div class="customer__account__details__item">
                                <div class="customer__account__details__item__flex">
                                    <strong>{{ __('Total:') }}</strong>
                                    <span><span id="total_after_discount">{{ float_amount_with_currency_symbol($order->total)  }}</span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex flex-row">
                        <div class="form-group">
                            <select name="coupon_code" id="coupon_code" class="form-select" style="height:47px;width:380px" >
                                <option value="">{{__('Select Cuppon Code')}}</option>
                                @foreach($coupon_code as $code)
                                    <option value="{{ $code->id }}"  data-code="{{$code->code}}" data-type="{{$code->discount_type}}" @if($code->code == $order->coupon_code ) selected @endif>{{ $code->code}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                             <button type="button" class="btn btn-primary ms-2" id="apply_coupon_code">{{__('Apply')}}</button>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <div class="btn-wrapper d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{__('Order Now')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

