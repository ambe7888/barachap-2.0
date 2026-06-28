<div class="col-xl-6 col-lg-6 col-md-6 col-12 mt-4">
    <div class="customer__details__author__item padding-20 radius-10">
        <div class="customer__details__author__item__header">
            <div class="customer__details__author__item__header__flex">
                <div class="customer__details__author__item__header__left">
                    <h4 class="customer__details__author__item__title">{{ __('Location Details') }}</h4>
                </div>
            </div>
        </div>
        <div class="customer__details__author__item__inner border_top_1 top_15">
            <div class="customer__account__details">
                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('State:') }}</strong>
                        <span>{{ $suborder->subOrderLocations?->state?->state  }}</span>
                    </div>
                </div>

                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('City:') }}</strong>
                        <span>{{ $suborder->subOrderLocations?->city?->city  }}</span>
                    </div>
                </div>

                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Area:') }}</strong>
                        <span>{{ $suborder->subOrderLocations?->area?->area  }}</span>
                    </div>
                </div>

                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Post Code:') }}</strong>
                        <span>{{ $suborder->subOrderLocations?->post_code  }}</span>
                    </div>
                </div>

                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Address:') }}</strong>
                        <span>{{ $suborder->subOrderLocations?->address  }}</span>
                    </div>
                </div>

                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Phone:') }}</strong>
                        <span>{{ $suborder->subOrderLocations?->phone  }}</span>
                    </div>
                </div>

                <div class="customer__account__details__item">
                    <div class="customer__account__details__item__flex">
                        <strong>{{ __('Emergency Phone:') }}</strong>
                        <span>{{ $suborder->subOrderLocations?->emergency_phone  }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

