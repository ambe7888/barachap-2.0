        <div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-4">
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
                                <strong>{{ __('Basic Price:') }}</strong>
                                <span>{{ float_amount_with_currency_symbol($suborder->basic_price)  }}</span>
                            </div>
                        </div>

                        <div class="customer__account__details__item">
                            <div class="customer__account__details__item__flex">
                                <strong>{{ __('Addon Price:') }}</strong>
                                <span><strong>+</strong> {{ float_amount_with_currency_symbol($suborder?->subOrderAddons->sum('total')) }}</span>
                            </div>
                        </div>

                         <div class="customer__account__details__item">
                            <div class="customer__account__details__item__flex">
                                <strong>{{ __('Sub Total:') }}</strong>
                                {{ float_amount_with_currency_symbol($suborder->sub_total) }}
                            </div>
                        </div>

                        <div class="customer__account__details__item">
                            <div class="customer__account__details__item__flex">
                                <strong>{{ __('Coupon Amount:') }}</strong>
                                <span><strong>-</strong>{{ float_amount_with_currency_symbol($suborder->coupon_amount) }}</span>
                            </div>
                        </div>

                        <div class="customer__account__details__item">
                            <div class="customer__account__details__item__flex">
                                <strong>{{ __('Tax:') }}</strong>
                                <span><strong>+</strong>{{ float_amount_with_currency_symbol($suborder->tax)  }}</span>
                            </div>
                        </div>
                        <div class="customer__account__details__item">
                            <div class="customer__account__details__item__flex">
                                <strong>{{ __('Total:') }}</strong>
                                <span>{{ float_amount_with_currency_symbol($suborder->total)  }}</span>
                            </div>
                        </div>
                         <div class="customer__account__details__item">
                            <div class="customer__account__details__item__flex">
                                <strong>{{ __('Commission Type:') }}</strong>
                                <span>{{ $suborder->commission_type }}</span>
                            </div>
                        </div>
                         <div class="customer__account__details__item">
                            <div class="customer__account__details__item__flex">
                                <strong>{{ __('Commission Charge:') }}</strong>
                                <span>{{ float_amount_with_currency_symbol($suborder->commission_charge) }}</span>
                            </div>
                        </div>
                         <div class="customer__account__details__item">
                            <div class="customer__account__details__item__flex">
                                <strong>{{ __('Commission Amount:') }}</strong>
                                <span>{{ float_amount_with_currency_symbol($suborder->commission_amount) }}</span>
                            </div>
                        </div>
                      
                    </div>
                </div>
            </div>
        </div>

