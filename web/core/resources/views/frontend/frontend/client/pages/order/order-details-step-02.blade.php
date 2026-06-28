<div class="col-xl-6 col-md-6">
    <div class="customer__details__author__item padding-20 radius-10">
        <div class="customer__details__author__item__header">
            <div class="customer__details__author__item__header__flex">
                <div class="customer__details__author__item__header__left">
                    <h4 class="customer__details__author__item__title">{{ __('Amount Details') }}</h4>
                </div>
            </div>
        </div>
        <div class="customer__details__author__item__inner border_top_1 top_15">
            <div class="customer__account__details">

                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Sub Total:') }}</strong>
                        <span>{{ float_amount_with_currency_symbol($order->sub_total)  }}</span>
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
                        <strong>{{ __('Coupon Type:') }}</strong>
                        <span>{{ $order->coupon_type }}</span>
                    </div>
                </div>
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Coupon Amount:') }}</strong>
                        <span><strong>-</strong>{{ float_amount_with_currency_symbol($order->coupon_amount) }}</span>
                    </div>
                </div>
                @endif

                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Tax:') }}</strong>
                        <span><strong>+</strong>{{ float_amount_with_currency_symbol($order->tax)  }}</span>
                    </div>
                </div>
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Total:') }}</strong>
                        <span>{{ float_amount_with_currency_symbol($order->total)  }}</span>
                    </div>
                </div>
                @foreach($order->subOrders as $sub_order)
                    @if(!empty($sub_order->provider_id))
                        <div class="customer__account__details__item">
                            <div class="customer__account__details__item__flex">
                                <strong>{{ __('Commission Type:') }}</strong>
                                <span>{{ $order->commission_type }}</span>
                            </div>
                        </div>
                        <div class="customer__account__details__item">
                            <div class="customer__account__details__item__flex">
                                <strong>{{ __('Commission Charge:') }}</strong>
                                <span>{{ float_amount_with_currency_symbol($order->commission_charge) }}</span>
                            </div>
                        </div>
                        <div class="customer__account__details__item">
                            <div class="customer__account__details__item__flex">
                                <strong>{{ __('Commission Amount:') }}</strong>
                                <span>{{ float_amount_with_currency_symbol($order->commission_amount) }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

