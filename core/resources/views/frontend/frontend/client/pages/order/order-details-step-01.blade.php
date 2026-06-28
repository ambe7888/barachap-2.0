<div class="col-xl-6 col-md-6">
    <div class="customer__details__author__item padding-20 radius-10">
        <div class="customer__details__author__item__header">
            <div class="customer__details__author__item__header__flex">
                <div class="customer__details__author__item__header__left">
                    <h4 class="customer__details__author__item__title">{{ __('Order Details') }}</h4>
                </div>
            </div>
        </div>
        <div class="customer__details__author__item__inner border_top_1 top_15">
            <div class="customer__account__details">
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Order ID:') }}</strong>
                        <span>{{ $order->id }}</span>
                    </div>
                </div>
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Status:') }}</strong>
                        <x-status.main-order-status :status="$order->status"/>
                    </div>
                </div>
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Payment Gateway:') }}</strong>
                        <span>{{ ucwords(str_replace("_", " ", $order->payment_gateway)) }}</span>
                    </div>
                </div>
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Payment Status:') }}</strong>
                        <span class="text-bold">{{ ucwords(str_replace("_", " ", $order->payment_status)) }}</span>
                    </div>
                </div>
               
            </div>
        </div>
    </div>
</div>
