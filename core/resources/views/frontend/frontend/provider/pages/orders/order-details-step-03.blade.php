<div class="col-xl-4 col-md-6">
    <div class="customer__details__author__item padding-20 radius-10">
        <div class="customer__details__author__item__header">
            <div class="customer__details__author__item__header__flex">
                <div class="customer__details__author__item__header__left">
                    <h4 class="customer__details__author__item__title">{{ __('Client Details') }}</h4>
                </div>
            </div>
        </div>
        <div class="customer__details__author__item__inner border_top_1 top_15">
            <div class="customer__account__details">
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Name:') }}</strong>
                        <span>{{ $order->client?->fullname }}</span>
                    </div>
                </div>
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Email:') }}</strong>
                        <span>{{ $order->client?->email }}</span>
                    </div>
                </div>
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Phone:') }}</strong>
                        <span>{{ $order->client?->phone }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
